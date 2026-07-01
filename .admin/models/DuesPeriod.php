<?php

require_once __DIR__ . '/../core/Model.php';

class DuesPeriod extends Model
{
    protected string $table = 'dues_periods';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByAppId(int $appId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT dp.*, 
                (SELECT COUNT(*) FROM dues_payments WHERE period_id = dp.id) as total_users,
                (SELECT COUNT(*) FROM dues_payments WHERE period_id = dp.id AND status = 'paid') as paid_users,
                (SELECT SUM(amount_paid) FROM dues_payments WHERE period_id = dp.id AND status = 'paid') as collected_amount
            FROM {$this->table} dp
            WHERE dp.app_id = ?
            ORDER BY dp.created_at DESC
        ");
        $stmt->execute([$appId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        return $this->insert($data);
    }

    public function delete(int $id): bool
    {
        return parent::delete($id);
    }

    public function getById(int $id): ?array
    {
        return $this->findById($id);
    }
}
