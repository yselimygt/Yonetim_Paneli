<?php

require_once __DIR__ . '/../models/User.php';

class ProfileController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        Auth::requireLogin();
        
        $user = $this->userModel->getById((int) Auth::user()['id']);
        
        // Departmanları al (header/navbar'da "Ana Sayfaya Dön" yönlendirmesi veya iptal için)
        $departments = Auth::getDepartments();
        
        // view çağrısı
        ob_start();
        require __DIR__ . '/../views/profile/index.php';
        echo ob_get_clean();
    }

    public function update(): void
    {
        Auth::requireLogin();
        $this->csrfCheck();

        $user = Auth::user();
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? ''; // Güvenlik: şifrelerde trim() kullanma
        $phone = trim($_POST['phone'] ?? '');
        $university = trim($_POST['university'] ?? '');
        $faculty = trim($_POST['faculty'] ?? '');
        $school_department = trim($_POST['school_department'] ?? '');
        $grade = trim($_POST['grade'] ?? '');
        $linkedin = trim($_POST['linkedin'] ?? '');
        $github = trim($_POST['github'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $universityLogo = trim($_POST['university_logo'] ?? '');
        $companyLogo = trim($_POST['company_logo'] ?? '');
        $communityHistory = $this->historyFromPost();
        $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
        $theme_color = !empty($_POST['theme_color']) ? trim($_POST['theme_color']) : null;

        if (empty($name) || empty($email)) {
            $this->flashError('Ad ve E-posta alanları zorunludur.');
            $this->redirect('/profile');
        }

        // Şifre politikası kontrolü
        if (!empty($password) && strlen($password) < MIN_PASSWORD_LENGTH) {
            $this->flashError('Şifre en az ' . MIN_PASSWORD_LENGTH . ' karakter olmalıdır.');
            $this->redirect('/profile');
        }

        $pdo = Database::getInstance();

        // E-posta başkası tarafından kullanılıyor mu?
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user['id']]);
        if ($stmt->fetch()) {
            $this->flashError('Bu e-posta adresi zaten kullanılıyor.');
            $this->redirect('/profile');
        }

        // Avatar upload
        $avatarPath = $user['avatar'];
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            // Güvenlik: Uzantı + gerçek MIME type kontrolü
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $realMime = finfo_file($finfo, $_FILES['avatar']['tmp_name']);
            finfo_close($finfo);

            if (in_array($ext, $allowed) && in_array($realMime, $allowedMimes)) {
                require_once __DIR__ . '/../core/ImageOptimizer.php';
                
                // Optimize and save as WebP
                $filename = 'avatar_' . $user['id'] . '_' . time() . '.webp';
                $targetDir = __DIR__ . '/../public/uploads/avatars/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $targetPath = $targetDir . $filename;
                
                if (ImageOptimizer::optimizeAndSave($_FILES['avatar']['tmp_name'], $targetPath, 800, 80)) {
                    if ($avatarPath && file_exists(__DIR__ . '/../public/' . $avatarPath)) {
                        @unlink(__DIR__ . '/../public/' . $avatarPath);
                    }
                    $avatarPath = 'uploads/avatars/' . $filename;
                }
            } else {
                $this->flashError('Profil fotoğrafı için sadece JPG, PNG ve GIF yüklenebilir.');
                $this->redirect('/profile');
            }
        }

        // Güncelleme sorgusu
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE users
                SET name = ?, email = ?, password = ?, phone = ?, university = ?, faculty = ?, school_department = ?, grade = ?,
                    linkedin = ?, github = ?, website = ?, bio = ?, university_logo = ?, company_logo = ?, community_history_json = ?,
                    birth_date = ?, avatar = ?, theme_color = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $name, $email, $hashed, $phone, $university, $faculty, $school_department, $grade,
                $linkedin, $github, $website, $bio, $universityLogo, $companyLogo, json_encode($communityHistory, JSON_UNESCAPED_UNICODE),
                $birth_date, $avatarPath, $theme_color, $user['id']
            ]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE users
                SET name = ?, email = ?, phone = ?, university = ?, faculty = ?, school_department = ?, grade = ?,
                    linkedin = ?, github = ?, website = ?, bio = ?, university_logo = ?, company_logo = ?, community_history_json = ?,
                    birth_date = ?, avatar = ?, theme_color = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $name, $email, $phone, $university, $faculty, $school_department, $grade,
                $linkedin, $github, $website, $bio, $universityLogo, $companyLogo, json_encode($communityHistory, JSON_UNESCAPED_UNICODE),
                $birth_date, $avatarPath, $theme_color, $user['id']
            ]);
        }

        // Oturum bilgilerini güncelle
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_university'] = $university;
        $_SESSION['user_faculty'] = $faculty;
        $_SESSION['user_school_department'] = $school_department;
        $_SESSION['user_grade'] = $grade;
        $_SESSION['user_birth_date'] = $birth_date;
        $_SESSION['user_avatar'] = $avatarPath;
        $_SESSION['user_linkedin'] = $linkedin;
        $_SESSION['user_github'] = $github;
        $_SESSION['user_website'] = $website;
        $_SESSION['user_bio'] = $bio;
        $_SESSION['user_university_logo'] = $universityLogo;
        $_SESSION['user_company_logo'] = $companyLogo;
        $_SESSION['user_community_history_json'] = json_encode($communityHistory, JSON_UNESCAPED_UNICODE);
        $_SESSION['user_theme_color'] = $theme_color;

        // Mezun profili varsa senkronize et
        $stmt = $pdo->prepare("UPDATE alumni_profiles SET full_name = ?, email = ?, phone = ?, university = ?, major = ?, avatar_path = ? WHERE user_id = ?");
        $stmt->execute([$name, $email, $phone, $university, $school_department, $avatarPath, $user['id']]);

        Logger::log('UPDATE_PROFILE', 'User', $user['id'], ['name' => $name, 'email' => $email]);
        $this->flashSuccess('Profil bilgileriniz başarıyla güncellendi.');
        $this->redirect('/profile');
    }

    private function historyFromPost(): array
    {
        $titles = $_POST['history_title'] ?? [];
        $departments = $_POST['history_dept'] ?? [];
        $start_months = $_POST['history_start_month'] ?? [];
        $start_years = $_POST['history_start_year'] ?? [];
        $end_months = $_POST['history_end_month'] ?? [];
        $end_years = $_POST['history_end_year'] ?? [];
        $currents = $_POST['history_current'] ?? [];
        // Mevcut period verisini de yakalayalım (eski form gönderimleri için uyumluluk)
        $periods = $_POST['history_period'] ?? [];
        $history = [];

        foreach ($titles as $i => $title) {
            $title = trim($title);
            $dept = trim($departments[$i] ?? '');
            
            // Eğer yeni tarih seçicilerden veri gelirse onları kullan
            $start_month = trim($start_months[$i] ?? '');
            $start_year = trim($start_years[$i] ?? '');
            
            // Checkbox kontrolü - html formlarında checkbox name="history_current[$i]" şeklinde gelirse dizi indislerini check et
            $is_current = isset($currents[$i]) && $currents[$i] == '1';
            
            $end_month = $is_current ? '' : trim($end_months[$i] ?? '');
            $end_year = $is_current ? '' : trim($end_years[$i] ?? '');

            // Dönem (period) stringini oluştur (ör: Eylül 2022 - Devam Ediyor veya Eylül 2022 - Haziran 2023)
            $period = trim($periods[$i] ?? '');
            if ($start_month || $start_year) {
                $start_str = trim("$start_month $start_year");
                if ($is_current) {
                    $period = "$start_str - Devam Ediyor";
                } else if ($end_month || $end_year) {
                    $end_str = trim("$end_month $end_year");
                    $period = "$start_str - $end_str";
                } else {
                    $period = $start_str;
                }
            }

            if ($title === '' && $dept === '' && $period === '') {
                continue;
            }

            $history[] = [
                'title' => $title,
                'role' => $title,
                'dept' => $dept,
                'period' => $period,
                'year' => $period,
                'start_month' => $start_month,
                'start_year' => $start_year,
                'end_month' => $end_month,
                'end_year' => $end_year,
                'is_current' => $is_current
            ];
        }

        return $history;
    }
}
