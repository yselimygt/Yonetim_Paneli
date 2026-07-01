<?php

require_once __DIR__ . '/../core/Model.php';

class SiteTeamProfile extends Model
{
    protected string $table = 'site_team_profiles';

    public function getGroupedAdmins(): array
    {
        $groups = [];

        foreach ($this->getAssignedAdmins() as $admin) {
            $key = (string) $admin['department_id'];
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'id' => $admin['department_id'],
                    'name' => $admin['department_name'],
                    'slug' => $admin['department_slug'],
                    'members' => []
                ];
            }

            $groups[$key]['members'][] = $this->normalizeAdmin($admin);
        }

        $unassigned = array_map(fn($admin) => $this->normalizeAdmin($admin), $this->getUnassignedAdmins());
        if (!empty($unassigned)) {
            $groups['unassigned'] = [
                'id' => null,
                'name' => 'Departmansız Adminler',
                'slug' => 'departmansiz-adminler',
                'members' => $unassigned
            ];
        }

        return array_values($groups);
    }

    public function saveProfile(array $data): bool
    {
        $existing = $this->findProfile((int) $data['user_id'], $data['department_id']);
        $payload = $this->preparePayload($data);

        if ($existing) {
            return $this->update((int) $existing['id'], $payload);
        }

        $payload['user_id'] = (int) $data['user_id'];
        $payload['department_id'] = $data['department_id'];
        return (bool) $this->insert($payload);
    }

    public function getPublicTeam(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                p.*,
                u.name AS user_name,
                u.email,
                u.avatar,
                ud.role AS department_role,
                d.name AS department_name
            FROM site_team_profiles p
            JOIN users u ON u.id = p.user_id
            LEFT JOIN user_departments ud ON ud.user_id = p.user_id
                AND ((p.department_id IS NULL AND ud.department_id IS NULL) OR ud.department_id = p.department_id)
            LEFT JOIN departments d ON d.id = p.department_id
            WHERE p.is_visible = 1
              AND u.is_active = 1
            ORDER BY p.display_type ASC, p.sort_order ASC, u.name ASC
        ");

        return array_map(fn($row) => $this->normalizePublicMember($row), $stmt->fetchAll());
    }

    public function hasVisibleProfiles(): bool
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM site_team_profiles WHERE is_visible = 1");
        return (int) $stmt->fetchColumn() > 0;
    }

    private function getAssignedAdmins(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                u.id AS user_id,
                u.name AS user_name,
                u.email,
                u.avatar,
                u.is_super_admin,
                ud.department_id,
                ud.role AS department_role,
                d.name AS department_name,
                d.slug AS department_slug,
                p.display_name,
                p.display_role,
                p.display_department,
                p.display_type,
                p.image_url,
                p.linkedin,
                p.github,
                p.bio,
                p.community_history_json,
                p.is_visible,
                p.sort_order
            FROM user_departments ud
            JOIN users u ON u.id = ud.user_id
            JOIN departments d ON d.id = ud.department_id
            LEFT JOIN site_team_profiles p ON p.user_id = u.id AND p.department_id = ud.department_id
            WHERE u.is_active = 1
            ORDER BY d.is_management DESC, d.name ASC, ud.role ASC, u.name ASC
        ");

        return $stmt->fetchAll();
    }

    private function getUnassignedAdmins(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                u.id AS user_id,
                u.name AS user_name,
                u.email,
                u.avatar,
                u.is_super_admin,
                NULL AS department_id,
                NULL AS department_role,
                NULL AS department_name,
                'departmansiz-adminler' AS department_slug,
                p.display_name,
                p.display_role,
                p.display_department,
                p.display_type,
                p.image_url,
                p.linkedin,
                p.github,
                p.bio,
                p.community_history_json,
                p.is_visible,
                p.sort_order
            FROM users u
            LEFT JOIN site_team_profiles p ON p.user_id = u.id AND p.department_id IS NULL
            WHERE u.is_active = 1
              AND NOT EXISTS (
                  SELECT 1 FROM user_departments ud WHERE ud.user_id = u.id
              )
            ORDER BY u.is_super_admin DESC, u.name ASC
        ");

        return $stmt->fetchAll();
    }

    private function findProfile(int $userId, $departmentId): ?array
    {
        if ($departmentId === null) {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE user_id = ? AND department_id IS NULL LIMIT 1");
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE user_id = ? AND department_id = ? LIMIT 1");
            $stmt->execute([$userId, $departmentId]);
        }

        $result = $stmt->fetch();
        return $result ?: null;
    }

    private function preparePayload(array $data): array
    {
        return [
            'department_id' => $data['department_id'],
            'display_name' => $data['display_name'] ?: null,
            'display_role' => $data['display_role'] ?: null,
            'display_department' => $data['display_department'] ?: null,
            'display_type' => $data['display_type'],
            'image_url' => $data['image_url'] ?: null,
            'linkedin' => $data['linkedin'] ?: null,
            'github' => $data['github'] ?: null,
            'bio' => $data['bio'] ?: null,
            'community_history_json' => json_encode($data['community_history'], JSON_UNESCAPED_UNICODE),
            'is_visible' => (int) $data['is_visible'],
            'sort_order' => (int) $data['sort_order']
        ];
    }

    private function normalizeAdmin(array $admin): array
    {
        $admin['display_type'] = $admin['display_type'] ?: 'department';
        $admin['is_visible'] = (int) ($admin['is_visible'] ?? 0);
        $admin['sort_order'] = (int) ($admin['sort_order'] ?? 0);
        $admin['display_role'] = $admin['display_role'] ?: $this->defaultRole($admin['department_role'], (bool) $admin['is_super_admin']);
        $admin['display_department'] = $admin['display_department'] ?: ($admin['department_name'] ?? 'Yönetim');
        $admin['image_url'] = $admin['image_url'] ?: $admin['avatar'];
        $admin['community_history_json'] = $admin['community_history_json'] ?: '[]';

        return $admin;
    }

    private function normalizePublicMember(array $member): array
    {
        $name = $member['display_name'] ?: $member['user_name'];
        $department = $member['display_department'] ?: ($member['department_name'] ?? 'Yönetim');
        $role = $member['display_role'] ?: $this->defaultRole($member['department_role'] ?? null, false);

        return [
            'id' => (int) $member['id'],
            'user_id' => (int) $member['user_id'],
            'name' => $name,
            'role' => $role,
            'department' => $department,
            'type' => $member['display_type'] ?: 'department',
            'image_url' => $member['image_url'] ?: $member['avatar'],
            'image' => $member['image_url'] ?: $member['avatar'],
            'linkedin' => $member['linkedin'],
            'github' => $member['github'],
            'email' => $member['email'],
            'bio' => $member['bio'],
            'communityHistory' => json_decode($member['community_history_json'] ?: '[]', true) ?: [],
            'sort_order' => (int) $member['sort_order']
        ];
    }

    private function defaultRole(?string $departmentRole, bool $isSuperAdmin): string
    {
        if ($isSuperAdmin) {
            return 'Süper Admin';
        }

        return $departmentRole === 'director' ? 'Direktör' : 'Üye';
    }
}
