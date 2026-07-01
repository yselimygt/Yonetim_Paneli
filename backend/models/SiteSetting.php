<?php
require_once __DIR__ . '/../core/Model.php';

class SiteSetting extends Model
{
    protected string $table = 'site_settings';

    public function get(string $key)
    {
        $stmt = $this->pdo->prepare("SELECT json_value FROM {$this->table} WHERE key_name = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        if ($result && $result['json_value']) {
            return json_decode($result['json_value'], true);
        }
        return null;
    }

    public function set(string $key, array $value): void
    {
        $json = json_encode($value, JSON_UNESCAPED_UNICODE);
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (key_name, json_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE json_value = VALUES(json_value)");
        $stmt->execute([$key, $json]);
    }

    public function getAllSettings(): array
    {
        $stmt = $this->pdo->query("SELECT key_name, json_value FROM {$this->table}");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['key_name']] = json_decode($row['json_value'], true);
        }
        return $settings;
    }
}
