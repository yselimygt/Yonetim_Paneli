<?php

require_once __DIR__ . '/../models/SiteEvent.php';

class SiteEventAdminController extends Controller
{
    private SiteEvent $siteEvent;

    public function __construct()
    {
        $this->siteEvent = new SiteEvent();
    }

    public function index(): void
    {
        Auth::requireSuperAdmin();
        $events = $this->siteEvent->getAll();
        
        // Add flag to normal events
        foreach ($events as &$e) {
            $e['is_bootcamp'] = 0;
            $e['is_site_event'] = 1;
        }
        
        // Fetch bootcamps
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT id, title, description, start_date as event_date, location, type as category, 'blue' as tag_color, (CASE WHEN is_completed = 1 THEN 'past' ELSE 'upcoming' END) as status, image_path as image_url, 1 as is_bootcamp, 0 as is_site_event, 0 as is_app_event FROM app_bootcamps WHERE status = 1");
        $bootcamps = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch app_events (Etkinlik Modülü)
        $stmtAppEvents = $pdo->query("SELECT id, title, description, event_date, location_or_platform as location, category, 'green' as tag_color, (CASE WHEN event_date < CURRENT_DATE() THEN 'past' ELSE 'upcoming' END) as status, image_path as image_url, 0 as is_bootcamp, 0 as is_site_event, 1 as is_app_event FROM app_events WHERE status = 1");
        $appEvents = $stmtAppEvents->fetchAll(PDO::FETCH_ASSOC);
        
        $events = array_merge($events, $bootcamps, $appEvents);
        
        // Sort by event date DESC
        usort($events, function($a, $b) {
            return strtotime($b['event_date']) - strtotime($a['event_date']);
        });
        
        $this->view('superadmin/site_events/index', compact('events'), 'superadmin');
    }

    // POST /superadmin/site-events
    public function store(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date  = $_POST['event_date'] ?? null;
        $location    = trim($_POST['location'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $tag_color   = trim($_POST['tag_color'] ?? '');
        $status      = trim($_POST['status'] ?? 'upcoming');
        $image_url   = trim($_POST['image_url'] ?? '');
        
        if (empty($title)) {
            $this->flashError('Etkinlik başlığı zorunludur.');
            $this->redirect('/superadmin/site-events');
        }

        $this->siteEvent->create([
            'title'       => $title,
            'description' => $description,
            'event_date'  => $event_date ?: null,
            'location'    => $location,
            'category'    => $category,
            'tag_color'   => $tag_color,
            'status'      => $status,
            'image_url'   => $image_url,
        ]);

        Logger::log('CREATE_SITE_EVENT', 'SiteEvent', null, ['title' => $title]);

        $this->flashSuccess("'{$title}' etkinliği oluşturuldu.");
        $this->redirect('/superadmin/site-events');
    }

    // POST /superadmin/site-events/{id}
    public function update(string $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $event = $this->siteEvent->getById((int)$id);
        if (!$event) {
            $this->flashError('Etkinlik bulunamadı.');
            $this->redirect('/superadmin/site-events');
        }

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date  = $_POST['event_date'] ?? null;
        $location    = trim($_POST['location'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $tag_color   = trim($_POST['tag_color'] ?? '');
        $status      = trim($_POST['status'] ?? 'upcoming');
        $image_url   = trim($_POST['image_url'] ?? '');

        if (empty($title)) {
            $this->flashError('Etkinlik başlığı zorunludur.');
            $this->redirect('/superadmin/site-events');
        }

        $this->siteEvent->updateById((int)$id, [
            'title'       => $title,
            'description' => $description,
            'event_date'  => $event_date ?: null,
            'location'    => $location,
            'category'    => $category,
            'tag_color'   => $tag_color,
            'status'      => $status,
            'image_url'   => $image_url,
        ]);

        Logger::log('UPDATE_SITE_EVENT', 'SiteEvent', (int)$id, ['title' => $title]);

        $this->flashSuccess("'{$title}' etkinliği güncellendi.");
        $this->redirect('/superadmin/site-events');
    }

    // POST /superadmin/site-events/{id}/delete
    public function destroy(string $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $event = $this->siteEvent->getById((int)$id);
        if (!$event) {
            $this->flashError('Etkinlik bulunamadı.');
            $this->redirect('/superadmin/site-events');
        }

        $this->siteEvent->deleteById((int)$id);
        Logger::log('DELETE_SITE_EVENT', 'SiteEvent', (int)$id, ['title' => $event['title']]);
        $this->flashSuccess("'{$event['title']}' etkinliği silindi.");
        $this->redirect('/superadmin/site-events');
    }
}
