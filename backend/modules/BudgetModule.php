<?php
// c:\xampp\htdocs\innomis-admin\modules\BudgetModule.php

class BudgetModule extends BaseModule
{
    public function render(): string
    {
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);
        $isSuperAdmin = !empty($this->user['is_super_admin']);
        
        $sql = "
            SELECT b.*, u.name as requester_name, u.avatar as requester_avatar 
            FROM app_budget_requests b 
            JOIN users u ON b.requester_id = u.id 
            WHERE b.app_id = ? AND b.department_id = ?
        ";
        $params = [$this->app['id'], $this->department['id']];
        
        // Standart users see only their requests
        if (!$canManage && !$isSuperAdmin) {
            $sql .= " AND b.requester_id = ?";
            $params[] = $this->user['id'];
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $requests = $stmt->fetchAll();

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'canManage' => $canManage,
            'isSuperAdmin' => $isSuperAdmin,
            'requests' => $requests
        ]);
        require __DIR__ . '/../views/modules/budget.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);
        $isSuperAdmin = !empty($this->user['is_super_admin']);

        if ($action === 'create_request') {
            $title = trim($data['title'] ?? '');
            $amount = (float)($data['amount'] ?? 0);
            $currency = trim($data['currency'] ?? 'TRY');
            $reason = trim($data['reason'] ?? '');
            
            if ($title && $amount > 0 && $reason) {
                $stmt = $this->pdo->prepare("INSERT INTO app_budget_requests (app_id, department_id, requester_id, title, amount, currency, reason) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $this->user['id'], $title, $amount, $currency, $reason]);
            }
        }

        if ($action === 'approve_request') {
            $reqId = (int)($data['request_id'] ?? 0);
            $newStatus = trim($data['new_status'] ?? '');
            
            if ($reqId && $newStatus) {
                // Determine if user has permission for this status transition
                if ($newStatus === 'director_approved' && $canManage) {
                    $this->updateStatus($reqId, 'director_approved');
                } elseif ($newStatus === 'admin_approved' && $isSuperAdmin) {
                    $this->updateStatus($reqId, 'admin_approved');
                }
            }
        }

        if ($action === 'reject_request') {
            $reqId = (int)($data['request_id'] ?? 0);
            if ($reqId && ($canManage || $isSuperAdmin)) {
                $this->updateStatus($reqId, 'rejected');
            }
        }

        $this->redirectBack();
    }
    
    private function updateStatus(int $id, string $status) {
        $stmt = $this->pdo->prepare("UPDATE app_budget_requests SET status = ? WHERE id = ? AND app_id = ?");
        $stmt->execute([$status, $id, $this->app['id']]);
    }

    private function redirectBack()
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}";
        header("Location: " . $url);
        exit;
    }
}
