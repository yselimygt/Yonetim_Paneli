<style>
    .public-form-container {
        max-width: 600px; margin: 40px auto; padding: 0 20px;
        animation: formFadeIn 0.5s ease-out;
    }
    @keyframes formFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .public-card {
        background: var(--card); border: 1px solid var(--border); border-radius: 16px; 
        overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .public-header {
        background: linear-gradient(135deg, var(--accent) 0%, #3b82f6 100%); 
        padding: 40px 24px; text-align: center; color: #fff; position: relative; overflow: hidden;
    }
    .public-header::after {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.1)" stroke-width="2" fill="none"/></svg>') center/100px repeat;
        opacity: 0.3; pointer-events: none;
    }
    
    .public-header h1 {
        margin: 0 0 12px 0; font-size: 28px; font-weight: 700; position: relative; z-index: 1;
    }
    .public-header p {
        margin: 0; opacity: 0.9; font-size: 15px; position: relative; z-index: 1; font-weight: 500;
    }
    
    .public-body {
        padding: 40px 32px; background: var(--card);
    }
    
    .pf-group { margin-bottom: 20px; }
    .pf-label {
        display: block; margin-bottom: 8px; color: var(--text); font-size: 14px; font-weight: 600;
    }
    .pf-label span { color: #ef4444; }
    
    .pf-control {
        width: 100%; padding: 14px 16px; border-radius: 10px; border: 1px solid var(--border); 
        background: var(--input-bg); color: var(--text); font-size: 15px; 
        transition: all 0.2s ease;
    }
    .pf-control:focus {
        outline: none; border-color: var(--accent); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        background: var(--card);
    }
    
    .pf-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    
    .pf-btn {
        width: 100%; padding: 16px; border-radius: 10px; border: none; 
        background: linear-gradient(135deg, #ff4500 0%, #e63e00 100%); color: #fff; 
        font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(255, 69, 0, 0.2);
    }
    .pf-btn:hover {
        transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255, 69, 0, 0.3);
    }
    
    .success-box {
        background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); 
        color: #10b981; padding: 40px 24px; border-radius: 16px; text-align: center; 
        margin-bottom: 24px; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
    }
    .error-box {
        background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 16px; 
        border-radius: 10px; margin-bottom: 24px; border: 1px solid rgba(239,68,68,0.2);
        display: flex; align-items: center; gap: 12px; font-weight: 500;
    }
    
    @media (max-width: 600px) {
        .pf-grid-2 { grid-template-columns: 1fr; gap: 0; }
        .public-body { padding: 24px 20px; }
    }
</style>

<div class="public-form-container">
    <?php if (isset($_GET['success'])): ?>
    <div class="success-box">
        <svg style="width: 64px; margin-bottom:20px; color:#10b981;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <h2 style="margin: 0 0 12px 0; font-size: 24px; font-weight: 700; color:var(--text);">Başvurunuz Alındı!</h2>
        <p style="margin: 0; font-size: 15px; color:var(--muted); line-height: 1.6;">Topluluğa kayıt başvurunuz başarıyla iletilmiştir. Başvurunuz incelendikten sonra sizinle iletişime geçilecektir. İlginiz için teşekkür ederiz.</p>
    </div>
    <?php else: ?>
    
    <div class="public-card">
        <div class="public-header">
            <h1><?= htmlspecialchars($app['name']) ?></h1>
            <p><?= htmlspecialchars($dept['name']) ?> - Üyelik Başvurusu</p>
        </div>
        
        <div class="public-body">
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="error-box">
                    <svg style="width:20px; flex-shrink:0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?= htmlspecialchars($_SESSION['flash_error']) ?>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="pf-grid-2">
                    <div class="pf-group">
                        <label class="pf-label">Adınız <span>*</span></label>
                        <input type="text" name="first_name" class="pf-control" placeholder="Adınız" required>
                    </div>
                    <div class="pf-group">
                        <label class="pf-label">Soyadınız <span>*</span></label>
                        <input type="text" name="last_name" class="pf-control" placeholder="Soyadınız" required>
                    </div>
                </div>

                <div class="pf-group">
                    <label class="pf-label">E-posta Adresi <span>*</span></label>
                    <input type="email" name="email" class="pf-control" placeholder="ornek@posta.com" required>
                </div>

                <div class="pf-group">
                    <label class="pf-label">Öğrenci Numarası</label>
                    <input type="text" name="student_id" class="pf-control" placeholder="Öğrenci Numaranız">
                </div>
                
                <div class="pf-group">
                    <label class="pf-label">Telefon Numarası</label>
                    <input type="text" name="phone" class="pf-control" placeholder="05XX XXX XX XX">
                </div>

                <div class="pf-group">
                    <label class="pf-label">Üniversite</label>
                    <input type="text" name="university" class="pf-control" placeholder="Örn: Boğaziçi Üniversitesi">
                </div>

                <div class="pf-grid-2">
                    <div class="pf-group">
                        <label class="pf-label">Fakülte</label>
                        <input type="text" name="faculty" class="pf-control" placeholder="Örn: Mühendislik">
                    </div>
                    <div class="pf-group">
                        <label class="pf-label">Bölüm</label>
                        <input type="text" name="department" class="pf-control" placeholder="Örn: Bilgisayar">
                    </div>
                </div>
                
                <div class="pf-group" style="margin-bottom: 32px;">
                    <label class="pf-label">Sınıf</label>
                    <select name="grade" class="pf-control">
                        <option value="">Seçiniz</option>
                        <option value="Hazırlık">Hazırlık</option>
                        <option value="1. Sınıf">1. Sınıf</option>
                        <option value="2. Sınıf">2. Sınıf</option>
                        <option value="3. Sınıf">3. Sınıf</option>
                        <option value="4. Sınıf">4. Sınıf</option>
                        <option value="Yüksek Lisans">Yüksek Lisans</option>
                        <option value="Mezun">Mezun</option>
                    </select>
                </div>

                <button type="submit" class="pf-btn">
                    Başvuruyu Tamamla
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>
