<?php

require_once __DIR__ . '/../core/Model.php';

class Department extends Model
{
    protected string $table = 'departments';

    public function getAll(): array
    {
        return $this->findAll('1 ORDER BY is_management DESC, name ASC');
    }

    public function getActive(): array
    {
        return $this->findAll('is_active = 1 ORDER BY is_management DESC, name ASC');
    }

    public function getById(int $id): ?array
    {
        return $this->findById($id);
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM departments WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function slugExists(string $slug, int $excludeId = 0): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM departments WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $excludeId]);
        return (bool) $stmt->fetchColumn();
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

    public function toggleActive(int $id): bool
    {
        $dept = $this->findById($id);
        if (!$dept) return false;
        return $this->update($id, ['is_active' => $dept['is_active'] ? 0 : 1]);
    }

    public static function generateSlug(string $name): string
    {
        $slug = mb_strtolower($name, 'UTF-8');
        $map = [
            'ğ'=>'g','ü'=>'u','ş'=>'s','ı'=>'i','ö'=>'o','ç'=>'c',
            'Ğ'=>'g','Ü'=>'u','Ş'=>'s','İ'=>'i','Ö'=>'o','Ç'=>'c',
            ' '=>'-','&'=>'ve','_'=>'-',
        ];
        $slug = strtr($slug, $map);
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    public function totalCount(): int
    {
        return $this->count();
    }
}
