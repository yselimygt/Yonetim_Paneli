<?php
// form_public.php
// $fields, $config
?>
<style>
/* Modern Public Form UI */
.form-public-wrapper {
    width: 100%;
}
.form-public-header {
    background: var(--card);
    border: 1px solid var(--border, #2d3142);
    border-top: 8px solid var(--accent, #6366f1);
    border-radius: 12px;
    padding: 32px 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
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
    border: 1px solid var(--border, #2d3142);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 16px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}
.public-question-card:focus-within {
    border-left: 4px solid var(--accent);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
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
    border: 1px solid var(--border);
    color: var(--text);
    padding: 12px 16px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 15px;
    transition: all 0.2s;
}
.inp-text:focus, .inp-textarea:focus, .inp-select:focus, .inp-file:focus {
    border-color: var(--accent);
    outline: none;
    background: var(--input-bg);
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
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
    transition: border-color 0.2s;
}
.social-group:focus-within { border-color: var(--accent); }
.social-icon {
    padding: 12px 16px;
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    border-right: 1px solid var(--border);
}
.social-input {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text);
    padding: 12px 16px;
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
    transition: all 0.2s;
    cursor: pointer;
    position: relative;
}
.rc-item input[type="checkbox"] { border-radius: 4px; }
.rc-item input:checked {
    border-color: var(--accent);
    background: var(--accent);
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
    background: var(--accent);
    color: #fff;
    border: none;
    padding: 14px 28px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 8px;
    cursor: pointer;
    transition: opacity 0.2s;
    width: 100%;
}
.submit-btn:hover { opacity: 0.9; }

</style>

<div class="form-public-wrapper">
    <div class="form-public-header">
        <h1><?= View::escape($app['name']) ?></h1>
        <?php if(!empty($app['description'])): ?>
            <p><?= nl2br(View::escape($app['description'])) ?></p>
        <?php endif; ?>
    </div>

    <form method="POST" action="" enctype="multipart/form-data">
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
                    <input type="text" name="<?= $name ?>" class="inp-text" placeholder="(555) 123 45 67" <?= $req ?>>
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
            <?php endif; ?>

        </div>
        <?php endforeach; ?>

        <button type="submit" class="submit-btn">Gönder</button>
    </form>

    <div style="text-align: center; margin-top: 32px; font-size: 13px; color: var(--muted);">
        Powered by <a href="https://viperadev.com" target="_blank" style="color: var(--accent); text-decoration: none; font-weight: 600;">ViperaDev</a>
    </div>
</div>
