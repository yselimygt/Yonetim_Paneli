<?php

require_once __DIR__ . '/../models/SiteTeamProfile.php';

class SiteTeamAdminController extends Controller
{
    private SiteTeamProfile $teamModel;

    public function __construct()
    {
        $this->teamModel = new SiteTeamProfile();
    }

    public function index(): void
    {
        Auth::requireSuperAdmin();
        $groups = $this->teamModel->getGroupedAdmins();
        
        $this->view('superadmin/site_team/index', ['groups' => $groups], 'superadmin');
    }

    public function saveProfile(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $data = $this->profileDataFromPost();

        if (empty($data['user_id'])) {
            $this->flashError('Admin bilgisi bulunamadı.');
            $this->redirect('/superadmin/site-team');
            return;
        }

        $this->teamModel->saveProfile($data);
        $this->teamModel->updateUserPublicProfile((int) $data['user_id'], $data);
        Logger::log('SAVE_SITE_TEAM_PROFILE', 'User', (int) $data['user_id'], [
            'department_id' => $data['department_id'],
            'is_visible' => $data['is_visible']
        ]);

        $this->flashSuccess('Ekip görünürlüğü güncellendi.');
        $this->redirect('/superadmin/site-team');
    }

    public function store(): void
    {
        $this->saveProfile();
    }

    public function toggleVisibility(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $userId = (int) ($_POST['user_id'] ?? 0);
        $departmentId = $_POST['department_id'] ?? null;
        $departmentId = $departmentId === '' ? null : (int) $departmentId;
        $isVisible = (int) ($_POST['is_visible'] ?? 0);

        if ($userId <= 0) {
            $this->flashError('Admin bilgisi bulunamadı.');
            $this->redirect('/superadmin/site-team');
            return;
        }

        $this->teamModel->updateVisibility($userId, $departmentId, $isVisible);
        Logger::log('TOGGLE_SITE_TEAM_VISIBILITY', 'User', $userId, [
            'department_id' => $departmentId,
            'is_visible' => $isVisible
        ]);

        $this->flashSuccess($isVisible ? 'Profil sitede gösterilecek.' : 'Profil siteden gizlendi.');
        $this->redirect('/superadmin/site-team');
    }

    private function profileDataFromPost(): array
    {
        $historyYears = $_POST['history_year'] ?? [];
        $historyRoles = $_POST['history_role'] ?? [];
        $history = [];

        foreach ($historyYears as $i => $year) {
            $year = trim($year);
            $role = trim($historyRoles[$i] ?? '');

            if ($year !== '' && $role !== '') {
                $history[] = [
                    'year' => $year,
                    'role' => $role
                ];
            }
        }

        $departmentId = $_POST['department_id'] ?? null;
        $departmentId = $departmentId === '' ? null : (int) $departmentId;
        $displayType = $_POST['display_type'] ?? 'department';
        $allowedTypes = ['board', 'department', 'campus'];

        return [
            'user_id' => (int) ($_POST['user_id'] ?? 0),
            'department_id' => $departmentId,
            'display_name' => trim($_POST['display_name'] ?? ''),
            'display_role' => trim($_POST['display_role'] ?? ''),
            'display_department' => trim($_POST['display_department'] ?? ''),
            'display_type' => in_array($displayType, $allowedTypes, true) ? $displayType : 'department',
            'image_url' => trim($_POST['image_url'] ?? ''),
            'linkedin' => trim($_POST['linkedin'] ?? ''),
            'github' => trim($_POST['github'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'university' => trim($_POST['university'] ?? ''),
            'faculty' => trim($_POST['faculty'] ?? ''),
            'school_department' => trim($_POST['school_department'] ?? ''),
            'grade' => trim($_POST['grade'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
            'university_logo' => trim($_POST['university_logo'] ?? ''),
            'company_logo' => trim($_POST['company_logo'] ?? ''),
            'community_history' => $history,
            'is_visible' => isset($_POST['is_visible']) ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0)
        ];
    }
}
