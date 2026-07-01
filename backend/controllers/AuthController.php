<?php

require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // GET /login
    public function showLogin(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirectAfterLogin();
        }
        $this->view('auth/login', [], 'auth');
    }

    // POST /login
    public function processLogin(): void
    {
        $this->csrfCheck();

        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password'] ?? ''; // Güvenlik: şifrelerde trim() kullanma
        $remember = !empty($_POST['remember_me']);
        $captcha  = trim($_POST['captcha']  ?? '');

        if (empty($email) || empty($password)) {
            $this->flashError('E-posta ve şifre gerekli.');
            $this->redirect('/login');
        }

        // CAPTCHA kontrolü
        if (!Auth::verifyCaptcha($captcha)) {
            $this->flashError('Robot doğrulaması hatalı. Lütfen tekrar deneyin.');
            $this->redirect('/login');
        }

        // Brute-force koruması: Hesap kilitlenme kontrolü
        if ($this->isAccountLocked($email)) {
            $this->flashError('Çok fazla başarısız giriş denemesi. Lütfen ' . LOGIN_LOCKOUT_MINUTES . ' dakika sonra tekrar deneyin.');
            $this->redirect('/login');
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Logger::log('LOGIN_FAILED', 'User', null, ['email' => $email]);
            $this->flashError('E-posta veya şifre hatalı.');
            $this->redirect('/login');
        }

        if (!$user['is_active']) {
            $this->flashError('Hesabınız devre dışı bırakılmış. Yönetici ile iletişime geçin.');
            $this->redirect('/login');
        }

        Auth::login($user, $remember);
        Logger::log('LOGIN_SUCCESS', 'User', $user['id'], ['email' => $user['email']]);
        $this->redirectAfterLogin();
    }

    // POST /logout (CSRF korumalı)
    public function logout(): void
    {
        // GET isteğini de kabul et (geriye uyumluluk + navbar linkleri)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->csrfCheck();
        }
        Logger::log('LOGOUT', 'User', Auth::user()['id']);
        Auth::logout();
    }

    // -------------------------------------------------------
    // Brute-force koruması
    // -------------------------------------------------------
    private function isAccountLocked(string $email): bool
    {
        $pdo = Database::getInstance();
        $lockoutWindow = date('Y-m-d H:i:s', time() - (LOGIN_LOCKOUT_MINUTES * 60));

        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM system_logs
            WHERE action = 'LOGIN_FAILED'
              AND details LIKE ?
              AND created_at > ?
        ");
        // LIKE wildcard escape
        $safeEmail = str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $email);
        $stmt->execute(['%"email":"' . $safeEmail . '"%', $lockoutWindow]);
        $failedAttempts = (int) $stmt->fetchColumn();

        return $failedAttempts >= MAX_LOGIN_ATTEMPTS;
    }

    // -------------------------------------------------------
    // Login sonrası rol bazlı yönlendirme
    // -------------------------------------------------------
    private function redirectAfterLogin(): void
    {
        if (Auth::isSuperAdmin()) {
            $this->redirect('/superadmin');
        }

        $departments = Auth::getDepartments();

        if (count($departments) === 0) {
            $this->flashWarning('Henüz bir departmana atanmadınız. Yönetici ile iletişime geçin.');
            $this->redirect('/login');
        }

        if (count($departments) === 1) {
            $this->redirect('/panel/' . $departments[0]['slug']);
        }

        // 2+ departman → seçim ekranı
        $this->redirect('/panel');
    }
}

