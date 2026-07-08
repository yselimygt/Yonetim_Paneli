<style>

.log-details-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.log-details-item {
    display: flex;
    align-items: baseline;
    gap: 10px;
    font-size: 12px;
    background: rgba(128, 128, 128, 0.04);
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid rgba(128, 128, 128, 0.08);
}
.log-details-label {
    font-weight: 600;
    color: var(--muted);
    min-width: 90px;
    flex-shrink: 0;
}
.log-details-value {
    color: var(--text);
    word-break: break-word;
}
.badge-action {
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.mobile-details-btn {
    display: none;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    background: rgba(128, 128, 128, 0.08);
    border: 1px solid rgba(128, 128, 128, 0.15);
    border-radius: 6px;
    color: var(--text);
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.mobile-details-btn:hover {
    background: rgba(128, 128, 128, 0.15);
}
.table-row-hover {
    transition: background 0.2s ease;
}
.table-row-hover:hover {
    background: rgba(128, 128, 128, 0.03);
}
.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border-radius: 8px;
    background: var(--card-alt);
    border: 1px solid var(--border);
    color: var(--text);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}
.pagination-link:hover {
    border-color: var(--accent);
    color: var(--accent);
}
.pagination-link.active {
    background: color-mix(in srgb, var(--accent) 15%, transparent);
    border-color: color-mix(in srgb, var(--accent) 40%, transparent);
    color: var(--accent);
    font-weight: 600;
}
</style>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold logs-page-title" style="color: var(--text);">Sistem Logları</h1>
        <p class="text-sm mt-1" style="color: var(--muted);">Tüm kullanıcı ve sistem hareketlerini detaylı olarak inceleyin.</p>
    </div>
</div>

<div class="hidden lg:block card overflow-hidden logs-card" style="background: var(--card); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.06);">
    <div class="overflow-x-auto logs-table-wrap">
        <table class="w-full logs-table" style="border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); border-bottom: 1px solid var(--border); background: var(--card-alt); text-align: left; width: 6%;">ID</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); border-bottom: 1px solid var(--border); background: var(--card-alt); text-align: left; width: 14%;">Tarih</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); border-bottom: 1px solid var(--border); background: var(--card-alt); text-align: left; width: 15%;">Kullanıcı</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); border-bottom: 1px solid var(--border); background: var(--card-alt); text-align: left; width: 16%;">İşlem</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); border-bottom: 1px solid var(--border); background: var(--card-alt); text-align: left; width: 14%;">Varlık</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); border-bottom: 1px solid var(--border); background: var(--card-alt); text-align: left; width: 10%;">IP Adresi</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); border-bottom: 1px solid var(--border); background: var(--card-alt); text-align: left; width: 25%;">Detaylar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): 
                        $actionLabel = $log['action'];
                        $actionBadge = '';
                        $actionIcon = 'fas fa-circle-dot';
                        switch ($log['action']) {
                            case 'LOGIN_SUCCESS': $actionLabel = 'Giriş Başarılı'; $actionIcon = 'fas fa-check-circle'; $actionBadge = 'color: #10b981; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);'; break;
                            case 'LOGIN_FAILED': $actionLabel = 'Giriş Başarısız'; $actionIcon = 'fas fa-times-circle'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                            case 'LOGOUT': $actionLabel = 'Çıkış Yapıldı'; $actionIcon = 'fas fa-sign-out-alt'; $actionBadge = 'color: #6b7280; background: rgba(107, 114, 128, 0.12); border: 1px solid rgba(107, 114, 128, 0.25);'; break;
                            case 'CREATE_USER': $actionLabel = 'Kullanıcı Eklendi'; $actionIcon = 'fas fa-user-plus'; $actionBadge = 'color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.25);'; break;
                            case 'UPDATE_USER': $actionLabel = 'Kullanıcı Güncellendi'; $actionIcon = 'fas fa-user-edit'; $actionBadge = 'color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);'; break;
                            case 'DELETE_USER': $actionLabel = 'Kullanıcı Silindi'; $actionIcon = 'fas fa-user-minus'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                            case 'CREATE_DEPARTMENT': $actionLabel = 'Departman Eklendi'; $actionIcon = 'fas fa-building'; $actionBadge = 'color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.25);'; break;
                            case 'UPDATE_DEPARTMENT': $actionLabel = 'Departman Güncellendi'; $actionIcon = 'fas fa-building'; $actionBadge = 'color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);'; break;
                            case 'DELETE_DEPARTMENT': $actionLabel = 'Departman Silindi'; $actionIcon = 'fas fa-building'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                            case 'ASSIGN_DEPARTMENT_TO_USER': $actionLabel = 'Departman Atandı'; $actionIcon = 'fas fa-id-badge'; $actionBadge = 'color: #8b5cf6; background: rgba(139, 92, 246, 0.12); border: 1px solid rgba(139, 92, 246, 0.25);'; break;
                            case 'REMOVE_DEPARTMENT_FROM_USER': $actionLabel = 'Departman Çıkarıldı'; $actionIcon = 'fas fa-id-badge'; $actionBadge = 'color: #f97316; background: rgba(249, 115, 22, 0.12); border: 1px solid rgba(249, 115, 22, 0.25);'; break;
                            case 'ASSIGN_APPS_TO_DEPARTMENT': $actionLabel = 'Uygulama Atandı'; $actionIcon = 'fas fa-cubes'; $actionBadge = 'color: #6366f1; background: rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.25);'; break;
                            case 'APP_INTERACTION': $actionLabel = 'Uygulama İçi İşlem'; $actionIcon = 'fas fa-bolt'; $actionBadge = 'color: #14b8a6; background: rgba(20, 184, 166, 0.12); border: 1px solid rgba(20, 184, 166, 0.25);'; break;
                            case 'PUBLIC_APP_INTERACTION':
                            case 'PUBLIC_GLOBAL_APP_INTERACTION': $actionLabel = 'Açık Form İşlemi'; $actionIcon = 'fas fa-globe'; $actionBadge = 'color: #0d9488; background: rgba(13, 148, 136, 0.12); border: 1px solid rgba(13, 148, 136, 0.25);'; break;
                            case 'UPDATE_PROFILE': $actionLabel = 'Profil Güncellendi'; $actionIcon = 'fas fa-id-card'; $actionBadge = 'color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.25);'; break;
                            case 'CREATE_GLOBAL_ANNOUNCEMENT': $actionLabel = 'Küresel Duyuru'; $actionIcon = 'fas fa-bullhorn'; $actionBadge = 'color: #d946ef; background: rgba(217, 70, 239, 0.12); border: 1px solid rgba(217, 70, 239, 0.25);'; break;
                            case 'CREATE_FORM': $actionLabel = 'Form Oluşturuldu'; $actionIcon = 'fas fa-file-alt'; $actionBadge = 'color: #ec4899; background: rgba(236, 72, 153, 0.12); border: 1px solid rgba(236, 72, 153, 0.25);'; break;
                            case 'TOGGLE_FORM_TEMPLATE': $actionLabel = 'Şablon Değişti'; $actionIcon = 'fas fa-copy'; $actionBadge = 'color: #6b7280; background: rgba(107, 114, 128, 0.12); border: 1px solid rgba(107, 114, 128, 0.25);'; break;
                            case 'CREATE_DUES_PERIOD': $actionLabel = 'Aidat Dönemi Açıldı'; $actionIcon = 'fas fa-calendar-plus'; $actionBadge = 'color: #10b981; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);'; break;
                            case 'DELETE_DUES_PERIOD': $actionLabel = 'Aidat Dönemi Silindi'; $actionIcon = 'fas fa-calendar-minus'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                            case 'TOGGLE_DUES_PAYMENT': $actionLabel = 'Aidat Ödemesi'; $actionIcon = 'fas fa-money-check-alt'; $actionBadge = 'color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);'; break;
                            case 'CREATE_FINANCE_TX': $actionLabel = 'Finans İşlemi Eklendi'; $actionIcon = 'fas fa-coins'; $actionBadge = 'color: #10b981; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);'; break;
                            case 'UPDATE_FINANCE_TX': $actionLabel = 'Finans İşlemi Düzenlendi'; $actionIcon = 'fas fa-coins'; $actionBadge = 'color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);'; break;
                            case 'DELETE_FINANCE_TX': $actionLabel = 'Finans İşlemi Silindi'; $actionIcon = 'fas fa-coins'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                            case 'RECEIVE_FINANCE_PROXY': $actionLabel = 'Elden Ödeme Alındı'; $actionIcon = 'fas fa-hand-holding-usd'; $actionBadge = 'color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.25);'; break;
                            default: $actionBadge = 'color: var(--text); background: rgba(128, 128, 128, 0.1); border: 1px solid rgba(128, 128, 128, 0.2);'; break;
                        }

                        $entityTypeMap = ['User' => 'Kullanıcı', 'Department' => 'Departman', 'App' => 'Uygulama', 'Announcement' => 'Duyuru', 'DuesPeriod' => 'Aidat Dönemi', 'DuesPayment' => 'Aidat Ödemesi', 'FinanceTransaction' => 'Finans İşlemi'];
                        $entityName = $log['entity_type'] ? ($entityTypeMap[$log['entity_type']] ?? $log['entity_type']) : '-';
                        if ($log['entity_id']) {
                            $eId = $log['entity_id'];
                            if ($log['entity_type'] === 'User') {
                                $uName = htmlspecialchars($usersMap[$eId] ?? "Silinmiş Üye (#$eId)");
                                $entityName .= " <span style='color: var(--muted); font-size: 11px; font-weight: 500; display: block; margin-top: 2px;'>{$uName}</span>";
                            } elseif ($log['entity_type'] === 'Department') {
                                $dName = htmlspecialchars($deptsMap[$eId] ?? "Silinmiş Departman (#$eId)");
                                $entityName .= " <span style='color: var(--muted); font-size: 11px; font-weight: 500; display: block; margin-top: 2px;'>{$dName}</span>";
                            } else {
                                $entityName .= " <span style='color: var(--muted); font-size: 11px; font-weight: 500; display: block; margin-top: 2px;'>(#{$eId})</span>";
                            }
                        }

                        $detailsHtml = '-';
                        if ($log['details']) {
                            $decoded = json_decode($log['details'], true);
                            if (is_array($decoded)) {
                                $appMap = ['form' => 'Form', 'drive' => 'Dosya Yöneticisi', 'calendar' => 'Takvim', 'announcement' => 'Duyuru', 'report' => 'Rapor', 'directory' => 'Kurumsal Rehber', 'members' => 'Üye Listesi', 'finance' => 'Finans Paneli', 'dues' => 'Aidat Takibi', 'sponsor' => 'Sponsorlar', 'event' => 'Etkinlik', 'partner' => 'Anlaşmalı Mekanlar', 'bootcamp' => 'Bootcamp Eğitimleri', 'certificate' => 'Sertifika', 'inventory' => 'Envanter', 'links' => 'Hızlı Bağlantılar', 'qr' => 'Karekod', 'shortener' => 'Link Kısaltıcı', 'agile' => 'Agile', 'passwords' => 'Şifre Yöneticisi', 'ideahub' => 'Idea Hub', 'bylaws' => 'Tüzük', 'audit' => 'Denetim', 'trophy' => 'Ödüller', 'wiki' => 'Wiki', 'meeting' => 'Toplantı', 'budget' => 'Bütçe', 'mentorship' => 'Mentor', 'social' => 'İçerik', 'alumni' => 'Mezunlar'];
                                $roleMap = ['director' => 'Yönetici', 'member' => 'Üye'];
                                $statusMap = ['paid' => 'Ödendi', 'unpaid' => 'Ödenmedi', 'pending' => 'Bekliyor', 'delivered' => 'Teslim Alındı'];

                                $detailsHtml = '<div class="log-details-list">';
                                foreach ($decoded as $k => $v) {
                                    $valStr = htmlspecialchars((string)(is_array($v) ? json_encode($v) : $v));
                                    
                                    if ($k === 'modules' && is_array($v)) {
                                        $modNames = array_map(fn($m) => $appMap[$m] ?? $m, $v);
                                        $valStr = implode(', ', $modNames);
                                    } elseif ($k === 'role' && is_string($v)) {
                                        $valStr = $roleMap[$v] ?? $valStr;
                                    } elseif ($k === 'status' && is_string($v)) {
                                        $valStr = $statusMap[$v] ?? $valStr;
                                    } elseif ($k === 'department_id') {
                                        $dName = htmlspecialchars($deptsMap[$v] ?? "Silinmiş Departman (#$v)");
                                        $valStr = $dName;
                                    } elseif (preg_match('/^(is_|can_|has_)/', $k)) {
                                        $valStr = filter_var($v, FILTER_VALIDATE_BOOLEAN) ? 'Evet' : 'Hayır';
                                    }

                                    $keyMap = [
                                        'email' => 'E-posta', 'name' => 'Ad', 'slug' => 'Kısa Ad', 
                                        'department_id' => 'Departman', 'role' => 'Rol', 
                                        'modules' => 'Atanan Uyg.', 'status' => 'Durum', 
                                        'type' => 'Tür', 'amount' => 'Tutar', 'title' => 'Başlık',
                                        'app_slug' => 'Uyg. Slug', 'is_template' => 'Şablon?',
                                        'is_active' => 'Aktif', 'is_super_admin' => 'S. Admin',
                                        'can_manage_passwords' => 'Şifre Yön.', 'is_management' => 'Yön. Dept.',
                                        'description' => 'Açıklama', 'category' => 'Kategori'
                                    ];
                                    $key = $keyMap[$k] ?? htmlspecialchars($k);
                                    $detailsHtml .= "<div class='log-details-item'><span class='log-details-label'>{$key}:</span> <span class='log-details-value'>{$valStr}</span></div>";
                                }
                                $detailsHtml .= '</div>';
                            } else {
                                $detailsHtml = htmlspecialchars($log['details']);
                            }
                        }
                    ?>
                        <tr class="table-row-hover">
                            <td data-label="ID" style="padding: 16px 20px; font-size: 13px; color: var(--muted); border-bottom: 1px solid rgba(128,128,128,0.1); vertical-align: top;">
                                #<?= $log['id'] ?>
                            </td>
                            <td data-label="Tarih" style="padding: 16px 20px; font-size: 13px; color: var(--text); border-bottom: 1px solid rgba(128,128,128,0.1); vertical-align: top;">
                                <div>
                                    <div style="font-weight: 500;"><?= htmlspecialchars(date('d.m.Y', strtotime($log['created_at']))) ?></div>
                                    <div style="font-size: 11px; color: var(--muted); margin-top: 2px;"><?= htmlspecialchars(date('H:i:s', strtotime($log['created_at']))) ?></div>
                                </div>
                            </td>
                            <td data-label="Kullanıcı" style="padding: 16px 20px; border-bottom: 1px solid rgba(128,128,128,0.1); vertical-align: top;">
                                <div>
                                    <?php if($log['user_id']): ?>
                                        <div style="font-size: 13px; font-weight: 600; color: var(--text);"><?= htmlspecialchars($log['user_name']) ?></div>
                                        <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">ID: <?= $log['user_id'] ?></div>
                                    <?php else: ?>
                                        <span style="color: var(--muted); font-style: italic; font-size: 13px;">Sistem / Anonim</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="İşlem" style="padding: 16px 20px; border-bottom: 1px solid rgba(128,128,128,0.1); vertical-align: top;">
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                    <span class="badge-action" style="<?= $actionBadge ?>">
                                        <i class="<?= $actionIcon ?>" style="font-size: 10px;"></i>
                                        <?= htmlspecialchars($actionLabel) ?>
                                    </span>
                                    <button type="button" class="mobile-details-btn" 
                                        data-log-id="<?= $log['id'] ?>"
                                        data-log-date="<?= htmlspecialchars(date('d.m.Y H:i:s', strtotime($log['created_at']))) ?>"
                                        data-log-user="<?= htmlspecialchars($log['user_id'] ? $log['user_name'] . ' (ID: ' . $log['user_id'] . ')' : 'Sistem / Anonim') ?>"
                                        data-log-entity="<?= htmlspecialchars(strip_tags($entityName)) ?>"
                                        data-log-ip="<?= htmlspecialchars($log['ip_address'] ?? '-') ?>"
                                        data-log-details="<?= htmlspecialchars($detailsHtml) ?>"
                                        onclick="openLogDetailsModal(this)">
                                        <i class="fas fa-eye"></i> Detay
                                    </button>
                                </div>
                            </td>
                            <td data-label="Varlık" style="padding: 16px 20px; font-size: 13px; color: var(--text); font-weight: 500; border-bottom: 1px solid rgba(128,128,128,0.1); vertical-align: top;">
                                <?= $entityName ?>
                            </td>
                            <td data-label="IP Adresi" style="padding: 16px 20px; font-size: 12px; font-family: monospace; color: var(--muted); border-bottom: 1px solid rgba(128,128,128,0.1); vertical-align: top;">
                                <?= htmlspecialchars($log['ip_address'] ?? '-') ?>
                            </td>
                            <td data-label="Detaylar" style="padding: 16px 20px; border-bottom: 1px solid rgba(128,128,128,0.1); vertical-align: top;">
                                <?= $detailsHtml ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" data-label="" style="padding: 40px 20px; text-align: center; color: var(--muted); font-size: 14px;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                <i class="fas fa-inbox" style="font-size: 32px; opacity: 0.5;"></i>
                                <span>Henüz kayıtlı log bulunmamaktadır.</span>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile View -->
    <div class="block lg:hidden flex flex-col gap-3">
        <?php if (!empty($logs)): ?>
            <?php foreach ($logs as $log): 
                $actionLabel = $log['action'];
                $actionBadge = '';
                $actionIcon = 'fas fa-circle-dot';
                switch ($log['action']) {
                    case 'LOGIN_SUCCESS': $actionLabel = 'Giriş Başarılı'; $actionIcon = 'fas fa-check-circle'; $actionBadge = 'color: #10b981; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);'; break;
                    case 'LOGIN_FAILED': $actionLabel = 'Giriş Başarısız'; $actionIcon = 'fas fa-times-circle'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                    case 'LOGOUT': $actionLabel = 'Çıkış Yapıldı'; $actionIcon = 'fas fa-sign-out-alt'; $actionBadge = 'color: #6b7280; background: rgba(107, 114, 128, 0.12); border: 1px solid rgba(107, 114, 128, 0.25);'; break;
                    case 'CREATE_USER': $actionLabel = 'Kullanıcı Eklendi'; $actionIcon = 'fas fa-user-plus'; $actionBadge = 'color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.25);'; break;
                    case 'UPDATE_USER': $actionLabel = 'Kullanıcı Güncellendi'; $actionIcon = 'fas fa-user-edit'; $actionBadge = 'color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);'; break;
                    case 'DELETE_USER': $actionLabel = 'Kullanıcı Silindi'; $actionIcon = 'fas fa-user-minus'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                    case 'CREATE_DEPARTMENT': $actionLabel = 'Departman Eklendi'; $actionIcon = 'fas fa-building'; $actionBadge = 'color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.25);'; break;
                    case 'UPDATE_DEPARTMENT': $actionLabel = 'Departman Güncellendi'; $actionIcon = 'fas fa-building'; $actionBadge = 'color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);'; break;
                    case 'DELETE_DEPARTMENT': $actionLabel = 'Departman Silindi'; $actionIcon = 'fas fa-building'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                    case 'ASSIGN_DEPARTMENT_TO_USER': $actionLabel = 'Departman Atandı'; $actionIcon = 'fas fa-id-badge'; $actionBadge = 'color: #8b5cf6; background: rgba(139, 92, 246, 0.12); border: 1px solid rgba(139, 92, 246, 0.25);'; break;
                    case 'REMOVE_DEPARTMENT_FROM_USER': $actionLabel = 'Departman Çıkarıldı'; $actionIcon = 'fas fa-id-badge'; $actionBadge = 'color: #f97316; background: rgba(249, 115, 22, 0.12); border: 1px solid rgba(249, 115, 22, 0.25);'; break;
                    case 'ASSIGN_APPS_TO_DEPARTMENT': $actionLabel = 'Uygulama Atandı'; $actionIcon = 'fas fa-cubes'; $actionBadge = 'color: #6366f1; background: rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.25);'; break;
                    case 'APP_INTERACTION': $actionLabel = 'Uygulama İçi İşlem'; $actionIcon = 'fas fa-bolt'; $actionBadge = 'color: #14b8a6; background: rgba(20, 184, 166, 0.12); border: 1px solid rgba(20, 184, 166, 0.25);'; break;
                    case 'PUBLIC_APP_INTERACTION':
                    case 'PUBLIC_GLOBAL_APP_INTERACTION': $actionLabel = 'Açık Form İşlemi'; $actionIcon = 'fas fa-globe'; $actionBadge = 'color: #0d9488; background: rgba(13, 148, 136, 0.12); border: 1px solid rgba(13, 148, 136, 0.25);'; break;
                    case 'UPDATE_PROFILE': $actionLabel = 'Profil Güncellendi'; $actionIcon = 'fas fa-id-card'; $actionBadge = 'color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.25);'; break;
                    case 'CREATE_GLOBAL_ANNOUNCEMENT': $actionLabel = 'Küresel Duyuru'; $actionIcon = 'fas fa-bullhorn'; $actionBadge = 'color: #d946ef; background: rgba(217, 70, 239, 0.12); border: 1px solid rgba(217, 70, 239, 0.25);'; break;
                    case 'CREATE_FORM': $actionLabel = 'Form Oluşturuldu'; $actionIcon = 'fas fa-file-alt'; $actionBadge = 'color: #ec4899; background: rgba(236, 72, 153, 0.12); border: 1px solid rgba(236, 72, 153, 0.25);'; break;
                    case 'TOGGLE_FORM_TEMPLATE': $actionLabel = 'Şablon Değişti'; $actionIcon = 'fas fa-copy'; $actionBadge = 'color: #6b7280; background: rgba(107, 114, 128, 0.12); border: 1px solid rgba(107, 114, 128, 0.25);'; break;
                    case 'CREATE_DUES_PERIOD': $actionLabel = 'Aidat Dönemi Açıldı'; $actionIcon = 'fas fa-calendar-plus'; $actionBadge = 'color: #10b981; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);'; break;
                    case 'DELETE_DUES_PERIOD': $actionLabel = 'Aidat Dönemi Silindi'; $actionIcon = 'fas fa-calendar-minus'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                    case 'TOGGLE_DUES_PAYMENT': $actionLabel = 'Aidat Ödemesi'; $actionIcon = 'fas fa-money-check-alt'; $actionBadge = 'color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);'; break;
                    case 'CREATE_FINANCE_TX': $actionLabel = 'Finans İşlemi Eklendi'; $actionIcon = 'fas fa-coins'; $actionBadge = 'color: #10b981; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);'; break;
                    case 'UPDATE_FINANCE_TX': $actionLabel = 'Finans İşlemi Düzenlendi'; $actionIcon = 'fas fa-coins'; $actionBadge = 'color: #f59e0b; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);'; break;
                    case 'DELETE_FINANCE_TX': $actionLabel = 'Finans İşlemi Silindi'; $actionIcon = 'fas fa-coins'; $actionBadge = 'color: #ef4444; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);'; break;
                    case 'RECEIVE_FINANCE_PROXY': $actionLabel = 'Elden Ödeme Alındı'; $actionIcon = 'fas fa-hand-holding-usd'; $actionBadge = 'color: #3b82f6; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.25);'; break;
                    default: $actionBadge = 'color: var(--text); background: rgba(128, 128, 128, 0.1); border: 1px solid rgba(128, 128, 128, 0.2);'; break;
                }
                
                $entityTypeMap = ['User' => 'Kullanıcı', 'Department' => 'Departman', 'App' => 'Uygulama', 'Announcement' => 'Duyuru', 'DuesPeriod' => 'Aidat Dönemi', 'DuesPayment' => 'Aidat Ödemesi', 'FinanceTransaction' => 'Finans İşlemi'];
                $entityName = $log['entity_type'] ? ($entityTypeMap[$log['entity_type']] ?? $log['entity_type']) : '-';
                if ($log['entity_id']) {
                    $eId = $log['entity_id'];
                    if ($log['entity_type'] === 'User') {
                        $uName = htmlspecialchars($usersMap[$eId] ?? "Silinmiş Üye (#$eId)");
                        $entityName .= " <span style='color: var(--muted); font-size: 11px; font-weight: 500; display: block; margin-top: 2px;'>{$uName}</span>";
                    } elseif ($log['entity_type'] === 'Department') {
                        $dName = htmlspecialchars($deptsMap[$eId] ?? "Silinmiş Departman (#$eId)");
                        $entityName .= " <span style='color: var(--muted); font-size: 11px; font-weight: 500; display: block; margin-top: 2px;'>{$dName}</span>";
                    } else {
                        $entityName .= " <span style='color: var(--muted); font-size: 11px; font-weight: 500; display: block; margin-top: 2px;'>(#{$eId})</span>";
                    }
                }
                
                $detailsHtml = '-';
                if ($log['details']) {
                    $decoded = json_decode($log['details'], true);
                    if (is_array($decoded)) {
                        $appMap = ['form' => 'Form', 'drive' => 'Dosya Yöneticisi', 'calendar' => 'Takvim', 'announcement' => 'Duyuru', 'report' => 'Rapor', 'directory' => 'Kurumsal Rehber', 'members' => 'Üye Listesi', 'finance' => 'Finans Paneli', 'dues' => 'Aidat Takibi', 'sponsor' => 'Sponsorlar', 'event' => 'Etkinlik', 'partner' => 'Anlaşmalı Mekanlar', 'bootcamp' => 'Bootcamp Eğitimleri', 'certificate' => 'Sertifika', 'inventory' => 'Envanter', 'links' => 'Hızlı Bağlantılar', 'qr' => 'Karekod', 'shortener' => 'Link Kısaltıcı', 'agile' => 'Agile', 'passwords' => 'Şifre Yöneticisi', 'ideahub' => 'Idea Hub', 'bylaws' => 'Tüzük', 'audit' => 'Denetim', 'trophy' => 'Ödüller', 'wiki' => 'Wiki', 'meeting' => 'Toplantı', 'budget' => 'Bütçe', 'mentorship' => 'Mentor', 'social' => 'İçerik', 'alumni' => 'Mezunlar'];
                        $roleMap = ['director' => 'Yönetici', 'member' => 'Üye'];
                        $statusMap = ['paid' => 'Ödendi', 'unpaid' => 'Ödenmedi', 'pending' => 'Bekliyor', 'delivered' => 'Teslim Alındı'];

                        $detailsHtml = '<div class="log-details-list">';
                        foreach ($decoded as $k => $v) {
                            $valStr = htmlspecialchars((string)(is_array($v) ? json_encode($v) : $v));
                            if ($k === 'modules' && is_array($v)) {
                                $modNames = array_map(fn($m) => $appMap[$m] ?? $m, $v);
                                $valStr = implode(', ', $modNames);
                            } elseif ($k === 'role' && is_string($v)) {
                                $valStr = $roleMap[$v] ?? $valStr;
                            } elseif ($k === 'status' && is_string($v)) {
                                $valStr = $statusMap[$v] ?? $valStr;
                            } elseif ($k === 'department_id') {
                                $dName = htmlspecialchars($deptsMap[$v] ?? "Silinmiş Departman (#$v)");
                                $valStr = $dName;
                            } elseif (preg_match('/^(is_|can_|has_)/', $k)) {
                                $valStr = filter_var($v, FILTER_VALIDATE_BOOLEAN) ? 'Evet' : 'Hayır';
                            }
                            $keyMap = ['email' => 'E-posta', 'name' => 'Ad', 'slug' => 'Kısa Ad', 'department_id' => 'Departman', 'role' => 'Rol', 'modules' => 'Atanan Uyg.', 'status' => 'Durum', 'type' => 'Tür', 'amount' => 'Tutar', 'title' => 'Başlık', 'app_slug' => 'Uyg. Slug', 'is_template' => 'Şablon?', 'is_active' => 'Aktif', 'is_super_admin' => 'S. Admin', 'can_manage_passwords' => 'Şifre Yön.', 'is_management' => 'Yön. Dept.', 'description' => 'Açıklama', 'category' => 'Kategori'];
                            $key = $keyMap[$k] ?? htmlspecialchars($k);
                            $detailsHtml .= "<div class='log-details-item'><span class='log-details-label'>{$key}:</span> <span class='log-details-value'>{$valStr}</span></div>";
                        }
                        $detailsHtml .= '</div>';
                    } else {
                        $detailsHtml = htmlspecialchars($log['details']);
                    }
                }
            ?>
            <div style="background: var(--card-alt); border: 1px solid var(--border); border-radius: 14px; padding: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed rgba(128,128,128,0.15); padding-bottom: 12px; margin-bottom: 12px;">
                    <div>
                        <div style="font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Kullanıcı</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text);">
                            <?= $log['user_id'] ? htmlspecialchars($log['user_name']) : 'Sistem / Anonim' ?>
                        </div>
                        <?php if($log['user_id']): ?>
                            <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">ID: <?= $log['user_id'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Tarih</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text);">
                            <?= htmlspecialchars(date('d.m.Y', strtotime($log['created_at']))) ?>
                        </div>
                        <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">
                            <?= htmlspecialchars(date('H:i:s', strtotime($log['created_at']))) ?>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">İşlem</div>
                        <span class="badge-action" style="<?= $actionBadge ?>">
                            <i class="<?= $actionIcon ?>" style="font-size: 10px;"></i>
                            <?= htmlspecialchars($actionLabel) ?>
                        </span>
                    </div>
                    
                    <button type="button" class="mobile-details-btn-real" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(128, 128, 128, 0.08); border: 1px solid rgba(128, 128, 128, 0.15); border-radius: 8px; color: var(--text); font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s;" 
                        data-log-id="<?= $log['id'] ?>"
                        data-log-date="<?= htmlspecialchars(date('d.m.Y H:i:s', strtotime($log['created_at']))) ?>"
                        data-log-user="<?= htmlspecialchars($log['user_id'] ? $log['user_name'] . ' (ID: ' . $log['user_id'] . ')' : 'Sistem / Anonim') ?>"
                        data-log-entity="<?= htmlspecialchars(strip_tags($entityName)) ?>"
                        data-log-ip="<?= htmlspecialchars($log['ip_address'] ?? '-') ?>"
                        data-log-details="<?= htmlspecialchars($detailsHtml) ?>"
                        onclick="openLogDetailsModal(this)">
                        <i class="fas fa-eye"></i> Detay
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="background: var(--card-alt); border: 1px solid var(--border); border-radius: 14px; padding: 40px 20px; text-align: center; color: var(--muted);">
                <i class="fas fa-inbox" style="font-size: 32px; opacity: 0.5; margin-bottom: 12px;"></i>
                <div style="font-size: 14px;">Henüz kayıtlı log bulunmamaktadır.</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
            <div style="font-size: 13px; color: var(--muted);">
                Toplam <span style="font-weight: 600; color: var(--text);"><?= $totalPages ?></span> sayfadan <span style="font-weight: 600; color: var(--text);"><?= $page ?></span>. sayfa
            </div>
            
            <nav style="display: flex; gap: 6px;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="pagination-link" title="Önceki Sayfa">
                        <i class="fas fa-chevron-left" style="font-size: 10px;"></i>
                    </a>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);

                for ($i = $start; $i <= $end; $i++):
                ?>
                    <a href="?page=<?= $i ?>" class="pagination-link <?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="pagination-link" title="Sonraki Sayfa">
                        <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Mobile Log Details Modal -->
<div id="mobileLogModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Log Detayları</h3>
            <button type="button" class="modal-close" onclick="closeLogDetailsModal()">&times;</button>
        </div>
        <div id="mobileLogModalBody">
            <!-- Modal Content Injected Here -->
        </div>
    </div>
</div>

<script>
function openLogDetailsModal(btn) {
    const id = btn.getAttribute('data-log-id');
    const date = btn.getAttribute('data-log-date');
    const user = btn.getAttribute('data-log-user');
    const entity = btn.getAttribute('data-log-entity');
    const ip = btn.getAttribute('data-log-ip');
    const details = btn.getAttribute('data-log-details');

    let html = `
        <div class="log-details-list" style="margin-bottom: 20px;">
            <div class="log-details-item"><span class="log-details-label">ID:</span> <span class="log-details-value">#${id}</span></div>
            <div class="log-details-item"><span class="log-details-label">Tarih:</span> <span class="log-details-value">${date}</span></div>
            <div class="log-details-item"><span class="log-details-label">Kullanıcı:</span> <span class="log-details-value">${user}</span></div>
            <div class="log-details-item"><span class="log-details-label">Varlık:</span> <span class="log-details-value">${entity}</span></div>
            <div class="log-details-item"><span class="log-details-label">IP Adresi:</span> <span class="log-details-value">${ip}</span></div>
        </div>
        <h4 style="font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border);">İşlem Detayları</h4>
        ${details !== '-' ? details : '<div style="color: var(--muted); font-size: 12px; font-style: italic;">Ek detay bulunmuyor.</div>'}
    `;

    document.getElementById('mobileLogModalBody').innerHTML = html;
    document.getElementById('mobileLogModal').classList.add('show');
}

function closeLogDetailsModal() {
    document.getElementById('mobileLogModal').classList.remove('show');
}
</script>
