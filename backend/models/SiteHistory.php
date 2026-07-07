<?php

require_once __DIR__ . '/../core/Model.php';

class SiteHistory extends Model
{
    protected string $table = 'site_history';

    public function getAllHistory(): array
    {
        $stmt = $this->pdo->query("
            SELECT *, 'history' AS source, CONCAT(year, '-12-31') AS sort_date
            FROM {$this->table}
            WHERE type <> 'award'
            ORDER BY year DESC, id DESC
        ");
        $history = $stmt->fetchAll();

        $trophies = $this->getTrophyAwards();
        $items = array_merge($history, $trophies);

        usort($items, function ($a, $b) {
            $dateCompare = strcmp((string) ($b['sort_date'] ?? ''), (string) ($a['sort_date'] ?? ''));
            if ($dateCompare !== 0) {
                return $dateCompare;
            }

            return strcmp((string) ($b['id'] ?? ''), (string) ($a['id'] ?? ''));
        });

        return $items;
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

    public function getTrophyById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM app_trophies WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateTrophy(int $id, array $data): bool
    {
        $fields = [
            'title = ?',
            'description = ?',
            'award_type = ?',
            'award_date = ?'
        ];
        $values = [
            $data['title'],
            $data['description'] ?? '',
            $data['award_type'] ?? 'other',
            $data['award_date']
        ];

        if (!empty($data['image_path'])) {
            $fields[] = 'image_path = ?';
            $values[] = $data['image_path'];
        }

        $values[] = $id;
        $sql = 'UPDATE app_trophies SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function deleteTrophy(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM app_trophies WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function getTrophyAwards(): array
    {
        try {
            $stmt = $this->pdo->query("
                SELECT
                    t.id,
                    t.title,
                    t.description,
                    t.award_type,
                    t.award_date,
                    t.image_path,
                    t.institution_name,
                    t.institution_logo,
                    t.institution_url,
                    t.pdf_url,
                    t.link,
                    t.created_at,
                    d.name AS department_name
                FROM app_trophies t
                LEFT JOIN departments d ON d.id = t.department_id
                ORDER BY t.award_date DESC, t.created_at DESC
            ");
        } catch (Throwable $e) {
            return [];
        }

        return array_map(function ($trophy) {
            $awardDate = $trophy['award_date'] ?: $trophy['created_at'];

            return [
                'id' => 'trophy_' . (int) $trophy['id'],
                'trophy_id' => (int) $trophy['id'],
                'year' => $awardDate ? date('Y', strtotime($awardDate)) : '',
                'title' => $trophy['title'],
                'description' => $trophy['description'],
                'icon' => 'Trophy',
                'type' => 'award',
                'link' => $trophy['link'] ?? '',
                'pdf_url' => $trophy['pdf_url'] ?? '',
                'source' => 'trophy',
                'award_type' => $trophy['award_type'] ?: 'other',
                'award_date' => $awardDate,
                'image_path' => $trophy['image_path'],
                'institution_name' => $trophy['institution_name'] ?? '',
                'institution_url' => $trophy['institution_url'] ?? '',
                'institution_logo' => $trophy['institution_logo'] ?? '',
                'department_name' => $trophy['department_name'] ?? '',
                'sort_date' => $awardDate ?: ''
            ];
        }, $stmt->fetchAll());
    }
}
