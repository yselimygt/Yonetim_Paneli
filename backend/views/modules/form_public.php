<?php
// form_public.php
// $fields, $config
$themeColor = $app['config']['settings']['theme_color'] ?? '#6366f1';
$headerImage = $app['config']['settings']['header_image'] ?? '';

$hex = str_replace("#", "", $themeColor);
if(strlen($hex) == 6) {
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
} else {
    $r = 99; $g = 102; $b = 241;
}
$accentRgb = "$r, $g, $b";
?>
<style>
:root {
    --accent: <?= $themeColor ?>;
    --accent-rgb: <?= $accentRgb ?>;
}

/* Engaging Glassmorphism Theme */
body {
    background: radial-gradient(circle at top left, rgba(var(--accent-rgb), 0.3), transparent 40%),
                radial-gradient(circle at bottom right, rgba(var(--accent-rgb), 0.3), transparent 40%),
                #0f172a !important; /* Slate 900 */
    color: #f8fafc !important;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
    position: relative;
    overflow-x: hidden;
}

body::before {
    content: '';
    position: fixed;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: radial-gradient(circle, rgba(var(--accent-rgb), 0.1) 0%, transparent 50%);
    animation: rotate 30s linear infinite;
    z-index: -1;
    pointer-events: none;
}
@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.form-public-wrapper {
    width: 100%;
    max-width: 800px;
    margin: 40px auto;
    
    /* Glassmorphism Variables */
    --card: rgba(255, 255, 255, 0.05);
    --border: rgba(255, 255, 255, 0.1);
    --text: #f8fafc;
    --muted: #94a3b8;
    --input-bg: rgba(0, 0, 0, 0.2);
    --danger: #f87171;
}

.form-public-header {
    background: var(--card);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--border);
    border-top: 4px solid var(--accent);
    border-radius: 20px;
    padding: 32px 24px;
    margin-bottom: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.form-public-header h1 {
    margin: 0 0 12px 0;
    font-size: 28px;
    font-weight: 700;
}
.form-public-header p {
    color: var(--muted);
    font-size: 15px;
    margin: 0;
}

.public-question-card {
    background: var(--card);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease, background 0.3s ease;
}

.public-question-card:focus-within, .public-question-card:hover {
    border-color: rgba(var(--accent-rgb), 0.5);
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 20px rgba(var(--accent-rgb), 0.2);
    background: rgba(255, 255, 255, 0.08);
}

.q-title {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 16px;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
}
.q-title .req { color: var(--danger); }
.q-title .icon {
    color: var(--muted);
}

/* Custom inputs */
.inp-text, .inp-textarea, .inp-select, .inp-file {
    width: 100%;
    background: var(--input-bg);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text);
    padding: 14px 16px;
    border-radius: 12px;
    font-family: inherit;
    font-size: 15px;
    transition: all 0.3s ease;
}

.inp-text:focus, .inp-textarea:focus, .inp-select:focus, .inp-file:focus {
    border-color: var(--accent);
    outline: none;
    background: rgba(0, 0, 0, 0.4);
    box-shadow: 0 0 0 4px rgba(var(--accent-rgb), 0.2);
}

/* Phone input group */
.phone-group {
    display: flex;
    gap: 8px;
}
.phone-code {
    width: 100px;
    flex-shrink: 0;
}

/* Social input group */
.social-group {
    display: flex;
    align-items: center;
    background: var(--input-bg);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.social-group:focus-within {
    border-color: var(--accent);
    background: rgba(0, 0, 0, 0.4);
    box-shadow: 0 0 0 4px rgba(var(--accent-rgb), 0.2);
}
.social-icon {
    padding: 14px 16px;
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
}
.social-input {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text);
    padding: 14px 16px;
    font-size: 15px;
    outline: none;
}

/* Radio & Checkbox */
.rc-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    cursor: pointer;
}
.rc-item input {
    appearance: none;
    width: 20px;
    height: 20px;
    border: 2px solid var(--muted);
    border-radius: 50%;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    background: rgba(0, 0, 0, 0.2);
}
.rc-item input[type="checkbox"] { border-radius: 6px; }
.rc-item input:checked {
    border-color: var(--accent);
    background: var(--accent);
    box-shadow: 0 0 10px rgba(var(--accent-rgb), 0.5);
}
.rc-item input[type="radio"]:checked::after {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 8px; height: 8px;
    background: #fff;
    border-radius: 50%;
}
.rc-item input[type="checkbox"]:checked::after {
    content: '✓';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 12px;
}

.submit-btn {
    background: linear-gradient(135deg, var(--accent), rgba(var(--accent-rgb), 0.7));
    color: #fff;
    border: none;
    padding: 16px 32px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    box-shadow: 0 4px 15px rgba(var(--accent-rgb), 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
}
.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(var(--accent-rgb), 0.6);
}

/* Success Screen Animations & Styles */
.success-screen-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
    animation: successFadeIn 0.6s ease-out forwards;
}

.success-icon-container {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: rgba(var(--accent-rgb), 0.1);
    border: 2px solid var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    box-shadow: 0 0 30px rgba(var(--accent-rgb), 0.3);
    animation: successBounce 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    animation-delay: 0.2s;
    opacity: 0;
    transform: scale(0.5);
}

.success-icon-container svg {
    width: 44px;
    height: 44px;
    color: var(--accent);
}

@keyframes successFadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes successBounce {
    0% { opacity: 0; transform: scale(0.5); }
    100% { opacity: 1; transform: scale(1); }
}

.success-title {
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 12px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.success-message {
    font-size: 15px;
    color: var(--muted);
    margin-bottom: 32px;
    line-height: 1.6;
    max-width: 400px;
}

.btn-back-home {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
}
.btn-back-home:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}
</style>

<?php if(isset($_GET['success']) && $_GET['success'] == '1'): ?>
<div class="form-public-wrapper">
    <div class="public-question-card success-screen-wrapper">
        <div class="success-icon-container">
            <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 class="success-title">İşlem Başarılı!</h2>
        <p class="success-message"><?= View::escape($app['config']['settings']['success_message'] ?? 'Formunuz başarıyla gönderildi, teşekkür ederiz.') ?></p>
        <a href="<?= PUBLIC_URL ?>/f/<?= $app['slug'] ?>" class="btn-back-home">Yeni Yanıt Gönder</a>
    </div>
    
    <div style="font-size: 11px; display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 20px; opacity: 0.8;">
        <span>Powered by</span>
        <a href="https://viperadev.com" target="_blank" style="display: flex; align-items: center;">
            <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" style="height: 16px; opacity: 0.7; filter: grayscale(1); transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.filter='none';" onmouseout="this.style.opacity='0.7'; this.style.filter='grayscale(1)';">
        </a>
    </div>
</div>
<?php else: ?>
<div class="form-public-wrapper">
    <?php if(!empty($headerImage)): ?>
        <div class="form-header-banner" style="width: 100%; border-radius: 12px; overflow: hidden; margin-bottom: 24px; border: 1px solid var(--border, #2d3142);">
            <img src="<?= View::escape($headerImage) ?>" style="width: 100%; height: auto; max-height: 250px; object-fit: cover; display: block;">
        </div>
    <?php endif; ?>

    <div class="form-public-header">
        <h1><?= View::escape($app['name']) ?></h1>
        <?php 
            $desc = $app['config']['settings']['form_description'] ?? $app['description'] ?? '';
            if(!empty($desc)): 
        ?>
            <p><?= nl2br(View::escape($desc)) ?></p>
        <?php endif; ?>
    </div>

    <form method="POST" action="<?= PUBLIC_URL ?>/f/<?= $app['slug'] ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="submit">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <?php foreach ($fields as $field): 
            $name = $field['name'] ?? '';
            $type = $field['type'] ?? 'text';
            $label = $field['label'] ?? '';
            $req = !empty($field['required']) ? 'required' : '';
            $reqMark = $req ? '<span class="req">*</span>' : '';
        ?>
        <div class="public-question-card">
            <div class="q-title">
                <span class="icon">≡</span>
                <?= View::escape($label) ?> <?= $reqMark ?>
            </div>

            <?php if ($type === 'text' || $type === 'number' || $type === 'email' || $type === 'tc'): ?>
                <?php 
                    $inputType = ($type === 'email') ? 'email' : ($type === 'number' ? 'number' : 'text'); 
                    $placeholder = 'Yanıtınız...';
                    if ($type === 'tc') $placeholder = '11 Haneli TC Kimlik No';
                ?>
                <input type="<?= $inputType ?>" name="<?= $name ?>" class="inp-text" placeholder="<?= $placeholder ?>" <?= $req ?>>

            <?php elseif ($type === 'textarea'): ?>
                <textarea name="<?= $name ?>" class="inp-textarea" rows="4" placeholder="Yanıtınız..." <?= $req ?>></textarea>

            <?php elseif ($type === 'dropdown'): ?>
                <select name="<?= $name ?>" class="inp-select" <?= $req ?>>
                    <option value="">Seçiniz...</option>
                    <?php foreach ($field['options'] ?? [] as $opt): ?>
                        <option value="<?= View::escape($opt) ?>"><?= View::escape($opt) ?></option>
                    <?php endforeach; ?>
                </select>

            <?php elseif ($type === 'radio'): ?>
                <div class="rc-group">
                    <?php foreach ($field['options'] ?? [] as $opt): ?>
                        <label class="rc-item">
                            <input type="radio" name="<?= $name ?>" value="<?= View::escape($opt) ?>" <?= $req ?>>
                            <span><?= View::escape($opt) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

            <?php elseif ($type === 'checkbox'): ?>
                <div class="rc-group">
                    <?php foreach ($field['options'] ?? [] as $opt): ?>
                        <label class="rc-item">
                            <input type="checkbox" name="<?= $name ?>[]" value="<?= View::escape($opt) ?>">
                            <span><?= View::escape($opt) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <!-- Checkbox validation workaround -->
                <?php if($req): ?>
                    <input type="hidden" name="<?= $name ?>_required" value="1">
                <?php endif; ?>

            <?php elseif ($type === 'file'): ?>
                <input type="file" name="<?= $name ?>" class="inp-file" <?= $req ?> accept=".<?= implode(',.', $field['allowed_exts'] ?? ['jpg','png','pdf']) ?>">
                <div style="font-size:12px; color:var(--muted); margin-top:8px;">
                    Maksimum boyut: <?= $field['max_size'] ?? 5 ?>MB
                </div>

            <?php elseif ($type === 'phone'): ?>
                <div class="phone-group">
                    <select class="inp-select phone-code" name="<?= $name ?>_code">
                        <option value="+90">+90 (TR)</option>
                        <option value="+1">+1 (US)</option>
                        <option value="+44">+44 (UK)</option>
                        <option value="+49">+49 (DE)</option>
                    </select>
                    <input type="text" name="<?= $name ?>" class="inp-text phone-mask" placeholder="(555) 123 45 67" maxlength="15" <?= $req ?>>
                </div>

            <?php elseif ($type === 'social'): ?>
                <div class="social-group">
                    <div class="social-icon">
                        <?php
                            $p = $field['platform'] ?? 'instagram';
                            // Minimal SVG icons based on platform
                            if($p === 'instagram') echo '@';
                            elseif($p === 'twitter') echo '𝕏';
                            elseif($p === 'linkedin') echo 'in';
                            elseif($p === 'github') echo '</>';
                            else echo '▶';
                        ?>
                    </div>
                    <input type="text" name="<?= $name ?>" class="social-input" placeholder="@kullaniciadi veya link" <?= $req ?>>
                </div>

            <?php elseif ($type === 'education'): ?>
                <div class="education-group" id="edu-<?= $name ?>">
                    <!-- Dropdowns -->
                    <div id="edu-dropdowns-<?= $name ?>" style="display:flex; flex-direction:column; gap:10px;">
                        <select name="<?= $name ?>[universite]" id="uni-<?= $name ?>" class="inp-select" <?= $req ?> onchange="uniChanged('<?= $name ?>')">
                            <option value="">Üniversite Seçiniz...</option>
                        </select>
                        <select name="<?= $name ?>[fakulte]" id="fac-<?= $name ?>" class="inp-select" <?= $req ?> onchange="facChanged('<?= $name ?>')">
                            <option value="">Önce Üniversite Seçiniz...</option>
                        </select>
                        <select name="<?= $name ?>[bolum]" id="dep-<?= $name ?>" class="inp-select" <?= $req ?>>
                            <option value="">Önce Fakülte Seçiniz...</option>
                        </select>
                        <select name="<?= $name ?>[sinif]" class="inp-select" <?= $req ?>>
                            <option value="">Sınıf Seçiniz...</option>
                            <option value="Hazırlık">Hazırlık</option>
                            <option value="1">1. Sınıf</option>
                            <option value="2">2. Sınıf</option>
                            <option value="3">3. Sınıf</option>
                            <option value="4">4. Sınıf</option>
                            <option value="Mezun">Mezun</option>
                            <option value="Yüksek Lisans">Yüksek Lisans</option>
                        </select>
                    </div>
                    
                    <!-- Manual Inputs for 'Diğer' -->
                    <div id="other-<?= $name ?>" style="display:none; flex-direction:column; gap:10px; margin-top:10px; padding-top:10px; border-top:1px dashed var(--border);">
                        <input type="text" id="uni-other-<?= $name ?>" name="<?= $name ?>[universite_other]" class="inp-text" placeholder="Üniversite Adı (Örn: Boğaziçi Üniversitesi)">
                        <input type="text" id="fac-other-<?= $name ?>" name="<?= $name ?>[fakulte_other]" class="inp-text" placeholder="Fakülte Adı (Örn: Mühendislik Fakültesi)">
                        <input type="text" id="dep-other-<?= $name ?>" name="<?= $name ?>[bolum_other]" class="inp-text" placeholder="Bölüm Adı (Örn: Bilgisayar Mühendisliği)">
                    </div>
                </div>

            <?php endif; ?>

        </div>
        <?php endforeach; ?>

        <div class="public-question-card" style="margin-bottom: 24px;">
            <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px; color: var(--muted); line-height: 1.4;">
                <input type="checkbox" name="kvkk_consent" value="1" required style="margin-top: 3px; cursor: pointer;">
                <span>Kişisel verilerimin işlenmesine ve tarafımla iletişime geçilmesine dair <a href="javascript:void(0)" onclick="openKvkkModal(event)" style="color: var(--accent); text-decoration: underline; font-weight: 600;">KVKK Açık Rıza Beyanı</a>'nı okudum, onaylıyorum.</span>
            </label>
        </div>

        <button type="submit" class="submit-btn">Gönder</button>
    </form>

        <div style="font-size: 11px; display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 10px; opacity: 0.8;">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" style="display: flex; align-items: center;">
                <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" style="height: 16px; opacity: 0.7; filter: grayscale(1); transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.filter='none';" onmouseout="this.style.opacity='0.7'; this.style.filter='grayscale(1)';">
            </a>
        </div>
</div>
<?php endif; ?>

<script>
    // Eğitim Bilgileri - Konya Üniversiteleri JS Mantığı
    const konyaUnis = {
  "Necmettin Erbakan Üniversitesi": {
    "Ahmet Keleşoğlu Eğitim Fakültesi": [
      "Almanca Öğretmenliği",
      "Arapça Öğretmenliği",
      "Biyoloji Öğretmenliği",
      "Coğrafya Öğretmenliği",
      "Fen Bilgisi Öğretmenliği",
      "Fizik Öğretmenliği",
      "İlköğretim Matematik Öğretmenliği",
      "İngilizce Öğretmenliği",
      "Kimya Öğretmenliği",
      "Matematik Öğretmenliği",
      "Okul Öncesi Öğretmenliği",
      "Okul Öncesi Öğretmenlik",
      "Özel Eğitim Öğretmenliği",
      "Rehberlik ve Psikolojik Danışmanlık",
      "Sınıf Öğretmenliği",
      "Sosyal Bilgiler Öğretmenliği",
      "Tarih Öğretmenliği",
      "Türkçe Öğretmenliği",
      "Türk Dili ve Edebiyatı Öğretmenliği"
    ],
    "Ahmet Keleşoğlu İlahiyat Fakültesi": [
      "İlahiyat"
    ],
    "Diş Hekimliği Fakültesi": [
      "Diş Hekimliği"
    ],
    "Ereğli Eğitim Fakültesi": [
      "İlköğretim Matematik Öğretmenliği",
      "Okul Öncesi Öğretmenliği",
      "Okul Öncesi Öğretmenlik",
      "Rehberlik ve Psikolojik Danışmanlık",
      "Sınıf Öğretmenliği",
      "Türkçe Öğretmenliği"
    ],
    "Ereğli Ziraat Fakültesi": [
      "Biyosistem Mühendisliği",
      "Tarla Bitkileri"
    ],
    "Fen Fakültesi": [
      "Biyoteknoloji",
      "İstatistik",
      "Matematik ve Bilgisayar Bilimleri",
      "Moleküler Biyoloji ve Genetik"
    ],
    "Güzel Sanatlar ve Mimarlık Fakültesi": [
      "Geleneksel Türk Sanatları",
      "İç Mimarlık ve Çevre Tasarımı",
      "Mimarlık",
      "Radyo, Televizyon ve Sinema",
      "Şehir ve Bölge Planlama"
    ],
    "Havacılık ve Uzay Bilimleri Fakültesi": [
      "Havacılık ve Uzay Mühendisliği",
      "Havacılık Yönetimi",
      "Uçak Mühendisliği"
    ],
    "Hemşirelik Fakültesi": [
      "Hemşirelik"
    ],
    "Hukuk Fakültesi": [
      "Hukuk"
    ],
    "Mühendislik Fakültesi": [
      "Bilgisayar Mühendisliği",
      "Elektrik Elektronik Mühendisliği",
      "Elektrik-Elektronik Mühendisliği",
      "Endüstri Mühendisliği",
      "Enerji Sistemleri Mühendisliği",
      "Gıda Mühendisliği",
      "Harita Mühendisliği",
      "İnşaat Mühendisliği",
      "Makine Mühendisliği",
      "Mekatronik Mühendisliği",
      "Metalurji ve Malzeme Mühendisliği"
    ],
    "Nezahat Keleşoğlu Sağlık Bilimleri Fakültesi": [
      "Beslenme ve Diyetetik",
      "Fizyoterapi ve Rehabilitasyon",
      "Sağlık Yönetimi",
      "Sosyal Hizmet"
    ],
    "Seydişehir Ahmet Cengiz Mühendislik Fakültesi": [
      "Bilgisayar Mühendisliği",
      "Makine Mühendisliği"
    ],
    "Seydişehir Kamil Akkanat Sağlık Bilimleri Fakültesi": [
      "Hemşirelik"
    ],
    "Siyasal Bilgiler Fakültesi": [
      "İktisat",
      "İşletme",
      "Maliye",
      "Siyaset Bilimi ve Kamu Yönetimi",
      "Siyaset Bilimi ve Uluslararası İlişkiler"
    ],
    "Sosyal ve Beşeri Bilimler Fakültesi": [
      "Dilbilimi",
      "Felsefe",
      "Halkla İlişkiler ve Reklamcılık",
      "İngiliz Dilbilimi",
      "Psikoloji",
      "Sanat Tarihi",
      "Sosyoloji",
      "Tarih",
      "Türk Dili ve Edebiyatı"
    ],
    "Tıp Fakültesi": [
      "Tıp"
    ],
    "Turizm Fakültesi": [
      "Gastronomi ve Mutfak Sanatları",
      "Rekreasyon Yönetimi",
      "Turizm İşletmeciliği",
      "Turizm Rehberliği"
    ],
    "Uygulamalı Bilimler Fakültesi": [
      "Bankacılık ve Finans",
      "Finans ve Bankacılık",
      "Lojistik Yönetimi",
      "Muhasebe ve Finans Yönetimi",
      "Uluslararası Ticaret ve Finans",
      "Uluslararası Ticaret ve Finansman",
      "Yönetim Bilişim Sistemleri"
    ],
    "Veteriner Fakültesi — Ereğli": [
      "Veteriner",
      "Veteriner Hekimliği"
    ],
    "Ereğli Sağlık Hizmetleri Meslek Yüksekokulu": [
      "Tıbbi Görüntüleme Teknikleri",
      "Tıbbi Laboratuvar Teknikleri"
    ],
    "Konya Ereğli Kemal Akman Meslek Yüksekokulu": [
      "Atçılık ve Antrenörlüğü",
      "Bankacılık ve Sigortacılık",
      "Bilgisayar Programcılığı",
      "Ceza İnfaz ve Güvenlik Hizmetleri",
      "Elektrik",
      "Elektronik Haberleşme Teknolojisi",
      "İnşaat Teknolojisi",
      "İşletme Yönetimi",
      "Mahkeme Büro Hizmetleri",
      "Makine",
      "Muhasebe ve Vergi Uygulamaları"
    ],
    "Konya Organize Sanayi Bölgesi Meslek Yüksekokulu": [
      "Büyük Veri Analistliği",
      "CNC Programlama ve Operatörlüğü",
      "Döküm",
      "Hidrolik ve Pnömatik Teknikerliği",
      "Kaynak Teknolojisi",
      "Kontrol ve Otomasyon Teknolojisi",
      "Yapay Zeka Operatörlüğü"
    ],
    "Meram Meslek Yüksekokulu": [
      "Aşçılık",
      "Grafik Tasarımı",
      "Özel Güvenlik ve Koruma",
      "Sivil Savunma ve İtfaiyecilik",
      "Tıbbi ve Aromatik Bitkiler",
      "Un ve Unlu Mamuller Teknolojisi"
    ],
    "Sağlık Hizmetleri Meslek Yüksekokulu": [
      "Ağız ve Diş Sağlığı",
      "İlk ve Acil Yardım"
    ],
    "Seydişehir Meslek Yüksekokulu": [
      "Bilgisayar Programcılığı",
      "Elektrik",
      "Grafik Tasarımı",
      "İşletme Yönetimi",
      "Makine",
      "Mekatronik",
      "Yenilenebilir Enerji Teknikerliği"
    ],
    "Seydişehir Sağlık Hizmetleri Meslek Yüksekokulu": [
      "Dijital Sağlık Sistemleri Teknikerliği",
      "Fizyoterapi",
      "Tele-Sağlık Teknikerliği"
    ]
  },
  "Selçuk Üniversitesi": {
    "Akşehir İktisadi ve İdari Bilimler Fakültesi": [
      "İşletme",
      "Maliye"
    ],
    "Akşehir Mühendislik ve Mimarlık Fakültesi": [
      "Gıda Mühendisliği",
      "Makine Mühendisliği"
    ],
    "Beyşehir Ali Akkanat İşletme Fakültesi": [
      "İşletme",
      "Uluslararası Ticaret ve İşletmecilik",
      "Yönetim Bilişim Sistemleri"
    ],
    "Beyşehir Ali Akkanat Turizm Fakültesi": [
      "Gastronomi ve Mutfak Sanatları",
      "Turizm İşletmeciliği",
      "Turizm Rehberliği"
    ],
    "Diş Hekimliği Fakültesi": [
      "Diş Hekimliği"
    ],
    "Eczacılık Fakültesi": [
      "Eczacılık"
    ],
    "Edebiyat Fakültesi": [
      "Alman Dili ve Edebiyatı",
      "Arapça Mütercim ve Tercümanlık",
      "Arap Dili ve Edebiyatı",
      "Arkeoloji",
      "Fars Dili ve Edebiyatı",
      "Felsefe",
      "Fransız Dili ve Edebiyatı",
      "İngilizce Mütercim ve Tercümanlık",
      "İngiliz Dili ve Edebiyatı",
      "Karşılaştırmalı Edebiyat",
      "Psikoloji",
      "Rus Dili ve Edebiyatı",
      "Sanat Tarihi",
      "Sosyoloji",
      "Tarih",
      "Türk Dili ve Edebiyatı",
      "Urdu Dili ve Edebiyatı"
    ],
    "Eğitim Fakültesi": [
      "İlköğretim Matematik Öğretmenliği",
      "Rehberlik ve Psikolojik Danışmanlık",
      "Sınıf Öğretmenliği",
      "Türkçe Öğretmenliği"
    ],
    "Fen Fakültesi": [
      "Aktüerya Bilimleri",
      "Biyokimya",
      "Biyoloji",
      "Biyoteknoloji",
      "Fizik",
      "İstatistik",
      "Kimya",
      "Matematik"
    ],
    "Güzel Sanatlar Fakültesi": [
      "Çizgi Film ve Animasyon",
      "Endüstriyel Tasarım"
    ],
    "Hemşirelik Fakültesi": [
      "Hemşirelik"
    ],
    "Hukuk Fakültesi": [
      "Hukuk"
    ],
    "İktisadi ve İdari Bilimler Fakültesi": [
      "İktisat",
      "İşletme",
      "Siyaset Bilimi ve Kamu Yönetimi",
      "Uluslararası İlişkiler",
      "Uluslararası Ticaret ve Finans",
      "Uluslararası Ticaret ve Finansman"
    ],
    "İlahiyat Fakültesi": [
      "İlahiyat"
    ],
    "İletişim Fakültesi": [
      "Gazetecilik",
      "Halkla İlişkiler ve Tanıtım",
      "Radyo, Televizyon ve Sinema",
      "Reklamcılık"
    ],
    "Kulu Sağlık Bilimleri Fakültesi": [
      "Beslenme ve Diyetetik",
      "Fizyoterapi ve Rehabilitasyon",
      "Hemşirelik"
    ],
    "Mimarlık ve Tasarım Fakültesi": [
      "Ayakkabı Tasarımı ve Üretimi",
      "El Sanatları",
      "İç Mimarlık",
      "Moda Tasarımı",
      "Peyzaj Mimarlığı"
    ],
    "Sağlık Bilimleri Fakültesi": [
      "Acil Yardım ve Afet Yönetimi",
      "Beslenme ve Diyetetik",
      "Çocuk Gelişimi",
      "Ebelik",
      "Fizyoterapi ve Rehabilitasyon",
      "Odyoloji",
      "Sağlık Yönetimi",
      "Sosyal Hizmet"
    ],
    "Spor Bilimleri Fakültesi": [
      "Spor Yöneticiliği"
    ],
    "Teknoloji Fakültesi": [
      "Bilgisayar Mühendisliği",
      "Elektrik Elektronik Mühendisliği",
      "Elektrik-Elektronik Mühendisliği",
      "Makine Mühendisliği",
      "Mekatronik Mühendisliği",
      "Metalurji ve Malzeme Mühendisliği"
    ],
    "Akşehir Kadir Yallagöz Sağlık Yüksekokulu": [
      "Beslenme ve Diyetetik",
      "Hemşirelik"
    ],
    "Beyşehir Ali Akkanat Uygulamalı Bilimler Yüksekokulu": [
      "Acil Yardım ve Afet Yönetimi",
      "Sosyal Hizmet"
    ],
    "Çumra Uygulamalı Bilimler Yüksekokulu": [
      "Organik Tarım İşletmeciliği"
    ],
    "Sivil Havacılık Yüksekokulu": [
      "Havacılık Yönetimi",
      "Uçak Gövde-Motor Bakım",
      "Uçak Gövde ve Motor Bakımı"
    ],
    "Akören Ali Rıza Ercan Meslek Yüksekokulu": [
      "Bankacılık ve Sigortacılık",
      "Bilgisayar Programcılığı",
      "Elektronik Teknolojisi",
      "Lojistik",
      "Muhasebe ve Vergi Uygulamaları"
    ],
    "Akşehir Meslek Yüksekokulu": [
      "Bankacılık ve Sigortacılık",
      "İşletme Yönetimi",
      "Maliye",
      "Muhasebe ve Vergi Uygulamaları",
      "Turist Rehberliği",
      "Turizm ve Otel İşletmeciliği"
    ],
    "Beyşehir Ali Akkanat Meslek Yüksekokulu": [
      "Aşçılık",
      "Bankacılık ve Sigortacılık",
      "Dış Ticaret",
      "İşletme Yönetimi",
      "Lojistik",
      "Maliye",
      "Muhasebe ve Vergi Uygulamaları",
      "Turist Rehberliği",
      "Turizm ve Otel İşletmeciliği",
      "Yerel Yönetimler"
    ],
    "Bozkır Meslek Yüksekokulu": [
      "Alternatif Enerji Kaynakları Teknolojisi",
      "Bilgisayar Programcılığı",
      "Halkla İlişkiler ve Tanıtım",
      "Mimari Restorasyon",
      "Sağlık Bilgi Sistemleri Teknikerliği"
    ],
    "Cihanbeyli Meslek Yüksekokulu": [
      "Makine",
      "Makine Resim ve Konstrüksiyonu",
      "Muhasebe ve Vergi Uygulamaları",
      "Otomotiv Teknolojisi"
    ],
    "Çumra Meslek Yüksekokulu": [
      "Coğrafi Bilgi Sistemleri",
      "Doğalgaz ve Tesisatı Teknolojisi",
      "Gıda Teknolojisi",
      "Laboratuvar Teknolojisi",
      "Makine Resim ve Konstrüksiyonu",
      "Organik Tarım"
    ],
    "Doğanhisar Meslek Yüksekokulu": [
      "Bankacılık ve Sigortacılık",
      "Lojistik",
      "Maliye",
      "Ulaştırma ve Trafik Hizmetleri"
    ],
    "Güneysınır Meslek Yüksekokulu": [
      "Bilişim Güvenliği Teknolojisi",
      "Harita ve Kadastro",
      "Tapu ve Kadastro"
    ],
    "Hadim Meslek Yüksekokulu": [
      "Bankacılık ve Sigortacılık",
      "Büro Yönetimi ve Yönetici Asistanlığı",
      "Harita ve Kadastro"
    ],
    "Huğlu Meslek Yüksekokulu": [
      "Bilişim Güvenliği Teknolojisi",
      "İş Sağlığı ve Güvenliği",
      "Makine",
      "Silah Sanayi Teknikerliği"
    ],
    "Ilgın Meslek Yüksekokulu": [
      "Bilgisayar Programcılığı",
      "Elektrik",
      "Elektronik Teknolojisi",
      "Makine",
      "Mekatronik"
    ],
    "Kadınhanı Faik İçil Meslek Yüksekokulu": [
      "Harita ve Kadastro",
      "İnşaat Teknolojisi",
      "İşletme Yönetimi",
      "Lojistik",
      "Mekatronik",
      "Sağlık Bilgi Sistemleri Teknikerliği",
      "Sivil Savunma ve İtfaiyecilik",
      "Yapı Denetimi",
      "Yerel Yönetimler"
    ],
    "Karapınar Aydoğanlar Meslek Yüksekokulu": [
      "Alternatif Enerji Kaynakları Teknolojisi",
      "Gıda Kalite Kontrolü ve Analizi",
      "Laborant ve Veteriner Sağlık",
      "Özel Güvenlik ve Koruma",
      "Süt ve Ürünleri Teknolojisi"
    ],
    "Kulu Meslek Yüksekokulu": [
      "Bilgisayar Programcılığı",
      "Elektrik",
      "İş Sağlığı ve Güvenliği"
    ],
    "Sağlık Hizmetleri Meslek Yüksekokulu": [
      "Ameliyathane Hizmetleri",
      "Anestezi",
      "Çocuk Gelişimi",
      "Diyaliz",
      "Engelli Bakımı ve Rehabilitasyon",
      "Fizyoterapi",
      "İlk ve Acil Yardım",
      "Odyometri",
      "Tıbbi Görüntüleme Teknikleri",
      "Tıbbi Laboratuvar Teknikleri",
      "Yaşlı Bakımı"
    ],
    "Sarayönü Meslek Yüksekokulu": [
      "Bilgisayar Programcılığı",
      "Çevre Koruma ve Kontrol",
      "Gıda Teknolojisi",
      "Organik Tarım",
      "Tohumculuk Teknolojisi"
    ],
    "Silifke Taşucu Meslek Yüksekokulu": [
      "Aşçılık",
      "Bahçe Tarımı",
      "Dış Ticaret",
      "Elektronik Teknolojisi",
      "İnşaat Teknolojisi",
      "Turist Rehberliği",
      "Turizm ve Otel İşletmeciliği",
      "Yerel Yönetimler"
    ],
    "Sosyal Bilimler Meslek Yüksekokulu": [
      "Bankacılık ve Sigortacılık",
      "Büro Yönetimi ve Yönetici Asistanlığı",
      "Çağrı Merkezi Hizmetleri",
      "Dış Ticaret",
      "Emlak Yönetimi",
      "Halkla İlişkiler ve Tanıtım",
      "İşletme Yönetimi",
      "Lojistik",
      "Muhasebe ve Vergi Uygulamaları",
      "Pazarlama",
      "Turizm ve Otel İşletmeciliği"
    ],
    "Taşkent Meslek Yüksekokulu": [
      "Bankacılık ve Sigortacılık",
      "Harita ve Kadastro",
      "İş Sağlığı ve Güvenliği"
    ]
  },
  "Konya Gıda ve Tarım Üniversitesi": {
    "Mühendislik ve Mimarlık Fakültesi": [
      "Bilgisayar Mühendisliği",
      "İç Mimarlık",
      "Yazılım Mühendisliği"
    ],
    "Sosyal ve Beşeri Bilimler Fakültesi": [
      "Psikoloji",
      "Uluslararası Ticaret ve İşletmecilik",
      "Yönetim Bilişim Sistemleri"
    ],
    "Tarım ve Doğa Bilimleri Fakültesi": [
      "Bitkisel Üretim ve Teknolojileri",
      "Moleküler Biyoloji ve Genetik"
    ]
  },
  "Konya Teknik Üniversitesi": {
    "Bilgisayar ve Bilişim Bilimleri Fakültesi": [
      "Bilgisayar Mühendisliği",
      "Yapay Zeka ve Makine Öğrenmesi",
      "Yazılım Mühendisliği"
    ],
    "Mimarlık ve Tasarım Fakültesi": [
      "İç Mimarlık",
      "Mimarlık",
      "Şehir ve Bölge Planlama"
    ],
    "Mühendislik ve Doğa Bilimleri Fakültesi": [
      "Bilgisayar Mühendisliği",
      "Elektrik Elektronik Mühendisliği",
      "Elektrik-Elektronik Mühendisliği",
      "Endüstri Mühendisliği",
      "Harita Mühendisliği",
      "İnşaat Mühendisliği",
      "Jeoloji Mühendisliği",
      "Kimya Mühendisliği",
      "Makine Mühendisliği",
      "Metalurji ve Malzeme Mühendisliği",
      "Yazılım Mühendisliği"
    ],
    "Teknik Bilimler Meslek Yüksekokulu": [
      "Ayakkabı Tasarım ve Üretimi",
      "Basım ve Yayım Teknolojileri",
      "Bilgisayar Programcılığı",
      "Elektrik",
      "Elektronik Haberleşme Teknolojisi",
      "Elektronik Teknolojisi",
      "Gıda Teknolojisi",
      "Giyim Üretim Teknolojisi",
      "Harita ve Kadastro",
      "İklimlendirme ve Soğutma Teknolojisi",
      "İnşaat Teknolojisi",
      "İş Sağlığı ve Güvenliği",
      "Kimya Teknolojisi",
      "Kontrol ve Otomasyon Teknolojisi",
      "Kuyumculuk ve Takı Tasarımı",
      "Makine",
      "Makine Resim ve Konstrüksiyonu",
      "Mobilya ve Dekorasyon",
      "Nükleer Teknoloji ve Radyasyon Güvenliği",
      "Tarım Makineleri",
      "Yapı Yalıtım Teknolojisi"
    ]
  },
  "KTO Karatay Üniversitesi": {
    "Güzel Sanatlar ve Tasarım Fakültesi": [
      "Grafik Tasarımı",
      "İç Mimarlık",
      "Mimarlık"
    ],
    "Hukuk Fakültesi": [
      "Hukuk"
    ],
    "İktisadi, İdari ve Sosyal Bilimler Fakültesi": [
      "Arapça Mütercim ve Tercümanlık",
      "Enerji Yönetimi",
      "İletişim ve Tasarımı",
      "İngilizce Mütercim ve Tercümanlık",
      "İslam İktisadı ve Finans",
      "İşletme",
      "Psikoloji",
      "Sigortacılık ve Sosyal Güvenlik",
      "Sosyal Hizmet",
      "Sosyoloji",
      "Tarih",
      "Uluslararası Ticaret ve Lojistik"
    ],
    "Mühendislik ve Doğa Bilimleri Fakültesi": [
      "Bilgisayar Mühendisliği",
      "Elektrik Elektronik Mühendisliği",
      "Elektrik-Elektronik Mühendisliği",
      "Endüstri Mühendisliği",
      "İnşaat Mühendisliği",
      "Makine Mühendisliği",
      "Mekatronik Mühendisliği"
    ],
    "Sağlık Bilimleri Fakültesi": [
      "Beslenme ve Diyetetik",
      "Çocuk Gelişimi",
      "Ebelik",
      "Fizyoterapi ve Rehabilitasyon",
      "Hemşirelik",
      "Odyoloji"
    ],
    "Tıp Fakültesi": [
      "Tıp"
    ],
    "Uygulamalı Bilimler Yüksekokulu": [
      "Pilotaj"
    ],
    "Karatay Meslek Yüksekokulu": [
      "Mahkeme Büro Hizmetleri"
    ],
    "Sağlık Hizmetleri Meslek Yüksekokulu": [
      "Ameliyathane Hizmetleri",
      "Anestezi",
      "Çocuk Gelişimi",
      "Diyaliz",
      "Fizyoterapi",
      "İlk ve Acil Yardım",
      "Optisyenlik",
      "Tıbbi Görüntüleme Teknikleri"
    ],
    "Ticaret ve Sanayi Meslek Yüksekokulu": [
      "Bilgisayar Programcılığı",
      "Bilişim Güvenliği Teknolojisi",
      "Dış Ticaret",
      "E-Ticaret ve Pazarlama",
      "İmalat Yürütme Sistemleri Operatörlüğü",
      "Mekatronik",
      "Silah Sanayi Teknikerliği",
      "Sivil Havacılık Kabin Hizmetleri"
    ]
  }
};

    document.addEventListener("DOMContentLoaded", function() {
        const eduGroups = document.querySelectorAll('.education-group');
        eduGroups.forEach(group => {
            const fieldName = group.id.replace('edu-', '');
            const uniSelect = document.getElementById('uni-' + fieldName);
            if (uniSelect) {
                // Populate universities
                for (let uni in konyaUnis) {
                    uniSelect.options.add(new Option(uni, uni));
                }
                uniSelect.options.add(new Option("Diğer", "Diğer"));
            }
        });

        // Telefon Maskeleme
        const phoneInputs = document.querySelectorAll('.phone-mask');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                if (!x[1]) {
                    e.target.value = '';
                    return;
                }
                e.target.value = !x[2] ? '(' + x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[4] : '');
            });
        });
    });

    function uniChanged(fieldName) {
        const uniVal = document.getElementById('uni-' + fieldName).value;
        const facSelect = document.getElementById('fac-' + fieldName);
        const depSelect = document.getElementById('dep-' + fieldName);
        const otherDiv = document.getElementById('other-' + fieldName);
        
        const otherUni = document.getElementById('uni-other-' + fieldName);
        const otherFac = document.getElementById('fac-other-' + fieldName);
        const otherDep = document.getElementById('dep-other-' + fieldName);

        if (uniVal === "Diğer") {
            // Hide fac/dep dropdowns
            facSelect.style.display = 'none';
            depSelect.style.display = 'none';
            
            // Show custom text inputs
            otherDiv.style.display = 'flex';
            otherUni.required = facSelect.required;
            otherFac.required = facSelect.required;
            otherDep.required = facSelect.required;
            
            // Remove required from dropdowns
            facSelect.required = false;
            depSelect.required = false;
        } else {
            // Show fac/dep dropdowns
            facSelect.style.display = '';
            depSelect.style.display = '';
            
            // Hide custom text inputs
            otherDiv.style.display = 'none';
            const wasRequired = otherUni.required || facSelect.hasAttribute('required');
            otherUni.required = false;
            otherFac.required = false;
            otherDep.required = false;
            
            if(wasRequired) {
                facSelect.required = true;
                depSelect.required = true;
            }

            // Populate faculties
            facSelect.innerHTML = '<option value="">Fakülte Seçiniz...</option>';
            depSelect.innerHTML = '<option value="">Önce Fakülte Seçiniz...</option>';
            
            if (uniVal && konyaUnis[uniVal]) {
                for (let fac in konyaUnis[uniVal]) {
                    facSelect.options.add(new Option(fac, fac));
                }
            }
        }
    }

    function facChanged(fieldName) {
        const uniVal = document.getElementById('uni-' + fieldName).value;
        const facVal = document.getElementById('fac-' + fieldName).value;
        const depSelect = document.getElementById('dep-' + fieldName);
        
        depSelect.innerHTML = '<option value="">Bölüm Seçiniz...</option>';
        if (uniVal && facVal && konyaUnis[uniVal] && konyaUnis[uniVal][facVal]) {
            konyaUnis[uniVal][facVal].forEach(dep => {
                depSelect.options.add(new Option(dep, dep));
            });
        }
    }

    function openKvkkModal(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        document.getElementById('kvkkPopupModal').style.display = 'flex';
    }

    function closeKvkkModal() {
        document.getElementById('kvkkPopupModal').style.display = 'none';
    }
</script>

<!-- KVKK Modal Element -->
<div id="kvkkPopupModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 99999; justify-content: center; align-items: center; padding: 20px;" onclick="closeKvkkModal()">
    <div style="background: var(--card-bg, #ffffff); color: var(--text-main, #333333); width: 100%; max-width: 600px; max-height: 80vh; display: flex; flex-direction: column; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden;" onclick="event.stopPropagation()">
        <!-- Header -->
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border, #e2e8f0); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: var(--text-main, #1e293b);">KVKK Açık Rıza Metni</h3>
            <button type="button" onclick="closeKvkkModal()" style="background: none; border: none; cursor: pointer; color: var(--muted, #64748b); font-size: 20px; padding: 4px; line-height: 1;">&times;</button>
        </div>
        <!-- Body -->
        <div style="padding: 20px; overflow-y: auto; font-size: 14px; line-height: 1.6; color: var(--text-muted, #475569); max-height: 60vh;">
            <p><strong>KİŞİSEL VERİLERİN KORUNMASI KANUNU (KVKK) KAPSAMINDA AÇIK RIZA BEYANI</strong></p>
            <p>Bu form vasıtasıyla paylaştığınız ad, soyad, e-posta adresi, telefon numarası, öğrenci numarası ve eğitim bilgileri gibi kişisel verileriniz; topluluğumuzun (departmanımızın) faaliyetleri kapsamında üye kaydı oluşturulması, etkinlik süreçlerinin yönetilmesi ve sizinle iletişim kurulması amaçlarıyla 6698 sayılı Kişisel Verilerin Korunması Kanunu'na uygun olarak işlenmektedir.</p>
            <p><strong>Verilerinizin İşlenme Amacı ve Aktarımı:</strong></p>
            <p>Toplanan kişisel verileriniz, kanunun öngördüğü sınırlar dahilinde sadece topluluğumuz bünyesinde işlenecek ve üçüncü şahıslarla paylaşılmayacaktır. Verileriniz, yasal saklama süreleri boyunca güvenli bir şekilde saklanacaktır.</p>
            <p>Onay kutusunu işaretleyerek kişisel verilerinizin yukarıda belirtilen amaçlar doğrultusunda işlenmesini ve tarafınızla iletişim kurulmasını özgür iradenizle kabul etmiş sayılmaktasınız.</p>
        </div>
        <!-- Footer -->
        <div style="padding: 12px 20px; border-top: 1px solid var(--border, #e2e8f0); display: flex; justify-content: flex-end; background: rgba(var(--accent-rgb), 0.02);">
            <button type="button" class="submit-btn" onclick="closeKvkkModal()" style="margin: 0; padding: 8px 24px; font-size: 14px; border-radius: 6px; width: auto; background: var(--accent); color: #fff; border: none; cursor: pointer;">Kapat</button>
        </div>
    </div>
</div>
