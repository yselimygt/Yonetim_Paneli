<style>
@media (max-width: 700px) {
    .logs-page-title {
        color: var(--text) !important;
        font-size: 22px !important;
        line-height: 1.25;
    }

    .logs-card {
        background: transparent !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .logs-table-wrap {
        overflow: visible !important;
    }

    .logs-table {
        display: table;
        min-width: 0 !important;
        overflow: visible;
    }

    .logs-table thead {
        display: none;
    }

    .logs-table,
    .logs-table tbody,
    .logs-table tr,
    .logs-table td {
        width: 100%;
    }

    .logs-table tbody {
        display: grid;
        gap: 12px;
        background: transparent !important;
    }

    .logs-table tr {
        display: grid;
        gap: 10px;
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--card);
        padding: 14px;
        box-shadow: 0 8px 28px rgba(0,0,0,0.14);
    }

    .logs-table td {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        max-width: none;
        border-bottom: 1px solid rgba(255,255,255,.04);
        padding: 0 0 10px !important;
        white-space: normal !important;
        overflow: visible;
        text-align: right;
        color: var(--text) !important;
    }

    .logs-table td::before {
        content: attr(data-label);
        flex: 0 0 92px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .05em;
        text-align: left;
        text-transform: uppercase;
    }

    .logs-table td:last-child {
        display: block;
        border-bottom: 0;
        padding-bottom: 0 !important;
        text-align: left;
    }

    .logs-table td:last-child::before {
        display: block;
        margin-bottom: 8px;
    }

    .log-user-cell,
    .log-entity-cell,
    .log-details-cell {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .log-details-cell ul {
        display: grid;
        gap: 6px;
        padding-left: 0;
        list-style: none;
    }

    .log-details-cell li {
        overflow-wrap: anywhere;
    }

    .logs-pagination {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
        padding: 14px !important;
        border-top: 0 !important;
    }

    .logs-pagination .hidden {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
        width: 100%;
    }

    .logs-pagination nav {
        display: flex !important;
        flex-wrap: wrap;
        gap: 6px;
        box-shadow: none !important;
    }

    .logs-pagination nav a {
        border-radius: 8px !important;
    }
}
</style>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 logs-page-title">Sistem Logları</h1>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden logs-card">
    <div class="overflow-x-auto logs-table-wrap">
        <table class="min-w-full divide-y divide-gray-200 logs-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlem</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Varlık</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Adresi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detaylar</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): 
                        $actionLabel = $log['action'];
                        $actionClass = 'bg-gray-100 text-gray-800';
                        switch ($log['action']) {
                            case 'LOGIN_SUCCESS': $actionLabel = 'Giriş Başarılı'; $actionClass = 'bg-green-100 text-green-800'; break;
                            case 'LOGIN_FAILED': $actionLabel = 'Giriş Başarısız'; $actionClass = 'bg-red-100 text-red-800'; break;
                            case 'LOGOUT': $actionLabel = 'Çıkış Yapıldı'; $actionClass = 'bg-gray-100 text-gray-600'; break;
                            case 'CREATE_USER': $actionLabel = 'Kullanıcı Eklendi'; $actionClass = 'bg-blue-100 text-blue-800'; break;
                            case 'UPDATE_USER': $actionLabel = 'Kullanıcı Güncellendi'; $actionClass = 'bg-yellow-100 text-yellow-800'; break;
                            case 'DELETE_USER': $actionLabel = 'Kullanıcı Silindi'; $actionClass = 'bg-red-100 text-red-800'; break;
                            case 'CREATE_DEPARTMENT': $actionLabel = 'Departman Eklendi'; $actionClass = 'bg-blue-100 text-blue-800'; break;
                            case 'UPDATE_DEPARTMENT': $actionLabel = 'Departman Güncellendi'; $actionClass = 'bg-yellow-100 text-yellow-800'; break;
                            case 'DELETE_DEPARTMENT': $actionLabel = 'Departman Silindi'; $actionClass = 'bg-red-100 text-red-800'; break;
                            case 'ASSIGN_DEPARTMENT_TO_USER': $actionLabel = 'Departman Atandı'; $actionClass = 'bg-purple-100 text-purple-800'; break;
                            case 'REMOVE_DEPARTMENT_FROM_USER': $actionLabel = 'Departman Çıkarıldı'; $actionClass = 'bg-orange-100 text-orange-800'; break;
                            case 'ASSIGN_APPS_TO_DEPARTMENT': $actionLabel = 'Uygulama Atandı'; $actionClass = 'bg-indigo-100 text-indigo-800'; break;
                            case 'APP_INTERACTION': $actionLabel = 'Uygulama İçi İşlem'; $actionClass = 'bg-teal-100 text-teal-800'; break;
                            case 'PUBLIC_APP_INTERACTION':
                            case 'PUBLIC_GLOBAL_APP_INTERACTION': $actionLabel = 'Açık Form/App İşlemi'; $actionClass = 'bg-teal-50 text-teal-600 border border-teal-200'; break;
                            case 'UPDATE_PROFILE': $actionLabel = 'Profil Güncellendi'; $actionClass = 'bg-blue-100 text-blue-800'; break;
                            case 'CREATE_GLOBAL_ANNOUNCEMENT': $actionLabel = 'Küresel Duyuru'; $actionClass = 'bg-fuchsia-100 text-fuchsia-800'; break;
                            case 'CREATE_FORM': $actionLabel = 'Form Oluşturuldu'; $actionClass = 'bg-pink-100 text-pink-800'; break;
                            case 'TOGGLE_FORM_TEMPLATE': $actionLabel = 'Şablon Durumu Değişti'; $actionClass = 'bg-gray-100 text-gray-800'; break;
                            case 'CREATE_DUES_PERIOD': $actionLabel = 'Aidat Dönemi Açıldı'; $actionClass = 'bg-emerald-100 text-emerald-800'; break;
                            case 'DELETE_DUES_PERIOD': $actionLabel = 'Aidat Dönemi Silindi'; $actionClass = 'bg-red-100 text-red-800'; break;
                            case 'TOGGLE_DUES_PAYMENT': $actionLabel = 'Aidat Ödemesi Güncellendi'; $actionClass = 'bg-yellow-100 text-yellow-800'; break;
                            case 'CREATE_FINANCE_TX': $actionLabel = 'Finans İşlemi Eklendi'; $actionClass = 'bg-emerald-100 text-emerald-800'; break;
                            case 'UPDATE_FINANCE_TX': $actionLabel = 'Finans İşlemi Düzenlendi'; $actionClass = 'bg-yellow-100 text-yellow-800'; break;
                            case 'DELETE_FINANCE_TX': $actionLabel = 'Finans İşlemi Silindi'; $actionClass = 'bg-red-100 text-red-800'; break;
                            case 'RECEIVE_FINANCE_PROXY': $actionLabel = 'Elden Ödeme Alındı'; $actionClass = 'bg-blue-100 text-blue-800'; break;
                        }

                        $entityTypeMap = ['User' => 'Kullanıcı', 'Department' => 'Departman', 'App' => 'Uygulama', 'Announcement' => 'Duyuru', 'DuesPeriod' => 'Aidat Dönemi', 'DuesPayment' => 'Aidat Ödemesi', 'FinanceTransaction' => 'Finans İşlemi'];
                        $entityName = $log['entity_type'] ? ($entityTypeMap[$log['entity_type']] ?? $log['entity_type']) : '-';
                        if ($log['entity_id']) {
                            $eId = $log['entity_id'];
                            if ($log['entity_type'] === 'User') {
                                $uName = htmlspecialchars($usersMap[$eId] ?? "Silinmiş Üye (#$eId)");
                                $entityName .= " ({$uName})";
                            } elseif ($log['entity_type'] === 'Department') {
                                $dName = htmlspecialchars($deptsMap[$eId] ?? "Silinmiş Departman (#$eId)");
                                $entityName .= " ({$dName})";
                            } else {
                                $entityName .= " (#{$eId})";
                            }
                        }

                        $detailsHtml = '-';
                        if ($log['details']) {
                            $decoded = json_decode($log['details'], true);
                            if (is_array($decoded)) {
                                $appMap = ['form' => 'Form', 'drive' => 'Dosya Yöneticisi', 'calendar' => 'Takvim', 'announcement' => 'Duyuru', 'report' => 'Rapor', 'directory' => 'Kurumsal Rehber', 'members' => 'Üye Listesi', 'finance' => 'Finans Paneli', 'dues' => 'Aidat Takibi', 'sponsor' => 'Sponsorlar', 'event' => 'Etkinlik', 'partner' => 'Anlaşmalı Mekanlar', 'bootcamp' => 'Bootcamp Eğitimleri', 'certificate' => 'Sertifika', 'inventory' => 'Envanter', 'links' => 'Hızlı Bağlantılar', 'qr' => 'Karekod', 'shortener' => 'Link Kısaltıcı', 'agile' => 'Agile', 'passwords' => 'Şifre Yöneticisi', 'ideahub' => 'Idea Hub', 'bylaws' => 'Tüzük', 'audit' => 'Denetim', 'trophy' => 'Ödüller', 'wiki' => 'Wiki', 'meeting' => 'Toplantı', 'budget' => 'Bütçe', 'mentorship' => 'Mentor', 'social' => 'İçerik', 'alumni' => 'Mezunlar'];
                                $roleMap = ['director' => 'Yönetici', 'member' => 'Üye'];
                                $statusMap = ['paid' => 'Ödendi', 'unpaid' => 'Ödenmedi', 'pending' => 'Bekliyor', 'delivered' => 'Teslim Alındı'];

                                $detailsHtml = '<ul class="text-xs space-y-1">';
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
                                        'modules' => 'Atanan Uygulamalar', 'status' => 'Durum', 
                                        'type' => 'Tür', 'amount' => 'Tutar', 'title' => 'Başlık',
                                        'app_slug' => 'Uygulama Slug', 'is_template' => 'Şablon Mu?',
                                        'is_active' => 'Aktif', 'is_super_admin' => 'Süper Admin',
                                        'can_manage_passwords' => 'Şifre Yönetimi', 'is_management' => 'Yönetim Dept.',
                                        'description' => 'Açıklama', 'category' => 'Kategori'
                                    ];
                                    $key = $keyMap[$k] ?? htmlspecialchars($k);
                                    $detailsHtml .= "<li><span class='font-medium text-gray-700'>{$key}:</span> <span class='text-gray-500'>{$valStr}</span></li>";
                                }
                                $detailsHtml .= '</ul>';
                            } else {
                                $detailsHtml = htmlspecialchars($log['details']);
                            }
                        }
                    ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td data-label="ID" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $log['id'] ?>
                            </td>
                            <td data-label="Tarih" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars(date('d.m.Y H:i:s', strtotime($log['created_at']))) ?>
                            </td>
                            <td data-label="Kullanıcı" class="px-6 py-4 whitespace-nowrap text-sm log-user-cell">
                                <?php if($log['user_id']): ?>
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($log['user_name']) ?></div>
                                    <div class="text-xs text-gray-500">ID: <?= $log['user_id'] ?></div>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Sistem/Anonim</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="İşlem" class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $actionClass ?>">
                                    <?= htmlspecialchars($actionLabel) ?>
                                </span>
                            </td>
                            <td data-label="Varlık" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 log-entity-cell">
                                <?= htmlspecialchars($entityName) ?>
                            </td>
                            <td data-label="IP Adresi" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($log['ip_address'] ?? '-') ?>
                            </td>
                            <td data-label="Detaylar" class="px-6 py-4 text-sm text-gray-700 log-details-cell">
                                <?= $detailsHtml ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" data-label="" class="px-6 py-4 text-center text-gray-500">
                            Henüz kayıtlı log bulunmamaktadır.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 logs-pagination">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Toplam <span class="font-medium"><?= $totalPages ?></span> sayfadan <span class="font-medium"><?= $page ?></span>. sayfa gösteriliyor
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Önceki</span>
                                &laquo;
                            </a>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);

                        for ($i = $start; $i <= $end; $i++):
                        ?>
                            <a href="?page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $page ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Sonraki</span>
                                &raquo;
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
