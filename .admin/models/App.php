<?php

require_once __DIR__ . '/../core/Model.php';

class App extends Model
{
    protected string $table = 'apps';

    public const MODULE_TYPES = [
        'form'         => 'Form',
        'tracker'      => 'Takip (Tracker)',
        'report'       => 'Rapor',
        'announcement' => 'Duyuru',
        'calendar'     => 'Takvim',
        'drive'        => 'Dosya Yöneticisi',
    ];

    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT a.*, d.name AS dept_name, d.slug AS dept_slug
            FROM apps a
            JOIN departments d ON d.id = a.department_id
            ORDER BY d.name ASC, a.sort_order ASC, a.name ASC
        ");
        return $stmt->fetchAll();
    }

    public function getByDepartment(int $deptId, bool $activeOnly = true): array
    {
        $where = "department_id = ?";
        if ($activeOnly) $where .= " AND is_active = 1";
        $stmt = $this->pdo->prepare(
            "SELECT * FROM apps WHERE {$where} ORDER BY sort_order ASC, name ASC"
        );
        $stmt->execute([$deptId]);
        return $stmt->fetchAll();
    }

    public function getBySlug(int $deptId, string $slug): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM apps WHERE department_id = ? AND slug = ? LIMIT 1"
        );
        $stmt->execute([$deptId, $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getByDepartmentAndModule(int $deptId, string $moduleType): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM apps WHERE department_id = ? AND module_type = ? LIMIT 1"
        );
        $stmt->execute([$deptId, $moduleType]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getById(int $id): ?array
    {
        return $this->findById($id);
    }

    public function create(array $data): int
    {
        return $this->insert($data);
    }

    public function updateById(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function deleteById(int $id): bool
    {
        return $this->delete($id);
    }

    public function totalCount(): int
    {
        return $this->count();
    }
}
