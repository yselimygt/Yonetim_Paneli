<?php

require_once __DIR__ . '/../core/Model.php';

class SiteProject extends Model
{
    protected string $table = 'site_projects';

    public function getAllProjects(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getProjectById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createProject(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (title, description, category, status, tech_stack_json, github_link, demo_link) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $techStack = isset($data['tech_stack']) ? json_encode($data['tech_stack'], JSON_UNESCAPED_UNICODE) : '[]';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['category'],
            $data['status'],
            $techStack,
            $data['github_link'] ?? null,
            $data['demo_link'] ?? null
        ]);
    }

    public function updateProject(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} 
                SET title = ?, description = ?, category = ?, status = ?, tech_stack_json = ?, github_link = ?, demo_link = ? 
                WHERE id = ?";
        
        $techStack = isset($data['tech_stack']) ? json_encode($data['tech_stack'], JSON_UNESCAPED_UNICODE) : '[]';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['category'],
            $data['status'],
            $techStack,
            $data['github_link'] ?? null,
            $data['demo_link'] ?? null,
            $id
        ]);
    }

    public function deleteProject(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
