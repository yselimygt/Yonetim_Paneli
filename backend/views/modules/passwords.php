<?php
// views/modules/passwords.php
// Variables available: $app, $department, $passwords, $canManage
?>
<style>
.vault-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 18px;
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
}
.vault-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    color: var(--accent);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}
.vault-kicker::before {
    content: "";
    width: 22px;
    height: 1px;
    background: var(--accent);
}
.vault-title {
    margin: 0;
    color: var(--text);
    font-size: 22px;
    font-weight: 700;
    letter-spacing: -0.03em;
}
.vault-copy {
    max-width: 460px;
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.5;
}
.vault-add-btn {
    min-height: 40px;
    white-space: nowrap;
}
.vault-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 12px;
}
.pwd-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    position: relative;
    overflow: hidden;
}
.pwd-card::before {
    content: '';
    position: absolute;
    top: 14px;
    bottom: 14px;
    left: 0;
    width: 3px;
    border-radius: 0 4px 4px 0;
    background: var(--accent);
}
.pwd-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.pwd-title {
    font-size: 15px;
    font-weight: 700;
    margin: 0;
    color: var(--text);
    letter-spacing: -0.01em;
}
.pwd-url {
    font-size: 12px;
    color: var(--muted);
    text-decoration: none;
    margin-top: 5px;
    display: inline-block;
}
.pwd-url:hover { color: var(--accent); text-decoration: underline; }

.pwd-field {
    background: transparent;
    border-radius: 6px;
    padding: 9px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border);
}
.pwd-field-label {
    font-size: 10px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 2px;
    font-weight: 700;
    letter-spacing: .08em;
}
.pwd-value-container { flex: 1; overflow: hidden; }
.pwd-value {
    font-family: ui-monospace, SFMono-Regular, Consolas, "Liberation Mono", monospace;
    font-size: 13px;
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
    gap: 6px;
    margin-left: 10px;
}
.pwd-btn {
    width: 30px;
    height: 30px;
    background: var(--input-bg);
    border: 1px solid var(--border);
    color: var(--muted);
    cursor: pointer;
    padding: 0;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.pwd-btn:hover { border-color: var(--accent); color: var(--accent); }
.pwd-notes {
    font-size: 12px;
    line-height: 1.55;
    color: var(--muted);
    background: var(--input-bg);
    padding: 10px 11px;
    border-radius: 6px;
    border: 1px solid var(--border);
    margin-top: auto;
}
.card-actions {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    margin-top: 2px;
    border-top: 1px solid var(--border);
    padding-top: 10px;
}
.card-actions .btn {
    justify-content: center;
    flex: 1;
}
.card-actions form {
    display: flex !important;
    flex: 1;
}
.card-actions form .btn {
    width: 100%;
}
.card-actions .btn-danger {
    background: rgba(239,68,68,.1);
    border: 1px solid rgba(239,68,68,.24);
    color: #fca5a5;
}
.card-actions .btn-danger:hover {
    background: rgba(239,68,68,.16);
    border-color: rgba(239,68,68,.38);
}
.pwd-empty {
    text-align: center;
    padding: 42px 18px;
    color: var(--muted);
    background: var(--card);
    border-radius: 8px;
    border: 1px dashed var(--border);
}
.pwd-empty svg {
    opacity: .42;
    margin-bottom: 12px;
}
.pwd-empty p {
    font-size: 13px;
}
.pwd-modal-content {
    background: var(--card);
    width: min(500px, calc(100vw - 32px));
    max-height: calc(100dvh - 32px);
    border-radius: 8px;
    border: 1px solid var(--border);
    overflow: auto;
    padding: 0;
}
.pwd-modal-head {
    padding: 16px 18px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.pwd-modal-head h3 {
    margin: 0;
    font-size: 16px;
    letter-spacing: -0.02em;
}
.pwd-modal-close {
    width: 34px;
    height: 34px;
    background: var(--input-bg);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--muted);
    cursor: pointer;
    font-size: 20px;
    line-height: 1;
}
.pwd-modal-form {
    padding: 18px;
}
.pwd-modal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.pwd-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 18px;
}
@media (max-width: 640px) {
    .vault-header {
        align-items: stretch;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 14px;
    }
    .vault-title {
        font-size: 20px;
    }
    .vault-copy {
        font-size: 12px;
    }
    .vault-header .btn {
        justify-content: center;
        width: 100%;
    }
    .vault-grid {
        grid-template-columns: 1fr;
    }
    .pwd-card,
    .pwd-field {
        min-width: 0;
    }
    .pwd-card {
        padding: 14px;
    }
    .pwd-actions {
        margin-left: 8px;
    }
    .card-actions {
        gap: 6px;
    }
    .pwd-modal-content {
        width: 100%;
        max-height: min(88dvh, 680px);
        border-radius: 8px 8px 0 0;
        align-self: flex-end;
    }
    .pwd-modal-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .pwd-modal-actions {
        position: sticky;
        bottom: 0;
        padding-top: 12px;
        background: var(--card);
    }
    .pwd-modal-actions .btn {
        justify-content: center;
        flex: 1;
    }
}
</style>

<div class="vault-header">
    <div>
        <span class="vault-kicker">Güvenli Erişim</span>
        <h2 class="vault-title">Şifre Kasası</h2>
        <p class="vault-copy">Sosyal medya ve uygulama girişlerini hızlıca görüntüle, kopyala ve yönet.</p>
    </div>
    <?php if ($canManage): ?>
    <button class="btn btn-primary vault-add-btn" onclick="openPwdModal()">+ Yeni Şifre</button>
    <?php endif; ?>
</div>

<?php if (empty($passwords)): ?>
<div class="pwd-empty">
    <svg width="42" height="42" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
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
            <?php
            $pwdPayload = htmlspecialchars(json_encode([
                "id" => $p["id"],
                "title" => $p["title"],
                "url" => $p["url"],
                "username" => $p["username"],
                "password" => $p["decrypted_password"],
                "notes" => $p["notes"]
            ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG), ENT_QUOTES, 'UTF-8');
            ?>
            <button class="btn btn-sm btn-outline" data-pwd="<?= $pwdPayload ?>" onclick="editPwd(JSON.parse(this.dataset.pwd))">Düzenle</button>
            <form method="POST" style="display:inline;" onsubmit="return confirm('Bu şifreyi silmek istediğinize emin misiniz?');">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
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
<div id="pwdModal" class="modal-overlay" style="z-index:9999;" aria-hidden="true">
    <div class="modal-content pwd-modal-content" role="dialog" aria-modal="true" aria-labelledby="pwdModalTitle">
        <div class="pwd-modal-head">
            <h3 id="pwdModalTitle">Yeni Şifre</h3>
            <button type="button" class="pwd-modal-close" onclick="closePwdModal()" aria-label="Kapat">&times;</button>
        </div>
        <form method="POST" class="pwd-modal-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
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

            <div class="pwd-modal-grid">
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

            <div class="pwd-modal-actions">
                <button type="button" class="btn btn-outline" onclick="closePwdModal()">İptal</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
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
    showPwdModal();
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
    showPwdModal();
}

function showPwdModal() {
    const modal = document.getElementById('pwdModal');
    if (!modal) return;
    modal.style.display = 'flex';
    modal.offsetHeight;
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    document.getElementById('pwdTitle').focus();
}

function closePwdModal() {
    const modal = document.getElementById('pwdModal');
    if (!modal) return;
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    setTimeout(() => {
        if (!modal.classList.contains('show')) {
            modal.style.display = 'none';
        }
    }, 300);
}

document.getElementById('pwdModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closePwdModal();
    }
});
<?php endif; ?>
</script>
