<?php
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
            $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $formatter->setPattern('dd MMM yyyy, HH:mm'); 
            
            $displayDate = $formatter->format($dateObj);
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
        
        // En yakın 50'nin katına yuvarlama (Örn: 124 -> 100, 126 -> 150)
        // Eğer sayı 25'ten küçükse en azından 50 göstermesi için (opsiyonel ama mantıklı) maks fonksiyonu ile 0'ı önleyebiliriz
        // ama tam istenen 'en yakın' olduğu için standart round kullanıyoruz. Ancak 0 çıkmaması için minimum 50 yapalım.
        $roundedUsers = round($totalUsersExact / 50) * 50;
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
            $sponsorStmt = $db->query("SELECT logo_url FROM sponsors WHERE status = 'active' ORDER BY created_at DESC");
            $sponsorsResult = $sponsorStmt->fetchAll();
            $totalSponsorsExact = count($sponsorsResult);
            foreach ($sponsorsResult as $sp) {
                if (!empty($sp['logo_url'])) {
                    // Frontend resimleri root Apache path'inden okuyabiliyor:
                    $sponsorLogos[] = '/innomis.tr/backend/' . ltrim($sp['logo_url'], '/');
                }
            }
        } catch (PDOException $e) {
        }

        if (!isset($settings['stats']) || !is_array($settings['stats'])) {
            $settings['stats'] = [
                [ 'label' => 'Aktif Üye', 'value' => $roundedUsers . '+', 'desc' => 'Topluluk ekosisteminde yer alan öğrenci ağı.' ],
                [ 'label' => 'Etkinlik', 'value' => (string)$totalEventsExact, 'desc' => 'Workshop, panel, zirve ve buluşmalar.' ],
                [ 'label' => 'İş Birliği', 'value' => (string)$totalSponsorsExact, 'desc' => 'Marka, topluluk ve sektör paydaşları.' ]
            ];
        } else {
            // Eğitim Saati istatistiğini sistemden filtrele
            $settings['stats'] = array_values(array_filter($settings['stats'], function($s) {
                return $s['label'] !== 'Eğitim Saati';
            }));

            $foundMember = false;
            $foundEvent = false;
            $foundSponsor = false;
            foreach ($settings['stats'] as &$stat) {
                if ($stat['label'] === 'Aktif Üye') {
                    $stat['value'] = $roundedUsers . '+';
                    $foundMember = true;
                }
                if ($stat['label'] === 'Etkinlik') {
                    $stat['value'] = (string)$totalEventsExact;
                    $foundEvent = true;
                }
                if ($stat['label'] === 'İş Birliği') {
                    $stat['value'] = (string)$totalSponsorsExact;
                    $foundSponsor = true;
                }
            }
            if (!$foundMember && count($settings['stats']) >= 0) {
                array_unshift($settings['stats'], [ 'label' => 'Aktif Üye', 'value' => $roundedUsers . '+', 'desc' => 'Topluluk ekosisteminde yer alan öğrenci ağı.' ]);
            }
            if (!$foundEvent && count($settings['stats']) >= 0) {
                // Sadece sonradan eklenecekse sonuna ekleyelim veya 2. sıraya
                array_splice($settings['stats'], 1, 0, [[ 'label' => 'Etkinlik', 'value' => (string)$totalEventsExact, 'desc' => 'Workshop, panel, zirve ve buluşmalar.' ]]);
            }
            if (!$foundSponsor) {
                $settings['stats'][] = [ 'label' => 'İş Birliği', 'value' => (string)$totalSponsorsExact, 'desc' => 'Marka, topluluk ve sektör paydaşları.' ];
            }
        }

        // Sponsor array'ini setting içine entegre et (ön tarafta logolar gösterilecek)
        $settings['sponsors'] = $sponsorLogos;

        // Projects
        $stmtProj = $db->query("SELECT * FROM site_projects ORDER BY id DESC");
        $projects = $stmtProj->fetchAll();

        // Gallery
        $stmtGal = $db->query("SELECT * FROM site_gallery ORDER BY id DESC");
        $gallery = $stmtGal->fetchAll();

        // History (Achievements & Awards)
        $stmtHist = $db->query("SELECT * FROM site_history ORDER BY year DESC, id DESC");
        $history = $stmtHist->fetchAll();

        $achievements = array_values(array_filter($history, fn($h) => $h['type'] === 'achievement'));
        $awards = array_values(array_filter($history, fn($h) => $h['type'] === 'award'));

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

    private function buildTeamStructure(array $members): array
    {
        $board = [];
        $departmentsMap = [];
        $campusMap = [];

        foreach ($members as $member) {
            if ($member['type'] === 'board') {
                $board[] = $member;
                continue;
            }

            if ($member['type'] === 'campus') {
                $campusName = $member['team_department'] ?? ($member['department'] ?: 'Bilinmeyen Kampüs');
                if (!isset($campusMap[$campusName])) {
                    $campusMap[$campusName] = ['university' => $campusName, 'logo' => null, 'representatives' => []];
                }

                $campusMap[$campusName]['representatives'][] = $member;
                continue;
            }

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

        return [
            'board' => $board,
            'departments' => array_values($departmentsMap),
            'campusRepresentatives' => array_values($campusMap)
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
}
