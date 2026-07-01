<?php

require_once __DIR__ . '/../core/Model.php';

class SiteHistory extends Model
{
    protected string $table = 'site_history';

    public function getAllHistory(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY year DESC, id DESC");
        return $stmt->fetchAll();
    }

    public function getHistoryById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createHistory(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (year, title, description, icon, type, link) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['year'],
            $data['title'],
            $data['description'] ?? null,
            $data['icon'] ?? null,
            $data['type'] ?? 'achievement',
            $data['link'] ?? null
        ]);
    }

    public function updateHistory(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET year = ?, title = ?, description = ?, icon = ?, type = ?, link = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['year'],
            $data['title'],
            $data['description'] ?? null,
            $data['icon'] ?? null,
            $data['type'] ?? 'achievement',
            $data['link'] ?? null,
            $id
        ]);
    }

    public function deleteHistory(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
