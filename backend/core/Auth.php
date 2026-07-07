<?php

class Auth
{
    // -------------------------------------------------------
    // Oturum Başlatma
    // -------------------------------------------------------
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Güvenlik: HTTPS algılandığında secure flag otomatik aktif olur
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 0) == 443;
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path'     => '/',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }

        // CSRF token üret (yoksa)
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Math CAPTCHA — her oturumda yeni oluştur
        if (empty($_SESSION['captcha_answer'])) {
            self::refreshCaptcha();
        }

        // "Beni Hatırla" — cookie varsa otomatik oturum aç
        if (!self::isLoggedIn() && isset($_COOKIE['remember_token'])) {
            self::loginFromCookie($_COOKIE['remember_token']);
        }
    }

    // -------------------------------------------------------
    // Giriş İşlemi
    // -------------------------------------------------------
    // -------------------------------------------------------
    // Math CAPTCHA
    // -------------------------------------------------------
    public static function refreshCaptcha(): void
    {
        $a = random_int(2, 12);
        $b = random_int(1, 9);
        $op  = '+';

        $answer = $a + $b;

        $_SESSION['captcha_question'] = "{$a} {$op} {$b} = ?";
        $_SESSION['captcha_answer']   = (string) $answer;
    }

    public static function getCaptchaQuestion(): string
    {
        return $_SESSION['captcha_question'] ?? '? + ? = ?';
    }

    public static function verifyCaptcha(string $input): bool
    {
        $valid = isset($_SESSION['captcha_answer']) &&
                 hash_equals($_SESSION['captcha_answer'], trim($input));
        // Her denemeden sonra yenile
        self::refreshCaptcha();
        return $valid;
    }

    // -------------------------------------------------------
    public static function login(array $user, bool $remember = false): void
    {
        session_regenerate_id(true); // session fixation koruması

        $_SESSION['user_id']       = $user['id'];
        $_SESSION['user_name']     = $user['name'];
        $_SESSION['user_email']    = $user['email'];
        $_SESSION['user_phone']    = $user['phone'] ?? null;
        $_SESSION['user_university'] = $user['university'] ?? null;
        $_SESSION['user_faculty']  = $user['faculty'] ?? null;
        $_SESSION['user_school_department'] = $user['school_department'] ?? null;
        $_SESSION['user_grade']    = $user['grade'] ?? null;
        $_SESSION['user_avatar']   = $user['avatar'] ?? null;
        $_SESSION['user_linkedin'] = $user['linkedin'] ?? null;
        $_SESSION['user_github'] = $user['github'] ?? null;
        $_SESSION['user_website'] = $user['website'] ?? null;
        $_SESSION['user_bio'] = $user['bio'] ?? null;
        $_SESSION['user_university_logo'] = $user['university_logo'] ?? null;
        $_SESSION['user_company_logo'] = $user['company_logo'] ?? null;
        $_SESSION['user_community_history_json'] = $user['community_history_json'] ?? null;
        $_SESSION['user_theme_color'] = $user['theme_color'] ?? null;
        $_SESSION['is_super_admin'] = (bool) $user['is_super_admin'];

        // Departman atamalarını yükle
        self::loadDepartments($user['id']);

        // Beni Hatırla
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 0) == 443;
            setcookie('remember_token', $token, [
                'expires'  => time() + (REMEMBER_ME_DAYS * 86400),
                'path'     => '/',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            // Güvenlik: Token'i hash'leyerek DB'ye kaydet
            $hashedToken = hash('sha256', $token);
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->execute([$hashedToken, $user['id']]);
        }
    }

    private static function loginFromCookie(string $token): void
    {
        // Güvenlik: DB'de saklanan hash ile karşılaştır
        $hashedToken = hash('sha256', $token);
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$hashedToken]);
        $user = $stmt->fetch();

        if ($user) {
            // Oturumu başlat (session_regenerate_id login içinde yapılıyor)
            self::login($user, false);
        } else {
            // Geçersiz token — cookie'yi temizle
            setcookie('remember_token', '', ['expires' => time() - 3600, 'path' => '/']);
        }
    }

    private static function loadDepartments(int $userId): void
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT ud.department_id, ud.role, d.slug, d.name, d.color, d.icon, d.is_management
            FROM user_departments ud
            JOIN departments d ON d.id = ud.department_id
            WHERE ud.user_id = ? AND d.is_active = 1
        ");
        $stmt->execute([$userId]);
        $_SESSION['departments'] = $stmt->fetchAll();
    }

    // -------------------------------------------------------
    // Çıkış
    // -------------------------------------------------------
    public static function logout(): void
    {
        // Güvenlik: DB'deki remember token'ı temizle
        if (!empty($_SESSION['user_id'])) {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
        }
        session_unset();
        session_destroy();
        setcookie('remember_token', '', ['expires' => time() - 3600, 'path' => '/']);
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    // -------------------------------------------------------
    // Kontroller
    // -------------------------------------------------------
    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function isSuperAdmin(): bool
    {
        return !empty($_SESSION['is_super_admin']);
    }

    public static function isManagement(): bool
    {
        foreach (($_SESSION['departments'] ?? []) as $dept) {
            if ($dept['slug'] === MANAGEMENT_DEPT_SLUG) {
                return true;
            }
        }
        return false;
    }

    public static function isPresidentOrVice(): bool
    {
        foreach (($_SESSION['departments'] ?? []) as $dept) {
            if (in_array($dept['role'], ['president', 'vice_president'], true)) {
                return true;
            }
        }
        return false;
    }

    public static function isDirectorOf(string $slug): bool
    {
        if (self::isSuperAdmin() || self::isPresidentOrVice()) return true;

        foreach (($_SESSION['departments'] ?? []) as $dept) {
            if ($dept['slug'] === $slug && in_array($dept['role'], ['director', 'president', 'vice_president'], true)) {
                return true;
            }
        }
        return false;
    }

    public static function hasDepartmentAccess(string $slug): bool
    {
        if (self::isSuperAdmin() || self::isPresidentOrVice()) return true;
        foreach (($_SESSION['departments'] ?? []) as $dept) {
            if ($dept['slug'] === $slug) return true;
        }
        return false;
    }

    public static function getDepartments(): array
    {
        return $_SESSION['departments'] ?? [];
    }

    public static function user(): array
    {
        return [
            'id'            => $_SESSION['user_id']       ?? null,
            'name'          => $_SESSION['user_name']     ?? '',
            'email'         => $_SESSION['user_email']    ?? '',
            'phone'         => $_SESSION['user_phone']    ?? '',
            'university'    => $_SESSION['user_university'] ?? '',
            'faculty'       => $_SESSION['user_faculty']  ?? '',
            'school_department' => $_SESSION['user_school_department'] ?? '',
            'grade'         => $_SESSION['user_grade']    ?? '',
            'avatar'        => $_SESSION['user_avatar']   ?? '',
            'linkedin'      => $_SESSION['user_linkedin'] ?? '',
            'github'        => $_SESSION['user_github'] ?? '',
            'website'       => $_SESSION['user_website'] ?? '',
            'bio'           => $_SESSION['user_bio'] ?? '',
            'university_logo' => $_SESSION['user_university_logo'] ?? '',
            'company_logo'  => $_SESSION['user_company_logo'] ?? '',
            'community_history_json' => $_SESSION['user_community_history_json'] ?? '',
            'is_super_admin'=> $_SESSION['is_super_admin'] ?? false,
        ];
    }

    // -------------------------------------------------------
    // Guard'lar — Controller'ların ilk satırında kullan
    // -------------------------------------------------------
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function requireSuperAdmin(): void
    {
        self::requireLogin();
        if (!self::isSuperAdmin()) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }

    public static function requireDepartment(string $slug): void
    {
        self::requireLogin();
        if (!self::hasDepartmentAccess($slug)) {
            header('Location: ' . BASE_URL . '/panel');
            exit;
        }
    }

    public static function requireDirector(string $slug): void
    {
        self::requireDepartment($slug);
        if (!self::isDirectorOf($slug) && !self::isSuperAdmin()) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }
}
