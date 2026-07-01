<?php
// c:\xampp\htdocs\innomis-admin\modules\MentorshipModule.php

class MentorshipModule extends BaseModule
{
    public function render(): string
    {
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);
        
        // Get all department users to populate the mentor/mentee select lists
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.name 
            FROM users u 
            JOIN user_departments du ON u.id = du.user_id 
            WHERE du.department_id = ?
            ORDER BY u.name ASC
        ");
        $stmt->execute([$this->department['id']]);
        $departmentMembers = $stmt->fetchAll();

        // Check if current user is part of a pair
        $activePairId = (int)($_GET['pair'] ?? 0);
        $userPair = null;

        // Fetch all active pairs
        $sql = "
            SELECT p.*, 
                   m1.name as mentor_name, m1.avatar as mentor_avatar,
                   m2.name as mentee_name, m2.avatar as mentee_avatar
            FROM app_mentorship_pairs p
            JOIN users m1 ON p.mentor_id = m1.id
            JOIN users m2 ON p.mentee_id = m2.id
            WHERE p.app_id = ? AND p.department_id = ? AND p.status = 'active'
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->app['id'], $this->department['id']]);
        $pairs = $stmt->fetchAll();

        // If no specific pair is selected but user is in a pair, auto-select it
        if (!$activePairId) {
            foreach ($pairs as $p) {
                if ($p['mentor_id'] == $this->user['id'] || $p['mentee_id'] == $this->user['id']) {
                    $activePairId = $p['id'];
                    break;
                }
            }
        }

        // Identify the active pair
        foreach ($pairs as $p) {
            if ($p['id'] == $activePairId) {
                $userPair = $p;
                break;
            }
        }

        $notes = [];
        if ($userPair) {
            // Get notes for this pair
            $stmt = $this->pdo->prepare("
                SELECT n.*, u.name as writer_name, u.avatar as writer_avatar 
                FROM app_mentorship_notes n 
                JOIN users u ON n.writer_id = u.id 
                WHERE n.pair_id = ? 
                ORDER BY n.meeting_date DESC, n.created_at DESC
            ");
            $stmt->execute([$userPair['id']]);
            $notes = $stmt->fetchAll();
        }

        ob_start();
        extract([
            'app' => $this->app,
            'department' => $this->department,
            'user' => $this->user,
            'canManage' => $canManage,
            'departmentMembers' => $departmentMembers,
            'pairs' => $pairs,
            'activePairId' => $activePairId,
            'userPair' => $userPair,
            'notes' => $notes
        ]);
        require __DIR__ . '/../views/modules/mentorship.php';
        return ob_get_clean();
    }

    public function handlePost(array $data): void
    {
        $action = $data['action'] ?? '';
        $canManage = $this->isDirectorOrAdmin() || !empty($this->department['is_management']);

        if ($action === 'create_pair' && $canManage) {
            $mentorId = (int)($data['mentor_id'] ?? 0);
            $menteeId = (int)($data['mentee_id'] ?? 0);
            
            if ($mentorId && $menteeId && $mentorId !== $menteeId) {
                $stmt = $this->pdo->prepare("INSERT INTO app_mentorship_pairs (app_id, department_id, mentor_id, mentee_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$this->app['id'], $this->department['id'], $mentorId, $menteeId]);
            }
            $this->redirectBack();
        }

        if ($action === 'add_note') {
            $pairId = (int)($data['pair_id'] ?? 0);
            $meetingDate = $data['meeting_date'] ?? date('Y-m-d');
            $note = trim($data['note'] ?? '');
            
            if ($pairId && $note) {
                // Ensure the user is actually part of this pair or is a manager
                $stmt = $this->pdo->prepare("SELECT * FROM app_mentorship_pairs WHERE id = ? AND app_id = ?");
                $stmt->execute([$pairId, $this->app['id']]);
                $pair = $stmt->fetch();
                
                if ($pair && ($canManage || $pair['mentor_id'] == $this->user['id'] || $pair['mentee_id'] == $this->user['id'])) {
                    $stmt = $this->pdo->prepare("INSERT INTO app_mentorship_notes (pair_id, writer_id, note, meeting_date) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$pairId, $this->user['id'], $note, $meetingDate]);
                }
            }
            $this->redirectBack("?pair=" . $pairId);
        }

        if ($action === 'complete_pair' && $canManage) {
            $pairId = (int)($data['pair_id'] ?? 0);
            if ($pairId) {
                $stmt = $this->pdo->prepare("UPDATE app_mentorship_pairs SET status = 'completed' WHERE id = ? AND app_id = ?");
                $stmt->execute([$pairId, $this->app['id']]);
            }
            $this->redirectBack();
        }

        $this->redirectBack();
    }

    private function redirectBack($query = "")
    {
        $url = BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}" . $query;
        header("Location: " . $url);
        exit;
    }
}
