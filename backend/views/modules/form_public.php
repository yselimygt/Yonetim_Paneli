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

    <form method="POST" action="<?= PUBLIC_URL ?>/f/<?= $app['slug'] ?>" enctype="multipart/form-data">
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
                    <input type="text" name="<?= $name ?>" class="inp-text phone-mask" placeholder="(555) 123 45 67" maxlength="15" <?= $req ?>>
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

            <?php elseif ($type === 'education'): ?>
                <div class="education-group" id="edu-<?= $name ?>">
                    <!-- Dropdowns -->
                    <div id="edu-dropdowns-<?= $name ?>" style="display:flex; flex-direction:column; gap:10px;">
                        <select name="<?= $name ?>[universite]" id="uni-<?= $name ?>" class="inp-select" <?= $req ?> onchange="uniChanged('<?= $name ?>')">
                            <option value="">Üniversite Seçiniz...</option>
                        </select>
                        <select name="<?= $name ?>[fakulte]" id="fac-<?= $name ?>" class="inp-select" <?= $req ?> onchange="facChanged('<?= $name ?>')">
                            <option value="">Önce Üniversite Seçiniz...</option>
                        </select>
                        <select name="<?= $name ?>[bolum]" id="dep-<?= $name ?>" class="inp-select" <?= $req ?>>
                            <option value="">Önce Fakülte Seçiniz...</option>
                        </select>
                        <select name="<?= $name ?>[sinif]" class="inp-select" <?= $req ?>>
                            <option value="">Sınıf Seçiniz...</option>
                            <option value="Hazırlık">Hazırlık</option>
                            <option value="1">1. Sınıf</option>
                            <option value="2">2. Sınıf</option>
                            <option value="3">3. Sınıf</option>
                            <option value="4">4. Sınıf</option>
                            <option value="Mezun">Mezun</option>
                            <option value="Yüksek Lisans">Yüksek Lisans</option>
                        </select>
                    </div>
                    
                    <!-- Manual Inputs for 'Diğer' -->
                    <div id="other-<?= $name ?>" style="display:none; flex-direction:column; gap:10px; margin-top:10px; padding-top:10px; border-top:1px dashed var(--border);">
                        <input type="text" id="uni-other-<?= $name ?>" name="<?= $name ?>[universite_other]" class="inp-text" placeholder="Üniversite Adı (Örn: Boğaziçi Üniversitesi)">
                        <input type="text" id="fac-other-<?= $name ?>" name="<?= $name ?>[fakulte_other]" class="inp-text" placeholder="Fakülte Adı (Örn: Mühendislik Fakültesi)">
                        <input type="text" id="dep-other-<?= $name ?>" name="<?= $name ?>[bolum_other]" class="inp-text" placeholder="Bölüm Adı (Örn: Bilgisayar Mühendisliği)">
                    </div>
                </div>

            <?php endif; ?>

        </div>
        <?php endforeach; ?>

        <div class="public-question-card" style="margin-bottom: 24px;">
            <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px; color: var(--muted); line-height: 1.4;">
                <input type="checkbox" name="kvkk_consent" value="1" required style="margin-top: 3px; cursor: pointer;">
                <span>Kişisel verilerimin işlenmesine ve tarafımla iletişime geçilmesine dair <a href="javascript:void(0)" onclick="openKvkkModal(event)" style="color: var(--accent); text-decoration: underline; font-weight: 600;">KVKK Açık Rıza Beyanı</a>'nı okudum, onaylıyorum.</span>
            </label>
        </div>

        <button type="submit" class="submit-btn">Gönder</button>
    </form>

        <div style="font-size: 11px; display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 10px; opacity: 0.8;">
            <span>Powered by</span>
            <a href="https://viperadev.com" target="_blank" style="display: flex; align-items: center;">
                <img src="https://viperadev.com/ViperaDev-Logo.png" alt="ViperaDev" style="height: 16px; opacity: 0.7; filter: grayscale(1); transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.filter='none';" onmouseout="this.style.opacity='0.7'; this.style.filter='grayscale(1)';">
            </a>
        </div>
</div>

<script>
    // Eğitim Bilgileri - Konya Üniversiteleri JS Mantığı
    const konyaUnis = {
        "Selçuk Üniversitesi": {
            "Mühendislik Fakültesi": ["Bilgisayar Mühendisliği", "Elektrik-Elektronik Mühendisliği", "Makine Mühendisliği", "Endüstri Mühendisliği", "İnşaat Mühendisliği"],
            "İletişim Fakültesi": ["Gazetecilik", "Halkla İlişkiler", "Radyo, Televizyon ve Sinema"],
            "Fen Fakültesi": ["Matematik", "Fizik", "Kimya", "Biyoloji", "İstatistik"],
            "Edebiyat Fakültesi": ["Türk Dili ve Edebiyatı", "Tarih", "Sosyoloji", "Psikoloji"],
            "Hukuk Fakültesi": ["Hukuk"],
            "Tıp Fakültesi": ["Tıp"],
            "Diş Hekimliği Fakültesi": ["Diş Hekimliği"]
        },
        "Necmettin Erbakan Üniversitesi": {
            "Mühendislik Fakültesi": ["Bilgisayar Mühendisliği", "Elektrik-Elektronik Mühendisliği", "İnşaat Mühendisliği", "Makine Mühendisliği"],
            "Eğitim Fakültesi": ["Sınıf Öğretmenliği", "İngilizce Öğretmenliği", "PDR"],
            "Meram Tıp Fakültesi": ["Tıp"],
            "İlahiyat Fakültesi": ["İlahiyat"]
        },
        "Konya Teknik Üniversitesi": {
            "Mühendislik ve Doğa Bilimleri Fakültesi": ["Bilgisayar Mühendisliği", "Elektrik-Elektronik Mühendisliği", "Makine Mühendisliği", "Endüstri Mühendisliği", "İnşaat Mühendisliği", "Yazılım Mühendisliği"],
            "Mimarlık ve Tasarım Fakültesi": ["Mimarlık", "Şehir ve Bölge Planlama"]
        },
        "KTO Karatay Üniversitesi": {
            "Mühendislik ve Doğa Bilimleri Fakültesi": ["Bilgisayar Mühendisliği", "Elektrik-Elektronik Mühendisliği", "Mekatronik Mühendisliği"],
            "Hukuk Fakültesi": ["Hukuk"],
            "Tıp Fakültesi": ["Tıp"]
        },
        "Konya Gıda ve Tarım Üniversitesi": {
            "Tarım ve Doğa Bilimleri Fakültesi": ["Bitkisel Üretim ve Teknolojileri"],
            "Mühendislik ve Mimarlık Fakültesi": ["Bilgisayar Mühendisliği", "Biyomühendislik"]
        }
    };

    document.addEventListener("DOMContentLoaded", function() {
        const eduGroups = document.querySelectorAll('.education-group');
        eduGroups.forEach(group => {
            const fieldName = group.id.replace('edu-', '');
            const uniSelect = document.getElementById('uni-' + fieldName);
            if (uniSelect) {
                // Populate universities
                for (let uni in konyaUnis) {
                    uniSelect.options.add(new Option(uni, uni));
                }
                uniSelect.options.add(new Option("Diğer", "Diğer"));
            }
        });

        // Telefon Maskeleme
        const phoneInputs = document.querySelectorAll('.phone-mask');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                if (!x[1]) {
                    e.target.value = '';
                    return;
                }
                e.target.value = !x[2] ? '(' + x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? ' ' + x[3] : '') + (x[4] ? ' ' + x[4] : '');
            });
        });
    });

    function uniChanged(fieldName) {
        const uniVal = document.getElementById('uni-' + fieldName).value;
        const facSelect = document.getElementById('fac-' + fieldName);
        const depSelect = document.getElementById('dep-' + fieldName);
        const otherDiv = document.getElementById('other-' + fieldName);
        
        const otherUni = document.getElementById('uni-other-' + fieldName);
        const otherFac = document.getElementById('fac-other-' + fieldName);
        const otherDep = document.getElementById('dep-other-' + fieldName);

        if (uniVal === "Diğer") {
            // Hide fac/dep dropdowns
            facSelect.style.display = 'none';
            depSelect.style.display = 'none';
            
            // Show custom text inputs
            otherDiv.style.display = 'flex';
            otherUni.required = facSelect.required;
            otherFac.required = facSelect.required;
            otherDep.required = facSelect.required;
            
            // Remove required from dropdowns
            facSelect.required = false;
            depSelect.required = false;
        } else {
            // Show fac/dep dropdowns
            facSelect.style.display = '';
            depSelect.style.display = '';
            
            // Hide custom text inputs
            otherDiv.style.display = 'none';
            const wasRequired = otherUni.required || facSelect.hasAttribute('required');
            otherUni.required = false;
            otherFac.required = false;
            otherDep.required = false;
            
            if(wasRequired) {
                facSelect.required = true;
                depSelect.required = true;
            }

            // Populate faculties
            facSelect.innerHTML = '<option value="">Fakülte Seçiniz...</option>';
            depSelect.innerHTML = '<option value="">Önce Fakülte Seçiniz...</option>';
            
            if (uniVal && konyaUnis[uniVal]) {
                for (let fac in konyaUnis[uniVal]) {
                    facSelect.options.add(new Option(fac, fac));
                }
            }
        }
    }

    function facChanged(fieldName) {
        const uniVal = document.getElementById('uni-' + fieldName).value;
        const facVal = document.getElementById('fac-' + fieldName).value;
        const depSelect = document.getElementById('dep-' + fieldName);
        
        depSelect.innerHTML = '<option value="">Bölüm Seçiniz...</option>';
        if (uniVal && facVal && konyaUnis[uniVal] && konyaUnis[uniVal][facVal]) {
            konyaUnis[uniVal][facVal].forEach(dep => {
                depSelect.options.add(new Option(dep, dep));
            });
        }
    }

    function openKvkkModal(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        document.getElementById('kvkkPopupModal').style.display = 'flex';
    }

    function closeKvkkModal() {
        document.getElementById('kvkkPopupModal').style.display = 'none';
    }
</script>

<!-- KVKK Modal Element -->
<div id="kvkkPopupModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 99999; justify-content: center; align-items: center; padding: 20px;" onclick="closeKvkkModal()">
    <div style="background: var(--card-bg, #ffffff); color: var(--text-main, #333333); width: 100%; max-width: 600px; max-height: 80vh; display: flex; flex-direction: column; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow: hidden;" onclick="event.stopPropagation()">
        <!-- Header -->
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border, #e2e8f0); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: var(--text-main, #1e293b);">KVKK Açık Rıza Metni</h3>
            <button type="button" onclick="closeKvkkModal()" style="background: none; border: none; cursor: pointer; color: var(--muted, #64748b); font-size: 20px; padding: 4px; line-height: 1;">&times;</button>
        </div>
        <!-- Body -->
        <div style="padding: 20px; overflow-y: auto; font-size: 14px; line-height: 1.6; color: var(--text-muted, #475569); max-height: 60vh;">
            <p><strong>KİŞİSEL VERİLERİN KORUNMASI KANUNU (KVKK) KAPSAMINDA AÇIK RIZA BEYANI</strong></p>
            <p>Bu form vasıtasıyla paylaştığınız ad, soyad, e-posta adresi, telefon numarası, öğrenci numarası ve eğitim bilgileri gibi kişisel verileriniz; topluluğumuzun (departmanımızın) faaliyetleri kapsamında üye kaydı oluşturulması, etkinlik süreçlerinin yönetilmesi ve sizinle iletişim kurulması amaçlarıyla 6698 sayılı Kişisel Verilerin Korunması Kanunu'na uygun olarak işlenmektedir.</p>
            <p><strong>Verilerinizin İşlenme Amacı ve Aktarımı:</strong></p>
            <p>Toplanan kişisel verileriniz, kanunun öngördüğü sınırlar dahilinde sadece topluluğumuz bünyesinde işlenecek ve üçüncü şahıslarla paylaşılmayacaktır. Verileriniz, yasal saklama süreleri boyunca güvenli bir şekilde saklanacaktır.</p>
            <p>Onay kutusunu işaretleyerek kişisel verilerinizin yukarıda belirtilen amaçlar doğrultusunda işlenmesini ve tarafınızla iletişim kurulmasını özgür iradenizle kabul etmiş sayılmaktasınız.</p>
        </div>
        <!-- Footer -->
        <div style="padding: 12px 20px; border-top: 1px solid var(--border, #e2e8f0); display: flex; justify-content: flex-end; background: rgba(var(--accent-rgb), 0.02);">
            <button type="button" class="submit-btn" onclick="closeKvkkModal()" style="margin: 0; padding: 8px 24px; font-size: 14px; border-radius: 6px; width: auto; background: var(--accent); color: #fff; border: none; cursor: pointer;">Kapat</button>
        </div>
    </div>
</div>
