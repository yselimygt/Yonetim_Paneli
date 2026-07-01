<?php

require_once __DIR__ . '/../core/Model.php';

class SiteTeamMember extends Model
{
    protected string $table = 'site_team_members';

    public function getAllMembers(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY type ASC, id ASC");
        return $stmt->fetchAll();
    }

    public function getMemberById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createMember(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (name, role, department, type, image_url, linkedin, github, bio, community_history_json) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $communityHistory = isset($data['community_history']) ? json_encode($data['community_history'], JSON_UNESCAPED_UNICODE) : '[]';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['role'],
            $data['department'] ?? null,
            $data['type'],
            $data['image_url'] ?? null,
            $data['linkedin'] ?? null,
            $data['github'] ?? null,
            $data['bio'] ?? null,
            $communityHistory
        ]);
    }

    public function updateMember(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} 
                SET name = ?, role = ?, department = ?, type = ?, image_url = ?, linkedin = ?, github = ?, bio = ?, community_history_json = ? 
                WHERE id = ?";
                
        $communityHistory = isset($data['community_history']) ? json_encode($data['community_history'], JSON_UNESCAPED_UNICODE) : '[]';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['role'],
            $data['department'] ?? null,
            $data['type'],
            $data['image_url'] ?? null,
            $data['linkedin'] ?? null,
            $data['github'] ?? null,
            $data['bio'] ?? null,
            $communityHistory,
            $id
        ]);
    }

    public function deleteMember(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
