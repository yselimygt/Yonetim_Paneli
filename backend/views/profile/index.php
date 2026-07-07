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
    <script>
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:       #0a0a0a;
            --card:     #111111;
            --border:   #1e1e1e;
            --text:     #e8e8e8;
            --muted:    #555;
            --accent:   #00BCD4;
            --input-bg: #0d0d0d;
        }

        [data-theme="light"] {
            --bg:       #f3f4f6;
            --card:     #ffffff;
            --border:   #e5e7eb;
            --text:     #111827;
            --muted:    #6b7280;
            --input-bg: #f9fafb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 18px; font-weight: 600; color: var(--text); }
        .header p { font-size: 13px; color: var(--muted); margin-top: 6px; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px 36px;
            width: 100%;
            max-width: 760px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            transition: box-shadow 0.3s ease;
        }
        
        [data-theme="dark"] .card {
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        /* Flash */
        .flash { border-radius: 10px; padding: 12px 16px; font-size: 13px; margin-bottom: 24px; }
        .flash.error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); color:#fca5a5; }
        .flash.success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.3); color:#86efac; }
        .flash.warning { background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:#fcd34d; }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 11px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 8px; }
        .form-control { width: 100%; background: var(--input-bg); border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; color: var(--text); font-family: 'Inter', sans-serif; font-size: 14px; outline: none; transition: all .2s ease; }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15); }
        .form-control::file-selector-button { border: 1px solid var(--border); background: var(--card); color: var(--text); padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: background 0.2s; margin-right: 12px; }
        .form-control::file-selector-button:hover { background: rgba(255,255,255,0.05); }
        
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;
            cursor: pointer; border: none; text-decoration: none;
            width: 100%;
            transition: all .2s ease;
        }
        .btn:active { transform: scale(0.96); }
        .btn-primary { background: var(--accent); color: #0a0a0a; box-shadow: 0 4px 14px rgba(0, 188, 212, 0.3); }
        .btn-primary:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0, 188, 212, 0.4); }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .avatar-preview { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border); box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto; }
        .section-title { margin: 28px 0 16px; padding-top: 22px; border-top: 1px solid var(--border); }
        .section-title h2 { font-size: 15px; font-weight: 700; color: var(--text); }
        .section-title p { font-size: 12px; color: var(--muted); margin-top: 5px; line-height: 1.5; }
        .history-list { display: grid; gap: 10px; }
        .history-item { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px; align-items: center; padding: 10px; border: 1px solid var(--border); border-radius: 12px; background: rgba(255,255,255,.02); }
        .btn-outline { background: transparent; color: var(--text); border: 1px solid var(--border); box-shadow: none; }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm { width: auto; padding: 8px 12px; font-size: 12px; }
        
        .footer-links {
            display: flex;
            gap: 20px;
            margin-top: 32px;
        }

        .footer-links a {
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
            cursor: pointer;
        }
        .footer-links a:hover { color: var(--text); }
        .hint { font-size: 11px; color: var(--muted); margin-top: 4px; display: block; }
        @media (max-width: 720px) {
            .card { padding: 24px 20px; }
            .form-row,
            .history-item { grid-template-columns: 1fr; }
            .btn-sm { width: 100%; }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Profilim</h1>
    <p>Hesap bilgilerinizi güncelleyin</p>
</div>

<div class="card">
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash <?= View::escape($_SESSION['flash']['type']) ?>">
            <?= View::escape($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/profile" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= View::escape($_SESSION['csrf_token']) ?>">
        
        <div class="form-group">
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= BASE_URL ?>/public/<?= View::escape($user['avatar']) ?>" class="avatar-preview" alt="Avatar">
            <?php endif; ?>
            <label class="form-label">Profil Fotoğrafı</label>
            <input type="file" name="avatar" class="form-control" accept="image/*">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Ad Soyad</label>
                <input type="text" name="name" class="form-control" value="<?= View::escape($user['name']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">E-posta</label>
                <input type="email" name="email" class="form-control" value="<?= View::escape($user['email']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Telefon Numarası</label>
                <input type="text" name="phone" class="form-control" value="<?= View::escape($user['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Doğum Tarihi</label>
                <input type="date" name="birth_date" class="form-control" value="<?= View::escape($user['birth_date'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group" style="width:100%;">
                <label class="form-label">Kişisel Tema Rengi (Panel Görünümü)</label>
                <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                    <input type="color" id="color_picker" class="form-control" style="width:60px; height:45px; padding:4px; cursor:pointer;" value="<?= View::escape($user['theme_color'] ?? '#00BCD4') ?>" onchange="document.getElementById('theme_color').value = this.value;">
                    <input type="text" id="theme_color" name="theme_color" class="form-control" style="width:120px;" placeholder="Örn: #00BCD4" value="<?= View::escape($user['theme_color'] ?? '') ?>" oninput="document.getElementById('color_picker').value = this.value || '#00BCD4';">
                    <button type="button" class="btn btn-outline btn-sm" style="height:45px;" onclick="document.getElementById('theme_color').value = ''; document.getElementById('color_picker').value='#00BCD4';">Varsayılana Dön</button>
                    <small style="color:var(--text-muted)">Yönetim panelinin tüm görünümünü bu renge boyayın.</small>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Üniversite</label>
                <select id="select_university" class="form-control" onchange="uniChanged()">
                    <option value="">Seçiniz...</option>
                </select>
                <input type="text" name="university" id="profile_university" class="form-control" style="display:none; margin-top:8px;" placeholder="Üniversite adını giriniz" value="<?= View::escape($user['university'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Fakülte</label>
                <select id="select_faculty" class="form-control" onchange="facChanged()">
                    <option value="">Önce Üniversite Seçin</option>
                </select>
                <input type="text" name="faculty" id="profile_faculty" class="form-control" style="display:none; margin-top:8px;" placeholder="Fakülte/Yüksekokul adını giriniz" value="<?= View::escape($user['faculty'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Bölüm</label>
                <select id="select_department" class="form-control" onchange="deptChanged()">
                    <option value="">Önce Fakülte Seçin</option>
                </select>
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

        <div class="section-title">
            <h2>Web Sitesi Ekip Profili</h2>
            <p>Bu bilgiler süper admin sizi ekip alanında görünür yaptığında web sitesindeki profil kartınızda kullanılır. Süper admin alanında girilen özel değerler varsa öncelik onlardadır.</p>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">LinkedIn URL</label>
                <input type="url" name="linkedin" class="form-control" value="<?= View::escape($user['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/kullanici">
            </div>
            <div class="form-group">
                <label class="form-label">GitHub URL</label>
                <input type="url" name="github" class="form-control" value="<?= View::escape($user['github'] ?? '') ?>" placeholder="https://github.com/kullanici">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Web Sitesi</label>
                <input type="text" name="website" class="form-control" value="<?= View::escape($user['website'] ?? '') ?>" placeholder="https://...">
            </div>
            <div class="form-group">
                <label class="form-label">Üniversite Logo URL</label>
                <input type="text" name="university_logo" id="profile_university_logo" class="form-control" value="<?= View::escape($user['university_logo'] ?? '') ?>" placeholder="/innomis/assets/uni/..." readonly>
                <span class="hint">Üniversite seçimine göre otomatik doldurulur.</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Şirket / Kurum Logo URL</label>
            <input type="text" name="company_logo" class="form-control" value="<?= View::escape($user['company_logo'] ?? '') ?>">
            <span class="hint">Şirket logosu varsa ekip modalında üniversite logosunun yerine kullanılabilir.</span>
        </div>

        <div class="form-group">
            <label class="form-label">Kısa Biyografi</label>
            <textarea name="bio" class="form-control" rows="3" placeholder="Kendinizi, ilgi alanlarınızı veya ekipteki odağınızı yazın."><?= View::escape($user['bio'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Topluluk Kariyer Geçmişi</label>
            <div id="historyList" class="history-list">
                <?php if (empty($communityHistory)): ?>
                    <div class="history-item">
                        <input type="text" name="history_title[]" class="form-control" placeholder="Görev / Ünvan">
                        <input type="text" name="history_dept[]" class="form-control" placeholder="Departman / Topluluk">
                        <input type="text" name="history_period[]" class="form-control" placeholder="Dönem">
                        <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">Sil</button>
                    </div>
                <?php else: ?>
                    <?php foreach ($communityHistory as $item): ?>
                        <div class="history-item">
                            <input type="text" name="history_title[]" class="form-control" placeholder="Görev / Ünvan" value="<?= View::escape($item['title'] ?? $item['role'] ?? '') ?>">
                            <input type="text" name="history_dept[]" class="form-control" placeholder="Departman / Topluluk" value="<?= View::escape($item['dept'] ?? '') ?>">
                            <input type="text" name="history_period[]" class="form-control" placeholder="Dönem" value="<?= View::escape($item['period'] ?? $item['year'] ?? '') ?>">
                            <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">Sil</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-outline btn-sm" style="margin-top:10px;" onclick="addHistoryItem()">+ Geçmiş Ekle</button>
        </div>

        <div class="form-group">
            <label class="form-label">Yeni Şifre</label>
            <input type="password" name="password" class="form-control">
            <span class="hint">Şifrenizi değiştirmek istemiyorsanız boş bırakın.</span>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Kaydet</button>
    </form>
</div>

<div class="footer-links">
    <?php if (Auth::isSuperAdmin()): ?>
        <a href="<?= BASE_URL ?>/superadmin">Geri Dön</a>
    <?php elseif (count($departments) === 1): ?>
        <a href="<?= BASE_URL ?>/panel/<?= View::escape($departments[0]['slug']) ?>">Geri Dön</a>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/panel">Geri Dön</a>
    <?php endif; ?>
    <a onclick="toggleTheme()">Tema Değiştir</a>
</div>

<script src="<?= BASE_URL ?>/public/assets/admin/js/universities_data.js"></script>
<script>
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

function addHistoryItem(title = '', dept = '', period = '') {
    const html = `
        <div class="history-item">
            <input type="text" name="history_title[]" class="form-control" placeholder="Görev / Ünvan" value="${escapeAttr(title)}">
            <input type="text" name="history_dept[]" class="form-control" placeholder="Departman / Topluluk" value="${escapeAttr(dept)}">
            <input type="text" name="history_period[]" class="form-control" placeholder="Dönem" value="${escapeAttr(period)}">
            <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">Sil</button>
        </div>
    `;
    document.getElementById('historyList').insertAdjacentHTML('beforeend', html);
}
</script>
</body>
</html>
