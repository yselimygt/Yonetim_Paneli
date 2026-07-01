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
}
