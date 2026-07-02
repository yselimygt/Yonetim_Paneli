<?php $pageTitle = 'Site Ayarları'; ?>

<div class="page-header settings-page-header">
    <div>
        <h2>Genel Site Ayarları</h2>
        <p style="color:var(--muted); font-size:13px; margin-top:4px;">Web sitesinin temel bileşenlerini ve içeriklerini buradan yönetebilirsiniz.</p>
    </div>
</div>

<!-- Tabs -->
<div class="settings-tabs">
    <button class="tab-btn active" onclick="switchTab(this, 'popup')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
        Duyuru Pop-up
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'stats')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        İstatistikler
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'sponsors')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 17 22 12"/></svg>
        Sponsorlar
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'faqs')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Sık Sorulan Sorular
    </button>
    <button class="tab-btn" onclick="switchTab(this, 'routing')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        Form Yönlendirme
    </button>
</div>

<!-- 1. POPUP -->
<div id="tab-popup" class="tab-content active">
    <div class="card">
        <div class="card-header">
            Duyuru Pop-up Ayarları
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Sitenin girişinde görünen duyuru modalını buradan yönetebilirsiniz.</p>
            
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/popup">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div class="form-group" style="display:flex; align-items:center; gap:12px; background:var(--input-bg); padding:16px; border-radius:12px; border:1px solid var(--border);">
                    <label class="toggle-switch">
                        <input type="checkbox" name="enabled" <?= !empty($popupConfig['enabled']) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <div>
                        <strong style="display:block; font-size:14px;">Duyuruyu Aktif Et</strong>
                        <span style="font-size:12px; color:var(--muted);">Kullanıcılar siteye girdiğinde pop-up olarak gösterilir.</span>
                    </div>
                </div>

                <div class="form-row mt-4">
                    <div class="form-group">
                        <label class="form-label">Başlık</label>
                        <input type="text" name="title" class="form-control" placeholder="Örn: Erken Kayıt Başladı!" value="<?= View::escape($popupConfig['title'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Etiket (Badge)</label>
                        <input type="text" name="badge" class="form-control" placeholder="Örn: YENİ, DUYURU" value="<?= View::escape($popupConfig['badge'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Açıklama (Description)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Pop-up içeriği buraya yazılır..."><?= View::escape($popupConfig['description'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">1. Buton Yazısı</label>
                        <input type="text" name="cta_label" class="form-control" placeholder="Örn: Detaylı Bilgi" value="<?= View::escape($popupConfig['cta']['label'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">1. Buton Linki</label>
                        <input type="text" name="cta_href" class="form-control" placeholder="https://..." value="<?= View::escape($popupConfig['cta']['href'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">2. Buton Yazısı</label>
                        <input type="text" name="secondaryCta_label" class="form-control" placeholder="İsteğe bağlı" value="<?= View::escape($popupConfig['secondaryCta']['label'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">2. Buton Linki</label>
                        <input type="text" name="secondaryCta_href" class="form-control" placeholder="İsteğe bağlı" value="<?= View::escape($popupConfig['secondaryCta']['href'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group" style="max-width: 200px;">
                    <label class="form-label">Otomatik Kapanma (Saniye)</label>
                    <div style="position:relative;">
                        <input type="number" name="autoCloseSec" class="form-control" style="padding-right:40px;" value="<?= (int)($popupConfig['autoCloseSec'] ?? 5) ?>">
                        <span style="position:absolute; right:14px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:12px;">sn</span>
                    </div>
                </div>

                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Ayarları Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. STATS -->
<div id="tab-stats" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            İstatistikler (Metrics)
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Sitenin ana sayfasında yer alan 4 metrik alanı.</p>
            
            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/stats">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div id="stats-container">
                    <?php if (empty($stats)): $stats = [['label'=>'', 'value'=>'', 'desc'=>'']]; endif; ?>
                    <?php foreach ($stats as $idx => $s): ?>
                    <div class="dynamic-item">
                        <div class="item-header">
                            <strong>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                Metrik #<?= $idx + 1 ?>
                            </strong>
                            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
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
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Açıklama</label>
                            <input type="text" name="desc[]" class="form-control" value="<?= View::escape($s['desc']) ?>">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-outline" onclick="addStat()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Yeni Metrik Ekle
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        İstatistikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. SPONSORS -->
<div id="tab-sponsors" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Sponsor Logoları
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Destekçilerin ve sponsorların logolarını anasayfada gösterir.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/sponsors">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div id="sponsors-container" class="sponsors-grid">
                    <?php if (empty($sponsors)): $sponsors = ['']; endif; ?>
                    <?php foreach ($sponsors as $url): ?>
                    <div class="dynamic-item" style="padding:16px;">
                        <div class="sponsor-url-row" style="display:flex; gap:12px; align-items:center;">
                            <div style="flex:1;">
                                <label class="form-label">Logo URL'si</label>
                                <input type="text" name="sponsor_url[]" class="form-control" value="<?= View::escape($url) ?>" placeholder="/assets/img/sponsor.png">
                            </div>
                            <button type="button" class="btn-icon btn-danger-icon" style="margin-top:20px;" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-outline" onclick="addSponsor()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Logo Ekle
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Sponsorları Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. FAQS -->
<div id="tab-faqs" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Sık Sorulan Sorular (FAQ)
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Anasayfada yer alan kullanıcı soruları ve cevapları.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/faqs">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div id="faqs-container">
                    <?php if (empty($faqs)): $faqs = [['q'=>'', 'a'=>'']]; endif; ?>
                    <?php foreach ($faqs as $idx => $f): ?>
                    <div class="dynamic-item">
                        <div class="item-header">
                            <strong>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                Soru #<?= $idx + 1 ?>
                            </strong>
                            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Soru</label>
                            <input type="text" name="q[]" class="form-control" value="<?= View::escape($f['q']) ?>">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Cevap</label>
                            <textarea name="a[]" class="form-control" rows="2"><?= View::escape($f['a']) ?></textarea>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-outline" onclick="addFaq()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Soru Ekle
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        SSS Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 5. FORM ROUTING -->
<div id="tab-routing" class="tab-content" style="display:none;">
    <div class="card">
        <div class="card-header">
            Form Yönlendirmeleri (Routing)
        </div>
        <div class="card-body">
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">Web sitesinden gelen formların hangi departmanların Gelen Kutusu'na düşeceğini ayarlayın. Bir form için birden fazla departman seçebilirsiniz.</p>

            <form method="POST" action="<?= BASE_URL ?>/superadmin/site-settings/form-routing">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div id="routing-container">
                    <?php 
                    // Normalize all formats to simple dept_ids array
                    $normalizedRouting = [];
                    foreach (($formRouting ?: []) as $type => $rule) {
                        $deptIds = [];
                        if (isset($rule['targets'])) {
                            foreach ($rule['targets'] as $t) {
                                if (!empty($t['department_id'])) $deptIds[] = (int)$t['department_id'];
                            }
                        } elseif (isset($rule['department_id']) && !empty($rule['department_id'])) {
                            $deptIds[] = (int)$rule['department_id'];
                        } elseif (is_array($rule)) {
                            $deptIds = array_filter(array_map('intval', $rule));
                        }
                        $normalizedRouting[$type] = $deptIds;
                    }
                    if (empty($normalizedRouting)) {
                        $normalizedRouting = ['sponsorship' => ['']];
                    }
                    ?>
                    <?php foreach ($normalizedRouting as $ruleType => $deptIds): ?>
                    <?php $ruleIdx = array_search($ruleType, array_keys($normalizedRouting)); ?>
                    <div class="routing-rule dynamic-item" data-rule-index="<?= $ruleIdx ?>">
                        <div class="item-header">
                            <strong style="display:flex; align-items:center; gap:8px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><path d="M9 18l6-6-6-6"/></svg>
                                <select name="rules[<?= $ruleIdx ?>][type]" class="form-control" style="width:240px; display:inline-block;">
                                    <option value="">Form Türü Seçin...</option>
                                    <?php foreach (FORM_TYPES as $typeKey => $typeLabel): ?>
                                    <option value="<?= $typeKey ?>" <?= $ruleType === $typeKey ? 'selected' : '' ?>><?= View::escape($typeLabel) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </strong>
                            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.routing-rule').remove()" title="Kuralı Sil">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>

                        <div class="targets-container" style="margin-top:12px; display:flex; flex-direction:column; gap:8px;">
                            <?php foreach (($deptIds ?: ['']) as $tIdx => $selDeptId): ?>
                            <div class="target-row" style="display:flex; align-items:center; gap:10px;">
                                <select name="rules[<?= $ruleIdx ?>][dept_ids][]" class="form-control">
                                    <option value="">Departman Seçin...</option>
                                    <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= $selDeptId == $d['id'] ? 'selected' : '' ?>><?= View::escape($d['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.target-row').remove()" title="Kaldır" style="flex-shrink:0;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" class="btn btn-outline" style="margin-top:10px; font-size:12px; padding:6px 12px;" onclick="addTarget(this, <?= $ruleIdx ?>)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Departman Ekle
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="settings-save-bar" style="border-top:1px solid var(--border); margin-top:24px; padding-top:20px; display:flex; justify-content:space-between; align-items:center;">
                    <button type="button" class="btn btn-outline" onclick="addRoutingRule()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Yeni Kural Ekle
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Ayarları Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modern Tabs */
.settings-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    background: var(--card);
    padding: 8px;
    border-radius: 12px;
    border: 1px solid var(--border);
    overflow-x: auto;
    scrollbar-width: none; /* Firefox */
}
.settings-tabs::-webkit-scrollbar { display: none; } /* Chrome */

.tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: transparent;
    border: none;
    border-radius: 8px;
    color: var(--muted);
    font-weight: 500;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.tab-btn:hover {
    color: var(--text);
    background: rgba(255,255,255,0.03);
}
.tab-btn.active {
    background: var(--accent);
    color: #000;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,188,212,0.25);
}
.tab-btn svg {
    width: 16px;
    height: 16px;
    transition: transform 0.2s;
}
.tab-btn.active svg {
    transform: scale(1.1);
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(255,255,255,0.1);
    transition: .3s;
    border-radius: 24px;
    border: 1px solid var(--border);
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 2px;
    bottom: 2px;
    background-color: var(--muted);
    transition: .3s;
    border-radius: 50%;
}
input:checked + .toggle-slider {
    background-color: rgba(0, 188, 212, 0.2);
    border-color: var(--accent);
}
input:checked + .toggle-slider:before {
    transform: translateX(20px);
    background-color: var(--accent);
}

/* Dynamic Items */
.dynamic-item {
    background: rgba(255,255,255,0.015);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
    position: relative;
    transition: all 0.2s;
}
.dynamic-item:hover {
    border-color: rgba(255,255,255,0.05);
    background: rgba(255,255,255,0.03);
}
.item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.03);
}
.item-header strong {
    font-size: 14px;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Icon Buttons */
.btn-icon {
    background: transparent;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    transition: all 0.2s;
}
.btn-danger-icon {
    color: var(--muted);
}
.btn-danger-icon:hover {
    background: rgba(239,68,68,0.1);
    color: var(--danger);
}

.sponsors-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media (max-width: 768px) {
    .sponsors-grid { grid-template-columns: 1fr; }
}

@media (max-width: 700px) {
    .settings-page-header p {
        line-height: 1.45;
    }

    .settings-tabs {
        margin-left: -2px;
        margin-right: -2px;
    }

    .settings-tabs .tab-btn {
        flex: 0 0 auto;
    }

    .tab-content .card-body {
        padding: 16px;
    }

    .dynamic-item {
        padding: 16px;
    }

    .item-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
    }

    .item-header strong,
    .item-header strong select {
        width: 100% !important;
    }

    .btn-icon {
        width: 40px;
        height: 40px;
    }

    .sponsor-url-row,
    .target-row {
        align-items: stretch !important;
        flex-direction: column;
    }

    .sponsor-url-row .btn-icon,
    .target-row .btn-icon {
        width: 100%;
    }

    .settings-save-bar {
        align-items: stretch !important;
        flex-direction: column;
        gap: 10px;
    }

    .settings-save-bar .btn {
        width: 100%;
    }
}

.mt-4 { margin-top: 24px; }
</style>

<script>
function switchTab(btn, tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tabId).style.display = 'block';
    btn.classList.add('active');
}

function addStat() {
    const idx = document.querySelectorAll('#stats-container .dynamic-item').length + 1;
    const html = `
    <div class="dynamic-item">
        <div class="item-header">
            <strong>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Yeni Metrik
            </strong>
            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
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
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Açıklama</label>
            <input type="text" name="desc[]" class="form-control">
        </div>
    </div>`;
    document.getElementById('stats-container').insertAdjacentHTML('beforeend', html);
}

function addSponsor() {
    const html = `
    <div class="dynamic-item" style="padding:16px;">
        <div class="sponsor-url-row" style="display:flex; gap:12px; align-items:center;">
            <div style="flex:1;">
                <label class="form-label">Logo URL'si</label>
                <input type="text" name="sponsor_url[]" class="form-control" placeholder="/assets/img/sponsor.png">
            </div>
            <button type="button" class="btn-icon btn-danger-icon" style="margin-top:20px;" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
    </div>`;
    document.getElementById('sponsors-container').insertAdjacentHTML('beforeend', html);
}

function addFaq() {
    const idx = document.querySelectorAll('#faqs-container .dynamic-item').length + 1;
    const html = `
    <div class="dynamic-item">
        <div class="item-header">
            <strong>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Yeni Soru
            </strong>
            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.dynamic-item').remove()" title="Sil">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
        <div class="form-group">
            <label class="form-label">Soru</label>
            <input type="text" name="q[]" class="form-control">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Cevap</label>
            <textarea name="a[]" class="form-control" rows="2"></textarea>
        </div>
    </div>`;
    document.getElementById('faqs-container').insertAdjacentHTML('beforeend', html);
}

// Form Türleri
const FORM_TYPES = <?= json_encode(FORM_TYPES) ?>;

function buildFormTypeOptions(selectedKey = '') {
    return Object.entries(FORM_TYPES)
        .map(([key, label]) => `<option value="${key}" ${key === selectedKey ? 'selected' : ''}>${label}</option>`)
        .join('');
}

const DEPT_OPTIONS = <?= json_encode(array_map(fn($d) => ['id' => $d['id'], 'name' => $d['name']], $departments)) ?>;

function buildDeptOptions(selectedId = '') {
    return DEPT_OPTIONS.map(d => `<option value="${d.id}" ${String(d.id) === String(selectedId) ? 'selected' : ''}>${d.name}</option>`).join('');
}

function buildTargetRow(ruleIdx) {
    return `
    <div class="target-row" style="display:flex; align-items:center; gap:10px;">
        <select name="rules[${ruleIdx}][dept_ids][]" class="form-control">
            <option value="">Departman Seçin...</option>
            ${buildDeptOptions()}
        </select>
        <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.target-row').remove()" title="Kaldır" style="flex-shrink:0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>`;
}

function addTarget(btn, ruleIdx) {
    const container = btn.closest('.routing-rule').querySelector('.targets-container');
    container.insertAdjacentHTML('beforeend', buildTargetRow(ruleIdx));
}

function addRoutingRule() {
    const container = document.getElementById('routing-container');
    const ruleIdx = container.querySelectorAll('.routing-rule').length;
    const html = `
    <div class="routing-rule dynamic-item" data-rule-index="${ruleIdx}">
        <div class="item-header">
            <strong style="display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent);"><path d="M9 18l6-6-6-6"/></svg>
                <select name="rules[${ruleIdx}][type]" class="form-control" style="width:240px; display:inline-block;">
                    <option value="">Form Türü Seçin...</option>
                    ${buildFormTypeOptions()}
                </select>
            </strong>
            <button type="button" class="btn-icon btn-danger-icon" onclick="this.closest('.routing-rule').remove()" title="Kuralı Sil">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
        <div class="targets-container" style="margin-top:12px; display:flex; flex-direction:column; gap:8px;">
            ${buildTargetRow(ruleIdx)}
        </div>
        <button type="button" class="btn btn-outline" style="margin-top:10px; font-size:12px; padding:6px 12px;" onclick="addTarget(this, ${ruleIdx})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Departman Ekle
        </button>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
}
</script>
