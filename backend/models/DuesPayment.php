<?php

require_once __DIR__ . '/../core/Model.php';

class DuesPayment extends Model
{
    protected string $table = 'dues_payments';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByPeriod(int $periodId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT dp.*, u.name as user_name 
            FROM {$this->table} dp
            JOIN users u ON dp.user_id = u.id
            WHERE dp.period_id = ?
            ORDER BY u.name ASC
        ");
        $stmt->execute([$periodId]);
        return $stmt->fetchAll();
    }

    public function getByUser(int $userId, int $appId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT dp.*, p.name as period_name, p.amount as expected_amount, p.due_date 
            FROM {$this->table} dp
            JOIN dues_periods p ON dp.period_id = p.id
            WHERE dp.user_id = ? AND p.app_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$userId, $appId]);
        return $stmt->fetchAll();
    }

    public function getByPeriodAndUser(int $periodId, int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE period_id = ? AND user_id = ?");
        $stmt->execute([$periodId, $userId]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        return $this->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return parent::update($id, $data);
    }

    public function getById(int $id): ?array
    {
        return $this->findById($id);
    }
}
