<?php
if (!defined('BASE_URL')) exit;

$pdo = Database::getInstance();
// Departmana özel başvuru mu yoksa tümü mü? 
// Şimdilik hepsi gösteriliyor, ya da departman ismine (slug vs) göre filtre yapılabilir.
$stmt = $pdo->query("SELECT * FROM app_applications ORDER BY created_at DESC");
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Post işlemi var mı?
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'status') {
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        if ($id > 0 && in_array($status, ['pending', 'reviewing', 'approved', 'rejected'])) {
            $upd = $pdo->prepare("UPDATE app_applications SET status = ? WHERE id = ?");
            $upd->execute([$status, $id]);
        }
    } elseif ($_POST['action'] === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $del = $pdo->prepare("DELETE FROM app_applications WHERE id = ?");
            $del->execute([$id]);
        }
    }
    // Refresh
    header("Location: " . BASE_URL . "/panel/{$department['slug']}/apps/applications");
    exit;
}

?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0"><i class="fas fa-file-text text-primary me-2"></i> Başvurular Modülü</h2>
    </div>
</div>

<style>
/* Split Pane Layout */
.app-container {
    display: flex;
    gap: 20px;
    height: calc(100vh - 200px);
    min-height: 500px;
}

.app-list-pane {
    flex: 0 0 350px;
    display: flex;
    flex-direction: column;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.app-list-header {
    padding: 16px;
    border-bottom: 1px solid var(--border);
    background: var(--card-alt);
}

.app-list-content {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.app-card {
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--input-bg);
    position: relative;
}
.app-card:hover {
    border-color: var(--primary);
    transform: translateY(-1px);
}
.app-card.active {
    border-color: var(--primary);
    background: rgba(245, 158, 11, 0.05); /* primary-alpha */
}
.app-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
}
.badge-pending { background: #eab308; color: #fff; }
.badge-reviewing { background: #3b82f6; color: #fff; }
.badge-approved { background: #22c55e; color: #fff; }
.badge-rejected { background: #ef4444; color: #fff; }

.app-detail-pane {
    flex: 1;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.app-detail-header {
    padding: 20px;
    border-bottom: 1px solid var(--border);
    background: var(--card-alt);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.app-detail-content {
    padding: 20px;
    flex: 1;
    overflow-y: auto;
}
.app-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--text-muted);
}
.json-field {
    background: var(--input-bg);
    padding: 12px;
    border-radius: 6px;
    border: 1px solid var(--border);
    margin-bottom: 16px;
}
.json-field h6 {
    margin-bottom: 8px;
    color: var(--text);
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.json-field p {
    margin-bottom: 0;
    color: var(--text-muted);
    font-size: 14px;
    white-space: pre-wrap;
}

@media (max-width: 900px) {
    .app-container { flex-direction: column; height: auto; }
    .app-list-pane { flex: none; max-height: 400px; }
    .app-detail-pane { min-height: 500px; }
}
</style>

<div class="app-container">
    <!-- Sol Liste -->
    <div class="app-list-pane">
        <div class="app-list-header">
            <h5 class="mb-0 fs-6 fw-bold">Başvuru Listesi</h5>
        </div>
        <div class="app-list-content" id="appList">
            <?php if(empty($applications)): ?>
                <div class="text-center text-muted p-4">
                    <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                    <p class="mb-0 fs-7">Henüz başvuru bulunmuyor.</p>
                </div>
            <?php else: ?>
                <?php foreach($applications as $app): 
                    $typeLabel = $app['type'] === 'management' ? 'Yönetim Kurulu' : 'Temsilcilik';
                    $badgeClass = 'badge-' . $app['status'];
                    $statusLabel = match($app['status']) {
                        'pending' => 'Bekliyor',
                        'reviewing' => 'İnceleniyor',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                        default => 'Bilinmiyor'
                    };
                ?>
                    <div class="app-card" onclick='showDetails(<?= json_encode($app, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                        <span class="app-badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
                        <div class="fw-bold text-white mb-1 pe-4"><?= htmlspecialchars($app['full_name']) ?></div>
                        <div class="fs-7 text-muted mb-1"><?= $typeLabel ?></div>
                        <div class="fs-7 text-muted"><i class="fas fa-clock me-1"></i><?= date('d.m.Y H:i', strtotime($app['created_at'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sağ Detay -->
    <div class="app-detail-pane" id="appDetailPane">
        <div class="app-empty-state" id="emptyState">
            <i class="fas fa-file-alt fa-3x mb-3 opacity-25"></i>
            <h5>Başvuru Seçin</h5>
            <p>Detayları görüntülemek için sol taraftan bir başvuru seçin.</p>
        </div>
        <div id="detailContent" class="d-none" style="display: flex; flex-direction: column; height: 100%;">
            <div class="app-detail-header">
                <div>
                    <h4 class="mb-1 text-white" id="d-name">-</h4>
                    <div class="text-muted fs-7" id="d-type-date">-</div>
                </div>
                <div class="d-flex gap-2">
                    <form method="POST" action="" id="statusForm" class="d-flex gap-2">
                        <input type="hidden" name="action" value="status">
                        <input type="hidden" name="id" id="status_id">
                        <select name="status" class="form-select form-select-sm w-auto bg-dark border-secondary text-white" id="status_select">
                            <option value="pending">Bekliyor</option>
                            <option value="reviewing">İnceleniyor</option>
                            <option value="approved">Onaylandı</option>
                            <option value="rejected">Reddedildi</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Güncelle</button>
                    </form>
                    <form method="POST" action="" onsubmit="return confirm('Bu başvuruyu kalıcı olarak silmek istediğinize emin misiniz?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <div class="app-detail-content">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="text-muted fs-7 mb-1">E-posta</div>
                        <div class="fw-bold" id="d-email">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="text-muted fs-7 mb-1">Telefon</div>
                        <div class="fw-bold" id="d-phone">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="text-muted fs-7 mb-1">Üniversite</div>
                        <div class="fw-bold" id="d-university">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="text-muted fs-7 mb-1">Fakülte / Bölüm</div>
                        <div class="fw-bold" id="d-faculty">-</div>
                    </div>
                </div>

                <h5 class="border-bottom border-secondary pb-2 mb-3">Form Detayları</h5>
                <div id="d-formdata">
                    <!-- JSON fields will be appended here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDetails(app) {
    document.getElementById('emptyState').classList.add('d-none');
    document.getElementById('detailContent').classList.remove('d-none');
    
    document.querySelectorAll('.app-card').forEach(c => c.classList.remove('active'));
    event.currentTarget.classList.add('active');

    const typeLabel = app.type === 'management' ? 'Yönetim Kurulu' : 'Temsilcilik';
    const dateStr = new Date(app.created_at).toLocaleString('tr-TR');

    document.getElementById('d-name').textContent = app.full_name;
    document.getElementById('d-type-date').textContent = `${typeLabel} • ${dateStr}`;
    document.getElementById('d-email').textContent = app.email || '-';
    document.getElementById('d-phone').textContent = app.phone || '-';
    document.getElementById('d-university').textContent = app.university || '-';
    document.getElementById('d-faculty').textContent = app.faculty || '-';
    
    document.getElementById('status_id').value = app.id;
    document.getElementById('delete_id').value = app.id;
    document.getElementById('status_select').value = app.status;

    const fdContainer = document.getElementById('d-formdata');
    fdContainer.innerHTML = '';
    
    if (app.form_data) {
        try {
            const data = JSON.parse(app.form_data);
            const ignoreKeys = ['name', 'email', 'phone', 'university', 'faculty', 'agreed', 'securityCode'];
            
            let hasFields = false;
            for (const [key, value] of Object.entries(data)) {
                if (ignoreKeys.includes(key)) continue;
                
                let formattedKey = key.replace(/([A-Z])/g, ' $1').replace(/^./, function(str){ return str.toUpperCase(); });
                
                let displayValue = value;
                if (Array.isArray(value)) {
                    displayValue = value.join(', ');
                } else if (typeof value === 'boolean') {
                    displayValue = value ? 'Evet' : 'Hayır';
                } else if (typeof value === 'object' && value !== null) {
                    displayValue = JSON.stringify(value, null, 2);
                }

                const fieldHtml = `
                    <div class="json-field">
                        <h6>${formattedKey}</h6>
                        <p>${displayValue || '-'}</p>
                    </div>
                `;
                fdContainer.insertAdjacentHTML('beforeend', fieldHtml);
                hasFields = true;
            }
            
            if (!hasFields) {
                fdContainer.innerHTML = '<div class="text-muted text-center p-3 border border-secondary rounded">Ek detay bulunmuyor.</div>';
            }
        } catch(e) {
            fdContainer.innerHTML = `<pre class="text-muted">${app.form_data}</pre>`;
        }
    } else {
        fdContainer.innerHTML = '<div class="text-muted text-center p-3 border border-secondary rounded">Ek detay bulunmuyor.</div>';
    }
}
</script>
