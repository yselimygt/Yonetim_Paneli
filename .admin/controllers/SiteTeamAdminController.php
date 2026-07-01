<?php

require_once __DIR__ . '/../models/SiteTeamMember.php';

class SiteTeamAdminController extends Controller
{
    private SiteTeamMember $teamModel;

    public function __construct()
    {
        $this->teamModel = new SiteTeamMember();
    }

    // GET /superadmin/site-team
    public function index(): void
    {
        Auth::requireSuperAdmin();
        $members = $this->teamModel->getAllMembers();
        
        $this->view('superadmin/site_team/index', ['members' => $members], 'superadmin');
    }

    // POST /superadmin/site-team
    public function store(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        // Process community_history
        $historyYears = $_POST['history_year'] ?? [];
        $historyRoles = $_POST['history_role'] ?? [];
        
        $history = [];
        foreach ($historyYears as $i => $year) {
            $year = trim($year);
            $role = trim($historyRoles[$i] ?? '');
            if (!empty($year) && !empty($role)) {
                $history[] = [
                    'year' => $year,
                    'role' => $role
                ];
            }
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'role' => trim($_POST['role'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'type' => trim($_POST['type'] ?? 'board'),
            'image_url' => trim($_POST['image_url'] ?? ''),
            'linkedin' => trim($_POST['linkedin'] ?? ''),
            'github' => trim($_POST['github'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'community_history' => $history
        ];

        if (empty($data['name']) || empty($data['role'])) {
            $this->flashError('Ad Soyad ve Görev alanları zorunludur.');
            $this->redirect('/superadmin/site-team');
            return;
        }

        $this->teamModel->createMember($data);
        Logger::log('CREATE_SITE_TEAM', 'SiteTeamMember', null, ['name' => $data['name']]);
        $this->flashSuccess('Ekip üyesi başarıyla eklendi.');
        $this->redirect('/superadmin/site-team');
    }

    // POST /superadmin/site-team/{id}
    public function update(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $member = $this->teamModel->getMemberById($id);
        if (!$member) {
            $this->flashError('Üye bulunamadı.');
            $this->redirect('/superadmin/site-team');
            return;
        }

        // Process community_history
        $historyYears = $_POST['history_year'] ?? [];
        $historyRoles = $_POST['history_role'] ?? [];
        
        $history = [];
        foreach ($historyYears as $i => $year) {
            $year = trim($year);
            $role = trim($historyRoles[$i] ?? '');
            if (!empty($year) && !empty($role)) {
                $history[] = [
                    'year' => $year,
                    'role' => $role
                ];
            }
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'role' => trim($_POST['role'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'type' => trim($_POST['type'] ?? 'board'),
            'image_url' => trim($_POST['image_url'] ?? ''),
            'linkedin' => trim($_POST['linkedin'] ?? ''),
            'github' => trim($_POST['github'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'community_history' => $history
        ];

        if (empty($data['name']) || empty($data['role'])) {
            $this->flashError('Ad Soyad ve Görev alanları zorunludur.');
            $this->redirect('/superadmin/site-team');
            return;
        }

        $this->teamModel->updateMember($id, $data);
        Logger::log('UPDATE_SITE_TEAM', 'SiteTeamMember', $id, ['name' => $data['name']]);
        $this->flashSuccess('Ekip üyesi güncellendi.');
        $this->redirect('/superadmin/site-team');
    }

    // POST /superadmin/site-team/{id}/delete
    public function destroy(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $member = $this->teamModel->getMemberById($id);
        if ($member) {
            $this->teamModel->deleteMember($id);
            Logger::log('DELETE_SITE_TEAM', 'SiteTeamMember', $id, ['name' => $member['name']]);
            $this->flashSuccess('Ekip üyesi silindi.');
        } else {
            $this->flashError('Üye bulunamadı.');
        }

        $this->redirect('/superadmin/site-team');
    }
}
