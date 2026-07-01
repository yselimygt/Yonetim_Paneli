<!-- Add / Edit Modal -->
<div class="modal-overlay" id="alumniModal">
    <div class="modal-content">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" id="alumniAction" value="create_alumni">
            <input type="hidden" name="id" id="alumniId" value="">
            
            <div class="modal-header">
                <h3 id="alumniModalTitle">Yeni Mezun Ekle</h3>
                <button type="button" class="modal-close" onclick="closeModal('alumniModal')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Ad Soyad *</label>
                        <input type="text" name="full_name" id="af_full_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>E-posta</label>
                        <input type="email" name="email" id="af_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Telefon</label>
                        <input type="text" name="phone" id="af_phone" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Üniversite</label>
                        <input type="text" name="university" id="af_university" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Bölüm</label>
                        <input type="text" name="major" id="af_major" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Mezuniyet Yılı</label>
                        <input type="number" name="graduation_year" id="af_graduation_year" class="form-control" placeholder="2020">
                    </div>
                    <div class="form-group">
                        <label>Konum (Şehir/Ülke)</label>
                        <input type="text" name="location" id="af_location" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Şirket</label>
                        <input type="text" name="company" id="af_company" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Ünvan (Job Title)</label>
                        <input type="text" name="job_title" id="af_job_title" class="form-control">
                    </div>

                    <div class="form-group full">
                        <label>LinkedIn Profil Linki</label>
                        <input type="url" name="linkedin_url" id="af_linkedin_url" class="form-control" placeholder="https://linkedin.com/in/username">
                    </div>

                    <div class="form-group full">
                        <label>Biyografi / Notlar</label>
                        <textarea name="bio" id="af_bio" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group full">
                        <label>Profil Fotoğrafı (İsteğe Bağlı)</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group full" style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="is_mentor" id="af_is_mentor" value="1" style="width:16px; height:16px;">
                        <label for="af_is_mentor" style="margin:0; cursor:pointer;">Bu kişi mentörlüğe açık</label>
                    </div>
                </div>
                
                <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('alumniModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- View Profile Modal -->
<div class="modal-overlay" id="viewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="vf_name">Mezun Profili</h3>
            <div style="display:flex; gap:8px;">
                <button type="button" class="modal-close" onclick="closeModal('viewModal')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
        <div class="modal-body" style="text-align: center;">
            <div id="vf_avatar_container" style="display:inline-block; margin-bottom:16px; position:relative;"></div>
            <h2 id="vf_full_name" style="margin:0 0 8px 0; font-size:22px;"></h2>
            <p id="vf_title_company" style="margin:0 0 16px 0; color:var(--muted); font-size:15px;"></p>
            
            <div id="vf_badges" class="alumni-meta" style="margin-bottom:24px;"></div>

            <div style="text-align: left; background: var(--surface); padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                <h4 style="margin:0 0 12px 0; font-size:14px; text-transform:uppercase; color:var(--muted); letter-spacing:0.5px;">Eğitim</h4>
                <p style="margin:0;" id="vf_education"></p>
            </div>

            <div style="text-align: left; background: var(--surface); padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                <h4 style="margin:0 0 12px 0; font-size:14px; text-transform:uppercase; color:var(--muted); letter-spacing:0.5px;">İletişim & Biyografi</h4>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <div id="vf_email_wrap"><strong style="display:inline-block; width:80px;">E-posta:</strong> <a id="vf_email_link" href=""></a></div>
                    <div id="vf_phone_wrap"><strong style="display:inline-block; width:80px;">Telefon:</strong> <span id="vf_phone_text"></span></div>
                    <div id="vf_bio_wrap" style="margin-top:12px; line-height:1.5;"></div>
                </div>
            </div>

            <div style="display:flex; justify-content:center; gap:12px;">
                <a href="#" id="vf_linkedin_btn" target="_blank" class="btn btn-outline" style="display:none; align-items:center; gap:8px;">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    LinkedIn
                </a>
                <button id="vf_edit_btn" class="btn btn-primary" onclick="editCurrentAlumni()">Düzenle</button>
            </div>
            
            <div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                
                <form method="POST" action="" style="margin:0;" id="form_spotlight">
                    <input type="hidden" name="action" value="toggle_spotlight">
                    <input type="hidden" name="item_id" id="vf_spotlight_id" value="">
                    <input type="hidden" name="spotlight_val" id="vf_spotlight_val" value="1">
                    <button type="submit" id="vf_spotlight_btn" class="btn btn-outline" style="color:#f59e0b; border-color:transparent;">⭐ Başarı Hikayesi Yap</button>
                </form>

                <form method="POST" action="" onsubmit="return confirm('Bu mezun kaydını silmek istediğinize emin misiniz?');" style="margin:0;">
                    <input type="hidden" name="action" value="delete_alumni">
                    <input type="hidden" name="item_id" id="vf_delete_id" value="">
                    <button type="submit" class="btn btn-outline" style="color:var(--danger); border-color:transparent;">Profili Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal-overlay" id="messageModal">
    <div class="modal-content">
        <form method="POST" action="">
            <input type="hidden" name="action" value="send_message">
            <input type="hidden" name="receiver_id" id="msg_receiver_id" value="">
            
            <div class="modal-header">
                <h3>Mentöre Mesaj Gönder</h3>
                <button type="button" class="modal-close" onclick="closeModal('messageModal')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom:16px; color:var(--muted);">Bu mesajınız, kişinin e-posta adresine güvenli bir şekilde (sizin e-postanız gizlenerek) sistem tarafından iletilecektir.</p>
                <div class="form-group full" style="margin-bottom:16px;">
                    <label>Kime:</label>
                    <input type="text" id="msg_receiver_name" class="form-control" disabled>
                </div>
                <div class="form-group full" style="margin-bottom:16px;">
                    <label>Konu</label>
                    <input type="text" name="subject" class="form-control" required placeholder="Mentörlük Talebi - Kariyer Gelişimi">
                </div>
                <div class="form-group full" style="margin-bottom:16px;">
                    <label>Mesajınız</label>
                    <textarea name="message" class="form-control" rows="5" required placeholder="Merhaba, kariyer planlamam hakkında sizinle görüşmek isterim..."></textarea>
                </div>
                
                <div style="display:flex; justify-content:flex-end; gap:12px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('messageModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">Mesajı Gönder</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Job Add Modal -->
<div class="modal-overlay" id="jobModal">
    <div class="modal-content">
        <form method="POST" action="">
            <input type="hidden" name="action" value="create_job">
            
            <div class="modal-header">
                <h3>Yeni İlan Ekle</h3>
                <button type="button" class="modal-close" onclick="closeModal('jobModal')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body form-grid">
                <div class="form-group full">
                    <label>İlan Başlığı *</label>
                    <input type="text" name="title" class="form-control" required placeholder="Yazılım Geliştirici Stajyeri">
                </div>
                <div class="form-group">
                    <label>Şirket *</label>
                    <input type="text" name="company" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>İlan Tipi</label>
                    <select name="job_type" class="form-control">
                        <option value="İş">Tam Zamanlı İş</option>
                        <option value="Staj">Staj</option>
                        <option value="Yarı Zamanlı">Yarı Zamanlı</option>
                        <option value="Proje Bazlı">Proje Bazlı</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Konum</label>
                    <input type="text" name="location" class="form-control" placeholder="İstanbul / Uzaktan">
                </div>
                <div class="form-group">
                    <label>Başvuru Linki veya E-posta</label>
                    <input type="text" name="apply_url" class="form-control" placeholder="https://...">
                </div>
                
                <div class="form-group full" style="margin-top:8px;">
                    <label>İlanı Veren Mezun (Varsa Eşleştir)</label>
                    <select name="posted_by_alumni_id" class="form-control">
                        <option value="">- Dernek Yönetimi Tarafından Eklendi -</option>
                        <?php foreach($alumni as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= View::escape($a['full_name']) ?> (<?= View::escape($a['company'] ?? '') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Açıklama</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>
                
                <div class="form-group full" style="display:flex; justify-content:flex-end; gap:12px; margin-top:16px;">
                    <button type="button" class="btn btn-outline" onclick="closeModal('jobModal')">İptal</button>
                    <button type="submit" class="btn btn-primary">İlanı Yayınla</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let currentViewAlum = null;

function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function openAlumniModal() {
    document.getElementById('alumniModalTitle').innerText = 'Yeni Mezun Ekle';
    document.getElementById('alumniAction').value = 'create_alumni';
    document.getElementById('alumniId').value = '';
    
    document.getElementById('af_full_name').value = '';
    document.getElementById('af_email').value = '';
    document.getElementById('af_phone').value = '';
    document.getElementById('af_university').value = '';
    document.getElementById('af_major').value = '';
    document.getElementById('af_graduation_year').value = '';
    document.getElementById('af_location').value = '';
    document.getElementById('af_company').value = '';
    document.getElementById('af_job_title').value = '';
    document.getElementById('af_linkedin_url').value = '';
    document.getElementById('af_bio').value = '';
    document.getElementById('af_is_mentor').checked = false;

    openModal('alumniModal');
}

function editAlumni(alum) {
    document.getElementById('alumniModalTitle').innerText = 'Mezun Düzenle';
    document.getElementById('alumniAction').value = 'update_alumni';
    document.getElementById('alumniId').value = alum.id;
    
    document.getElementById('af_full_name').value = alum.full_name || '';
    document.getElementById('af_email').value = alum.email || '';
    document.getElementById('af_phone').value = alum.phone || '';
    document.getElementById('af_university').value = alum.university || '';
    document.getElementById('af_major').value = alum.major || '';
    document.getElementById('af_graduation_year').value = alum.graduation_year || '';
    document.getElementById('af_location').value = alum.location || '';
    document.getElementById('af_company').value = alum.company || '';
    document.getElementById('af_job_title').value = alum.job_title || '';
    document.getElementById('af_linkedin_url').value = alum.linkedin_url || '';
    document.getElementById('af_bio').value = alum.bio || '';
    document.getElementById('af_is_mentor').checked = (alum.is_mentor == 1);

    openModal('alumniModal');
}

function viewAlumni(alum) {
    currentViewAlum = alum;
    document.getElementById('vf_name').innerText = alum.full_name;
    document.getElementById('vf_full_name').innerText = alum.full_name;
    document.getElementById('vf_delete_id').value = alum.id;
    document.getElementById('vf_spotlight_id').value = alum.id;
    
    if (alum.is_spotlight == 1) {
        document.getElementById('vf_spotlight_btn').innerHTML = '❌ Başarı Hikayelerinden Çıkar';
        document.getElementById('vf_spotlight_val').value = '0';
    } else {
        document.getElementById('vf_spotlight_btn').innerHTML = '⭐ Başarı Hikayesi Yap';
        document.getElementById('vf_spotlight_val').value = '1';
    }

    let subtitle = '';
    if(alum.job_title) subtitle += alum.job_title;
    if(alum.company) {
        if(subtitle) subtitle += ' @ ';
        subtitle += alum.company;
    }
    document.getElementById('vf_title_company').innerText = subtitle || 'Belirtilmedi';

    let avatarHtml = '';
    if (alum.avatar_path) {
        avatarHtml = `<img src="<?= BASE_URL ?>/public/${alum.avatar_path}" alt="" style="width:100px; height:100px; border-radius:50%; object-fit:cover; box-shadow:0 8px 24px rgba(0,0,0,0.15);">`;
    } else {
        let initials = alum.full_name.split(' ').map(n => n[0]).join('').substring(0,2).toUpperCase();
        avatarHtml = `<div style="width:100px; height:100px; border-radius:50%; background:var(--accent); color:#fff; display:flex; align-items:center; justify-content:center; font-size:36px; font-weight:bold; box-shadow:0 8px 24px rgba(0,0,0,0.15);">${initials}</div>`;
    }
    document.getElementById('vf_avatar_container').innerHTML = avatarHtml;

    let badgesHtml = '';
    if (alum.is_mentor == 1) { badgesHtml += `<span class="alumni-badge badge-mentor"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path></svg> Mentör</span>`; }
    if (alum.graduation_year) { badgesHtml += `<span class="alumni-badge"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg> ${alum.graduation_year}</span>`; }
    if (alum.location) { badgesHtml += `<span class="alumni-badge"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> ${alum.location}</span>`; }
    document.getElementById('vf_badges').innerHTML = badgesHtml;

    let edu = '';
    if(alum.university) edu += `<strong>${alum.university}</strong>`;
    if(alum.major) edu += ` <br><span style="color:var(--muted);">${alum.major}</span>`;
    if(!edu) edu = '<i>Belirtilmedi</i>';
    document.getElementById('vf_education').innerHTML = edu;

    if(alum.email) { document.getElementById('vf_email_wrap').style.display = 'block'; document.getElementById('vf_email_link').href = 'mailto:'+alum.email; document.getElementById('vf_email_link').innerText = alum.email; } else { document.getElementById('vf_email_wrap').style.display = 'none'; }
    if(alum.phone) { document.getElementById('vf_phone_wrap').style.display = 'block'; document.getElementById('vf_phone_text').innerText = alum.phone; } else { document.getElementById('vf_phone_wrap').style.display = 'none'; }
    if(alum.bio) { document.getElementById('vf_bio_wrap').style.display = 'block'; document.getElementById('vf_bio_wrap').innerText = alum.bio; } else { document.getElementById('vf_bio_wrap').style.display = 'none'; }

    let lnBtn = document.getElementById('vf_linkedin_btn');
    if (alum.linkedin_url) { lnBtn.style.display = 'flex'; lnBtn.href = alum.linkedin_url; } else { lnBtn.style.display = 'none'; }

    openModal('viewModal');
}

function editCurrentAlumni() {
    if(currentViewAlum) {
        closeModal('viewModal');
        editAlumni(currentViewAlum);
    }
}

function openMessageModal(alum) {
    document.getElementById('msg_receiver_id').value = alum.id;
    document.getElementById('msg_receiver_name').value = alum.full_name;
    openModal('messageModal');
}

function openJobModal() {
    openModal('jobModal');
}
</script>
