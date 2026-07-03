<?php

require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/App.php';
require_once __DIR__ . '/../models/FinanceTransaction.php';
require_once __DIR__ . '/../models/UserDepartment.php';

class FinanceController extends Controller
{
    private Department $deptModel;
    private App $appModel;
    private FinanceTransaction $financeModel;
    private UserDepartment $pivotModel;

    public function __construct()
    {
        $this->deptModel = new Department();
        $this->appModel = new App();
        $this->financeModel = new FinanceTransaction();
        $this->pivotModel = new UserDepartment();
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

        $app = $this->appModel->getByDepartmentAndModule($dept['id'], 'finance');
        if (!$app || !$app['is_active']) {
            http_response_code(404);
            exit('Bu departman için Finans uygulaması aktif değil.');
        }

        return [$dept, $app];
    }

    public function index(string $slug): void
    {
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $transactions = $this->financeModel->getByAppId($app['id']);
        $summary = $this->financeModel->getSummary($app['id']);

        $userRole = 'member';
        foreach (Auth::getDepartments() as $d) {
            if ($d['slug'] === $slug) {
                $userRole = $d['role'];
                break;
            }
        }
        if (Auth::isSuperAdmin() || Auth::isPresidentOrVice()) {
            $userRole = 'director'; // Admin is director everywhere implicitly
        }

        $deptApps = $this->appModel->getByDepartment($currentDept['id']);
        $members = $this->pivotModel->getByDepartment($currentDept['id']);
        $activeSection = 'finance';

        $this->view('panel/finance/index', compact('currentDept', 'app', 'transactions', 'summary', 'userRole', 'deptApps', 'members', 'activeSection'), 'panel');
    }

    public function store(string $slug): void
    {
        $this->csrfCheck();
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $type = $_POST['type'] ?? '';
        $amount = (float)($_POST['amount'] ?? 0);
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
        $received_by_proxy = trim($_POST['received_by_proxy'] ?? '');

        if (!in_array($type, ['income', 'expense']) || $amount <= 0 || !$category) {
            $this->flashError("Lütfen geçerli bir tip, tutar ve kategori giriniz.");
            $this->redirect("/panel/{$slug}/finance");
        }

        $this->financeModel->create([
            'app_id' => $app['id'],
            'department_id' => $currentDept['id'],
            'type' => $type,
            'amount' => $amount,
            'category' => $category,
            'description' => $description,
            'transaction_date' => $transaction_date,
            'proxy_user_id' => $_POST['proxy_user_id'] ? (int)$_POST['proxy_user_id'] : null,
            'proxy_status' => $_POST['proxy_user_id'] ? 'pending' : 'delivered',
            'created_by' => Auth::user()['id']
        ]);

        $this->flashSuccess("İşlem başarıyla eklendi.");
        Logger::log('CREATE_FINANCE_TX', 'FinanceTransaction', (int)Database::getInstance()->lastInsertId(), ['type' => $type, 'amount' => $amount, 'department_id' => $currentDept['id']]);
        $this->redirect("/panel/{$slug}/finance");
    }

    public function destroy(string $slug, string $id): void
    {
        $this->csrfCheck();
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $tx = $this->financeModel->getById((int)$id);
        if ($tx && $tx['app_id'] === $app['id']) {
            $this->financeModel->delete((int)$id);
            Logger::log('DELETE_FINANCE_TX', 'FinanceTransaction', (int)$id, ['department_id' => $currentDept['id']]);
            $this->flashSuccess("İşlem silindi.");
        }
        
        $this->redirect("/panel/{$slug}/finance");
    }

    public function update(string $slug, string $id): void
    {
        $this->csrfCheck();
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $tx = $this->financeModel->getById((int)$id);
        if (!$tx || $tx['app_id'] !== $app['id']) {
            $this->flashError("İşlem bulunamadı.");
            $this->redirect("/panel/{$slug}/finance");
        }

        $type = $_POST['type'] ?? '';
        $amount = (float)($_POST['amount'] ?? 0);
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
        $received_by_proxy = trim($_POST['received_by_proxy'] ?? '');

        if (!in_array($type, ['income', 'expense']) || $amount <= 0 || !$category) {
            $this->flashError("Lütfen geçerli bir tip, tutar ve kategori giriniz.");
            $this->redirect("/panel/{$slug}/finance");
        }

        $this->financeModel->update((int)$id, [
            'type' => $type,
            'amount' => $amount,
            'category' => $category,
            'description' => $description,
            'transaction_date' => $transaction_date,
            'proxy_user_id' => $_POST['proxy_user_id'] ? (int)$_POST['proxy_user_id'] : null,
            'proxy_status' => $_POST['proxy_user_id'] ? 'pending' : 'delivered'
        ]);

        $this->flashSuccess("İşlem başarıyla güncellendi.");
        Logger::log('UPDATE_FINANCE_TX', 'FinanceTransaction', (int)$id, ['type' => $type, 'amount' => $amount, 'department_id' => $currentDept['id']]);
        $this->redirect("/panel/{$slug}/finance");
    }

    public function receiveProxy(string $slug, string $id): void
    {
        $this->csrfCheck();
        [$currentDept, $app] = $this->getAppOrFail($slug);

        $tx = $this->financeModel->getById((int)$id);
        if (!$tx || $tx['app_id'] !== $app['id']) {
            $this->flashError("İşlem bulunamadı.");
            $this->redirect("/panel/{$slug}/finance");
        }

        $this->financeModel->update((int)$id, [
            'proxy_status' => 'delivered'
        ]);

        $this->flashSuccess("Ödeme teslim alındı olarak işaretlendi.");
        Logger::log('RECEIVE_FINANCE_PROXY', 'FinanceTransaction', (int)$id, ['department_id' => $currentDept['id']]);
        $this->redirect("/panel/{$slug}/finance");
    }
}
