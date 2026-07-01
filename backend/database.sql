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
    linkedin       VARCHAR(255) NULL,
    github         VARCHAR(255) NULL,
    website        VARCHAR(255) NULL,
    bio            TEXT NULL,
    university_logo VARCHAR(255) NULL,
    company_logo   VARCHAR(255) NULL,
    community_history_json JSON NULL,
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
-- Tablo: site_team_profiles
-- -------
CREATE TABLE IF NOT EXISTS site_team_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    department_id INT NULL,
    display_name VARCHAR(150) NULL,
    display_role VARCHAR(150) NULL,
    display_department VARCHAR(150) NULL,
    display_type ENUM('board','department','campus') DEFAULT 'department',
    image_url VARCHAR(255) NULL,
    linkedin VARCHAR(255) NULL,
    github VARCHAR(255) NULL,
    bio TEXT NULL,
    community_history_json JSON NULL,
    is_visible TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_site_team_user_department (user_id, department_id),
    INDEX idx_site_team_visible (is_visible, display_type, sort_order),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
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

-- ============================
-- APP MODULE TABLES
-- ============================

CREATE TABLE IF NOT EXISTS `alumni_profiles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `user_id` INT,
    `full_name` VARCHAR(255),
    `email` VARCHAR(255),
    `phone` VARCHAR(255),
    `university` VARCHAR(255),
    `major` VARCHAR(255),
    `avatar_path` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_agile_sprints` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `name` VARCHAR(255),
    `start_date` DATE,
    `end_date` DATE,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_agile_tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `sprint_id` INT,
    `title` VARCHAR(255),
    `description` TEXT,
    `status` TINYINT(1) DEFAULT 1,`n    `is_completed` TINYINT(1) DEFAULT 0,
    `assignee_id` INT,
    `priority` VARCHAR(255),
    `created_by` VARCHAR(255),
    `is_approved` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_agile_task_comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `task_id` INT,
    `user_id` INT,
    `comment` TEXT,
    `file_path` VARCHAR(255),
    `file_name` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_audit_reports` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `reporter_id` INT,
    `subject` VARCHAR(255),
    `description` TEXT,
    `category` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_audit_notes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `report_id` INT,
    `user_id` INT,
    `note` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_bootcamps` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `title` VARCHAR(255),
    `type` VARCHAR(255),
    `location` VARCHAR(255),
    `description` TEXT,
    `start_date` DATE,
    `end_date` DATE,
    `instructor` VARCHAR(255),
    `quota` INT,
    `link` VARCHAR(255),
    `category` VARCHAR(255),
    `image_path` VARCHAR(255),
    `status` TINYINT(1) DEFAULT 1,`n    `is_completed` TINYINT(1) DEFAULT 0,
    `created_by` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_budget_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `requester_id` INT,
    `title` VARCHAR(255),
    `amount` INT,
    `currency` VARCHAR(255),
    `reason` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_bylaw_documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `title` VARCHAR(255),
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_bylaw_versions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `document_id` INT,
    `version_number` VARCHAR(255),
    `content` TEXT,
    `changes_summary` TEXT,
    `is_published` TINYINT(1) DEFAULT 1,
    `created_by` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_certificate_templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `title` VARCHAR(255),
    `description` TEXT,
    `template_type` VARCHAR(255),
    `background_path` VARCHAR(255),
    `canvas_width` VARCHAR(255),
    `canvas_height` VARCHAR(255),
    `fields_json` VARCHAR(255),
    `linked_form_app_id` INT,
    `form_field_map` VARCHAR(255),
    `status` TINYINT(1) DEFAULT 1,`n    `is_completed` TINYINT(1) DEFAULT 0,
    `created_by` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_certificates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `template_id` INT,
    `app_id` INT,
    `department_id` INT,
    `recipient_name` VARCHAR(255),
    `recipient_email` VARCHAR(255),
    `field_values` VARCHAR(255),
    `certificate_no` VARCHAR(255),
    `issue_date` DATE,
    `expiry_date` DATE,
    `notes` VARCHAR(255),
    `status` TINYINT(1) DEFAULT 1,`n    `is_completed` TINYINT(1) DEFAULT 0,
    `created_by` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_ideas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `user_id` INT,
    `title` VARCHAR(255),
    `description` TEXT,
    `category` VARCHAR(255),
    `status` TINYINT(1) DEFAULT 1,`n    `is_completed` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_idea_votes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `idea_id` INT,
    `user_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_idea_comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `idea_id` INT,
    `user_id` INT,
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_inventory` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `item_name` VARCHAR(255),
    `category` VARCHAR(255),
    `serial_number` VARCHAR(255),
    `price` VARCHAR(255),
    `location` VARCHAR(255),
    `assigned_to` VARCHAR(255),
    `status` TINYINT(1) DEFAULT 1,`n    `is_completed` TINYINT(1) DEFAULT 0,
    `purchase_date` DATE,
    `notes` VARCHAR(255),
    `image_url` VARCHAR(255),
    `created_by` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_meetings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `created_by` VARCHAR(255),
    `title` VARCHAR(255),
    `meeting_date` DATE,
    `location` VARCHAR(255),
    `agenda` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_meeting_approvals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `meeting_id` INT,
    `user_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_mentorship_pairs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `mentor_id` INT,
    `mentee_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_mentorship_notes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `pair_id` INT,
    `writer_id` INT,
    `note` TEXT,
    `meeting_date` DATE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_partners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `venue_name` VARCHAR(255),
    `address` VARCHAR(255),
    `map_link` VARCHAR(255),
    `discount_detail` VARCHAR(255),
    `contact_person` VARCHAR(255),
    `contact_phone` VARCHAR(255),
    `image_path` VARCHAR(255),
    `status` TINYINT(1) DEFAULT 1,`n    `is_completed` TINYINT(1) DEFAULT 0,
    `created_by` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `passwords` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `department_id` INT,
    `app_id` INT,
    `title` VARCHAR(255),
    `url` VARCHAR(255),
    `username` VARCHAR(255),
    `encrypted_password` VARCHAR(255),
    `notes` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_social_posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `creator_id` INT,
    `assigned_to` VARCHAR(255),
    `platform` VARCHAR(255),
    `publish_date` DATE,
    `title` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_social_post_comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `post_id` INT,
    `user_id` INT,
    `comment` TEXT,
    `file_path` VARCHAR(255),
    `file_name` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_trophies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `title` VARCHAR(255),
    `description` TEXT,
    `award_type` VARCHAR(255),
    `award_date` DATE,
    `image_path` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_wiki_articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `app_id` INT,
    `department_id` INT,
    `author_id` INT,
    `title` VARCHAR(255),
    `content` TEXT,
    `category` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
