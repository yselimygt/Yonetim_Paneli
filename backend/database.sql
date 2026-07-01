-- ============================
-- NexPanel — Veritabanı Şeması
-- ============================

CREATE DATABASE IF NOT EXISTS `innomis-nexpanel` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `innomis-nexpanel`;

-- -------
-- Tablo: users
-- -------
CREATE TABLE users (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100) NOT NULL,
    email          VARCHAR(150) UNIQUE NOT NULL,
    password       VARCHAR(255) NOT NULL,
    is_super_admin TINYINT(1) DEFAULT 0,
    can_manage_passwords TINYINT(1) DEFAULT 0,
    is_active      TINYINT(1) DEFAULT 1,
    phone          VARCHAR(50) NULL,
    university     VARCHAR(150) NULL,
    faculty        VARCHAR(150) NULL,
    school_department VARCHAR(150) NULL,
    grade          VARCHAR(50) NULL,
    avatar         VARCHAR(255) NULL,
    birth_date     DATE NULL,
    remember_token VARCHAR(64) NULL,      -- Güvenlik: "Beni Hatırla" token hash'i (sha256)
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================
-- Tablo: site_team_members
-- ============================
CREATE TABLE IF NOT EXISTS site_team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100),
    department VARCHAR(100),
    type ENUM('board','department','campus') DEFAULT 'department',
    image_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    instagram_url VARCHAR(255),
    email VARCHAR(255),
    quote TEXT,
    bio TEXT,
    join_year INT,
    status ENUM('active','alumni') DEFAULT 'active',
    community_history_json JSON,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- Tablo: site_events
-- ============================
CREATE TABLE IF NOT EXISTS site_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATETIME DEFAULT NULL,
    location VARCHAR(255) DEFAULT NULL,
    category VARCHAR(100) DEFAULT NULL,
    tag_color VARCHAR(50) DEFAULT NULL,
    status VARCHAR(50) DEFAULT 'upcoming',
    image_url VARCHAR(255) DEFAULT NULL,
    target_date DATETIME DEFAULT NULL,
    registration_link VARCHAR(255) DEFAULT NULL,
    speakers JSON DEFAULT NULL,
    agenda JSON DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- Tablo: site_settings
-- ============================
CREATE TABLE IF NOT EXISTS site_settings (
    key_name VARCHAR(100) PRIMARY KEY,
    json_value JSON
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- Tablo: site_projects
-- ============================
CREATE TABLE IF NOT EXISTS site_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    status VARCHAR(50),
    tech_stack_json JSON,
    github_link VARCHAR(255),
    demo_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- Tablo: site_history
-- ============================
CREATE TABLE IF NOT EXISTS site_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year VARCHAR(20),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(100),
    type VARCHAR(50) DEFAULT 'achievement',
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================
-- Tablo: site_gallery
-- ============================
CREATE TABLE IF NOT EXISTS site_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255) NOT NULL,
    category VARCHAR(100) DEFAULT 'Genel',
    title VARCHAR(255),
    span_class VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------
-- Tablo: departments
-- -------
CREATE TABLE departments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NULL,
    icon        VARCHAR(50) NULL,
    color       VARCHAR(7) DEFAULT '#00BCD4',
    is_active   TINYINT(1) DEFAULT 1,
    is_management TINYINT(1) DEFAULT 0, -- Yönetim Paneli ayrıcalığı
    created_by  INT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- -------
-- Tablo: user_departments (pivot)
-- -------
CREATE TABLE user_departments (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    department_id INT NOT NULL,
    role          ENUM('director', 'member') DEFAULT 'member',
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_dept (user_id, department_id),
    FOREIGN KEY (user_id)       REFERENCES users(id)       ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
);

-- -------
-- Tablo: apps
-- -------
CREATE TABLE apps (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    slug          VARCHAR(100) NOT NULL,
    description   TEXT NULL,
    module_type   VARCHAR(50) NOT NULL,
    department_id INT NOT NULL,
    icon          VARCHAR(50) NULL,
    sort_order    INT DEFAULT 0,
    is_active     TINYINT(1) DEFAULT 1,
    director_only TINYINT(1) DEFAULT 0,
    is_global_module TINYINT(1) DEFAULT 0,
    config        JSON NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_app_dept_slug (department_id, slug),
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
);

-- -------
-- Modül Veri Tabloları
-- -------
CREATE TABLE form_entries (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    user_id       INT NULL,
    data          JSON NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE tracker_items (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    created_by    INT NOT NULL,
    title         VARCHAR(255) NOT NULL,
    status        VARCHAR(100) NOT NULL,
    data          JSON NULL,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE
);

CREATE TABLE announcements (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    author_id     INT NOT NULL,
    title         VARCHAR(255) NOT NULL,
    content       TEXT NOT NULL,
    is_pinned     TINYINT(1) DEFAULT 0,
    is_global     TINYINT(1) DEFAULT 0, -- Yönetim Paneli küresel duyurusu
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE calendar_events (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    created_by    INT NOT NULL,
    title         VARCHAR(255) NOT NULL,
    description   TEXT NULL,
    event_date    DATE NOT NULL,
    event_time    TIME NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE
);

CREATE TABLE sponsors (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    created_by    INT NOT NULL,
    name          VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255) NULL,
    email         VARCHAR(150) NULL,
    phone         VARCHAR(50) NULL,
    logo_url      VARCHAR(255) NULL,
    sponsorship_type VARCHAR(100) NULL,
    status        VARCHAR(50) DEFAULT 'active',
    notes         TEXT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE
);

CREATE TABLE app_members (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    created_by    INT NULL,
    first_name    VARCHAR(100) NOT NULL,
    last_name     VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL,
    student_id    VARCHAR(50) NULL,
    university    VARCHAR(150) NULL,
    faculty       VARCHAR(150) NULL,
    department    VARCHAR(150) NULL,
    grade         VARCHAR(50) NULL,
    phone         VARCHAR(50) NULL,
    status        VARCHAR(50) DEFAULT 'active',
    suspend_reason TEXT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY uq_app_email (app_id, email)
);

CREATE TABLE app_directory (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    app_id        INT NOT NULL,
    department_id INT NOT NULL,
    created_by    INT NULL,
    full_name     VARCHAR(200) NOT NULL,
    company       VARCHAR(200) NULL,
    title         VARCHAR(200) NULL,
    emails        TEXT NULL,
    phones        TEXT NULL,
    links         TEXT NULL,
    notes         TEXT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================
-- SEED — Super Admin
-- ============================
-- Şifre: password  (bcrypt cost=10, PHP 7.4+ ile üretildi)
INSERT INTO users (name, email, password, is_super_admin, is_active) VALUES

('Innox Admin', 'innox@innomis.tr',
 '$2y$10$Tu0//Ewobk.RnuWVigz76e2X0ma8ay1IHCZMc6EHH.V.IMWcSKjyi',
 1, 1);

-- ============================
-- SEED — 7 Varsayılan Departman
-- ============================
INSERT INTO departments (name, slug, description, icon, color, is_management, created_by) VALUES
('Yönetim',           'yonetim',            'Başkan ve Yardımcılarının yönetim paneli. Tüm departmanları izleyebilir.', 'crown',           '#F59E0B', 1, 1),
('Mezunlar',          'mezunlar',           'Eski üyelerin iletişimde kaldığı mezun paneli.',                            'graduation-cap',  '#8B5CF6', 0, 1),
('Sosyal Medya',      'sosyal-medya',       'Sosyal medya planlama ve içerik yönetimi.',                                 'share-2',         '#EC4899', 0, 1),
('İnsan Kaynakları',  'insan-kaynaklari',   'Üye alımı ve insan kaynakları takibi.',                                     'users',           '#10B981', 0, 1),
('Kurumsal İlişkiler','kurumsal-iliskiler', 'Kurum dışı ilişkiler ve sponsorluk süreçleri.',                             'briefcase',       '#3B82F6', 0, 1),
('Ar-Ge & Teknoloji', 'arge-teknoloji',     'Teknik projelerin ve AR-GE süreçlerinin yönetimi.',                         'cpu',             '#00BCD4', 0, 1),
('Organizasyon',      'organizasyon',       'Etkinlik ve toplantı planlama.',                                             'calendar',        '#F97316', 0, 1);


CREATE TABLE IF NOT EXISTS app_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_id INT NOT NULL,
    department_id INT NOT NULL,
    type ENUM('physical', 'online') NOT NULL,
    title VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME DEFAULT NULL,
    location_or_platform VARCHAR(255) DEFAULT NULL,
    description TEXT,
    link VARCHAR(255) DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    category VARCHAR(100) DEFAULT NULL,
    status TINYINT(1) DEFAULT 1,
    is_global TINYINT(1) DEFAULT 1,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE drive_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_id INT NOT NULL,
    department_id INT NOT NULL,
    created_by INT NOT NULL,
    parent_id INT NULL, 
    type ENUM('folder', 'file') NOT NULL,
    name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NULL,
    file_size BIGINT NULL,
    mime_type VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (app_id) REFERENCES apps(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES drive_items(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100) NULL,
    entity_id INT NULL,
    ip_address VARCHAR(45) NULL,
    details JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
