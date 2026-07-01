<?php $pageTitle = 'Site Ekibi'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Site Ekibi</h2>
    <button class="btn btn-primary" onclick="openModal('addMemberModal')">Yeni Üye Ekle</button>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:70px;">Profil</th>
                    <th>Ad Soyad</th>
                    <th>Görev / Departman</th>
                    <th>Tür</th>
                    <th>Linkler</th>
                    <th style="width:100px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($members)): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px; color:var(--muted);">Henüz üye eklenmemiş.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($members as $m): ?>
                    <tr>
                        <td>
                            <?php if($m['image_url']): ?>
                                <img src="<?= View::escape($m['image_url']) ?>" alt="Profil" style="width:40px; height:40px; object-fit:cover; border-radius:50%; border:1px solid var(--border);">
                            <?php else: ?>
                                <div style="width:40px; height:40px; border-radius:50%; background:var(--bg); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; color:var(--muted); font-size:12px;">Yok</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= View::escape($m['name']) ?></strong>
                            <?php if($m['bio']): ?>
                            <div style="font-size:12px; color:var(--muted); margin-top:4px;">
                                <?= View::escape(mb_strimwidth($m['bio'], 0, 40, '...')) ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div><?= View::escape($m['role']) ?></div>
                            <?php if($m['department']): ?>
                            <div style="font-size:11px; color:var(--muted); margin-top:2px;"><?= View::escape($m['department']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge" style="background:var(--bg); border:1px solid var(--border); color:var(--text);"><?= View::escape($m['type']) ?></span>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px;">
                            <?php if($m['linkedin']): ?>
                                <a href="<?= View::escape($m['linkedin']) ?>" target="_blank" style="color:#0077b5; text-decoration:none;" title="LinkedIn">IN</a>
                            <?php endif; ?>
                            <?php if($m['github']): ?>
                                <a href="<?= View::escape($m['github']) ?>" target="_blank" style="color:var(--text); text-decoration:none;" title="GitHub">GH</a>
                            <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex; gap:8px;">
                                <button class="btn btn-sm btn-outline" onclick='editMember(<?= json_encode($m) ?>)'>Düzenle</button>
                                <form method="POST" action="<?= BASE_URL ?>/superadmin/site-team/<?= $m['id'] ?>/delete" onsubmit="return confirm('Üyeyi silmek istediğinize emin misiniz?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Member -->
<div id="addMemberModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Yeni Üye Ekle</h3>
            <button class="modal-close" onclick="closeModal('addMemberModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-team">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ad Soyad</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Üyelik Türü (Grup)</label>
                        <select name="type" class="form-control" required>
                            <option value="board">Yönetim Kurulu (Board)</option>
                            <option value="department">Departman Üyesi</option>
                            <option value="campus">Kampüs Temsilcisi</option>
                            <option value="alumni">Mezun</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Görev / Ünvan</label>
                        <input type="text" name="role" class="form-control" placeholder="Örn: Yönetim Kurulu Başkanı" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Departman (Opsiyonel)</label>
                        <input type="text" name="department" class="form-control" placeholder="Örn: Yazılım Departmanı">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Fotoğraf URL (Opsiyonel)</label>
                    <input type="text" name="image_url" class="form-control" placeholder="/innomis/assets/team/...">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">LinkedIn URL (Opsiyonel)</label>
                        <input type="url" name="linkedin" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">GitHub URL (Opsiyonel)</label>
                        <input type="url" name="github" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kısa Biyografi (Opsiyonel)</label>
                    <textarea name="bio" class="form-control" rows="2"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Topluluk Geçmişi (Yıllara göre önceki görevleri)</label>
                    <div id="add_history_container">
                        <div class="history-item form-row" style="margin-bottom:8px;">
                            <input type="text" name="history_year[]" class="form-control" placeholder="Yıl (Örn: 2024-2025)" style="width:140px;">
                            <input type="text" name="history_role[]" class="form-control" placeholder="Görev">
                            <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">X</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline mt-2" onclick="addHistoryField('add_history_container')">+ Ekle</button>
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addMemberModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Member -->
<div id="editMemberModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Üyeyi Düzenle</h3>
            <button class="modal-close" onclick="closeModal('editMemberModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editMemberForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ad Soyad</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Üyelik Türü (Grup)</label>
                        <select name="type" id="edit_type" class="form-control" required>
                            <option value="board">Yönetim Kurulu (Board)</option>
                            <option value="department">Departman Üyesi</option>
                            <option value="campus">Kampüs Temsilcisi</option>
                            <option value="alumni">Mezun</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Görev / Ünvan</label>
                        <input type="text" name="role" id="edit_role" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Departman (Opsiyonel)</label>
                        <input type="text" name="department" id="edit_department" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Fotoğraf URL (Opsiyonel)</label>
                    <input type="text" name="image_url" id="edit_image_url" class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">LinkedIn URL (Opsiyonel)</label>
                        <input type="url" name="linkedin" id="edit_linkedin" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">GitHub URL (Opsiyonel)</label>
                        <input type="url" name="github" id="edit_github" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kısa Biyografi (Opsiyonel)</label>
                    <textarea name="bio" id="edit_bio" class="form-control" rows="2"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Topluluk Geçmişi</label>
                    <div id="edit_history_container">
                        <!-- Populated via JS -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline mt-2" onclick="addHistoryField('edit_history_container')">+ Ekle</button>
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editMemberModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addHistoryField(containerId, year = '', role = '') {
    const html = `
        <div class="history-item form-row" style="margin-bottom:8px;">
            <input type="text" name="history_year[]" class="form-control" placeholder="Yıl" value="${year}" style="width:140px;">
            <input type="text" name="history_role[]" class="form-control" placeholder="Görev" value="${role}">
            <button type="button" class="btn btn-outline btn-sm" onclick="this.parentElement.remove()">X</button>
        </div>
    `;
    document.getElementById(containerId).insertAdjacentHTML('beforeend', html);
}

function editMember(member) {
    document.getElementById('edit_name').value = member.name;
    document.getElementById('edit_type').value = member.type;
    document.getElementById('edit_role').value = member.role;
    document.getElementById('edit_department').value = member.department || '';
    document.getElementById('edit_image_url').value = member.image_url || '';
    document.getElementById('edit_linkedin').value = member.linkedin || '';
    document.getElementById('edit_github').value = member.github || '';
    document.getElementById('edit_bio').value = member.bio || '';

    const historyContainer = document.getElementById('edit_history_container');
    historyContainer.innerHTML = '';
    try {
        const history = JSON.parse(member.community_history_json);
        if(history && history.length > 0) {
            history.forEach(h => addHistoryField('edit_history_container', h.year, h.role));
        } else {
            addHistoryField('edit_history_container');
        }
    } catch(e) {
        addHistoryField('edit_history_container');
    }

    document.getElementById('editMemberForm').action = `<?= BASE_URL ?>/superadmin/site-team/${member.id}`;
    openModal('editMemberModal');
}
</script>
