<?php $pageTitle = 'Site Ekibi'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px;">
    <div>
        <h2>Site Ekibi</h2>
        <p style="margin:6px 0 0; color:var(--muted); max-width:760px;">
            Ekip Köprüsü aktif: Web sitesindeki ekip alanı artık backend adminlerinden oluşur. Görünürlük ve site profili sadece buradan yönetilir.
        </p>
    </div>
    <a href="<?= BASE_URL ?>/superadmin/users" class="btn btn-outline">Adminleri Yönet</a>
</div>

<div class="card" style="border-left:4px solid var(--primary); margin-bottom:18px;">
    <strong>Global ekip kaynağı</strong>
    <p style="margin:6px 0 0; color:var(--muted);">
        Adminler departman atamalarına göre listelenir. Bir admini sitede göstermek için satırdaki <strong>Site Profili</strong> butonundan görünürlüğü açın.
    </p>
</div>

<?php if (empty($groups)): ?>
    <div class="card" style="text-align:center; padding:32px; color:var(--muted);">
        Henüz aktif admin veya departman ataması bulunmuyor.
    </div>
<?php else: ?>
    <div style="display:grid; gap:18px;">
        <?php foreach ($groups as $group): ?>
            <div class="card" style="overflow:hidden;">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:14px;">
                    <div>
                        <h3 style="margin:0;"><?= View::escape($group['name']) ?></h3>
                        <div style="font-size:12px; color:var(--muted); margin-top:4px;">
                            <?= count($group['members']) ?> admin
                        </div>
                    </div>
                    <span class="badge" style="background:var(--bg); border:1px solid var(--border); color:var(--text);">
                        Departman Grubu
                    </span>
                </div>

                <div style="overflow-x:auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:64px;">Profil</th>
                                <th>Admin</th>
                                <th>Panel Rolü</th>
                                <th>Site Görünümü</th>
                                <th style="width:110px;">Durum</th>
                                <th style="width:120px;">Sıra</th>
                                <th style="width:120px;">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group['members'] as $admin): ?>
                                <?php
                                    $payload = [
                                        'user_id' => (int) $admin['user_id'],
                                        'department_id' => $admin['department_id'] === null ? '' : (int) $admin['department_id'],
                                        'user_name' => $admin['user_name'],
                                        'email' => $admin['email'],
                                        'department_name' => $admin['department_name'] ?? 'Yönetim',
                                        'display_name' => $admin['display_name'] ?? '',
                                        'display_role' => $admin['display_role'] ?? '',
                                        'display_department' => $admin['display_department'] ?? '',
                                        'display_type' => $admin['display_type'] ?? 'department',
                                        'image_url' => $admin['image_url'] ?? '',
                                        'linkedin' => $admin['linkedin'] ?? '',
                                        'github' => $admin['github'] ?? '',
                                        'bio' => $admin['bio'] ?? '',
                                        'community_history_json' => $admin['community_history_json'] ?? '[]',
                                        'is_visible' => (int) ($admin['is_visible'] ?? 0),
                                        'sort_order' => (int) ($admin['sort_order'] ?? 0),
                                    ];
                                ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($admin['image_url'])): ?>
                                            <img src="<?= View::escape($admin['image_url']) ?>" alt="Profil" style="width:42px; height:42px; object-fit:cover; border-radius:50%; border:1px solid var(--border);">
                                        <?php else: ?>
                                            <div style="width:42px; height:42px; border-radius:50%; background:var(--bg); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; color:var(--muted); font-weight:700;">
                                                <?= View::escape(mb_substr($admin['user_name'], 0, 1, 'UTF-8')) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= View::escape($admin['user_name']) ?></strong>
                                        <div style="font-size:12px; color:var(--muted); margin-top:3px;"><?= View::escape($admin['email']) ?></div>
                                    </td>
                                    <td>
                                        <?php if (!empty($admin['is_super_admin'])): ?>
                                            <span class="badge" style="background:rgba(245,158,11,.12); color:var(--warning);">Süper Admin</span>
                                        <?php endif; ?>
                                        <span class="badge" style="background:var(--bg); color:var(--text); border:1px solid var(--border);">
                                            <?= ($admin['department_role'] ?? '') === 'director' ? 'Direktör' : 'Üye' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div><?= View::escape($admin['display_role']) ?></div>
                                        <div style="font-size:12px; color:var(--muted); margin-top:3px;">
                                            <?= View::escape($admin['display_department']) ?> · <?= View::escape($admin['display_type']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($admin['is_visible']): ?>
                                            <span class="badge" style="background:rgba(34,197,94,.12); color:#22c55e;">Sitede</span>
                                        <?php else: ?>
                                            <span class="badge" style="background:rgba(148,163,184,.12); color:var(--muted);">Gizli</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= (int) $admin['sort_order'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick='editProfile(<?= json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                            Site Profili
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div id="profileModal" class="modal">
    <div class="modal-content" style="max-width:680px;">
        <div class="modal-header">
            <div>
                <h3 id="profileModalTitle">Site Profili</h3>
                <div id="profileModalSubtitle" style="font-size:12px; color:var(--muted); margin-top:3px;"></div>
            </div>
            <button class="modal-close" onclick="closeModal('profileModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-team/profile">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="user_id" id="profile_user_id">
                <input type="hidden" name="department_id" id="profile_department_id">

                <div class="form-group" style="display:flex; align-items:center; gap:10px; padding:12px; border:1px solid var(--border); border-radius:8px; background:var(--bg);">
                    <input type="checkbox" id="profile_is_visible" name="is_visible" value="1">
                    <label for="profile_is_visible" style="font-weight:700; margin:0;">Bu admin web sitesindeki ekip alanında görünsün</label>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Görünen Ad</label>
                        <input type="text" name="display_name" id="profile_display_name" class="form-control" placeholder="Boşsa admin adı kullanılır">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ekip Tipi</label>
                        <select name="display_type" id="profile_display_type" class="form-control">
                            <option value="board">Yönetim Kurulu</option>
                            <option value="department">Departman Üyesi</option>
                            <option value="campus">Kampüs Temsilcisi</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sitedeki Ünvan</label>
                        <input type="text" name="display_role" id="profile_display_role" class="form-control" placeholder="Örn: Yazılım Direktörü">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sitedeki Departman/Kampüs</label>
                        <input type="text" name="display_department" id="profile_display_department" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Fotoğraf URL</label>
                        <input type="text" name="image_url" id="profile_image_url" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sıralama</label>
                        <input type="number" name="sort_order" id="profile_sort_order" class="form-control" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">LinkedIn URL</label>
                        <input type="url" name="linkedin" id="profile_linkedin" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">GitHub URL</label>
                        <input type="url" name="github" id="profile_github" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kısa Biyografi</label>
                    <textarea name="bio" id="profile_bio" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Topluluk Geçmişi</label>
                    <div id="profile_history_container"></div>
                    <button type="button" class="btn btn-sm btn-outline mt-2" onclick="addHistoryField('profile_history_container')">+ Geçmiş Ekle</button>
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('profileModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Profili Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function escapeAttr(value) {
    return String(value || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function addHistoryField(containerId, year = '', role = '') {
    const html = `
        <div class="history-item form-row" style="margin-bottom:8px;">
            <input type="text" name="history_year[]" class="form-control" placeholder="Yıl" value="${escapeAttr(year)}" style="width:140px;">
            <input type="text" name="history_role[]" class="form-control" placeholder="Görev" value="${escapeAttr(role)}">
            <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">X</button>
        </div>
    `;
    document.getElementById(containerId).insertAdjacentHTML('beforeend', html);
}

function editProfile(admin) {
    document.getElementById('profileModalTitle').textContent = admin.user_name;
    document.getElementById('profileModalSubtitle').textContent = `${admin.email} · ${admin.department_name}`;
    document.getElementById('profile_user_id').value = admin.user_id;
    document.getElementById('profile_department_id').value = admin.department_id;
    document.getElementById('profile_is_visible').checked = Number(admin.is_visible) === 1;
    document.getElementById('profile_display_name').value = admin.display_name || '';
    document.getElementById('profile_display_role').value = admin.display_role || '';
    document.getElementById('profile_display_department').value = admin.display_department || admin.department_name || '';
    document.getElementById('profile_display_type').value = admin.display_type || 'department';
    document.getElementById('profile_image_url').value = admin.image_url || '';
    document.getElementById('profile_linkedin').value = admin.linkedin || '';
    document.getElementById('profile_github').value = admin.github || '';
    document.getElementById('profile_bio').value = admin.bio || '';
    document.getElementById('profile_sort_order').value = admin.sort_order || 0;

    const historyContainer = document.getElementById('profile_history_container');
    historyContainer.innerHTML = '';

    try {
        const history = JSON.parse(admin.community_history_json || '[]');
        if (Array.isArray(history) && history.length > 0) {
            history.forEach(item => addHistoryField('profile_history_container', item.year, item.role));
        } else {
            addHistoryField('profile_history_container');
        }
    } catch (e) {
        addHistoryField('profile_history_container');
    }

    openModal('profileModal');
}
</script>
