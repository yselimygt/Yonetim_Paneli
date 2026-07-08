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
        
        $stmtForms = $pdo->query("SELECT id, name, slug FROM apps WHERE module_type = 'form' AND is_active = 1 ORDER BY name ASC");
        $forms = $stmtForms->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('superadmin/site_events/index', compact('events', 'forms'), 'superadmin');
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
        $registration_link = trim($_POST['registration_link'] ?? '');
        $location_link     = trim($_POST['location_link'] ?? '');
        
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
            'registration_link' => $registration_link,
            'location_link'     => $location_link,
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

        $eventType = $_POST['event_type'] ?? 'site_event';
        $pdo = Database::getInstance();

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date  = $_POST['event_date'] ?? null;
        $location    = trim($_POST['location'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $tag_color   = trim($_POST['tag_color'] ?? '');
        $status      = trim($_POST['status'] ?? 'upcoming');
        $image_url   = trim($_POST['image_url'] ?? '');
        $registration_link = trim($_POST['registration_link'] ?? '');
        $location_link     = trim($_POST['location_link'] ?? '');

        if (empty($title)) {
            $this->flashError('Etkinlik başlığı zorunludur.');
            $this->redirect('/superadmin/site-events');
        }

        if ($eventType === 'bootcamp') {
            $mappedStatus = ($status === 'upcoming') ? 0 : 1;
            $stmt = $pdo->prepare("UPDATE app_bootcamps SET title = ?, description = ?, start_date = ?, location = ?, type = ?, image_path = ?, is_completed = ? WHERE id = ?");
            if ($stmt->execute([$title, $description, $event_date, $location, $category, $image_url, $mappedStatus, $id])) {
                $this->flashSuccess("'{$title}' bootcamp etkinliği güncellendi.");
            }
        } elseif ($eventType === 'app_event') {
            $dateOnly = $event_date ? date('Y-m-d', strtotime($event_date)) : null;
            $stmt = $pdo->prepare("UPDATE app_events SET title = ?, description = ?, event_date = ?, location_or_platform = ?, category = ?, image_path = ?, link = ?, location_link = ? WHERE id = ?");
            if ($stmt->execute([$title, $description, $dateOnly, $location, $category, $image_url, $registration_link, $location_link, $id])) {
                $this->flashSuccess("'{$title}' modül etkinliği güncellendi.");
            }
        } else {
            $event = $this->siteEvent->getById((int)$id);
            if (!$event) {
                $this->flashError('Etkinlik bulunamadı.');
                $this->redirect('/superadmin/site-events');
            }

            // Using updateById based on the model we saw
            $this->siteEvent->updateById((int)$id, [
                'title'       => $title,
                'description' => $description,
                'event_date'  => $event_date ?: null,
                'location'    => $location,
                'category'    => $category,
                'tag_color'   => $tag_color,
                'status'      => $status,
                'image_url'   => $image_url,
                'registration_link' => $registration_link,
                'location_link'     => $location_link,
            ]);

            Logger::log('UPDATE_SITE_EVENT', 'SiteEvent', (int)$id, ['title' => $title]);
            $this->flashSuccess("'{$title}' etkinliği güncellendi.");
        }

        $this->redirect('/superadmin/site-events');
    }

    // POST /superadmin/site-events/{id}/delete
    public function destroy(string $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $type = $_GET['type'] ?? 'site_event';
        $pdo = Database::getInstance();

        if ($type === 'bootcamp') {
            $stmt = $pdo->prepare("DELETE FROM app_bootcamps WHERE id = ?");
            if ($stmt->execute([$id])) {
                $this->flashSuccess("Bootcamp etkinliği silindi.");
            } else {
                $this->flashError("Bootcamp silinirken hata oluştu.");
            }
        } elseif ($type === 'app_event') {
            $stmt = $pdo->prepare("DELETE FROM app_events WHERE id = ?");
            if ($stmt->execute([$id])) {
                $this->flashSuccess("Modül etkinliği silindi.");
            } else {
                $this->flashError("Modül etkinliği silinirken hata oluştu.");
            }
        } else {
            $event = $this->siteEvent->getById((int)$id);
            if (!$event) {
                $this->flashError('Etkinlik bulunamadı.');
                $this->redirect('/superadmin/site-events');
            }

            $this->siteEvent->deleteById((int)$id);
            Logger::log('DELETE_SITE_EVENT', 'SiteEvent', (int)$id, ['title' => $event['title']]);
            $this->flashSuccess("'{$event['title']}' etkinliği silindi.");
        }

        $this->redirect('/superadmin/site-events');
    }
}
