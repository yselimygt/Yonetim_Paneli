<?php
require_once __DIR__ . '/../models/SiteHistory.php';
require_once __DIR__ . '/../models/SiteTeamProfile.php';

class PublicApiController extends Controller
{
    public function getEvents(): void
    {
        $pdo = Database::getInstance();

        // Get all site events
        $stmt = $pdo->query("SELECT * FROM site_events ORDER BY event_date ASC");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get all bootcamps and merge them as events
        $stmtBootcamps = $pdo->query("SELECT * FROM app_bootcamps WHERE status = 1 ORDER BY start_date ASC");
        $bootcamps = $stmtBootcamps->fetchAll(PDO::FETCH_ASSOC);

        foreach ($bootcamps as $bc) {
            $events[] = [
                'id' => 'bc_' . $bc['id'],
                'title' => $bc['title'],
                'description' => $bc['description'],
                'event_date' => $bc['start_date'],
                'location' => $bc['location'],
                'category' => $bc['category'] ?: 'Bootcamp',
                'tag_color' => 'blue',
                'status' => $bc['is_completed'] ? 'past' : 'upcoming',
                'image_url' => $bc['image_path'],
                'target_date' => $bc['start_date'],
                'registration_link' => $bc['link'],
                'location_link' => null,
                'speakers' => null,
                'agenda' => null
            ];
        }

        // Get all app_events
        $stmtAppEvents = $pdo->query("SELECT * FROM app_events WHERE status = 1 ORDER BY event_date ASC");
        $appEvents = $stmtAppEvents->fetchAll(PDO::FETCH_ASSOC);

        foreach ($appEvents as $ae) {
            $status = (strtotime($ae['event_date']) < time()) ? 'past' : 'upcoming';
            $events[] = [
                'id' => 'ae_' . $ae['id'],
                'title' => $ae['title'],
                'description' => $ae['description'],
                'event_date' => $ae['event_date'],
                'location' => $ae['location_or_platform'],
                'category' => $ae['category'] ?: 'Modül',
                'tag_color' => 'green',
                'status' => $status,
                'image_url' => $ae['image_path'],
                'target_date' => $ae['event_date'],
                'registration_link' => null,
                'location_link' => $ae['location_link'] ?? null,
                'speakers' => null,
                'agenda' => null
            ];
        }

        // Sort combined events by date
        usort($events, function($a, $b) {
            return strtotime($a['event_date']) - strtotime($b['event_date']);
        });

        // Format for the frontend
        $formattedEvents = [];
        foreach ($events as $event) {
            $speakers = $event['speakers'] ? json_decode($event['speakers'], true) : [];
            $agenda = $event['agenda'] ? json_decode($event['agenda'], true) : [];
            
            $dateObj = new DateTime($event['event_date']);
            
            if (class_exists('IntlDateFormatter')) {
                $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                $formatter->setPattern('dd MMM yyyy, HH:mm'); 
                $displayDate = $formatter->format($dateObj);
            } else {
                $months = [
                    '01' => 'Oca', '02' => 'Şub', '03' => 'Mar', '04' => 'Nis', 
                    '05' => 'May', '06' => 'Haz', '07' => 'Tem', '08' => 'Ağu', 
                    '09' => 'Eyl', '10' => 'Eki', '11' => 'Kas', '12' => 'Ara'
                ];
                $day = $dateObj->format('d');
                $monthStr = clone $dateObj; // To get the month index
                $month = $months[$monthStr->format('m')];
                $year = $dateObj->format('Y');
                $time = $dateObj->format('H:i');
                $displayDate = "$day $month $year, $time";
            }
            $tagColorClass = 'bg-primary/20 text-primary border-primary/30';
            if ($event['tag_color'] === 'blue') {
                $tagColorClass = 'bg-blue-500/20 text-blue-500 border-blue-500/30';
            } elseif ($event['tag_color'] === 'green') {
                $tagColorClass = 'bg-green-500/20 text-green-500 border-green-500/30';
            } elseif (!empty($event['tag_color'])) {
                // If it's already a full tailwind class string
                if (strpos($event['tag_color'], 'bg-') !== false) {
                    $tagColorClass = $event['tag_color'];
                }
            }
            
            $formattedEvents[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'desc' => $event['description'],
                'date' => $displayDate,
                'location' => $event['location'],
                'category' => $event['category'] ?: 'Genel',
                'tagColor' => $tagColorClass,
                'status' => strtolower($event['status'] ?? 'upcoming'),
                'image' => $event['image_url'] ?: null,
                'targetDate' => !empty($event['target_date']) ? str_replace(' ', 'T', $event['target_date']) : null,
                'registrationLink' => $event['registration_link'] ?? null,
                'locationLink' => $event['location_link'] ?? null,
                'speakers' => is_array($speakers) ? $speakers : [],
                'agenda' => is_array($agenda) ? $agenda : [],
            ];
        }

        // Output as JSON
        // Allow CORS if frontend runs on a different port (like Vite dev server)
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        $this->json(['success' => true, 'data' => $formattedEvents]);
    }

    public function getSiteData(): void
    {
        $db = Database::getInstance();
        
        // Settings
        $stmt = $db->query("SELECT key_name, json_value FROM site_settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key_name']] = json_decode($row['json_value'], true);
        }

        // --- Dinamik Üye Sayısı Güncellemesi ---
        // Topluluk üyeleri "app_members" tablosunda tutuluyor, "suspended" olmayanları sayıyoruz.
        $userCountStmt = $db->query("SELECT count(*) as total FROM app_members WHERE status != 'suspended' OR status IS NULL");
        $userCountRow = $userCountStmt->fetch();
        $totalUsersExact = $userCountRow['total'] ?? 0;
        
        // Kendisinden büyük en yakın 50'nin katına yuvarlama
        $roundedUsers = ceil($totalUsersExact / 50) * 50;
        if ($roundedUsers == 0 && $totalUsersExact > 0) {
            $roundedUsers = 50;
        }


        // --- Dinamik Etkinlik ve Bootcamp Sayısı Güncellemesi ---
        // app_events, site_events ve app_bootcamps tablolarındaki toplam sayı
        $totalEventsExact = 0;
        try {
            $eventCountStmt = $db->query("SELECT count(*) as total FROM app_events");
            $totalEventsExact += (int)($eventCountStmt->fetch()['total'] ?? 0);
            
            $siteEventCountStmt = $db->query("SELECT count(*) as total FROM site_events");
            $totalEventsExact += (int)($siteEventCountStmt->fetch()['total'] ?? 0);
            
            $bootcampCountStmt = $db->query("SELECT count(*) as total FROM app_bootcamps");
            $totalEventsExact += (int)($bootcampCountStmt->fetch()['total'] ?? 0);
        } catch (PDOException $e) {
            // Tablo yoksa hata fırlatmasını engelliyoruz
        }

        // --- Dinamik Sponsor Sayısı ve Logoları Güncellemesi ---
        $totalSponsorsExact = 0;
        $sponsorLogos = [];
        try {
            $selectedSponsorIds = [];
            foreach (($settings['sponsors'] ?? []) as $sponsorValue) {
                if (is_numeric($sponsorValue)) {
                    $selectedSponsorIds[] = (int)$sponsorValue;
                }
            }
            $selectedSponsorIds = array_values(array_unique(array_filter($selectedSponsorIds)));

            if (!empty($selectedSponsorIds)) {
                $placeholders = implode(',', array_fill(0, count($selectedSponsorIds), '?'));
                $sponsorStmt = $db->prepare("
                    SELECT logo_url
                    FROM sponsors
                    WHERE status = 'active'
                      AND logo_url IS NOT NULL
                      AND logo_url != ''
                      AND id IN ($placeholders)
                    ORDER BY FIELD(id, $placeholders)
                ");
                $sponsorStmt->execute(array_merge($selectedSponsorIds, $selectedSponsorIds));
            } else {
                $sponsorStmt = $db->query("
                    SELECT logo_url
                    FROM sponsors
                    WHERE status = 'active'
                      AND logo_url IS NOT NULL
                      AND logo_url != ''
                    ORDER BY created_at DESC
                ");
            }

            $sponsorsResult = $sponsorStmt->fetchAll();
            $totalSponsorsExact = count($sponsorsResult);
            foreach ($sponsorsResult as $sp) {
                if (!empty($sp['logo_url'])) {
                    // Frontend resimleri tam link ile okusun (CORS/Proxy sorunu olmaması için)
                    $sponsorLogos[] = BASE_URL . '/' . ltrim($sp['logo_url'], '/');
                }
            }
        } catch (PDOException $e) {
        }

        $dynamicStats = [
            'Aktif Üye' => $roundedUsers . '+',
            'Etkinlik'  => (string)$totalEventsExact,
            'İş Birliği'=> (string)$totalSponsorsExact
        ];

        if (!isset($settings['stats']) || !is_array($settings['stats']) || empty($settings['stats'])) {
            $settings['stats'] = [
                [ 'label' => 'Aktif Üye', 'value' => $dynamicStats['Aktif Üye'], 'desc' => 'Topluluk ekosisteminde yer alan öğrenci ağı.' ],
                [ 'label' => 'Etkinlik', 'value' => $dynamicStats['Etkinlik'], 'desc' => 'Workshop, panel, zirve ve buluşmalar.' ],
                [ 'label' => 'İş Birliği', 'value' => $dynamicStats['İş Birliği'], 'desc' => 'Marka, topluluk ve sektör paydaşları.' ]
            ];
        } else {
            foreach ($settings['stats'] as &$statItem) {
                if (isset($dynamicStats[$statItem['label']])) {
                    $statItem['value'] = $dynamicStats[$statItem['label']];
                }
            }
        }

        // Sponsor array'ini setting içine entegre et (ön tarafta logolar gösterilecek)
        $settings['sponsors'] = $sponsorLogos;

        // Projects
        $stmtProj = $db->query("SELECT * FROM site_projects ORDER BY id DESC");
        $projects = $stmtProj->fetchAll();

        // Gallery
        $stmtGal = $db->query("SELECT * FROM site_gallery ORDER BY id DESC");
        $gallery = array_map(fn($image) => $this->withGalleryThumbnail($image), $stmtGal->fetchAll());

        // History (Achievements & Awards)
        $history = (new SiteHistory())->getAllHistory();

        $achievements = array_values(array_filter($history, fn($h) => $h['type'] === 'achievement'));
        $awardsRaw = array_values(array_filter($history, fn($h) => $h['type'] === 'award'));
        $awards = array_map(function($award) {
            if (!empty($award['image_path'])) {
                $award['image_url'] = BASE_URL . '/public/uploads/trophies/' . ltrim($award['image_path'], '/');
            }
            if (!empty($award['institution_logo'])) {
                $award['institution_logo_url'] = BASE_URL . '/public/uploads/trophies/' . ltrim($award['institution_logo'], '/');
            }
            if (!empty($award['bg_image_path'])) {
                $award['bg_image_url'] = BASE_URL . '/public/uploads/trophies/' . ltrim($award['bg_image_path'], '/');
            }
            return $award;
        }, $awardsRaw);

        $teamModel = new SiteTeamProfile();
        $members = $teamModel->hasVisibleProfiles()
            ? $teamModel->getPublicTeam()
            : $this->getLegacyTeamMembers($db);
        $team = $this->buildTeamStructure($members);

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        $this->json([
            'success' => true,
            'data' => [
                'settings' => $settings,
                'projects' => $projects,
                'gallery' => $gallery,
                'achievements' => $achievements,
                'awards' => $awards,
                'team' => $team
            ]
        ]);
    }

    private function getLegacyTeamMembers(PDO $db): array
    {
        $stmtTeam = $db->query("SELECT * FROM site_team_members ORDER BY sort_order ASC, id ASC");
        $members = $stmtTeam->fetchAll();

        return array_map(function ($member) {
            $member['linkedin'] = $member['linkedin'] ?? ($member['linkedin_url'] ?? null);
            $member['github'] = $member['github'] ?? null;
            $member['initials'] = $member['initials'] ?? $this->initials($member['name'] ?? '');
            $member['team_department'] = $member['team_department'] ?? ($member['department'] ?? 'Genel');
            $member['communityHistory'] = $this->normalizeHistory(
                $member['community_history_json'] ?? '[]',
                $member['team_department'] ?? 'Genel'
            );
            return $member;
        }, $members);
    }

    private function withGalleryThumbnail(array $image): array
    {
        $image['thumb_url'] = $this->galleryThumbnailUrl($image['image_url'] ?? '');
        return $image;
    }

    private function galleryThumbnailUrl(string $imageUrl): ?string
    {
        $path = parse_url($imageUrl, PHP_URL_PATH) ?: $imageUrl;
        $marker = '/public/uploads/gallery/';
        $position = strpos($path, $marker);

        if ($position === false) {
            return null;
        }

        $relativePath = urldecode(substr($path, $position + strlen($marker)));
        $parts = explode('/', $relativePath, 2);
        if (count($parts) !== 2) {
            return null;
        }

        [$folder, $file] = $parts;
        $sourcePath = __DIR__ . '/../public/uploads/gallery/' . $folder . '/' . $file;
        $thumbName = pathinfo($file, PATHINFO_FILENAME) . '-thumb.' . (ImageOptimizer::canOptimizeImages() ? 'webp' : 'jpg');
        $thumbDir = __DIR__ . '/../public/uploads/gallery_thumbs/' . $folder;
        $thumbPath = $thumbDir . '/' . $thumbName;

        if (!is_file($thumbPath) && ImageOptimizer::canResizeImages() && is_file($sourcePath)) {
            if (!is_dir($thumbDir)) {
                mkdir($thumbDir, 0777, true);
            }
            ImageOptimizer::createThumbnail($sourcePath, $thumbPath);
        }

        if (!is_file($thumbPath)) {
            return null;
        }

        return BASE_URL . '/public/uploads/gallery_thumbs/' . rawurlencode($folder) . '/' . rawurlencode($thumbName);
    }

    private function buildTeamStructure(array $members): array
    {
        $board = [];
        $departmentsMap = [];
        $campusMap = [];
        $alumniMap = [];

        foreach ($members as $member) {
            if ($member['type'] === 'board') {
                $board[] = $member;
                continue;
            }

            $tag = $member['department_tag'] ?? 'main';

            if ($tag === 'campus' || $member['type'] === 'campus' || $member['type'] === 'campus_rep') {
                $campusName = $member['team_department'] ?? ($member['department'] ?: 'Bilinmeyen Kampüs');
                if (!isset($campusMap[$campusName])) {
                    $campusMap[$campusName] = ['university' => $campusName, 'logo' => null, 'representatives' => []];
                }
                $campusMap[$campusName]['representatives'][] = $member;
                continue;
            }

            if ($tag === 'alumni') {
                $yearName = $member['team_department'] ?? 'Geçmiş Mezunlar';
                // Extract year from department name if possible (e.g., "2023 Mezunları")
                preg_match('/(20\d{2})/', $yearName, $matches);
                $year = $matches[1] ?? 'Mezun';
                
                if (!isset($alumniMap[$yearName])) {
                    $alumniMap[$yearName] = ['year' => $year, 'name' => $yearName, 'members' => []];
                }
                $alumniMap[$yearName]['members'][] = $member;
                continue;
            }

            // main
            $deptName = $member['team_department'] ?? ($member['department'] ?: 'Genel');
            if (!isset($departmentsMap[$deptName])) {
                $departmentsMap[$deptName] = ['id' => md5($deptName), 'name' => $deptName, 'director' => null, 'members' => []];
            }

            if ($this->isDirectorRole($member['role'] ?? '')) {
                $departmentsMap[$deptName]['director'] = $member;
            } else {
                $departmentsMap[$deptName]['members'][] = $member;
            }
        }

        foreach ($departmentsMap as &$department) {
            if (!$department['director'] && !empty($department['members'])) {
                $department['director'] = array_shift($department['members']);
            }
        }

        // Sort alumni by year descending
        $alumniList = array_values($alumniMap);
        usort($alumniList, function($a, $b) {
            return (int)$b['year'] <=> (int)$a['year'];
        });

        return [
            'board' => $board,
            'departments' => array_values($departmentsMap),
            'campusRepresentatives' => array_values($campusMap),
            'alumni' => $alumniList
        ];
    }

    private function isDirectorRole(string $role): bool
    {
        return stripos($role, 'Direktör') !== false
            || stripos($role, 'Başkan') !== false
            || stripos($role, 'Sorumlu') !== false;
    }

    private function normalizeHistory(string $historyJson, string $department): array
    {
        $history = json_decode($historyJson, true) ?: [];

        return array_map(function ($item) use ($department) {
            return [
                'title' => $item['title'] ?? ($item['role'] ?? ''),
                'dept' => $item['dept'] ?? $department,
                'period' => $item['period'] ?? ($item['year'] ?? ''),
                'year' => $item['year'] ?? ($item['period'] ?? ''),
                'role' => $item['role'] ?? ($item['title'] ?? '')
            ];
        }, $history);
    }

    private function initials(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name)) ?: [];
        $letters = [];

        foreach ($parts as $part) {
            if ($part !== '') {
                $letters[] = mb_substr($part, 0, 1, 'UTF-8');
            }

            if (count($letters) === 2) {
                break;
            }
        }

        return mb_strtoupper(implode('', $letters), 'UTF-8');
    }

    public function submitMessage(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $type = trim($input['type'] ?? '');
        
        if (empty($type)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Form tipi belirtilmedi.']);
            exit;
        }

        $db = Database::getInstance();
        
        $stmtSettings = $db->query("SELECT json_value FROM site_settings WHERE key_name = 'form_routing'");
        $routingSetting = $stmtSettings->fetchColumn();
        $routing = $routingSetting ? json_decode($routingSetting, true) : [];
        
        if (!isset($routing[$type]) || empty($routing[$type])) {
            // Fallback to 'all' if defined
            if (isset($routing['all']) && !empty($routing['all'])) {
                $rule = $routing['all'];
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Bu form türü için bir yönlendirme ayarı bulunamadı.']);
                exit;
            }
        } else {
            $rule = $routing[$type];
        }

        // Support formats: simple dept_ids array [1,2,3] OR old targets/department_id structures
        if (isset($rule['targets'])) {
            $deptIds = array_column($rule['targets'], 'department_id');
        } elseif (isset($rule['department_id'])) {
            $deptIds = [(int)$rule['department_id']];
        } elseif (is_array($rule) && (empty($rule) || array_keys($rule) === range(0, count($rule) - 1))) {
            $deptIds = array_filter(array_map('intval', $rule));
        } else {
            $deptIds = [];
        }

        if (empty($deptIds)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Bu form türü için geçerli bir departman bulunamadı.']);
            exit;
        }

        $sender_name = trim($input['fullName'] ?? '');
        $company_name = trim($input['company'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $category = trim($input['category'] ?? '');
        $message = trim($input['message'] ?? '');

        $inserted = 0;
        try {
            $stmtApp = $db->prepare("SELECT id FROM apps WHERE department_id = ? AND module_type = 'inbox' AND is_active = 1 LIMIT 1");
            $stmtInsert = $db->prepare("
                INSERT INTO app_inbox_messages 
                (app_id, department_id, type, sender_name, company_name, email, phone, category, message, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'unread')
            ");

            foreach ($deptIds as $deptId) {
                $deptId = (int)$deptId;
                if ($deptId <= 0) continue;

                $stmtApp->execute([$deptId]);
                $appId = (int)$stmtApp->fetchColumn();
                if (!$appId) continue; // Bu departmanda inbox uygulaması yok

                $stmtInsert->execute([$appId, $deptId, $type, $sender_name, $company_name, $email, $phone, $category, $message]);
                $inserted++;
            }
            
            if ($inserted === 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Hedef departmanlarda aktif Gelen Kutusu uygulaması bulunamadı.']);
                exit;
            }

            echo json_encode(['success' => true, 'message' => 'Başvurunuz başarıyla alındı. Ekibimiz en kısa sürede dönüş yapacaktır.']);
        } catch (\Throwable $e) {
            http_response_code(200); // Prevent framework global 500 HTML override
            echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
            exit;
        }
        exit;
    }

    public function submitApplication(): void
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $type = trim($input['type'] ?? '');
        $fullName = trim($input['fullName'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone'] ?? '');
        
        if (empty($type) || empty($fullName) || empty($email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Lütfen zorunlu alanları (Ad Soyad, E-posta) doldurun.']);
            exit;
        }
        
        // Form details
        $university = trim($input['university'] ?? '');
        $faculty = trim($input['faculty'] ?? '');
        
        // Remove known basic keys from message to keep form_data clean
        $formData = $input['message'] ?? '{}';
        
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("
                INSERT INTO app_applications 
                (type, full_name, email, phone, university, faculty, form_data, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$type, $fullName, $email, $phone, $university, $faculty, $formData]);
            
            echo json_encode(['success' => true, 'message' => 'Başvurunuz başarıyla alındı. İlgili birimlerimiz en kısa sürede dönüş yapacaktır.']);
        } catch (\Throwable $e) {
            http_response_code(200);
            echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        exit;
    }
    public function submitMembership(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $fullName = trim($input['fullName'] ?? '');
        $email = trim($input['email'] ?? '');
        
        if (empty($fullName) || empty($email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Lütfen zorunlu alanları (Ad Soyad, E-posta) doldurun.']);
            exit;
        }

        $phone = trim($input['phone'] ?? '');
        $faculty = trim($input['faculty'] ?? '');
        $department = trim($input['department'] ?? '');
        $grade = trim($input['grade'] ?? '');
        $studentId = trim($input['studentId'] ?? '');
        // We don't have city in app_members, maybe map to university or leave out. We can put city in university if university isn't asked.
        $university = trim($input['city'] ?? ''); // Use city as university since innomis usually operates in a single university or city.

        // Split fullName into first_name and last_name
        $lastSpacePos = mb_strrpos($fullName, ' ', 0, 'UTF-8');
        if ($lastSpacePos !== false) {
            $firstName = mb_substr($fullName, 0, $lastSpacePos, 'UTF-8');
            $lastName = mb_substr($fullName, $lastSpacePos + 1, null, 'UTF-8');
        } else {
            $firstName = $fullName;
            $lastName = '';
        }

        try {
            $db = Database::getInstance();
            
            // Find target department from form_routing for 'membership'
            $stmtSettings = $db->query("SELECT json_value FROM site_settings WHERE key_name = 'form_routing'");
            $routingSetting = $stmtSettings->fetchColumn();
            $routing = $routingSetting ? json_decode($routingSetting, true) : [];
            
            $rule = $routing['membership'] ?? ($routing['all'] ?? []);
            
            if (isset($rule['targets'])) {
                $deptIds = array_column($rule['targets'], 'department_id');
            } elseif (isset($rule['department_id'])) {
                $deptIds = [(int)$rule['department_id']];
            } elseif (is_array($rule) && (empty($rule) || array_keys($rule) === range(0, count($rule) - 1))) {
                $deptIds = array_filter(array_map('intval', $rule));
            } else {
                $deptIds = [];
            }

            if (empty($deptIds)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Sistemde üye alımı yapılandırılmamış (Hedef departman yok).']);
                exit;
            }

            $stmtApp = $db->prepare("SELECT id FROM apps WHERE department_id = ? AND module_type = 'members' AND is_active = 1 LIMIT 1");
            
            $inserted = 0;
            $duplicateError = false;

            $stmtInsert = $db->prepare("
                INSERT INTO app_members 
                (app_id, department_id, first_name, last_name, email, phone, university, faculty, department, grade, student_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
            ");

            foreach ($deptIds as $deptId) {
                $deptId = (int)$deptId;
                if ($deptId <= 0) continue;

                $stmtApp->execute([$deptId]);
                $appId = (int)$stmtApp->fetchColumn();
                if (!$appId) continue; // Bu departmanda members uygulaması yok

                try {
                    $stmtInsert->execute([$appId, $deptId, $firstName, $lastName, $email, $phone, $university, $faculty, $department, $grade, $studentId]);
                    $inserted++;
                } catch (\PDOException $e) {
                    // Check for duplicate entry (1062) for uq_app_email
                    if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $duplicateError = true;
                    } else {
                        throw $e;
                    }
                }
            }
            
            if ($inserted === 0) {
                if ($duplicateError) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Bu e-posta adresi ile zaten kayıt oluşturulmuş.']);
                    exit;
                }
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Hedef departmanlarda aktif Üyeler uygulaması bulunamadı.']);
                exit;
            }

            echo json_encode(['success' => true, 'message' => 'Kaydınız başarıyla tamamlandı. Aramıza hoş geldiniz!']);
        } catch (\Throwable $e) {
            http_response_code(200);
            echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
        }
        exit;
    }
}
