<?php

require_once __DIR__ . '/../models/SiteProject.php';

class SiteProjectAdminController extends Controller
{
    private SiteProject $projectModel;

    public function __construct()
    {
        $this->projectModel = new SiteProject();
    }

    // GET /superadmin/site-projects
    public function index(): void
    {
        Auth::requireSuperAdmin();
        $projects = $this->projectModel->getAllProjects();
        
        $this->view('superadmin/site_projects/index', ['projects' => $projects], 'superadmin');
    }

    // POST /superadmin/site-projects
    public function store(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'status' => trim($_POST['status'] ?? 'active'),
            'github_link' => trim($_POST['github_link'] ?? ''),
            'demo_link' => trim($_POST['demo_link'] ?? ''),
            'tech_stack' => array_filter(array_map('trim', explode(',', $_POST['tech_stack'] ?? '')))
        ];

        if (empty($data['title'])) {
            $this->flashError('Proje başlığı zorunludur.');
            $this->redirect('/superadmin/site-projects');
            return;
        }

        $this->projectModel->createProject($data);
        Logger::log('CREATE_SITE_PROJECT', 'SiteProject', null, ['title' => $data['title']]);
        $this->flashSuccess('Proje başarıyla eklendi.');
        $this->redirect('/superadmin/site-projects');
    }

    // POST /superadmin/site-projects/{id}
    public function update(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $project = $this->projectModel->getProjectById($id);
        if (!$project) {
            $this->flashError('Proje bulunamadı.');
            $this->redirect('/superadmin/site-projects');
            return;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'status' => trim($_POST['status'] ?? 'active'),
            'github_link' => trim($_POST['github_link'] ?? ''),
            'demo_link' => trim($_POST['demo_link'] ?? ''),
            'tech_stack' => array_filter(array_map('trim', explode(',', $_POST['tech_stack'] ?? '')))
        ];

        if (empty($data['title'])) {
            $this->flashError('Proje başlığı zorunludur.');
            $this->redirect('/superadmin/site-projects');
            return;
        }

        $this->projectModel->updateProject($id, $data);
        Logger::log('UPDATE_SITE_PROJECT', 'SiteProject', $id, ['title' => $data['title']]);
        $this->flashSuccess('Proje güncellendi.');
        $this->redirect('/superadmin/site-projects');
    }

    // POST /superadmin/site-projects/{id}/delete
    public function destroy(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $project = $this->projectModel->getProjectById($id);
        if ($project) {
            $this->projectModel->deleteProject($id);
            Logger::log('DELETE_SITE_PROJECT', 'SiteProject', $id, ['title' => $project['title']]);
            $this->flashSuccess('Proje silindi.');
        } else {
            $this->flashError('Proje bulunamadı.');
        }

        $this->redirect('/superadmin/site-projects');
    }
}
