<?php

require_once __DIR__ . '/../models/Department.php';

class DepartmentAdminController extends Controller
{
    private Department $dept;

    public function __construct()
    {
        $this->dept = new Department();
    }

    // GET /superadmin/departments
    public function index(): void
    {
        Auth::requireSuperAdmin();
        $departments = $this->dept->getAll();
        $this->view('superadmin/departments/index', compact('departments'), 'superadmin');
    }

    // GET /superadmin/departments/create  (route: /superadmin/departments — GET with ?create=1 workaround)
    // POST /superadmin/departments
    public function store(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $name        = trim($_POST['name']          ?? '');
        $description = trim($_POST['description']   ?? '');
        $icon        = trim($_POST['icon']          ?? 'folder');
        $color       = trim($_POST['color']         ?? '#00BCD4');
        $is_mgmt     = isset($_POST['is_management']) ? 1 : 0;
        $slug        = Department::generateSlug($name);

        if (empty($name)) {
            $this->flashError('Departman adı zorunludur.');
            $this->redirect('/superadmin/departments');
        }

        if ($this->dept->slugExists($slug)) {
            $this->flashError("'{$name}' adıyla zaten bir departman var.");
            $this->redirect('/superadmin/departments');
        }

        $this->dept->create([
            'name'         => $name,
            'slug'         => $slug,
            'description'  => $description,
            'icon'         => $icon,
            'color'        => $color,
            'is_management'=> $is_mgmt,
            'is_active'    => 1,
            'created_by'   => Auth::user()['id'],
        ]);

        Logger::log('CREATE_DEPARTMENT', 'Department', null, ['name' => $name, 'slug' => $slug]);

        $this->flashSuccess("'{$name}' departmanı oluşturuldu.");
        $this->redirect('/superadmin/departments');
    }

    // GET /superadmin/departments/{id}/edit
    public function edit(string $id): void
    {
        Auth::requireSuperAdmin();
        $department = $this->dept->getById((int)$id);
        if (!$department) {
            $this->flashError('Departman bulunamadı.');
            $this->redirect('/superadmin/departments');
        }
        $this->view('superadmin/departments/edit', compact('department'), 'superadmin');
    }

    // POST /superadmin/departments/{id}
    public function update(string $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $department = $this->dept->getById((int)$id);
        if (!$department) {
            $this->flashError('Departman bulunamadı.');
            $this->redirect('/superadmin/departments');
        }

        $name        = trim($_POST['name']          ?? '');
        $description = trim($_POST['description']   ?? '');
        $icon        = trim($_POST['icon']          ?? 'folder');
        $color       = trim($_POST['color']         ?? '#00BCD4');
        $is_mgmt     = isset($_POST['is_management']) ? 1 : 0;
        $is_active   = isset($_POST['is_active'])    ? 1 : 0;
        $slug        = Department::generateSlug($name);

        if (empty($name)) {
            $this->flashError('Departman adı zorunludur.');
            $this->redirect('/superadmin/departments/' . $id . '/edit');
        }

        if ($this->dept->slugExists($slug, (int)$id)) {
            $this->flashError("'{$name}' adıyla zaten bir departman var.");
            $this->redirect('/superadmin/departments/' . $id . '/edit');
        }

        $this->dept->updateById((int)$id, [
            'name'         => $name,
            'slug'         => $slug,
            'description'  => $description,
            'icon'         => $icon,
            'color'        => $color,
            'is_management'=> $is_mgmt,
            'is_active'    => $is_active,
        ]);

        Logger::log('UPDATE_DEPARTMENT', 'Department', (int)$id, ['name' => $name, 'slug' => $slug]);

        $this->flashSuccess("'{$name}' departmanı güncellendi.");
        $this->redirect('/superadmin/departments');
    }

    // POST /superadmin/departments/{id}/delete
    public function destroy(string $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $department = $this->dept->getById((int)$id);
        if (!$department) {
            $this->flashError('Departman bulunamadı.');
            $this->redirect('/superadmin/departments');
        }

        $this->dept->deleteById((int)$id);
        Logger::log('DELETE_DEPARTMENT', 'Department', (int)$id, ['name' => $department['name']]);
        $this->flashSuccess("'{$department['name']}' departmanı silindi.");
        $this->redirect('/superadmin/departments');
    }
}
