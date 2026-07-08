<?php

require_once __DIR__ . '/../models/SiteSetting.php';
require_once __DIR__ . '/../core/Logger.php';

class DatabaseAdminController extends Controller
{
    private string $backupDir = __DIR__ . '/../database/backups/';

    public function index(): void
    {
        Auth::requireSuperAdmin();
        
        $siteSetting = new SiteSetting();
        $settings = $siteSetting->get('database_backup');
        
        // Default settings if null
        if (!is_array($settings)) {
            $settings = [
                'enabled' => 0,
                'token' => '',
                'limit' => 7
            ];
        }

        $backups = [];
        if (is_dir($this->backupDir)) {
            $files = array_diff(scandir($this->backupDir), ['.', '..', '.htaccess']);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($this->backupDir . $file),
                        'time' => filemtime($this->backupDir . $file),
                    ];
                }
            }
        }
        
        usort($backups, fn($a, $b) => $b['time'] <=> $a['time']);

        $this->view('superadmin/database/index', [
            'backups' => $backups,
            'settings' => $settings,
            'backupDir' => $this->backupDir
        ], 'superadmin');
    }

    public function backup(): void
    {
        Auth::requireSuperAdmin();
        $filepath = $this->generateBackup(false);
        Logger::log('MANUAL_BACKUP', 'Database', null, ['message' => "Yeni manuel yedek oluşturuldu: " . basename($filepath)]);
        $this->flashSuccess('Veritabanı yedeği başarıyla alındı ve sunucuya kaydedildi.');
        $this->redirect('/superadmin/database');
    }

    public function download(): void
    {
        Auth::requireSuperAdmin();
        $file = $_GET['file'] ?? '';
        
        if ($file && preg_match('/^[a-zA-Z0-9_\-\.]+$/', $file) && file_exists($this->backupDir . $file)) {
            $filepath = $this->backupDir . $file;
            Logger::log('DOWNLOAD_BACKUP', 'Database', null, ['message' => "Yedek indirildi: $file"]);
            
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        } else {
            $this->flashError('Geçersiz veya bulunamayan dosya.');
            $this->redirect('/superadmin/database');
        }
    }

    public function delete(): void
    {
        Auth::requireSuperAdmin();
        $file = $_POST['file'] ?? '';
        
        if ($file && preg_match('/^[a-zA-Z0-9_\-\.]+$/', $file) && file_exists($this->backupDir . $file)) {
            unlink($this->backupDir . $file);
            Logger::log('DELETE_BACKUP', 'Database', null, ['message' => "Silinen Yedek: $file"]);
            $this->flashSuccess('Yedek başarıyla silindi.');
        } else {
            $this->flashError('Geçersiz dosya.');
        }
        
        $this->redirect('/superadmin/database');
    }

    public function updateSettings(): void
    {
        Auth::requireSuperAdmin();
        
        $enabled = isset($_POST['enabled']) ? 1 : 0;
        $token = trim($_POST['token'] ?? '');
        $limit = (int)($_POST['limit'] ?? 7);

        if (empty($token)) {
            try {
                $token = bin2hex(random_bytes(16));
            } catch (Exception $e) {
                $token = md5(uniqid('', true));
            }
        }

        $siteSetting = new SiteSetting();
        $siteSetting->set('database_backup', [
            'enabled' => $enabled,
            'token' => $token,
            'limit' => $limit
        ]);
        
        Logger::log('UPDATE_BACKUP_SETTINGS', 'SiteSetting', null, "Yedekleme ayarları güncellendi.");
        $this->flashSuccess('Otomatik yedekleme ayarları başarıyla kaydedildi.');
        
        $this->redirect('/superadmin/database');
    }

    public function cronBackup(): void
    {
        $siteSetting = new SiteSetting();
        $settings = $siteSetting->get('database_backup');
        
        if (empty($settings['enabled']) || $settings['enabled'] == 0) {
            http_response_code(403);
            echo "Otomatik yedekleme kapali.";
            exit;
        }

        $token = $_GET['token'] ?? '';
        if (empty($settings['token']) || $token !== $settings['token']) {
            http_response_code(403);
            echo "Gecersiz token.";
            exit;
        }

        // Token ok, generate backup and save to file
        $filepath = $this->generateBackup(false);
        
        // Prune old backups
        $limit = (int)($settings['limit'] ?? 7);
        $this->pruneBackups($limit);
        
        Logger::log('AUTO_BACKUP', 'Database', null, ['message' => "Otomatik yedek alındı: " . basename($filepath)]);
        echo "Yedek basariyla alindi: " . basename($filepath);
        exit;
    }

    private function pruneBackups(int $daysToKeep): void
    {
        if ($daysToKeep <= 0) return;
        
        $now = time();
        if (!is_dir($this->backupDir)) return;
        
        $files = array_diff(scandir($this->backupDir), ['.', '..', '.htaccess']);
        
        foreach ($files as $file) {
            $filePath = $this->backupDir . $file;
            if (pathinfo($filePath, PATHINFO_EXTENSION) === 'sql') {
                $mtime = filemtime($filePath);
                if (($now - $mtime) > ($daysToKeep * 86400)) {
                    unlink($filePath);
                    Logger::log('PRUNE_BACKUP', 'Database', null, ['message' => "Eski yedek silindi: $file"]);
                }
            }
        }
    }

    private function generateBackup(bool $download = false): string
    {
        $pdo = Database::getInstance();
        
        $tables = [];
        $stmt = $pdo->query("SHOW TABLES");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        $sql = "-- Innomis Veritabani Yedegi\n";
        $sql .= "-- Olusturulma: " . date('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $sql .= "-- Tablo yapisi: $table\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            
            $createStmt = $pdo->query("SHOW CREATE TABLE `$table`");
            $createRow = $createStmt->fetch(PDO::FETCH_NUM);
            $sql .= $createRow[1] . ";\n\n";

            $sql .= "-- Tablo verisi: $table\n";
            $dataStmt = $pdo->query("SELECT * FROM `$table`");
            $rowCount = $dataStmt->rowCount();

            if ($rowCount > 0) {
                while ($row = $dataStmt->fetch(PDO::FETCH_ASSOC)) {
                    $keys = array_keys($row);
                    $values = array_values($row);
                    
                    $escapedValues = array_map(function($val) use ($pdo) {
                        if ($val === null) return 'NULL';
                        return $pdo->quote($val);
                    }, $values);
                    
                    $sql .= "INSERT INTO `$table` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $escapedValues) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $filename = 'backup_' . date('Y_m_d_His') . '.sql';
        $filepath = $this->backupDir . $filename;
        
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }

        file_put_contents($filepath, $sql);

        if ($download) {
            Logger::log('MANUAL_BACKUP', 'Database', null, ['message' => "Manuel yedek indirildi: $filename"]);
            
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        }

        return $filepath;
    }
}
