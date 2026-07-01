<?php

require_once __DIR__ . '/../core/Model.php';

class SiteGallery extends Model
{
    protected string $table = 'site_gallery';

    public function getAllImages(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getImageById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createImage(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (image_url, category, title, span_class) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['image_url'],
            $data['category'] ?? 'Genel',
            $data['title'] ?? null,
            $data['span_class'] ?? ''
        ]);
    }

    public function updateImage(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET image_url = ?, category = ?, title = ?, span_class = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['image_url'],
            $data['category'] ?? 'Genel',
            $data['title'] ?? null,
            $data['span_class'] ?? '',
            $id
        ]);
    }

    public function deleteImage(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
