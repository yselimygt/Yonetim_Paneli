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
        
        $user = Auth::user();
        
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
        $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;

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
                $filename = 'avatar_' . $user['id'] . '_' . time() . '.' . $ext;
                $targetDir = __DIR__ . '/../public/uploads/avatars/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $targetPath = $targetDir . $filename;
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
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
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, phone = ?, university = ?, faculty = ?, school_department = ?, grade = ?, birth_date = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$name, $email, $hashed, $phone, $university, $faculty, $school_department, $grade, $birth_date, $avatarPath, $user['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, university = ?, faculty = ?, school_department = ?, grade = ?, birth_date = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $university, $faculty, $school_department, $grade, $birth_date, $avatarPath, $user['id']]);
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

        // Mezun profili varsa senkronize et
        $stmt = $pdo->prepare("UPDATE alumni_profiles SET full_name = ?, email = ?, phone = ?, university = ?, major = ?, avatar_path = ? WHERE user_id = ?");
        $stmt->execute([$name, $email, $phone, $university, $school_department, $avatarPath, $user['id']]);

        Logger::log('UPDATE_PROFILE', 'User', $user['id'], ['name' => $name, 'email' => $email]);
        $this->flashSuccess('Profil bilgileriniz başarıyla güncellendi.');
        $this->redirect('/profile');
    }
}
