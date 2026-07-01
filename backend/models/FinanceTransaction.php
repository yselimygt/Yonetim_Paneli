<?php

require_once __DIR__ . '/../core/Model.php';

class FinanceTransaction extends Model
{
    protected string $table = 'finance_transactions';

    public function __construct()
    {
        parent::__construct();
    }

    public function getByAppId(int $appId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT ft.*, u.name as proxy_user_name 
            FROM {$this->table} ft
            LEFT JOIN users u ON u.id = ft.proxy_user_id
            WHERE ft.app_id = ? 
            ORDER BY ft.transaction_date DESC, ft.id DESC
        ");
        $stmt->execute([$appId]);
        return $stmt->fetchAll();
    }

    public function getBalance(int $appId): float
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) - 
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as balance
            FROM {$this->table}
            WHERE app_id = ?
        ");
        $stmt->execute([$appId]);
        return (float) $stmt->fetchColumn();
    }

    public function getSummary(int $appId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
            FROM {$this->table}
            WHERE app_id = ?
        ");
        $stmt->execute([$appId]);
        $res = $stmt->fetch();
        return [
            'income'  => (float) ($res['total_income'] ?? 0),
            'expense' => (float) ($res['total_expense'] ?? 0),
            'balance' => (float) (($res['total_income'] ?? 0) - ($res['total_expense'] ?? 0))
        ];
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

    public function update(int $id, array $data): bool
    {
        return parent::update($id, $data);
    }
}
