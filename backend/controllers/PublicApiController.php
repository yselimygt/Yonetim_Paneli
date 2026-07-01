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
                'category' => $bc['type'] ?? 'Bootcamp',
                'tag_color' => 'blue',
                'status' => $bc['is_completed'] ? 'Tamamlandı' : 'Yakında',
                'image_url' => $bc['image_path'],
                'target_date' => $bc['start_date'],
                'registration_link' => $bc['link'],
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
        $db = Database::getInstance();
        
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
