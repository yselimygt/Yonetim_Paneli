<?php

require_once __DIR__ . '/BaseModule.php';
require_once __DIR__ . '/../core/Database.php';

class MembersModule extends BaseModule
{
    public function __construct(array $app, array $dept, ?array $user, string $userRole)
    {
        parent::__construct($app, $dept, $user, $userRole);
    }

    public function render(): string
    {
        $appId = $this->app['id'];
        
        $stmt = $this->pdo->prepare("SELECT * FROM app_members ORDER BY id ASC");
        $stmt->execute();
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $redList = array_filter($members, fn($m) => $m['status'] === 'suspended');
        $activeMembers = array_filter($members, fn($m) => $m['status'] !== 'suspended');

        ob_start();
        extract([
            'members' => $activeMembers,
            'redList' => $redList,
            'app' => $this->app,
            'dept' => $this->department,
            'userRole' => $this->userRole
        ]);
        require __DIR__ . '/../views/modules/members.php';
        return ob_get_clean();
    }

    public function handlePost(array $post): void
    {
        // Remove global block to allow members to add/edit members. Access will be checked per action.

        $action = $post['action'] ?? '';
        $appId = $this->app['id'];
        $deptId = $this->department['id'];
        $userId = $this->user['id'] ?? null;

        try {
            if ($action === 'create' || $action === 'update') {
                $memberId = (int)($post['member_id'] ?? 0);
                $first_name = trim($post['first_name'] ?? '');
                $last_name = trim($post['last_name'] ?? '');
                $email = trim($post['email'] ?? '');
                $student_id = trim($post['student_id'] ?? '');
                $university = trim($post['university'] ?? '');
                $faculty = trim($post['faculty'] ?? '');
                $department = trim($post['department'] ?? '');
                $grade = trim($post['grade'] ?? '');
                $phone = trim($post['phone'] ?? '');

                if (empty($first_name) || empty($last_name) || empty($email)) {
                    throw new Exception("Ad, Soyad ve E-posta zorunludur.");
                }

                if ($action === 'create') {
                    // Check email uniqueness
                    $stmt = $this->pdo->prepare("SELECT id FROM app_members WHERE email = ?");
                    $stmt->execute([$email]);
                    if ($stmt->fetch()) throw new Exception("Bu e-posta adresi zaten kayıtlı.");

                    $stmt = $this->pdo->prepare("INSERT INTO app_members (app_id, department_id, created_by, first_name, last_name, email, student_id, university, faculty, department, grade, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
                    $stmt->execute([$appId, $deptId, $userId, $first_name, $last_name, $email, $student_id, $university, $faculty, $department, $grade, $phone]);
                    $_SESSION['flash_success'] = 'Üye başarıyla eklendi.';
                } else {
                    $stmt = $this->pdo->prepare("UPDATE app_members SET first_name=?, last_name=?, email=?, student_id=?, university=?, faculty=?, department=?, grade=?, phone=? WHERE id=?");
                    $stmt->execute([$first_name, $last_name, $email, $student_id, $university, $faculty, $department, $grade, $phone, $memberId]);
                    $_SESSION['flash_success'] = 'Üye güncellendi.';
                }
            } elseif ($action === 'delete') {
                if ($this->userRole !== 'director' && !(Auth::isSuperAdmin() || Auth::isPresidentOrVice())) throw new Exception("Yetkiniz yok.");
                $memberId = (int)($post['member_id'] ?? 0);
                $stmt = $this->pdo->prepare("DELETE FROM app_members WHERE id=?");
                $stmt->execute([$memberId]);
                $_SESSION['flash_success'] = 'Üye silindi.';
            } elseif ($action === 'suspend') {
                if ($this->userRole !== 'director' && !(Auth::isSuperAdmin() || Auth::isPresidentOrVice())) throw new Exception("Yetkiniz yok.");
                $memberId = (int)($post['member_id'] ?? 0);
                $reason = trim($post['suspend_reason'] ?? '');
                $stmt = $this->pdo->prepare("UPDATE app_members SET status='suspended', suspend_reason=? WHERE id=?");
                $stmt->execute([$reason, $memberId]);
                $_SESSION['flash_success'] = 'Üye kırmızı listeye eklendi.';
            } elseif ($action === 'restore') {
                if ($this->userRole !== 'director' && !(Auth::isSuperAdmin() || Auth::isPresidentOrVice())) throw new Exception("Yetkiniz yok.");
                $memberId = (int)($post['member_id'] ?? 0);
                $stmt = $this->pdo->prepare("UPDATE app_members SET status='active', suspend_reason=NULL WHERE id=?");
                $stmt->execute([$memberId]);
                $_SESSION['flash_success'] = 'Üye aktif listeye alındı.';
            } elseif ($action === 'upload_excel') {
                $this->handleExcelUpload();
            } elseif ($action === 'download_template') {
                require_once __DIR__ . '/../core/SimpleXLSXGen.php';
                $data = [
                    ['ID', 'Ad', 'Soyad', 'E-posta', 'Öğrenci No', 'Üniversite', 'Fakülte', 'Bölüm', 'Sınıf', 'Telefon']
                ];
                \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs('uye_sablonu.xlsx');
                exit;
            } elseif ($action === 'export_excel') {
                require_once __DIR__ . '/../core/SimpleXLSXGen.php';
                $stmt = $this->pdo->prepare("SELECT id, first_name, last_name, email, student_id, university, faculty, department, grade, phone, status, created_at FROM app_members ORDER BY id ASC");
                $stmt->execute();
                $membersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = [
                    ['ID', 'Ad', 'Soyad', 'E-posta', 'Öğrenci No', 'Üniversite', 'Fakülte', 'Bölüm', 'Sınıf', 'Telefon', 'Durum', 'Kayıt Tarihi']
                ];

                foreach ($membersData as $m) {
                    $data[] = [
                        $m['id'],
                        $m['first_name'],
                        $m['last_name'],
                        $m['email'],
                        $m['student_id'],
                        $m['university'],
                        $m['faculty'],
                        $m['department'],
                        $m['grade'],
                        $m['phone'],
                        $m['status'] === 'active' ? 'Aktif' : 'Askıda',
                        $m['created_at']
                    ];
                }

                \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs('uyeler_' . date('Ymd_His') . '.xlsx');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
        }

        $this->redirectBack();
    }

    private function handleExcelUpload(): void
    {
        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Dosya yüklenemedi.");
        }

        $ext = strtolower(pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'xlsx') {
            throw new Exception("Sadece .xlsx uzantılı dosyalar kabul edilir.");
        }

        require_once __DIR__ . '/../core/SimpleXLSX.php';

        if ($xlsx = \Shuchkin\SimpleXLSX::parse($_FILES['excel_file']['tmp_name'])) {
            $rows = $xlsx->rows();
            if (count($rows) <= 1) {
                throw new Exception("Excel dosyası boş veya sadece başlık içeriyor.");
            }

            $appId = $this->app['id'];
            $deptId = $this->department['id'];
            $userId = $this->user['id'] ?? null;
            $successCount = 0;
            $skipCount = 0;

            for ($i = 1; $i < count($rows); $i++) {
                $r = $rows[$i];
                if (empty($r[1]) || empty($r[3])) continue; // Ad ve E-posta zorunlu

                $first_name = trim($r[1]);
                $last_name = trim($r[2] ?? '');
                $email = trim($r[3]);
                $student_id = trim($r[4] ?? '');
                $university = trim($r[5] ?? '');
                $faculty = trim($r[6] ?? '');
                $department = trim($r[7] ?? '');
                $grade = trim($r[8] ?? '');
                $phone = trim($r[9] ?? '');

                // Benzersizlik kontrolü
                $stmt = $this->pdo->prepare("SELECT id FROM app_members WHERE (email=? OR (student_id=? AND student_id != ''))");
                $stmt->execute([$email, $student_id]);
                if ($stmt->fetch()) {
                    $skipCount++;
                    continue; // Mevcut kayıt, atla
                }

                $ins = $this->pdo->prepare("INSERT INTO app_members (app_id, department_id, created_by, first_name, last_name, email, student_id, university, faculty, department, grade, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
                $ins->execute([$appId, $deptId, $userId, $first_name, $last_name, $email, $student_id, $university, $faculty, $department, $grade, $phone]);
                $successCount++;
            }

            if ($skipCount > 0) {
                $_SESSION['flash_success'] = "{$successCount} üye başarıyla eklendi. {$skipCount} kayıt (öğrenci numarası veya e-posta zaten kayıtlı olduğu için) atlandı.";
            } else {
                $_SESSION['flash_success'] = "{$successCount} üye başarıyla eklendi.";
            }
        } else {
            throw new Exception("Excel dosyası okunamadı: " . \Shuchkin\SimpleXLSX::parseError());
        }
    }

    public function renderPublic(): string
    {
        ob_start();
        extract([
            'app' => $this->app,
            'dept' => $this->department
        ]);
        require __DIR__ . '/../views/modules/members_public.php';
        return ob_get_clean();
    }

    public function handlePostPublic(array $post): void
    {
        $appId = $this->app['id'];
        $deptId = $this->department['id'];

        try {
            $first_name = trim($post['first_name'] ?? '');
            $last_name = trim($post['last_name'] ?? '');
            $email = trim($post['email'] ?? '');
            $student_id = trim($post['student_id'] ?? '');
            $university = trim($post['university'] ?? '');
            $faculty = trim($post['faculty'] ?? '');
            $department = trim($post['department'] ?? '');
            $grade = trim($post['grade'] ?? '');
            $phone = trim($post['phone'] ?? '');

            if (empty($first_name) || empty($last_name) || empty($email)) {
                throw new Exception("Ad, Soyad ve E-posta alanları zorunludur.");
            }

            // Check email uniqueness
            $stmt = $this->pdo->prepare("SELECT id FROM app_members WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) throw new Exception("Bu e-posta adresi zaten kayıtlı.");

            $stmt = $this->pdo->prepare("INSERT INTO app_members (app_id, department_id, first_name, last_name, email, student_id, university, faculty, department, grade, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
            $stmt->execute([$appId, $deptId, $first_name, $last_name, $email, $student_id, $university, $faculty, $department, $grade, $phone]);

            $_SESSION['flash_success'] = 'Başvurunuz başarıyla alınmıştır!';
            header("Location: " . BASE_URL . "/f/{$this->department['slug']}/{$this->app['slug']}?success=1");
            exit;

        } catch (Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            header("Location: " . BASE_URL . "/f/{$this->department['slug']}/{$this->app['slug']}");
            exit;
        }
    }

    private function redirectBack()
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}";
        header("Location: $url");
        exit;
    }
}
