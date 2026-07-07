<?php
$communityHistory = json_decode($user['community_history_json'] ?? '[]', true) ?: [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Profilim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:       #050505;
            --card:     #0d0d0d;
            --card-alt: #141414;
            --border:   #222;
            --text:     #ededed;
            --muted:    #888;
            --accent:   <?= View::escape($_SESSION['user_theme_color'] ?? '#00BCD4') ?>;
            --accent-rgb: 0, 188, 212;
            --input-bg: #111;
            --input-border: #333;
            --glass: rgba(255, 255, 255, 0.03);
            --danger: #ef4444;
            --success: #22c55e;
        }

        [data-theme="light"] {
            --bg:       #f3f4f6;
            --card:     #ffffff;
            --card-alt: #f9fafb;
            --border:   #e5e7eb;
            --text:     #111827;
            --muted:    #6b7280;
            --input-bg: #ffffff;
            --input-border: #d1d5db;
            --glass: rgba(0, 0, 0, 0.03);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 40px 24px 100px 24px;
        }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .header h1 { font-size: 24px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 10px; }
        .header p { font-size: 14px; color: var(--muted); margin-top: 4px; }
        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* Bento Grid */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
        }

        .bento-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: border-color 0.3s;
        }
        .bento-card:hover {
            border-color: var(--accent);
        }

        .bento-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 24px;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bento-title i {
            color: var(--accent);
            font-size: 18px;
        }

        /* Specific Cards */
        .card-personal { grid-column: span 4; }
        .card-contact { grid-column: span 8; }
        .card-education { grid-column: span 6; }
        .card-history { grid-column: span 6; }
        .card-preferences { grid-column: span 12; }

        @media (max-width: 1024px) {
            .card-personal, .card-contact, .card-education, .card-history { grid-column: span 12; }
        }

        /* Avatar Uploader */
        .avatar-uploader {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 24px;
        }
        .avatar-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--card);
            box-shadow: 0 0 0 2px var(--accent);
            cursor: pointer;
        }
        .avatar-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .avatar-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            opacity: 0;
            transition: opacity 0.2s;
            font-size: 12px;
            font-weight: 500;
            gap: 4px;
        }
        .avatar-wrapper:hover .avatar-overlay {
            opacity: 1;
        }
        .avatar-overlay i { font-size: 24px; }
        #avatarInput { display: none; }

        /* Form Elements */
        .form-group { margin-bottom: 20px; width: 100%; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; width: 100%; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
        
        .form-label { display: block; font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 8px; }
        .form-control { 
            width: 100%; 
            background: var(--input-bg); 
            border: 1px solid var(--input-border); 
            border-radius: 12px; 
            padding: 12px 16px; 
            color: var(--text); 
            font-family: 'Inter', sans-serif; 
            font-size: 14px; 
            outline: none; 
            transition: all .2s ease; 
        }
        .form-control:focus { 
            border-color: var(--accent); 
            background: var(--card-alt);
        }
        .form-control::placeholder { color: var(--muted); opacity: 0.7; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 20px; border-radius: 12px;
            font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; border: none; text-decoration: none;
            transition: all .2s ease;
        }
        .btn:active { transform: scale(0.97); }
        .btn-primary { background: var(--accent); color: #000; }
        .btn-primary:hover { filter: brightness(1.1); box-shadow: 0 4px 14px rgba(var(--accent-rgb), 0.3); }
        .btn-outline { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--glass); border-color: var(--accent); color: var(--accent); }
        .btn-sm { padding: 8px 14px; font-size: 13px; border-radius: 8px; }

        /* History List */
        .history-list { display: flex; flex-direction: column; gap: 12px; }
        .history-item { 
            display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 12px; 
            align-items: center; padding: 16px; 
            border: 1px solid var(--border); border-radius: 14px; 
            background: var(--glass); 
        }
        @media (max-width: 600px) {
            .history-item { grid-template-columns: 1fr; }
            .history-item button { width: 100%; }
        }

        /* Sticky Footer Bar */
        .sticky-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: var(--card);
            border-top: 1px solid var(--border);
            padding: 16px 24px;
            display: flex;
            justify-content: center;
            z-index: 100;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
        }
        .sticky-footer-inner {
            width: 100%;
            max-width: 1100px;
            display: flex;
            justify-content: flex-end;
            gap: 16px;
        }

        /* Flash */
        .flash { border-radius: 12px; padding: 14px 20px; font-size: 14px; margin-bottom: 24px; display:flex; align-items:center; gap:10px; }
        .flash.error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); color:#fca5a5; }
        .flash.success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.3); color:#86efac; }
        
        .hint { font-size: 12px; color: var(--muted); margin-top: 6px; display: block; }
    </style>
</head>
<body>

<form method="POST" action="<?= BASE_URL ?>/profile" enctype="multipart/form-data" id="profileForm">
    <input type="hidden" name="csrf_token" value="<?= View::escape($_SESSION['csrf_token']) ?>">
    <input type="file" name="avatar" id="avatarInput" accept="image/*">

    <div class="container">
        
        <div class="header">
            <div>
                <h1><i class="ri-user-settings-line"></i> Profil Ayarları</h1>
                <p>Hesap bilgilerinizi ve tercihlerinizi yönetin.</p>
            </div>
            <div class="header-actions">
                <button type="button" class="btn btn-outline" onclick="toggleTheme()"><i class="ri-moon-line"></i> Tema</button>
                <?php if (Auth::isSuperAdmin()): ?>
                    <a href="<?= BASE_URL ?>/superadmin" class="btn btn-outline"><i class="ri-arrow-left-line"></i> Panele Dön</a>
                <?php elseif (count($departments) === 1): ?>
                    <a href="<?= BASE_URL ?>/panel/<?= View::escape($departments[0]['slug']) ?>" class="btn btn-outline"><i class="ri-arrow-left-line"></i> Panele Dön</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/panel" class="btn btn-outline"><i class="ri-arrow-left-line"></i> Panele Dön</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash <?= View::escape($_SESSION['flash']['type']) ?>">
                <i class="ri-information-line"></i> <?= View::escape($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <div class="profile-grid">
            
            <!-- Profil & Bio -->
            <div class="bento-card card-personal">
                <div class="bento-title"><i class="ri-id-card-line"></i> Temel Bilgiler</div>
                
                <div class="avatar-uploader" onclick="document.getElementById('avatarInput').click()">
                    <div class="avatar-wrapper">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= BASE_URL ?>/public/<?= View::escape($user['avatar']) ?>" id="avatarPreview" class="avatar-preview" alt="Avatar">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=random" id="avatarPreview" class="avatar-preview" alt="Avatar">
                        <?php endif; ?>
                        <div class="avatar-overlay">
                            <i class="ri-camera-fill"></i>
                            <span>Değiştir</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="name" class="form-control" value="<?= View::escape($user['name']) ?>" required>
                </div>
                
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Kısa Biyografi</label>
                    <textarea name="bio" class="form-control" placeholder="Kendinizi veya ekibinizdeki rolünüzü kısaca anlatın..."><?= View::escape($user['bio'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- İletişim & Sosyal Medya -->
            <div class="bento-card card-contact">
                <div class="bento-title"><i class="ri-contacts-book-line"></i> İletişim ve Sosyal Medya</div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">E-posta</label>
                        <input type="email" name="email" class="form-control" value="<?= View::escape($user['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telefon Numarası</label>
                        <input type="text" name="phone" class="form-control" value="<?= View::escape($user['phone'] ?? '') ?>" placeholder="5XX XXX XX XX">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">LinkedIn Profil URL</label>
                        <input type="url" name="linkedin" class="form-control" value="<?= View::escape($user['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/username">
                    </div>
                    <div class="form-group">
                        <label class="form-label">GitHub Profil URL</label>
                        <input type="url" name="github" class="form-control" value="<?= View::escape($user['github'] ?? '') ?>" placeholder="https://github.com/username">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Kişisel Web Sitesi</label>
                    <input type="url" name="website" class="form-control" value="<?= View::escape($user['website'] ?? '') ?>" placeholder="https://yourwebsite.com">
                </div>
            </div>

            <!-- Eğitim Bilgileri -->
            <div class="bento-card card-education">
                <div class="bento-title"><i class="ri-graduation-cap-line"></i> Eğitim Bilgileri</div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Üniversite</label>
                        <select id="select_university" class="form-control" onchange="uniChanged()"><option value="">Seçiniz...</option></select>
                        <input type="text" name="university" id="profile_university" class="form-control" style="display:none; margin-top:8px;" placeholder="Üniversite adını giriniz" value="<?= View::escape($user['university'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fakülte</label>
                        <select id="select_faculty" class="form-control" onchange="facChanged()"><option value="">Önce Üniversite Seçin</option></select>
                        <input type="text" name="faculty" id="profile_faculty" class="form-control" style="display:none; margin-top:8px;" placeholder="Fakülte adını giriniz" value="<?= View::escape($user['faculty'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bölüm</label>
                        <select id="select_department" class="form-control" onchange="deptChanged()"><option value="">Önce Fakülte Seçin</option></select>
                        <input type="text" name="school_department" id="profile_school_department" class="form-control" style="display:none; margin-top:8px;" placeholder="Bölüm adını giriniz" value="<?= View::escape($user['school_department'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sınıf</label>
                        <select id="select_grade" class="form-control" onchange="gradeChanged()">
                            <option value="">Seçiniz...</option>
                            <option value="Hazırlık">Hazırlık</option>
                            <option value="1. Sınıf">1. Sınıf</option>
                            <option value="2. Sınıf">2. Sınıf</option>
                            <option value="3. Sınıf">3. Sınıf</option>
                            <option value="4. Sınıf">4. Sınıf</option>
                            <option value="Mezun">Mezun</option>
                            <option value="Diğer">Diğer...</option>
                        </select>
                        <input type="text" name="grade" id="profile_grade" class="form-control" style="display:none; margin-top:8px;" placeholder="Sınıf bilgisi giriniz" value="<?= View::escape($user['grade'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row" style="margin-bottom:0;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Üni. Logo URL</label>
                        <input type="text" name="university_logo" id="profile_university_logo" class="form-control" value="<?= View::escape($user['university_logo'] ?? '') ?>" readonly>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Şirket Logo URL</label>
                        <input type="text" name="company_logo" class="form-control" value="<?= View::escape($user['company_logo'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Topluluk Geçmişi -->
            <div class="bento-card card-history">
                <div class="bento-title"><i class="ri-history-line"></i> Topluluk Kariyer Geçmişi</div>
                
                <?php
                $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
                $years = range(2024, 2035);
                
                $deptOptionsStr = '<option value="">Departman Seç</option>';
                foreach ($allDepartments as $d) {
                    $deptOptionsStr .= '<option value="' . View::escape($d['name']) . '">' . View::escape($d['name']) . '</option>';
                }
                $deptOptionsStr .= '<option value="Diğer">Diğer...</option>';
                ?>
                <div id="historyList" class="history-list">
                    <?php if (empty($communityHistory)): ?>
                        <!-- Boşken JS üzerinden tetikleyeceğiz, script'e bırakalım -->
                    <?php else: ?>
                        <?php foreach ($communityHistory as $item): 
                            $is_current = !empty($item['is_current']);
                            $isOther = !empty($item['dept']) && !in_array($item['dept'], array_column($allDepartments, 'name'));
                        ?>
                            <div class="history-item" style="grid-template-columns: 1fr auto; align-items: stretch;">
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    <div class="form-row">
                                        <div style="flex:1;">
                                            <input type="text" name="history_title[]" class="form-control" placeholder="Görev / Ünvan" value="<?= View::escape($item['title'] ?? $item['role'] ?? '') ?>">
                                        </div>
                                        <div style="flex:1;">
                                            <select class="form-control" onchange="
                                                const input = this.nextElementSibling;
                                                if(this.value === 'Diğer') {
                                                    input.style.display = 'block';
                                                    if(input.value === 'Diğer') input.value = '';
                                                } else {
                                                    input.style.display = 'none';
                                                    input.value = this.value;
                                                }
                                            ">
                                                <option value="">Departman Seç</option>
                                                <?php foreach($allDepartments as $d): ?>
                                                    <option value="<?= View::escape($d['name']) ?>" <?= ($item['dept'] ?? '') === $d['name'] ? 'selected' : '' ?>><?= View::escape($d['name']) ?></option>
                                                <?php endforeach; ?>
                                                <option value="Diğer" <?= $isOther ? 'selected' : '' ?>>Diğer...</option>
                                            </select>
                                            <input type="text" name="history_dept[]" class="form-control" placeholder="Departman Giriniz" value="<?= View::escape($item['dept'] ?? '') ?>" style="display: <?= $isOther ? 'block' : 'none' ?>; margin-top:8px;">
                                        </div>
                                    </div>
                                    <div class="form-row" style="gap: 12px;">
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                            <span style="font-size: 13px; color: var(--muted); min-width: 65px;">Başlangıç:</span>
                                            <select name="history_start_month[]" class="form-control" style="flex: 1; min-width: 90px; padding:8px 12px;">
                                                <option value="">Ay Seç</option>
                                                <?php foreach($months as $m): ?>
                                                    <option value="<?= $m ?>" <?= ($item['start_month'] ?? '') === $m ? 'selected' : '' ?>><?= $m ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <select name="history_start_year[]" class="form-control" style="width: 80px; padding:8px 8px;">
                                                <option value="">Yıl Seç</option>
                                                <?php foreach($years as $y): ?>
                                                    <option value="<?= $y ?>" <?= ($item['start_year'] ?? '') == $y ? 'selected' : '' ?>><?= $y ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                            <span style="font-size: 13px; color: var(--muted); min-width: 40px;">Bitiş:</span>
                                            <select name="history_end_month[]" class="form-control end-month" style="flex: 1; min-width: 90px; padding:8px 12px;" <?= $is_current ? 'disabled' : '' ?>>
                                                <option value="">Ay Seç</option>
                                                <?php foreach($months as $m): ?>
                                                    <option value="<?= $m ?>" <?= ($item['end_month'] ?? '') === $m ? 'selected' : '' ?>><?= $m ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <select name="history_end_year[]" class="form-control end-year" style="width: 80px; padding:8px 8px;" <?= $is_current ? 'disabled' : '' ?>>
                                                <option value="">Yıl Seç</option>
                                                <?php foreach($years as $y): ?>
                                                    <option value="<?= $y ?>" <?= ($item['end_year'] ?? '') == $y ? 'selected' : '' ?>><?= $y ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            
                                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--muted); cursor: pointer; user-select: none;">
                                                <input type="hidden" name="history_current[]" value="<?= $is_current ? '1' : '0' ?>">
                                                <input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? '1' : '0'; toggleEndDate(this)" <?= $is_current ? 'checked' : '' ?>> Devam
                                            </label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="history_period[]" value="<?= View::escape($item['period'] ?? $item['year'] ?? '') ?>">
                                </div>
                                <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()" style="display: flex; align-items: center; justify-content: center;"><i class="ri-delete-bin-line"></i></button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-outline btn-sm" style="margin-top:16px; align-self:flex-start;" onclick="addHistoryItem()"><i class="ri-add-line"></i> Yeni Geçmiş Ekle</button>
            </div>

            <!-- Tercihler ve Güvenlik -->
            <div class="bento-card card-preferences">
                <div class="bento-title"><i class="ri-settings-3-line"></i> Tercihler ve Güvenlik</div>
                
                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Kişisel Tema Rengi (Panel Vurgusu)</label>
                        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                            <input type="color" id="color_picker" class="form-control" style="width:60px; height:45px; padding:4px; cursor:pointer;" value="<?= View::escape($user['theme_color'] ?? '#00BCD4') ?>" onchange="document.getElementById('theme_color').value = this.value;">
                            <input type="text" id="theme_color" name="theme_color" class="form-control" style="width:140px;" placeholder="Örn: #00BCD4" value="<?= View::escape($user['theme_color'] ?? '') ?>" oninput="document.getElementById('color_picker').value = this.value || '#00BCD4';">
                            <button type="button" class="btn btn-outline btn-sm" style="height:45px;" onclick="document.getElementById('theme_color').value = ''; document.getElementById('color_picker').value='#00BCD4';"><i class="ri-refresh-line"></i> Sıfırla</button>
                        </div>
                        <span class="hint">Paneldeki ana renkleri kendi zevkinize göre ayarlayın.</span>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Doğum Tarihi</label>
                        <input type="date" name="birth_date" class="form-control" value="<?= View::escape($user['birth_date'] ?? '') ?>">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Yeni Şifre Belirle</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                        <span class="hint">Şifrenizi değiştirmek istemiyorsanız boş bırakın.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Sticky Footer -->
    <div class="sticky-footer">
        <div class="sticky-footer-inner">
            <button type="reset" class="btn btn-outline"><i class="ri-close-line"></i> Formu Temizle</button>
            <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i> Değişiklikleri Kaydet</button>
        </div>
    </div>
</form>

<script src="<?= BASE_URL ?>/public/assets/admin/js/universities_data.js"></script>
<script>
    // Avatar Preview
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    const initUni = <?= json_encode($user['university'] ?? '') ?>;
    const initFac = <?= json_encode($user['faculty'] ?? '') ?>;
    const initDept = <?= json_encode($user['school_department'] ?? '') ?>;
    const initGrade = <?= json_encode($user['grade'] ?? '') ?>;

    document.addEventListener('DOMContentLoaded', () => {
        const uniSelect = document.getElementById('select_university');
        if (typeof KON_UNIS !== 'undefined') {
            Object.keys(KON_UNIS).forEach(uni => {
                const opt = document.createElement('option');
                opt.value = uni;
                opt.textContent = uni;
                uniSelect.appendChild(opt);
            });
        }
        const otherOpt = document.createElement('option');
        otherOpt.value = 'Diğer';
        otherOpt.textContent = 'Diğer...';
        uniSelect.appendChild(otherOpt);

        if (initUni && typeof KON_UNIS !== 'undefined' && KON_UNIS[initUni]) {
            uniSelect.value = initUni;
            uniChanged();
            const fSelect = document.getElementById('select_faculty');
            if (initFac && KON_UNIS[initUni][initFac]) {
                fSelect.value = initFac;
                facChanged();
                const dSelect = document.getElementById('select_department');
                if (initDept && KON_UNIS[initUni][initFac].includes(initDept)) {
                    dSelect.value = initDept;
                    deptChanged();
                } else {
                    dSelect.value = 'Diğer';
                    deptChanged();
                }
            } else {
                fSelect.value = 'Diğer';
                facChanged();
            }
        } else if (initUni) {
            uniSelect.value = 'Diğer';
            uniChanged();
        }

        const gSelect = document.getElementById('select_grade');
        const validGrades = ["Hazırlık", "1. Sınıf", "2. Sınıf", "3. Sınıf", "4. Sınıf", "Mezun"];
        if (initGrade && validGrades.includes(initGrade)) {
            gSelect.value = initGrade;
            gradeChanged();
        } else if (initGrade) {
            gSelect.value = 'Diğer';
            gradeChanged();
        }
    });

    function getLogoForUniversity(uniName) {
        if (!uniName) return '';
        const uni = uniName.toLowerCase();
        const base = '';
        if (uni.includes('necmettin erbakan') || uni.includes('neu')) return base + '/assets/uni/neuLogo.png';
        if (uni.includes('selçuk') || uni.includes('selcuk')) return base + '/assets/uni/selcukLogo.png';
        if (uni.includes('teknik') || uni.includes('ktün') || uni.includes('ktun')) return base + '/assets/uni/ktunLogo.png';
        if (uni.includes('kto') || uni.includes('karatay')) return base + '/assets/uni/ktoLogo.png';
        if (uni.includes('gıda ve tarım') || uni.includes('gida ve tarim')) return base + '/assets/uni/kgtuLogo.png';
        return '';
    }

    function uniChanged() {
        const val = document.getElementById('select_university').value;
        const input = document.getElementById('profile_university');
        const facSelect = document.getElementById('select_faculty');
        const deptSelect = document.getElementById('select_department');
        const logoInput = document.getElementById('profile_university_logo');

        facSelect.innerHTML = '<option value="">Önce Üniversite Seçin</option>';
        deptSelect.innerHTML = '<option value="">Önce Fakülte Seçin</option>';
        document.getElementById('profile_faculty').style.display = 'none';
        document.getElementById('profile_school_department').style.display = 'none';

        if (val === 'Diğer') {
            input.style.display = 'block';
            if(input.value === 'Diğer') input.value = '';
            facSelect.innerHTML = '<option value="Diğer">Diğer...</option>';
            document.getElementById('profile_faculty').style.display = 'block';
            deptSelect.innerHTML = '<option value="Diğer">Diğer...</option>';
            document.getElementById('profile_school_department').style.display = 'block';
            logoInput.value = '';
        } else if (val) {
            input.style.display = 'none';
            input.value = val;
            logoInput.value = getLogoForUniversity(val);
            facSelect.innerHTML = '<option value="">Seçiniz...</option>';
            if (typeof KON_UNIS !== 'undefined' && KON_UNIS[val]) {
                Object.keys(KON_UNIS[val]).forEach(fac => {
                    const opt = document.createElement('option');
                    opt.value = fac;
                    opt.textContent = fac;
                    facSelect.appendChild(opt);
                });
            }
            facSelect.innerHTML += '<option value="Diğer">Diğer...</option>';
        } else {
            input.style.display = 'none';
            input.value = '';
            logoInput.value = '';
        }
    }

    function facChanged() {
        const uniVal = document.getElementById('select_university').value;
        const facVal = document.getElementById('select_faculty').value;
        const input = document.getElementById('profile_faculty');
        const deptSelect = document.getElementById('select_department');

        deptSelect.innerHTML = '<option value="">Seçiniz...</option>';
        document.getElementById('profile_school_department').style.display = 'none';

        if (facVal === 'Diğer') {
            input.style.display = 'block';
            if(input.value === 'Diğer') input.value = '';
            deptSelect.innerHTML = '<option value="Diğer">Diğer...</option>';
            document.getElementById('profile_school_department').style.display = 'block';
        } else if (facVal && uniVal && typeof KON_UNIS !== 'undefined' && KON_UNIS[uniVal]) {
            input.style.display = 'none';
            input.value = facVal;
            if (KON_UNIS[uniVal][facVal]) {
                KON_UNIS[uniVal][facVal].forEach(dept => {
                    const opt = document.createElement('option');
                    opt.value = dept;
                    opt.textContent = dept;
                    deptSelect.appendChild(opt);
                });
            }
            deptSelect.innerHTML += '<option value="Diğer">Diğer...</option>';
        } else {
            input.style.display = 'none';
            input.value = '';
            deptSelect.innerHTML = '<option value="">Önce Fakülte Seçin</option>';
        }
    }

    function deptChanged() {
        const val = document.getElementById('select_department').value;
        const input = document.getElementById('profile_school_department');
        if (val === 'Diğer') {
            input.style.display = 'block';
            if(input.value === 'Diğer') input.value = '';
        } else if (val) {
            input.style.display = 'none';
            input.value = val;
        } else {
            input.style.display = 'none';
            input.value = '';
        }
    }

    function gradeChanged() {
        const val = document.getElementById('select_grade').value;
        const input = document.getElementById('profile_grade');
        if (val === 'Diğer') {
            input.style.display = 'block';
            if(input.value === 'Diğer') input.value = '';
        } else if (val) {
            input.style.display = 'none';
            input.value = val;
        } else {
            input.style.display = 'none';
            input.value = '';
        }
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme') || 'dark';
        const next = current === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
    }

    function escapeAttr(value) {
        return String(value || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function toggleEndDate(checkbox) {
        const container = checkbox.closest('.form-row');
        const endMonth = container.querySelector('.end-month');
        const endYear = container.querySelector('.end-year');
        if (checkbox.checked) {
            endMonth.disabled = true;
            endMonth.value = '';
            endYear.disabled = true;
            endYear.value = '';
        } else {
            endMonth.disabled = false;
            endYear.disabled = false;
        }
    }

    function addHistoryItem(title = '', dept = '', period = '') {
        const months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
        let optionsHtml = '<option value="">Ay Seç</option>';
        months.forEach(m => optionsHtml += `<option value="${m}">${m}</option>`);

        let yearOptionsHtml = '<option value="">Yıl Seç</option>';
        for(let y = 2024; y <= 2035; y++) {
            yearOptionsHtml += `<option value="${y}">${y}</option>`;
        }

        const html = `
            <div class="history-item" style="grid-template-columns: 1fr auto; align-items: stretch;">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div class="form-row">
                        <div style="flex:1;">
                            <input type="text" name="history_title[]" class="form-control" placeholder="Görev / Ünvan" value="${escapeAttr(title)}">
                        </div>
                        <div style="flex:1;">
                            <select class="form-control" onchange="
                                const input = this.nextElementSibling;
                                if(this.value === 'Diğer') {
                                    input.style.display = 'block';
                                    if(input.value === 'Diğer') input.value = '';
                                } else {
                                    input.style.display = 'none';
                                    input.value = this.value;
                                }
                            ">
                                <?= $deptOptionsStr ?>
                            </select>
                            <input type="text" name="history_dept[]" class="form-control" placeholder="Departman Giriniz" value="${escapeAttr(dept)}" style="display: ${dept ? 'block' : 'none'}; margin-top:8px;">
                        </div>
                    </div>
                    <div class="form-row" style="gap: 12px;">
                        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                            <span style="font-size: 13px; color: var(--muted); min-width: 65px;">Başlangıç:</span>
                            <select name="history_start_month[]" class="form-control" style="flex: 1; min-width: 90px; padding:8px 12px;">${optionsHtml}</select>
                            <select name="history_start_year[]" class="form-control" style="width: 80px; padding:8px 8px;">${yearOptionsHtml}</select>
                        </div>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                            <span style="font-size: 13px; color: var(--muted); min-width: 40px;">Bitiş:</span>
                            <select name="history_end_month[]" class="form-control end-month" style="flex: 1; min-width: 90px; padding:8px 12px;">${optionsHtml}</select>
                            <select name="history_end_year[]" class="form-control end-year" style="width: 80px; padding:8px 8px;">${yearOptionsHtml}</select>
                            
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--muted); cursor: pointer; user-select: none;">
                                <input type="hidden" name="history_current[]" value="0">
                                <input type="checkbox" onchange="this.previousElementSibling.value = this.checked ? '1' : '0'; toggleEndDate(this)"> Devam
                            </label>
                        </div>
                    </div>
                    <input type="hidden" name="history_period[]" value="${escapeAttr(period)}">
                </div>
                <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()" style="display: flex; align-items: center; justify-content: center;"><i class="ri-delete-bin-line"></i></button>
            </div>
        `;
        document.getElementById('historyList').insertAdjacentHTML('beforeend', html);
    }
    
    // Sayfa yüklendiğinde boş geçmiş varsa 1 tane ekleyelim
    if (document.getElementById('historyList').children.length === 0) {
        addHistoryItem();
    }
</script>
</body>
</html>
