<?php $pageTitle = 'Site Projeleri'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Site Projeleri</h2>
    <button class="btn btn-primary" onclick="openModal('addProjectModal')">Yeni Proje Ekle</button>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Kategori</th>
                    <th>Durum</th>
                    <th>Teknolojiler</th>
                    <th>Linkler</th>
                    <th style="width:100px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($projects)): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px; color:var(--muted);">Henüz proje eklenmemiş.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($projects as $proj): 
                        $techs = json_decode($proj['tech_stack_json'], true) ?? [];
                    ?>
                    <tr>
                        <td>
                            <strong><?= View::escape($proj['title']) ?></strong>
                            <div style="font-size:12px; color:var(--muted); margin-top:4px;">
                                <?= View::escape(mb_strimwidth($proj['description'], 0, 50, '...')) ?>
                            </div>
                        </td>
                        <td><span class="badge" style="background:var(--bg); border:1px solid var(--border); color:var(--text);"><?= View::escape($proj['category']) ?></span></td>
                        <td>
                            <?php if($proj['status'] === 'active'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php elseif($proj['status'] === 'completed'): ?>
                                <span class="badge badge-info">Tamamlandı</span>
                            <?php else: ?>
                                <span class="badge badge-warning"><?= View::escape($proj['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display:flex; gap:4px; flex-wrap:wrap;">
                            <?php foreach(array_slice($techs, 0, 3) as $tech): ?>
                                <span class="badge" style="background:rgba(244,159,32,0.1); color:var(--primary); font-size:10px;"><?= View::escape($tech) ?></span>
                            <?php endforeach; ?>
                            <?php if(count($techs) > 3): ?>
                                <span class="badge" style="background:rgba(244,159,32,0.1); color:var(--primary); font-size:10px;">+<?= count($techs)-3 ?></span>
                            <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if($proj['github_link']): ?>
                                <a href="<?= View::escape($proj['github_link']) ?>" target="_blank" style="color:var(--text); text-decoration:none; margin-right:8px;" title="GitHub">GH</a>
                            <?php endif; ?>
                            <?php if($proj['demo_link']): ?>
                                <a href="<?= View::escape($proj['demo_link']) ?>" target="_blank" style="color:var(--primary); text-decoration:none;" title="Demo">Demo</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display:flex; gap:8px;">
                                <button class="btn btn-sm btn-outline" onclick='editProject(<?= json_encode($proj) ?>)'>Düzenle</button>
                                <form method="POST" action="<?= BASE_URL ?>/superadmin/site-projects/<?= $proj['id'] ?>/delete" onsubmit="return confirm('Projeyi silmek istediğinize emin misiniz?');">
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

<!-- Modal: Add Project -->
<div id="addProjectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Yeni Proje Ekle</h3>
            <button class="modal-close" onclick="closeModal('addProjectModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-projects">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group">
                    <label class="form-label">Proje Başlığı</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-control" required>
                            <option value="Web Geliştirme">Web Geliştirme</option>
                            <option value="Mobil Uygulama">Mobil Uygulama</option>
                            <option value="Yapay Zeka">Yapay Zeka</option>
                            <option value="Oyun Geliştirme">Oyun Geliştirme</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durum</label>
                        <select name="status" class="form-control" required>
                            <option value="active">Aktif Geliştirme</option>
                            <option value="completed">Tamamlandı</option>
                            <option value="planning">Planlama</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Teknolojiler (Virgülle ayırın, Örn: React, Node.js, MySQL)</label>
                    <input type="text" name="tech_stack" class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">GitHub Linki (Opsiyonel)</label>
                        <input type="url" name="github_link" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Demo Linki (Opsiyonel)</label>
                        <input type="url" name="demo_link" class="form-control">
                    </div>
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addProjectModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Project -->
<div id="editProjectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Projeyi Düzenle</h3>
            <button class="modal-close" onclick="closeModal('editProjectModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editProjectForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group">
                    <label class="form-label">Proje Başlığı</label>
                    <input type="text" name="title" id="edit_title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori</label>
                        <select name="category" id="edit_category" class="form-control" required>
                            <option value="Web Geliştirme">Web Geliştirme</option>
                            <option value="Mobil Uygulama">Mobil Uygulama</option>
                            <option value="Yapay Zeka">Yapay Zeka</option>
                            <option value="Oyun Geliştirme">Oyun Geliştirme</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durum</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="active">Aktif Geliştirme</option>
                            <option value="completed">Tamamlandı</option>
                            <option value="planning">Planlama</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Teknolojiler (Virgülle ayırın)</label>
                    <input type="text" name="tech_stack" id="edit_tech_stack" class="form-control">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">GitHub Linki (Opsiyonel)</label>
                        <input type="url" name="github_link" id="edit_github_link" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Demo Linki (Opsiyonel)</label>
                        <input type="url" name="demo_link" id="edit_demo_link" class="form-control">
                    </div>
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editProjectModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editProject(project) {
    document.getElementById('edit_title').value = project.title;
    document.getElementById('edit_description').value = project.description;
    document.getElementById('edit_category').value = project.category;
    document.getElementById('edit_status').value = project.status;
    document.getElementById('edit_github_link').value = project.github_link || '';
    document.getElementById('edit_demo_link').value = project.demo_link || '';
    
    try {
        const techs = JSON.parse(project.tech_stack_json);
        document.getElementById('edit_tech_stack').value = techs ? techs.join(', ') : '';
    } catch(e) {
        document.getElementById('edit_tech_stack').value = '';
    }

    document.getElementById('editProjectForm').action = `<?= BASE_URL ?>/superadmin/site-projects/${project.id}`;
    openModal('editProjectModal');
}
</script>
