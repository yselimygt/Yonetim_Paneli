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
                    'color' => $admin['department_color'] ?? '#00BCD4',
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
                'color' => '#6b7280',
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

    public function updateVisibility(int $userId, $departmentId, int $isVisible): bool
    {
        $existing = $this->findProfile($userId, $departmentId);

        if ($existing) {
            return $this->update((int) $existing['id'], ['is_visible' => $isVisible]);
        }

        return (bool) $this->insert([
            'user_id' => $userId,
            'department_id' => $departmentId,
            'display_type' => 'department',
            'is_visible' => $isVisible,
            'sort_order' => 0
        ]);
    }

    public function updateUserPublicProfile(int $userId, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET phone = ?,
                university = ?,
                faculty = ?,
                school_department = ?,
                grade = ?,
                website = ?,
                university_logo = ?,
                company_logo = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['phone'] ?: null,
            $data['university'] ?: null,
            $data['faculty'] ?: null,
            $data['school_department'] ?: null,
            $data['grade'] ?: null,
            $data['website'] ?: null,
            $data['university_logo'] ?: null,
            $data['company_logo'] ?: null,
            $userId
        ]);
    }

    public function getPublicTeam(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                p.*,
                u.name AS user_name,
                u.email,
                u.phone,
                u.university,
                u.faculty,
                u.school_department,
                u.grade,
                u.avatar,
                u.linkedin AS user_linkedin,
                u.github AS user_github,
                u.website AS user_website,
                u.bio AS user_bio,
                u.university_logo AS user_university_logo,
                u.company_logo AS user_company_logo,
                u.community_history_json AS user_community_history_json,
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
                u.phone,
                u.university,
                u.faculty,
                u.school_department,
                u.grade,
                u.avatar,
                u.linkedin AS user_linkedin,
                u.github AS user_github,
                u.website AS user_website,
                u.bio AS user_bio,
                u.university_logo AS user_university_logo,
                u.company_logo AS user_company_logo,
                u.community_history_json AS user_community_history_json,
                u.is_super_admin,
                ud.department_id,
                ud.role AS department_role,
                d.name AS department_name,
                d.slug AS department_slug,
                d.color AS department_color,
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
                u.phone,
                u.university,
                u.faculty,
                u.school_department,
                u.grade,
                u.avatar,
                u.linkedin AS user_linkedin,
                u.github AS user_github,
                u.website AS user_website,
                u.bio AS user_bio,
                u.university_logo AS user_university_logo,
                u.company_logo AS user_company_logo,
                u.community_history_json AS user_community_history_json,
                u.is_super_admin,
                NULL AS department_id,
                NULL AS department_role,
                NULL AS department_name,
                'departmansiz-adminler' AS department_slug,
                '#6b7280' AS department_color,
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
        $admin['linkedin'] = $admin['linkedin'] ?: ($admin['user_linkedin'] ?? null);
        $admin['github'] = $admin['github'] ?: ($admin['user_github'] ?? null);
        $admin['bio'] = $admin['bio'] ?: ($admin['user_bio'] ?? null);
        $admin['community_history_json'] = $this->profileJsonOrUserJson(
            $admin['community_history_json'] ?? null,
            $admin['user_community_history_json'] ?? null
        );

        return $admin;
    }

    private function normalizePublicMember(array $member): array
    {
        $name = $member['display_name'] ?: $member['user_name'];
        $teamDepartment = $member['display_department'] ?: ($member['department_name'] ?? 'Yönetim');
        $role = $member['display_role'] ?: $this->defaultRole($member['department_role'] ?? null, false);
        $avatar = $this->publicAssetUrl($member['image_url'] ?: $member['avatar']);
        $historyJson = $this->profileJsonOrUserJson(
            $member['community_history_json'] ?? null,
            $member['user_community_history_json'] ?? null
        );

        return [
            'id' => (int) $member['id'],
            'user_id' => (int) $member['user_id'],
            'name' => $name,
            'initials' => $this->initials($name),
            'role' => $role,
            'team_department' => $teamDepartment,
            'department' => $member['school_department'],
            'schoolDepartment' => $member['school_department'],
            'faculty' => $member['faculty'],
            'grade' => $member['grade'],
            'university' => $member['university'],
            'universityLogo' => $this->publicAssetUrl($member['user_university_logo']),
            'companyLogo' => $this->publicAssetUrl($member['user_company_logo']),
            'type' => $member['display_type'] ?: 'department',
            'image_url' => $avatar,
            'image' => $avatar,
            'linkedin' => $member['linkedin'] ?: $member['user_linkedin'],
            'github' => $member['github'] ?: $member['user_github'],
            'website' => $member['user_website'],
            'email' => $member['email'],
            'phone' => $member['phone'],
            'bio' => $member['bio'] ?: $member['user_bio'],
            'communityHistory' => $this->normalizeHistory($historyJson, $teamDepartment),
            'sort_order' => (int) $member['sort_order']
        ];
    }

    private function profileJsonOrUserJson(?string $profileJson, ?string $userJson): string
    {
        if ($profileJson && trim($profileJson) !== '' && trim($profileJson) !== '[]') {
            return $profileJson;
        }

        return $userJson && trim($userJson) !== '' ? $userJson : '[]';
    }

    private function publicAssetUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (preg_match('#^https?://#', $path) || str_starts_with($path, '/')) {
            return $path;
        }

        return BASE_URL . '/public/' . ltrim($path, '/');
    }

    private function normalizeHistory(string $historyJson, string $department): array
    {
        $history = json_decode($historyJson, true) ?: [];

        return array_map(function ($item) use ($department) {
            return [
                'title' => $item['title'] ?? ($item['role'] ?? ''),
                'dept' => $item['dept'] ?? $department,
                'period' => $item['period'] ?? ($item['year'] ?? ''),
                'year' => $item['year'] ?? ($item['period'] ?? ''),
                'role' => $item['role'] ?? ($item['title'] ?? '')
            ];
        }, $history);
    }

    private function initials(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name)) ?: [];
        $letters = [];

        foreach ($parts as $part) {
            if ($part !== '') {
                $letters[] = mb_substr($part, 0, 1, 'UTF-8');
            }

            if (count($letters) === 2) {
                break;
            }
        }

        return mb_strtoupper(implode('', $letters), 'UTF-8');
    }

    private function defaultRole(?string $departmentRole, bool $isSuperAdmin): string
    {
        if ($isSuperAdmin) {
            return 'Süper Admin';
        }

        return $departmentRole === 'director' ? 'Direktör' : 'Üye';
    }
}
