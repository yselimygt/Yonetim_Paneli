<?php

require_once __DIR__ . '/../core/Model.php';

class UserDepartment extends Model
{
    protected string $table = 'user_departments';

    /** Kullanıcının departmanlarını döndür */
    public function getByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT ud.*, d.name AS dept_name, d.slug, d.color, d.is_management
            FROM user_departments ud
            JOIN departments d ON d.id = ud.department_id
            WHERE ud.user_id = ?
            ORDER BY d.name ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /** Departmanın üyelerini döndür */
    public function getByDepartment(int $deptId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT ud.*, u.name AS user_name, u.email, u.is_active, u.birth_date, u.avatar
            FROM user_departments ud
            JOIN users u ON u.id = ud.user_id
            WHERE ud.department_id = ?
            ORDER BY ud.role DESC, u.name ASC
        ");
        $stmt->execute([$deptId]);
        return $stmt->fetchAll();
    }

    /** Kullanıcı zaten bu departmanda mı? */
    public function exists(int $userId, int $deptId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM user_departments WHERE user_id = ? AND department_id = ?"
        );
        $stmt->execute([$userId, $deptId]);
        return (bool) $stmt->fetchColumn();
    }

    /** Atama yap */
    public function assign(int $userId, int $deptId, string $role = 'member'): bool
    {
        if ($this->exists($userId, $deptId)) {
            // Rol güncelle
            $stmt = $this->pdo->prepare(
                "UPDATE user_departments SET role = ? WHERE user_id = ? AND department_id = ?"
            );
            return $stmt->execute([$role, $userId, $deptId]);
        }

        return (bool) $this->insert([
            'user_id'       => $userId,
            'department_id' => $deptId,
            'role'          => $role,
        ]);
    }

    /** Atamayı kaldır */
    public function remove(int $userId, int $deptId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM user_departments WHERE user_id = ? AND department_id = ?"
        );
        return $stmt->execute([$userId, $deptId]);
    }

    /** Kullanıcının tüm atamalarını kaldır */
    public function removeAllByUser(int $userId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_departments WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
}
