SET NAMES utf8mb4;
SET @user_id = 2;
SET @user_name = 'Yavuz Selim Yiğit';
SET @user_email = 'mail@yavuzselimyigit.com';

-- Mevcut departman ve app ID'lerini kullanalım
SET @dept_id = 6; -- Ar-Ge & Teknoloji

-- Kullanıcıyı departmana ata (Direktör)
INSERT IGNORE INTO user_departments (user_id, department_id, role) VALUES (@user_id, @dept_id, 'director');

-- Site Team Profile
INSERT IGNORE INTO site_team_profiles (user_id, department_id, display_name, display_role, display_department, display_type, bio, is_visible)
VALUES (@user_id, @dept_id, @user_name, 'Direktör', 'Ar-Ge & Teknoloji', 'board', 'Yazılım Geliştirici ve Kurucu Üye.', 1);

-- App Events
INSERT INTO app_events (app_id, department_id, type, title, description, event_date, location_or_platform, created_by, is_global)
VALUES (1, @dept_id, 'physical', 'Geliştirici Buluşması', 'Teknik toplantı.', DATE_ADD(NOW(), INTERVAL 1 DAY), 'Ofis', @user_id, 1);

-- Site Events (Halka Açık)
INSERT INTO site_events (title, description, event_date, location, category, status) 
VALUES ('Yapay Zeka ve Gelecek', 'Yapay zeka ekosistemi.', DATE_ADD(NOW(), INTERVAL 2 DAY), 'Online', 'Seminer', 'upcoming');

-- Bootcamps
INSERT INTO app_bootcamps (app_id, department_id, title, type, location, description, start_date, end_date, created_by)
VALUES (2, @dept_id, 'Web Geliştirme Kampı 2026', 'Eğitim', 'Online', 'Modern web eğitim programı.', NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), @user_id);

-- Form Entries (Varsayılan Form yanıtı)
INSERT INTO form_entries (app_id, department_id, user_id, data) 
VALUES (6, @dept_id, @user_id, JSON_OBJECT('name', @user_name, 'feedback', 'Sistem harika.'));

-- Sponsorlar
INSERT INTO sponsors (app_id, department_id, created_by, name, sponsorship_type) 
VALUES (15, @dept_id, @user_id, 'ViperaDev', 'Teknoloji Sponsoru');

-- Tracker Items
INSERT INTO tracker_items (app_id, department_id, created_by, title, status) 
VALUES (22, @dept_id, @user_id, 'InnoMIS v2 Paneli', 'done');

-- Projeler
INSERT INTO site_projects (title, description, status) 
VALUES ('InnoMIS Connect', 'Topluluk yönetim sistemi.', 'completed');

-- Başarı/Ödül
INSERT INTO site_history (year, title, description, type) 
VALUES (2026, 'En İnovatif Topluluk Ödülü', 'Yavuz Selim Yiğit ile alındı.', 'achievement');
