<?php
class PublicApiController extends Controller
{
    public function getEvents(): void
    {
        $pdo = Database::getInstance();

        // Get all events, sort by date (upcoming first)
        $stmt = $pdo->query("
            SELECT * FROM site_events
            ORDER BY event_date ASC
        ");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Format for the frontend
        $formattedEvents = [];
        foreach ($events as $event) {
            // Frontend expects specific structure for speakers and agenda
            $speakers = $event['speakers'] ? json_decode($event['speakers'], true) : [];
            $agenda = $event['agenda'] ? json_decode($event['agenda'], true) : [];
            
            // Format date for display like "Nisan 2026" or "Yakında"
            // For now, let's keep it simple and just send what we have, 
            // but mapped to the frontend's expected properties.
            $dateObj = new DateTime($event['event_date']);
            $formatter = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $formatter->setPattern('MMMM yyyy'); // e.g. "Nisan 2026"
            
            $displayDate = $formatter->format($dateObj);
            
            $formattedEvents[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'desc' => $event['description'],
                'date' => $displayDate,
                'location' => $event['location'],
                'category' => $event['category'],
                'tagColor' => $event['tag_color'],
                'status' => $event['status'],
                'image' => $event['image_url'],
                'targetDate' => $event['target_date'] ? str_replace(' ', 'T', $event['target_date']) : null,
                'registrationLink' => $event['registration_link'],
                'speakers' => $speakers,
                'agenda' => $agenda,
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
        $db = $this->db;
        
        // Settings
        $stmt = $db->query("SELECT key_name, json_value FROM site_settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key_name']] = json_decode($row['json_value'], true);
        }

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

        // Team
        $stmtTeam = $db->query("SELECT * FROM site_team_members ORDER BY id ASC");
        $members = $stmtTeam->fetchAll();
        $board = [];
        $departmentsMap = [];
        $campusMap = [];

        foreach ($members as $m) {
            $m['communityHistory'] = json_decode($m['community_history_json'], true) ?? [];
            if ($m['type'] === 'board') {
                $board[] = $m;
            } elseif ($m['type'] === 'department') {
                $deptName = $m['department'] ?: 'Genel';
                if (!isset($departmentsMap[$deptName])) {
                    $departmentsMap[$deptName] = ['id' => md5($deptName), 'name' => $deptName, 'director' => null, 'members' => []];
                }
                
                if (stripos($m['role'], 'Direktör') !== false || stripos($m['role'], 'Başkan') !== false) {
                    $departmentsMap[$deptName]['director'] = $m;
                } else {
                    $departmentsMap[$deptName]['members'][] = $m;
                }
            } elseif ($m['type'] === 'campus') {
                $uni = $m['department'] ?: 'Bilinmeyen Kampüs';
                if (!isset($campusMap[$uni])) {
                    $campusMap[$uni] = ['university' => $uni, 'logo' => null, 'representatives' => []];
                }
                $campusMap[$uni]['representatives'][] = $m;
            }
        }

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
                'team' => [
                    'board' => $board,
                    'departments' => array_values($departmentsMap),
                    'campusRepresentatives' => array_values($campusMap)
                ]
            ]
        ]);
    }
}
