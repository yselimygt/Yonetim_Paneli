<?php
// views/modules/passwords.php
// Variables available: $app, $department, $passwords, $canManage
?>
<style>
.vault-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.vault-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}
.pwd-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    gap: 12px;
    position: relative;
    overflow: hidden;
}
.pwd-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 4px;
    background: var(--accent);
}
.pwd-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.pwd-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
    color: var(--text);
}
.pwd-url {
    font-size: 13px;
    color: var(--muted);
    text-decoration: none;
    margin-top: 4px;
    display: inline-block;
}
.pwd-url:hover { color: var(--accent); text-decoration: underline; }

.pwd-field {
    background: var(--input-bg);
    border-radius: 8px;
    padding: 10px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid var(--border);
}
.pwd-field-label {
    font-size: 11px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 4px;
    font-weight: 600;
}
.pwd-value-container { flex: 1; overflow: hidden; }
.pwd-value {
    font-family: monospace;
    font-size: 14px;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.pwd-hidden {
    filter: blur(4px);
    user-select: none;
}
.pwd-actions {
    display: flex;
    gap: 8px;
    margin-left: 12px;
}
.pwd-btn {
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.pwd-btn:hover { background: var(--border); color: var(--text); }
.pwd-notes {
    font-size: 13px;
    color: var(--muted);
    background: var(--bg);
    padding: 10px;
    border-radius: 6px;
    margin-top: auto;
}
.card-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 8px;
    border-top: 1px solid var(--border);
    padding-top: 12px;
}
</style>

<div class="vault-header">
    <div>
        <h2 style="margin:0;">Şifre Kasası</h2>
        <p style="color:var(--muted); margin:4px 0 0 0;">Sosyal medya ve diğer uygulamaların şifreleri</p>
    </div>
    <?php if ($canManage): ?>
    <button class="btn btn-primary" onclick="openPwdModal()">+ Yeni Şifre Ekle</button>
    <?php endif; ?>
</div>

<?php if (empty($passwords)): ?>
<div style="text-align:center; padding:40px; color:var(--muted); background:var(--card); border-radius:12px; border:1px dashed var(--border);">
    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="opacity:0.5; margin-bottom:12px;">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
    </svg>
    <p>Henüz kayıtlı şifre bulunmuyor.</p>
</div>
<?php else: ?>
<div class="vault-grid">
    <?php foreach ($passwords as $p): ?>
    <div class="pwd-card">
        <div class="pwd-header">
            <div>
                <h3 class="pwd-title"><?= View::escape($p['title']) ?></h3>
                <?php if ($p['url']): ?>
                <a href="<?= View::escape($p['url']) ?>" target="_blank" class="pwd-url">
                    <?= View::escape(parse_url($p['url'], PHP_URL_HOST) ?? $p['url']) ?>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($p['username']): ?>
        <div>
            <div class="pwd-field-label">Kullanıcı Adı / E-posta</div>
            <div class="pwd-field">
                <div class="pwd-value-container">
                    <div class="pwd-value" id="usr-<?= $p['id'] ?>"><?= View::escape($p['username']) ?></div>
                </div>
                <div class="pwd-actions">
                    <button class="pwd-btn" onclick="copyToClipboard('usr-<?= $p['id'] ?>')" title="Kopyala">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div>
            <div class="pwd-field-label">Şifre</div>
            <div class="pwd-field">
                <div class="pwd-value-container">
                    <div class="pwd-value pwd-hidden" id="pwd-<?= $p['id'] ?>"><?= View::escape($p['decrypted_password']) ?></div>
                </div>
                <div class="pwd-actions">
                    <button class="pwd-btn" onclick="toggleVisibility('pwd-<?= $p['id'] ?>')" title="Göster/Gizle">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                    <button class="pwd-btn" onclick="copyToClipboard('pwd-<?= $p['id'] ?>', true)" title="Kopyala">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <?php if ($p['notes']): ?>
        <div class="pwd-notes">
            <?= nl2br(View::escape($p['notes'])) ?>
        </div>
        <?php endif; ?>

        <?php if ($canManage): ?>
        <div class="card-actions">
            <button class="btn btn-sm btn-outline" onclick='editPwd(<?= json_encode([
                "id" => $p["id"],
                "title" => $p["title"],
                "url" => $p["url"],
                "username" => $p["username"],
                "password" => $p["decrypted_password"],
                "notes" => $p["notes"]
            ]) ?>)'>Düzenle</button>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Bu şifreyi silmek istediğinize emin misiniz?');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
            </form>
        </div>
        <?php endif; ?>

    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($canManage): ?>
<!-- Modal -->
<div id="pwdModal" class="modal-overlay" style="display:none; position:fixed; top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9999;align-items:center;justify-content:center;">
    <div class="modal-content" style="background:var(--card); width:90%; max-width:500px; border-radius:12px; border:1px solid var(--border); overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <h3 id="pwdModalTitle" style="margin:0;">Yeni Şifre</h3>
            <button type="button" onclick="closePwdModal()" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px;">&times;</button>
        </div>
        <form method="POST" style="padding:20px;">
            <input type="hidden" name="action" id="pwdAction" value="create">
            <input type="hidden" name="id" id="pwdId" value="">
            
            <div class="form-group">
                <label class="form-label">Başlık (Örn: Instagram, CPanel) <span style="color:var(--danger)">*</span></label>
                <input type="text" name="title" id="pwdTitle" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Giriş URL (Opsiyonel)</label>
                <input type="url" name="url" id="pwdUrl" class="form-control" placeholder="https://...">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">Kullanıcı Adı / E-posta</label>
                    <input type="text" name="username" id="pwdUsername" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Şifre <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="password" id="pwdPassword" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Notlar (Opsiyonel)</label>
                <textarea name="notes" id="pwdNotes" class="form-control" rows="3"></textarea>
            </div>

            <div style="text-align:right; margin-top:20px;">
                <button type="button" class="btn btn-outline" onclick="closePwdModal()">İptal</button>
                <button type="submit" class="btn btn-primary" style="margin-left:8px;">Kaydet</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
function toggleVisibility(id) {
    const el = document.getElementById(id);
    if (el.classList.contains('pwd-hidden')) {
        el.classList.remove('pwd-hidden');
    } else {
        el.classList.add('pwd-hidden');
    }
}

function copyToClipboard(id, wasHidden = false) {
    const el = document.getElementById(id);
    const text = el.innerText;
    
    // Geçici textarea oluşturup kopyalama yap
    const ta = document.createElement('textarea');
    ta.value = text;
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    document.body.removeChild(ta);

    // Toast bildirimi yapabiliriz
    alert("Kopyalandı!");
}

<?php if ($canManage): ?>
function openPwdModal() {
    document.getElementById('pwdAction').value = 'create';
    document.getElementById('pwdId').value = '';
    document.getElementById('pwdTitle').value = '';
    document.getElementById('pwdUrl').value = '';
    document.getElementById('pwdUsername').value = '';
    document.getElementById('pwdPassword').value = '';
    document.getElementById('pwdNotes').value = '';
    document.getElementById('pwdModalTitle').innerText = 'Yeni Şifre Ekle';
    document.getElementById('pwdModal').style.display = 'flex';
}

function editPwd(data) {
    document.getElementById('pwdAction').value = 'update';
    document.getElementById('pwdId').value = data.id;
    document.getElementById('pwdTitle').value = data.title;
    document.getElementById('pwdUrl').value = data.url;
    document.getElementById('pwdUsername').value = data.username;
    document.getElementById('pwdPassword').value = data.password;
    document.getElementById('pwdNotes').value = data.notes;
    document.getElementById('pwdModalTitle').innerText = 'Şifreyi Düzenle';
    document.getElementById('pwdModal').style.display = 'flex';
}

function closePwdModal() {
    document.getElementById('pwdModal').style.display = 'none';
}
<?php endif; ?>
</script>
