<?php

class Logger
{
    /**
     * Log a system action
     *
     * @param string $action       Action name (e.g. 'USER_LOGIN', 'CREATE_DEPARTMENT')
     * @param string|null $entityType The entity being modified (e.g. 'User', 'Department')
     * @param int|null $entityId   ID of the entity being modified
     * @param array $details       Extra information to store as JSON
     */
    public static function log(string $action, ?string $entityType = null, ?int $entityId = null, array $details = []): void
    {
        try {
            $pdo = Database::getInstance();
            
            $userId = Auth::user()['id'] ?? null;
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $detailsJson = !empty($details) ? json_encode($details, JSON_UNESCAPED_UNICODE) : null;
            
            $stmt = $pdo->prepare("
                INSERT INTO system_logs (user_id, action, entity_type, entity_id, ip_address, details)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $userId,
                $action,
                $entityType,
                $entityId,
                $ipAddress,
                $detailsJson
            ]);
        } catch (Throwable $e) {
            // Silently ignore logging errors to prevent breaking the application,
            // but we could log to a file if needed.
            error_log("Logger Error: " . $e->getMessage());
        }
    }
}
