<?php

require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getById(int $id): ?array
    {
        return $this->findById($id);
    }

    public function create(string $name, string $email, string $password, int $is_super_admin = 0, int $can_manage_passwords = 0, int $is_representative = 0, int $is_alumni = 0): int
    {
        return $this->insert([
            'name'                   => $name,
            'email'                  => $email,
            'password'               => password_hash($password, PASSWORD_BCRYPT),
            'is_super_admin'         => $is_super_admin,
            'can_manage_passwords'   => $can_manage_passwords,
            'is_representative'      => $is_representative,
            'is_alumni'              => $is_alumni
        ]);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]);
    }

    public function getAllActive(): array
    {
        return $this->findAll('is_active = 1', []);
    }

    public function toggleActive(int $id): bool
    {
        $user = $this->findById($id);
        if (!$user) return false;
        return $this->update($id, ['is_active' => $user['is_active'] ? 0 : 1]);
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->delete($id);
    }
}
