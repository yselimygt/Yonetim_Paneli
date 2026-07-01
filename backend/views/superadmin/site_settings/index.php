<?php $pageTitle = 'Site Ayarları'; ?>

<div class="page-header">
    <h2>Genel Site Ayarları</h2>
</div>

<!-- Tabs -->
<div style="display:flex; gap:16px; margin-bottom:24px; border-bottom: 1px solid var(--border);">
    <button class="tab-btn active" onclick="switchTab('popup')">Duyuru Pop-up</button>
    <button class="tab-btn" onclick="switchTab('stats')">İstatistikler</button>
    <button class="tab-btn" onclick="switchTab('sponsors')">Sponsorlar</button>
    <button class="tab-btn" onclick="switchTab('faqs')">Sık Sorulan Sorular</button>
</div>

<!-- 1. POPUP -->
<div id="tab-popup" class="tab-content active">
    <div class="card">
        <h3>Duyuru Pop-up Ayarları</h3>
        <p style="color:var(--muted); font-size:13px; margin-bottom:16px;">Sitenin girişinde görünen duyuru modalını buradan yönetebilirsiniz.</p>
        
        <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/popup">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div class="form-group" style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="enabled" id="popup_enabled" <?= !empty($popupConfig['enabled']) ? 'checked' : '' ?> style="width:18px;height:18px;">
                <label for="popup_enabled" style="margin:0; font-weight:600;">Duyuruyu Aktif Et</label>
            </div>

            <div class="form-row mt-3">
                <div class="form-group">
                    <label class="form-label">Başlık</label>
                    <input type="text" name="title" class="form-control" value="<?= View::escape($popupConfig['title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Etiket (Badge)</label>
                    <input type="text" name="badge" class="form-control" value="<?= View::escape($popupConfig['badge'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Açıklama (Description)</label>
                <textarea name="description" class="form-control" rows="3"><?= View::escape($popupConfig['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">1. Buton Yazısı</label>
                    <input type="text" name="cta_label" class="form-control" value="<?= View::escape($popupConfig['cta']['label'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">1. Buton Linki</label>
                    <input type="text" name="cta_href" class="form-control" value="<?= View::escape($popupConfig['cta']['href'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">2. Buton Yazısı</label>
                    <input type="text" name="secondaryCta_label" class="form-control" value="<?= View::escape($popupConfig['secondaryCta']['label'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">2. Buton Linki</label>
                    <input type="text" name="secondaryCta_href" class="form-control" value="<?= View::escape($popupConfig['secondaryCta']['href'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group" style="width:200px;">
                <label class="form-label">Otomatik Kapanma (Saniye)</label>
                <input type="number" name="autoCloseSec" class="form-control" value="<?= (int)($popupConfig['autoCloseSec'] ?? 5) ?>">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Pop-up Ayarlarını Kaydet</button>
        </form>
    </div>
</div>

<!-- 2. STATS -->
<div id="tab-stats" class="tab-content" style="display:none;">
    <div class="card">
        <h3>İstatistikler (Metrics)</h3>
        <p style="color:var(--muted); font-size:13px; margin-bottom:16px;">Sitenin ana sayfasında yer alan 4 metrik alanı.</p>
        
        <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/stats">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div id="stats-container">
                <?php if (empty($stats)): $stats = [['label'=>'', 'value'=>'', 'desc'=>'']]; endif; ?>
                <?php foreach ($stats as $idx => $s): ?>
                <div class="stat-item" style="border:1px solid var(--border); padding:16px; border-radius:8px; margin-bottom:16px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <strong>Metrik #<?= $idx + 1 ?></strong>
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">Sil</button>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Başlık (Örn: Aktif Üye)</label>
                            <input type="text" name="label[]" class="form-control" value="<?= View::escape($s['label']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Değer (Örn: 500+)</label>
                            <input type="text" name="value[]" class="form-control" value="<?= View::escape($s['value']) ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Açıklama</label>
                        <input type="text" name="desc[]" class="form-control" value="<?= View::escape($s['desc']) ?>">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:16px;">
                <button type="button" class="btn btn-outline" onclick="addStat()">+ Yeni Metrik Ekle</button>
                <button type="submit" class="btn btn-primary">İstatistikleri Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. SPONSORS -->
<div id="tab-sponsors" class="tab-content" style="display:none;">
    <div class="card">
        <h3>Sponsor Logoları</h3>
        
        <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/sponsors">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div id="sponsors-container">
                <?php if (empty($sponsors)): $sponsors = ['']; endif; ?>
                <?php foreach ($sponsors as $url): ?>
                <div class="sponsor-item form-row" style="margin-bottom:8px;">
                    <div class="form-group" style="flex:1;">
                        <input type="text" name="sponsor_url[]" class="form-control" value="<?= View::escape($url) ?>" placeholder="Logo URL (Örn: /innomis/assets/...)">
                    </div>
                    <div>
                        <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Sil</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:16px;">
                <button type="button" class="btn btn-outline" onclick="addSponsor()">+ Logo Ekle</button>
                <button type="submit" class="btn btn-primary">Sponsorları Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. FAQS -->
<div id="tab-faqs" class="tab-content" style="display:none;">
    <div class="card">
        <h3>Sık Sorulan Sorular (FAQ)</h3>
        
        <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/faqs">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div id="faqs-container">
                <?php if (empty($faqs)): $faqs = [['q'=>'', 'a'=>'']]; endif; ?>
                <?php foreach ($faqs as $f): ?>
                <div class="faq-item" style="border:1px solid var(--border); padding:16px; border-radius:8px; margin-bottom:16px;">
                    <div class="form-group">
                        <label class="form-label">Soru</label>
                        <input type="text" name="q[]" class="form-control" value="<?= View::escape($f['q']) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cevap</label>
                        <textarea name="a[]" class="form-control" rows="2"><?= View::escape($f['a']) ?></textarea>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Sil</button>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="display:flex; gap:10px; margin-top:16px;">
                <button type="button" class="btn btn-outline" onclick="addFaq()">+ Soru Ekle</button>
                <button type="submit" class="btn btn-primary">SSS Kaydet</button>
            </div>
        </form>
    </div>
</div>

<style>
.tab-btn {
    padding: 10px 16px; background: none; border: none; border-bottom: 2px solid transparent;
    color: var(--muted); cursor: pointer; font-weight: 500; font-size: 15px; margin-bottom: -1px;
}
.tab-btn.active {
    color: var(--primary); border-bottom-color: var(--primary);
}
.mt-3 { margin-top: 16px; }
</style>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tabId).style.display = 'block';
    event.currentTarget.classList.add('active');
}

function addStat() {
    const html = `
    <div class="stat-item" style="border:1px solid var(--border); padding:16px; border-radius:8px; margin-bottom:16px;">
        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
            <strong>Yeni Metrik</strong>
            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">Sil</button>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Başlık</label>
                <input type="text" name="label[]" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Değer</label>
                <input type="text" name="value[]" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Açıklama</label>
            <input type="text" name="desc[]" class="form-control">
        </div>
    </div>`;
    document.getElementById('stats-container').insertAdjacentHTML('beforeend', html);
}

function addSponsor() {
    const html = `
    <div class="sponsor-item form-row" style="margin-bottom:8px;">
        <div class="form-group" style="flex:1;">
            <input type="text" name="sponsor_url[]" class="form-control" placeholder="Logo URL">
        </div>
        <div>
            <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">Sil</button>
        </div>
    </div>`;
    document.getElementById('sponsors-container').insertAdjacentHTML('beforeend', html);
}

function addFaq() {
    const html = `
    <div class="faq-item" style="border:1px solid var(--border); padding:16px; border-radius:8px; margin-bottom:16px;">
        <div class="form-group">
            <label class="form-label">Soru</label>
            <input type="text" name="q[]" class="form-control">
        </div>
        <div class="form-group">
            <label class="form-label">Cevap</label>
            <textarea name="a[]" class="form-control" rows="2"></textarea>
        </div>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Sil</button>
    </div>`;
    document.getElementById('faqs-container').insertAdjacentHTML('beforeend', html);
}
</script>
