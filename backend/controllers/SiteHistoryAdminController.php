<?php

require_once __DIR__ . '/../models/SiteHistory.php';

class SiteHistoryAdminController extends Controller
{
    private SiteHistory $historyModel;

    public function __construct()
    {
        $this->historyModel = new SiteHistory();
    }

    // GET /superadmin/site-history
    public function index(): void
    {
        Auth::requireSuperAdmin();
        $historyItems = $this->historyModel->getAllHistory();
        
        $this->view('superadmin/site_history/index', ['historyItems' => $historyItems], 'superadmin');
    }

    // POST /superadmin/site-history
    public function store(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $data = [
            'year' => trim($_POST['year'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'icon' => trim($_POST['icon'] ?? ''),
            'type' => trim($_POST['type'] ?? 'achievement'),
            'link' => trim($_POST['link'] ?? '')
        ];

        if (empty($data['year']) || empty($data['title'])) {
            $this->flashError('Yıl ve Başlık zorunludur.');
            $this->redirect('/superadmin/site-history');
            return;
        }

        $this->historyModel->createHistory($data);
        Logger::log('CREATE_SITE_HISTORY', 'SiteHistory', null, ['title' => $data['title']]);
        $this->flashSuccess('Kayıt başarıyla eklendi.');
        $this->redirect('/superadmin/site-history');
    }

    // POST /superadmin/site-history/{id}
    public function update(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $item = $this->historyModel->getHistoryById($id);
        if (!$item) {
            $this->flashError('Kayıt bulunamadı.');
            $this->redirect('/superadmin/site-history');
            return;
        }

        $data = [
            'year' => trim($_POST['year'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'icon' => trim($_POST['icon'] ?? ''),
            'type' => trim($_POST['type'] ?? 'achievement'),
            'link' => trim($_POST['link'] ?? '')
        ];

        if (empty($data['year']) || empty($data['title'])) {
            $this->flashError('Yıl ve Başlık zorunludur.');
            $this->redirect('/superadmin/site-history');
            return;
        }

        $this->historyModel->updateHistory($id, $data);
        Logger::log('UPDATE_SITE_HISTORY', 'SiteHistory', $id, ['title' => $data['title']]);
        $this->flashSuccess('Kayıt güncellendi.');
        $this->redirect('/superadmin/site-history');
    }

    // POST /superadmin/site-history/{id}/delete
    public function destroy(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $item = $this->historyModel->getHistoryById($id);
        if ($item) {
            $this->historyModel->deleteHistory($id);
            Logger::log('DELETE_SITE_HISTORY', 'SiteHistory', $id, ['title' => $item['title']]);
            $this->flashSuccess('Kayıt silindi.');
        } else {
            $this->flashError('Kayıt bulunamadı.');
        }

        $this->redirect('/superadmin/site-history');
    }

    public function updateTrophy(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $trophy = $this->historyModel->getTrophyById($id);
        if (!$trophy) {
            $this->flashError('Ödül kaydı bulunamadı.');
            $this->redirect('/superadmin/site-history');
            return;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'award_type' => trim($_POST['award_type'] ?? 'other'),
            'award_date' => trim($_POST['award_date'] ?? '')
        ];

        if (empty($data['title']) || empty($data['award_date'])) {
            $this->flashError('Başlık ve tarih zorunludur.');
            $this->redirect('/superadmin/site-history');
            return;
        }

        $imagePath = $this->uploadTrophyImage();
        if ($imagePath) {
            $data['image_path'] = $imagePath;
            $this->deleteTrophyImage($trophy['image_path'] ?? '');
        }

        $this->historyModel->updateTrophy($id, $data);
        Logger::log('UPDATE_TROPHY_FROM_SUPERADMIN', 'AppTrophy', $id, ['title' => $data['title']]);
        $this->flashSuccess('Ödül kaydı güncellendi.');
        $this->redirect('/superadmin/site-history');
    }

    public function destroyTrophy(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $trophy = $this->historyModel->getTrophyById($id);
        if (!$trophy) {
            $this->flashError('Ödül kaydı bulunamadı.');
            $this->redirect('/superadmin/site-history');
            return;
        }

        $this->historyModel->deleteTrophy($id);
        $this->deleteTrophyImage($trophy['image_path'] ?? '');
        Logger::log('DELETE_TROPHY_FROM_SUPERADMIN', 'AppTrophy', $id, ['title' => $trophy['title'] ?? '']);
        $this->flashSuccess('Ödül kaydı silindi.');
        $this->redirect('/superadmin/site-history');
    }

    private function uploadTrophyImage(): string
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return '';
        }

        $uploadDir = __DIR__ . '/../public/uploads/trophies';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid('trophy_', true) . '.' . $ext;
        $target = $uploadDir . '/' . $fileName;

        return move_uploaded_file($_FILES['image']['tmp_name'], $target) ? $fileName : '';
    }

    private function deleteTrophyImage(string $imagePath): void
    {
        if ($imagePath === '') {
            return;
        }

        $filePath = __DIR__ . '/../public/uploads/trophies/' . basename($imagePath);
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }
}
