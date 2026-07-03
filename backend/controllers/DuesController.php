<?php

require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/App.php';
require_once __DIR__ . '/../models/DuesPeriod.php';
require_once __DIR__ . '/../models/DuesPayment.php';
require_once __DIR__ . '/../models/UserDepartment.php';
require_once __DIR__ . '/../models/FinanceTransaction.php';

class DuesController extends Controller
{
    private Department $deptModel;
    private App $appModel;
    private DuesPeriod $periodModel;
    private DuesPayment $paymentModel;
    private UserDepartment $udModel;
    private FinanceTransaction $financeModel;

    public function __construct()
    {
        $this->deptModel = new Department();
        $this->appModel = new App();
        $this->periodModel = new DuesPeriod();
        $this->paymentModel = new DuesPayment();
        $this->udModel = new UserDepartment();
        $this->financeModel = new FinanceTransaction();
    }

    private function getAppOrFail(string $slug): array
    {
        Auth::requireLogin();
        Auth::requireDepartment($slug);
        
        $dept = $this->deptModel->getBySlug($slug);
        if (!$dept) {
            http_response_code(404);
            exit('Departman bulunamadı');
        }

        $app = $this->appModel->getByDepartmentAndModule($dept['id'], 'dues');
        if (!$app || !$app['is_active']) {
            http_response_code(404);
            exit('Bu departman için Aidat uygulaması aktif değil.');
        }

        return [$dept, $app];
    }

    public function index(string $slug): void
    {
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $userRole = 'member';
        foreach (Auth::getDepartments() as $d) {
            if ($d['slug'] === $slug) {
                $userRole = $d['role'];
                break;
            }
        }
        if (Auth::isSuperAdmin() || Auth::isPresidentOrVice()) {
            $userRole = 'director';
        }

        $periods = $this->periodModel->getByAppId($app['id']);
        
        $selectedPeriodId = (int)($_GET['period_id'] ?? ($periods[0]['id'] ?? 0));
        
        $payments = [];
        $selectedPeriod = null;
        if ($selectedPeriodId) {
            $payments = $this->paymentModel->getByPeriod($selectedPeriodId);
            foreach ($periods as $p) {
                if ($p['id'] == $selectedPeriodId) {
                    $selectedPeriod = $p;
                    break;
                }
            }
        }

        // Kendi aidat geçmişi (üyeler için)
        $myPayments = [];
        if ($userRole === 'member') {
            $myPayments = $this->paymentModel->getByUser(Auth::user()['id'], $app['id']);
        }

        $deptApps = $this->appModel->getByDepartment($currentDept['id']);
        $activeSection = 'dues';

        $this->view('panel/dues/index', compact('currentDept', 'app', 'userRole', 'periods', 'selectedPeriod', 'payments', 'myPayments', 'deptApps', 'activeSection'), 'panel');
    }

    public function storePeriod(string $slug): void
    {
        $this->csrfCheck();
        Auth::requireDirector($slug);
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $name = trim($_POST['name'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);
        
        if (!$name || $amount <= 0) {
            $this->flashError("Geçerli bir dönem adı ve tutar giriniz.");
            $this->redirect("/panel/{$slug}/dues");
        }

        $periodId = $this->periodModel->create([
            'app_id' => $app['id'],
            'department_id' => $currentDept['id'],
            'name' => $name,
            'amount' => $amount,
            'created_by' => Auth::user()['id']
        ]);

        // Departmandaki herkesi bu döneme ekle
        $users = $this->udModel->getByDepartment($currentDept['id']);
        foreach ($users as $u) {
            $this->paymentModel->create([
                'period_id' => $periodId,
                'user_id' => $u['user_id'],
                'amount_paid' => 0,
                'status' => 'unpaid'
            ]);
        }

        $this->flashSuccess("Aidat dönemi oluşturuldu ve üyelere atandı.");
        Logger::log('CREATE_DUES_PERIOD', 'DuesPeriod', $periodId, ['name' => $name, 'amount' => $amount, 'department_id' => $currentDept['id']]);
        $this->redirect("/panel/{$slug}/dues?period_id={$periodId}");
    }

    public function destroyPeriod(string $slug, string $id): void
    {
        $this->csrfCheck();
        Auth::requireDirector($slug);
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $period = $this->periodModel->getById((int)$id);
        if ($period && $period['app_id'] === $app['id']) {
            $this->periodModel->delete((int)$id);
            Logger::log('DELETE_DUES_PERIOD', 'DuesPeriod', (int)$id, ['department_id' => $currentDept['id']]);
            $this->flashSuccess("Dönem ve ona bağlı tüm kayıtlar silindi.");
        }

        $this->redirect("/panel/{$slug}/dues");
    }

    public function togglePayment(string $slug): void
    {
        $this->csrfCheck();
        Auth::requireDirector($slug);
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $paymentId = (int)($_POST['payment_id'] ?? 0);
        $status = $_POST['status'] ?? 'unpaid';

        $payment = $this->paymentModel->getById($paymentId);
        if (!$payment) {
            $this->flashError("Kayıt bulunamadı.");
            $this->redirect("/panel/{$slug}/dues");
        }

        $period = $this->periodModel->getById($payment['period_id']);
        if ($period['app_id'] !== $app['id']) {
            $this->flashError("Geçersiz işlem.");
            $this->redirect("/panel/{$slug}/dues");
        }

        $amountPaid = $status === 'paid' ? $period['amount'] : 0;
        
        $this->paymentModel->update($paymentId, [
            'status' => $status,
            'amount_paid' => $amountPaid,
            'payment_date' => $status === 'paid' ? date('Y-m-d H:i:s') : null,
            'recorded_by' => Auth::user()['id']
        ]);

        // Finans entegrasyonu
        $financeApp = $this->appModel->getByDepartmentAndModule($currentDept['id'], 'finance');
        if ($financeApp && $financeApp['is_active']) {
            // Önce bu ödemeye ait eski bir gelir kaydı varsa bulalım
            $stmt = Database::getInstance()->prepare("SELECT id FROM finance_transactions WHERE related_due_id = ?");
            $stmt->execute([$paymentId]);
            $existingTx = $stmt->fetchColumn();

            if ($status === 'paid') {
                if (!$existingTx) {
                    $userStmt = Database::getInstance()->prepare("SELECT name FROM users WHERE id = ?");
                    $userStmt->execute([$payment['user_id']]);
                    $userName = $userStmt->fetchColumn();

                    $this->financeModel->create([
                        'app_id' => $financeApp['id'],
                        'department_id' => $currentDept['id'],
                        'type' => 'income',
                        'amount' => $amountPaid,
                        'category' => 'Aidat Geliri',
                        'description' => $period['name'] . ' - ' . $userName,
                        'transaction_date' => date('Y-m-d'),
                        'related_due_id' => $paymentId,
                        'created_by' => Auth::user()['id']
                    ]);
                }
            } else {
                // Eğer status unpaid olduysa veya pending ise, ve daha önce finans kaydı varsa sil
                if ($existingTx) {
                    $this->financeModel->delete((int)$existingTx);
                }
            }
        }

        $this->flashSuccess("Ödeme durumu güncellendi.");
        Logger::log('TOGGLE_DUES_PAYMENT', 'DuesPayment', $paymentId, ['status' => $status, 'department_id' => $currentDept['id']]);
        $this->redirect("/panel/{$slug}/dues?period_id=" . $payment['period_id']);
    }
}
