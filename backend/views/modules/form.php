<?php
$config = json_decode($app['config'] ?? '{}', true) ?: [];
$fields = $config['fields'] ?? [];
$settings = $config['settings'] ?? ['is_public' => false, 'success_message' => 'Form başarıyla gönderildi.', 'category' => 'Genel'];
$templateStr = json_encode($config);
?>

<style>
/* Modern Google Forms Like UI */
.form-builder-tabs {
    display: flex;
    justify-content: center;
    border-bottom: 1px solid var(--border);
    margin-bottom: 24px;
    gap: 32px;
}
.form-builder-tabs button {
    background: transparent;
    border: none;
    color: var(--muted);
    font-size: 14px;
    font-weight: 500;
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
}
.form-builder-tabs button.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
}
.tab-content { display: none; }
.tab-content.active { display: block; }

/* Builder Styles */
.builder-container {
    max-width: 800px;
    margin: 0 auto;
}
.question-card {
    background: var(--card);
    border: 1px solid var(--border, #2d3142);
    border-left: 4px solid var(--accent);
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 16px;
    position: relative;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s;
}
.question-card.dragging {
    opacity: 0.5;
    border: 2px dashed var(--accent);
}
.drag-handle {
    position: absolute;
    top: 8px;
    left: 50%;
    transform: translateX(-50%);
    cursor: grab;
    color: var(--muted);
}
.question-actions {
    position: absolute;
    top: 16px;
    right: 16px;
    display: flex;
    gap: 8px;
}
.q-btn {
    background: transparent;
    border: none;
    color: var(--muted);
    cursor: pointer;
    padding: 4px;
}
.q-btn:hover { color: var(--text); }
.q-btn.delete:hover { color: var(--danger); }

/* Builder Floating Menu */
.floating-menu {
    position: fixed;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
.floating-menu button {
    background: transparent;
    border: none;
    color: var(--text);
    cursor: pointer;
    width: 100%;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 0 12px;
    gap: 12px;
    transition: background 0.2s;
    font-size: 13px;
    white-space: nowrap;
}
.floating-menu button .icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    font-size: 16px;
}
.floating-menu button:hover {
    background: rgba(255,255,255,0.1);
}

.options-list { margin-top: 12px; }
.option-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}
.option-item input { flex: 1; }

.responses-table th, .responses-table td {
    padding: 12px;
    border-bottom: 1px solid var(--border);
    text-align: left;
}
</style>

<!-- TABS -->
<div class="form-builder-tabs">
    <button class="active" onclick="switchTab('questions')">Sorular</button>
    <button onclick="switchTab('responses')">Yanıtlar (<?= count($entries) ?>)</button>
    <button onclick="switchTab('settings')">Ayarlar</button>
</div>

<!-- QUESTIONS TAB -->
<div id="tab-questions" class="tab-content active">
    <div class="builder-container" id="builder-container">
        <!-- Form Header Card -->
        <div class="question-card" style="border-top: 8px solid var(--accent); border-left: 1px solid var(--border); padding-bottom: 16px;">
            <input type="text" id="app-name-input" style="font-size: 24px; font-weight: bold; border: none; border-bottom: 1px solid transparent; padding: 4px 8px; margin-bottom: 8px; width: 100%; background: transparent; color: var(--text); outline: none; transition: border 0.3s;" value="<?= View::escape($app['name']) ?>" onfocus="this.style.borderBottomColor='var(--accent)'" onblur="this.style.borderBottomColor='transparent'" placeholder="Form Başlığı">
            
            <textarea id="app-desc-input" style="font-size: 14px; color: var(--text); border: none; border-bottom: 1px solid transparent; padding: 4px 8px; width: 100%; background: transparent; resize: vertical; min-height: 40px; outline: none; transition: border 0.3s;" onfocus="this.style.borderBottomColor='var(--accent)'" onblur="this.style.borderBottomColor='transparent'" placeholder="Form açıklaması..."><?= View::escape($settings['form_description'] ?? $app['description'] ?? '') ?></textarea>
            
            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
                <span class="badge" style="background: var(--accent); color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                    <span id="public-status-badge"><?= !empty($settings['is_public']) ? 'Kamuya Açık Form' : 'Sadece Üyelere Açık' ?></span>
                </span>
                <?php 
                if (!empty($settings['is_public'])): 
                    $publicLink = PUBLIC_URL . '/f/' . $app['slug'];
                ?>
                <a href="<?= $publicLink ?>" target="_blank" class="btn btn-outline btn-sm">Public Linkini Aç</a>
                <?php endif; ?>
            </div>
        </div>

        <div id="questions-list"></div>
        
        <div style="margin-top: 24px; text-align: right;">
            <button class="btn btn-primary" onclick="saveFormConfig()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle; margin-right: 4px;"><path d="M5 13l4 4L19 7"/></svg>
                Formu Kaydet
            </button>
        </div>
    </div>

    <!-- Floating Action Menu -->
    <div class="floating-menu">
        <button onclick="addQuestion('text')" title="Kısa Metin"><span class="icon">T</span> Kısa Metin</button>
        <button onclick="addQuestion('textarea')" title="Paragraf"><span class="icon">¶</span> Paragraf</button>
        <button onclick="addQuestion('radio')" title="Çoktan Seçmeli"><span class="icon">◉</span> Çoktan Seçmeli</button>
        <button onclick="addQuestion('checkbox')" title="Onay Kutuları"><span class="icon">☑</span> Onay Kutuları</button>
        <button onclick="addQuestion('dropdown')" title="Açılır Menü"><span class="icon">▼</span> Açılır Menü</button>
        <button onclick="addQuestion('file')" title="Dosya Yükleme"><span class="icon">📁</span> Dosya Yükleme</button>
        <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
        <button onclick="addTemplate('adsoyad')" title="Şablon: Ad Soyad"><span class="icon">👤</span> Ad Soyad</button>
        <button onclick="addTemplate('tc')" title="Şablon: T.C. Kimlik"><span class="icon">🆔</span> T.C. Kimlik</button>
        <button onclick="addTemplate('student_no')" title="Şablon: Öğrenci Numarası"><span class="icon">🔢</span> Öğrenci No</button>
        <button onclick="addTemplate('telefon')" title="Şablon: Telefon"><span class="icon">📞</span> Telefon</button>
        <button onclick="addTemplate('email')" title="Şablon: E-posta"><span class="icon">✉</span> E-posta</button>
        <button onclick="addTemplate('sosyal')" title="Şablon: Sosyal Medya"><span class="icon">@</span> Sosyal Medya</button>
        <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
        <button onclick="addTemplate('education')" title="Şablon: Eğitim Bilgileri"><span class="icon">🎓</span> Eğitim Bilgileri</button>
    </div>
</div>

<!-- RESPONSES TAB -->
<div id="tab-responses" class="tab-content">
    <div class="builder-container">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between;">
                <span><?= count($entries) ?> Yanıt</span>
                <div>
                    <a href="?action=export_excel" class="btn btn-outline btn-sm">Excel İndir</a>
                    <a href="?action=export" class="btn btn-outline btn-sm">CSV İndir</a>
                </div>
            </div>
            <div class="card-body" style="overflow-x: auto;">
                <table class="responses-table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Kullanıcı</th>
                            <?php 
                            $showCount = 0;
                            foreach ($fields as $f): 
                                if($showCount >= 3) break;
                                echo "<th>".View::escape($f['label'])."</th>";
                                $showCount++;
                            endforeach; 
                            ?>
                            <th>Detay</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($entries as $e): 
                            $d = json_decode($e['data'], true) ?: [];
                        ?>
                        <tr>
                            <td><?= date('d.m.Y H:i', strtotime($e['created_at'])) ?></td>
                            <td>
                                <?php if($e['user_id']): ?>
                                    <?= View::escape($e['user_name']) ?> <br><small style="color:var(--muted);"><?= View::escape($e['user_email']) ?></small>
                                <?php else: ?>
                                    Misafir <br><small style="color:var(--muted);">IP: <?= View::escape($d['_meta']['ip'] ?? '') ?></small>
                                <?php endif; ?>
                            </td>
                            <?php 
                            $showCount = 0;
                            foreach ($fields as $f): 
                                if($showCount >= 3) break;
                                $val = $d[$f['name']] ?? '';
                                if (is_array($val)) $val = implode(', ', $val);
                                echo "<td>".View::escape(mb_substr($val, 0, 30))."</td>";
                                $showCount++;
                            endforeach; 
                            ?>
                            <td>
                                <button class="btn btn-sm btn-outline" onclick="showResponseDetails(<?= $e['id'] ?>)">İncele</button>
                                <div id="resp-<?= $e['id'] ?>" style="display:none;"><?= htmlspecialchars($e['data']) ?></div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($entries)): ?>
                        <tr><td colspan="6" style="text-align:center; padding: 20px; color: var(--muted);">Henüz yanıt yok.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SETTINGS TAB -->
<div id="tab-settings" class="tab-content">
    <div class="builder-container">
        <div class="card">
            <div class="card-header">Form Ayarları</div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Erişim Türü</label>
                    <select id="setting-is-public" class="form-control">
                        <option value="0" <?= empty($settings['is_public']) ? 'selected' : '' ?>>Sadece Üyeler (Giriş zorunlu)</option>
                        <option value="1" <?= !empty($settings['is_public']) ? 'selected' : '' ?>>Kamuya Açık (Herkes erişebilir)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" id="setting-category" class="form-control" value="<?= View::escape($settings['category'] ?? '') ?>">
                </div>
                <div class="form-group" style="display: none;">
                    <label class="form-label">Form Açıklaması</label>
                    <textarea id="setting-form-description" class="form-control" rows="3"><?= View::escape($settings['form_description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Başarı Mesajı (Form gönderildikten sonra gösterilir)</label>
                    <input type="text" id="setting-success-msg" class="form-control" value="<?= View::escape($settings['success_message'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Renk Teması</label>
                    <input type="color" id="setting-theme-color" class="form-control" value="<?= View::escape($settings['theme_color'] ?? '#6366f1') ?>" style="height: 40px; padding: 4px; cursor: pointer; width: 100px;">
                </div>
                <div class="form-group">
                    <label class="form-label">Başlık Resmi (Banner)</label>
                    
                    <div id="banner-preview-container" style="margin-bottom: 12px; <?= empty($settings['header_image']) ? 'display: none;' : '' ?>">
                        <img id="banner-preview-img" src="<?= View::escape($settings['header_image'] ?? '') ?>" style="max-width: 100%; border-radius: 8px; max-height: 200px; object-fit: cover; border: 1px solid var(--border);">
                        <div style="margin-top: 8px;">
                            <button type="button" class="btn btn-sm btn-outline" onclick="removeBanner()" style="color: var(--danger); border-color: var(--danger);">Görseli Kaldır</button>
                        </div>
                    </div>

                    <div id="banner-upload-area" style="border: 2px dashed var(--border); background: rgba(0,0,0,0.02); border-radius: 8px; padding: 32px 24px; text-align: center; cursor: pointer; transition: all 0.2s;" onclick="document.getElementById('setting-header-image-file').click()" onmouseover="this.style.borderColor='var(--accent)'; this.style.background='rgba(var(--accent-rgb), 0.05)';" onmouseout="this.style.borderColor='var(--border)'; this.style.background='rgba(0,0,0,0.02)';">
                        <span style="font-size: 32px; color: var(--muted);">🖼️</span>
                        <p style="margin: 12px 0 0 0; color: var(--text); font-weight: 500;">Görsel yüklemek için tıklayın</p>
                        <p style="margin: 4px 0 0 0; color: var(--muted); font-size: 13px;">Görsel otomatik optimize edilir (Maks: 1200px)</p>
                        <input type="file" id="setting-header-image-file" accept="image/*" style="display: none;" onchange="uploadBannerFile()">
                    </div>
                    
                    <input type="hidden" id="setting-header-image" value="<?= View::escape($settings['header_image'] ?? '') ?>">
                </div>
                <button class="btn btn-primary" onclick="saveSettingsOnly()">Ayarları Kaydet</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="resModal" style="display:none; position:fixed; top:0;left:0;width:100%;height:100%;background:var(--modal-overlay);z-index:9999;align-items:center;justify-content:center;">
    <div class="card" style="width: 90%; max-width: 600px; max-height: 90vh; display: flex; flex-direction: column;">
        <div class="card-header" style="display:flex; justify-content:space-between;">
            <span>Yanıt Detayı</span>
            <button onclick="document.getElementById('resModal').style.display='none'" style="background:none;border:none;color:#fff;cursor:pointer;">&times;</button>
        </div>
        <div class="card-body" id="resModalBody" style="overflow-y:auto; padding: 20px;"></div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.form-builder-tabs button').forEach(b => b.classList.remove('active'));
    document.querySelector(`.form-builder-tabs button[onclick="switchTab('${tab}')"]`).classList.add('active');
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
}

let formFields = <?= json_encode($fields) ?>;

function renderBuilder() {
    const container = document.getElementById('questions-list');
    container.innerHTML = '';
    formFields.forEach((field, index) => {
        container.appendChild(createQuestionCard(field, index));
    });
}

function createQuestionCard(field, index) {
    const card = document.createElement('div');
    card.className = 'question-card';
    card.draggable = true;
    card.dataset.index = index;
    
    // Drag events
    card.addEventListener('dragstart', handleDragStart);
    card.addEventListener('dragover', handleDragOver);
    card.addEventListener('drop', handleDrop);
    card.addEventListener('dragend', handleDragEnd);

    let html = `
        <div class="drag-handle" title="Sürükle bırak">≡</div>
        <div class="question-actions">
            <button class="q-btn delete" onclick="deleteQuestion(${index})">🗑️</button>
        </div>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-top: 10px;">
            <div>
                <input type="text" class="form-control" value="${escapeHtml(field.label)}" onchange="updateField(${index}, 'label', this.value)" placeholder="Soru Başlığı" style="font-size: 16px; font-weight: 500; border: none; border-bottom: 1px solid var(--border); border-radius: 0; padding: 8px 0; background: transparent; color: var(--text);">
                <input type="hidden" value="${field.name}">
            </div>
            <div>
                <select class="form-control" onchange="updateFieldType(${index}, this.value)">
                    <option value="text" ${field.type==='text'?'selected':''}>Kısa Metin</option>
                    <option value="textarea" ${field.type==='textarea'?'selected':''}>Paragraf</option>
                    <option value="radio" ${field.type==='radio'?'selected':''}>Çoktan Seçmeli</option>
                    <option value="checkbox" ${field.type==='checkbox'?'selected':''}>Onay Kutuları</option>
                    <option value="dropdown" ${field.type==='dropdown'?'selected':''}>Açılır Menü</option>
                    <option value="file" ${field.type==='file'?'selected':''}>Dosya Yükleme</option>
                    <option value="tc" ${field.type==='tc'?'selected':''}>T.C. Kimlik</option>
                    <option value="phone" ${field.type==='phone'?'selected':''}>Telefon</option>
                    <option value="email" ${field.type==='email'?'selected':''}>E-posta</option>
                    <option value="social" ${field.type==='social'?'selected':''}>Sosyal Medya</option>
                    <option value="number" ${field.type==='number'?'selected':''}>Sayı</option>
                </select>
            </div>
        </div>
    `;

    // Options for radio/checkbox/dropdown
    if (['radio', 'checkbox', 'dropdown'].includes(field.type)) {
        html += `<div class="options-list">`;
        let options = field.options || [];
        options.forEach((opt, i) => {
            html += `
                <div class="option-item">
                    <span style="color:var(--muted);">${field.type==='radio'?'◉':(field.type==='checkbox'?'☑':'▼')}</span>
                    <input type="text" class="form-control" value="${escapeHtml(opt)}" onchange="updateOption(${index}, ${i}, this.value)">
                    <button class="q-btn" onclick="removeOption(${index}, ${i})">❌</button>
                </div>
            `;
        });
        html += `
            <div class="option-item">
                <span style="color:var(--muted);">${field.type==='radio'?'◉':(field.type==='checkbox'?'☑':'▼')}</span>
                <input type="text" class="form-control" placeholder="Seçenek ekle..." onkeypress="if(event.key==='Enter'){addOption(${index}, this.value); this.value='';}">
            </div>
        </div>`;
    }

    // Specific settings
    if (field.type === 'file') {
        let maxMB = field.max_size || 5;
        html += `
            <div style="margin-top: 16px; padding: 12px; background: rgba(128,128,128,0.1); border-radius: 6px;">
                <label style="font-size: 12px; color: var(--muted);">Maksimum Boyut (MB)</label>
                <input type="number" class="form-control" value="${maxMB}" onchange="updateField(${index}, 'max_size', this.value)" style="width: 100px; display: inline-block; margin-left: 10px;">
            </div>
        `;
    }

    if (field.type === 'social') {
        let platform = field.platform || 'instagram';
        html += `
            <div style="margin-top: 16px;">
                <label style="font-size: 12px; color: var(--muted);">Platform:</label>
                <select class="form-control" style="width: 200px; display: inline-block; margin-left: 10px;" onchange="updateField(${index}, 'platform', this.value)">
                    <option value="instagram" ${platform==='instagram'?'selected':''}>Instagram</option>
                    <option value="twitter" ${platform==='twitter'?'selected':''}>X (Twitter)</option>
                    <option value="linkedin" ${platform==='linkedin'?'selected':''}>LinkedIn</option>
                    <option value="github" ${platform==='github'?'selected':''}>GitHub</option>
                    <option value="youtube" ${platform==='youtube'?'selected':''}>YouTube</option>
                </select>
            </div>
        `;
    }

    if (field.type === 'education') {
        html += `
            <div style="margin-top: 16px; padding: 12px; background: rgba(var(--accent-rgb), 0.05); border-radius: 6px; border: 1px dashed var(--accent);">
                <p style="font-size: 13px; color: var(--muted); margin: 0;">Bu şablon ön yüzde otomatik olarak <strong>Üniversite, Fakülte, Bölüm ve Sınıf</strong> seçimi yaptırır (Konya üniversiteleri için özel zincirleme seçim mantığı içerir).</p>
            </div>
        `;
    }

    html += `
        <div style="margin-top: 16px; border-top: 1px solid var(--border); padding-top: 12px; display: flex; justify-content: flex-end; align-items: center;">
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); cursor: pointer;">
                Gerekli
                <input type="checkbox" ${field.required ? 'checked' : ''} onchange="updateField(${index}, 'required', this.checked)">
            </label>
        </div>
    `;

    card.innerHTML = html;
    return card;
}

// DRAG AND DROP
let dragSrcEl = null;

function handleDragStart(e) {
    dragSrcEl = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
    this.classList.add('dragging');
}

function handleDragOver(e) {
    if (e.preventDefault) e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDrop(e) {
    if (e.stopPropagation) e.stopPropagation();
    
    if (dragSrcEl !== this) {
        let srcIndex = parseInt(dragSrcEl.dataset.index);
        let tgtIndex = parseInt(this.dataset.index);
        
        // Swap array items
        let temp = formFields[srcIndex];
        formFields[srcIndex] = formFields[tgtIndex];
        formFields[tgtIndex] = temp;
        
        renderBuilder();
    }
    return false;
}

function handleDragEnd(e) {
    this.classList.remove('dragging');
}

// BUILDER ACTIONS
function addQuestion(type) {
    formFields.push({
        name: 'field_' + Date.now(),
        label: 'Yeni Soru',
        type: type,
        required: false,
        options: ['Seçenek 1']
    });
    renderBuilder();
}

function addTemplate(tpl) {
    let f = { name: 'field_' + Date.now(), required: true };
    switch(tpl) {
        case 'adsoyad': f.label = 'Ad Soyad'; f.type = 'text'; break;
        case 'tc': f.label = 'T.C. Kimlik Numarası'; f.type = 'tc'; break;
        case 'student_no': f.label = 'Öğrenci Numarası'; f.type = 'number'; break;
        case 'telefon': f.label = 'Telefon Numarası'; f.type = 'phone'; break;
        case 'email': f.label = 'E-posta Adresi'; f.type = 'email'; break;
        case 'sosyal': f.label = 'Sosyal Medya Hesabınız'; f.type = 'social'; f.platform = 'instagram'; break;
        case 'education': f.label = 'Eğitim Bilgileri'; f.type = 'education'; break;
    }
    formFields.push(f);
    renderBuilder();
}

function deleteQuestion(index) {
    if(confirm('Silmek istediğinize emin misiniz?')) {
        formFields.splice(index, 1);
        renderBuilder();
    }
}

function updateField(index, key, value) {
    formFields[index][key] = value;
}

function updateFieldType(index, value) {
    formFields[index].type = value;
    if(['radio', 'checkbox', 'dropdown'].includes(value) && (!formFields[index].options || formFields[index].options.length === 0)) {
        formFields[index].options = ['Seçenek 1'];
    }
    renderBuilder();
}

function updateOption(qIndex, oIndex, value) {
    formFields[qIndex].options[oIndex] = value;
}

function removeOption(qIndex, oIndex) {
    formFields[qIndex].options.splice(oIndex, 1);
    renderBuilder();
}

function addOption(qIndex, value) {
    if(!value.trim()) return;
    if(!formFields[qIndex].options) formFields[qIndex].options = [];
    formFields[qIndex].options.push(value);
    renderBuilder();
}

// SAVE CONFIG
function saveFormConfig() {
    saveConfigData(formFields, getSettings());
}

function saveSettingsOnly() {
    saveConfigData(formFields, getSettings());
}

function getSettings() {
    return {
        is_public: document.getElementById('setting-is-public').value == '1',
        category: document.getElementById('setting-category').value,
        form_description: document.getElementById('app-desc-input').value,
        success_message: document.getElementById('setting-success-msg').value,
        theme_color: document.getElementById('setting-theme-color').value,
        header_image: document.getElementById('setting-header-image').value
    };
}

function uploadBannerFile() {
    const fileInput = document.getElementById('setting-header-image-file');
    if (fileInput.files.length === 0) return;

    const uploadArea = document.getElementById('banner-upload-area');
    uploadArea.style.opacity = '0.5';
    uploadArea.style.pointerEvents = 'none';

    const formData = new FormData();
    formData.append('action', 'upload_banner');
    formData.append('banner', fileInput.files[0]);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        uploadArea.style.opacity = '1';
        uploadArea.style.pointerEvents = 'auto';
        
        if (data.success) {
            const imgUrl = '<?= BASE_URL ?>/' + data.url;
            document.getElementById('setting-header-image').value = imgUrl;
            document.getElementById('banner-preview-img').src = imgUrl;
            document.getElementById('banner-preview-container').style.display = 'block';
            saveSettingsOnly(); // Otomatik kaydet
        } else {
            alert(data.message || 'Görsel yüklenirken hata oluştu.');
        }
    })
    .catch(err => {
        uploadArea.style.opacity = '1';
        uploadArea.style.pointerEvents = 'auto';
        alert('Görsel yüklenirken iletişim hatası oluştu.');
    });
}

function removeBanner() {
    if(confirm('Görseli kaldırmak istediğinize emin misiniz?')) {
        document.getElementById('setting-header-image').value = '';
        document.getElementById('banner-preview-img').src = '';
        document.getElementById('banner-preview-container').style.display = 'none';
        document.getElementById('setting-header-image-file').value = '';
        saveSettingsOnly(); // Otomatik kaydet
    }
}

function saveConfigData(fields, settings) {
    const configData = { fields: fields, settings: settings };
    const formData = new FormData();
    formData.append('action', 'save_config');
    formData.append('config', JSON.stringify(configData));
    
    // Uygulama ismini ve açıklamasını da sunucuya gönderelim
    const appNameInput = document.getElementById('app-name-input');
    if (appNameInput) {
        formData.append('name', appNameInput.value);
        formData.append('description', document.getElementById('app-desc-input').value);
    }

    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Form başarıyla kaydedildi!');
            document.getElementById('public-status-badge').innerText = settings.is_public ? 'Kamuya Açık Form' : 'Sadece Üyelere Açık';
        } else {
            alert('Kaydetme hatası!');
        }
    })
    .catch(err => {
        alert('Sunucu ile iletişim hatası.');
    });
}

function showResponseDetails(id) {
    const raw = document.getElementById('resp-' + id).innerText;
    let data = {};
    try { data = JSON.parse(raw); } catch(e){}
    
    let html = `<div style="display:grid; gap:16px;">`;
    formFields.forEach(f => {
        let val = data[f.name] || '—';
        if (Array.isArray(val)) val = val.join(', ');
        if (f.type === 'file' && val !== '—') {
            val = `<a href="${'<?= BASE_URL ?>/' + val}" target="_blank" style="color:var(--accent);">Dosyayı Görüntüle</a>`;
        }
        html += `
            <div>
                <div style="font-size:12px; color:var(--muted); text-transform:uppercase; margin-bottom:4px;">${escapeHtml(f.label)}</div>
                <div style="background: rgba(255,255,255,0.02); padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">${val}</div>
            </div>
        `;
    });
    html += `</div>`;
    document.getElementById('resModalBody').innerHTML = html;
    document.getElementById('resModal').style.display = 'flex';
}

function escapeHtml(unsafe) {
    if(!unsafe) return '';
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

// INIT
renderBuilder();

// Check if URL asks for a specific tab
const urlParams = new URLSearchParams(window.location.search);
if(urlParams.get('tab') === 'responses') {
    switchTab('responses');
}
</script>
