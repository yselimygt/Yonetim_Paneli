<?php

class ReportModule extends BaseModule
{
    public function render(): string
    {
        // 1. Departmandaki tüm uygulamaları çek (CSV indirme seçeneği için)
        $stmtApps = $this->pdo->prepare("SELECT id, name, module_type, slug FROM apps WHERE department_id = ? AND is_active = 1 ORDER BY name ASC");
        $stmtApps->execute([$this->department['id']]);
        $deptApps = $stmtApps->fetchAll();

        // 2. Finans İstatistikleri
        $financeStats = ['income' => 0, 'expense' => 0, 'balance' => 0];
        $stmtFin = $this->pdo->prepare("SELECT type, SUM(amount) as total FROM finance_transactions WHERE department_id = ? GROUP BY type");
        $stmtFin->execute([$this->department['id']]);
        while($row = $stmtFin->fetch()) {
            if($row['type'] === 'income') $financeStats['income'] = (float)$row['total'];
            if($row['type'] === 'expense') $financeStats['expense'] = (float)$row['total'];
        }
        $financeStats['balance'] = $financeStats['income'] - $financeStats['expense'];

        // 3. Üye İstatistikleri
        $memberStats = ['total' => 0, 'active' => 0, 'passive' => 0, 'by_department' => []];
        $stmtMem = $this->pdo->prepare("SELECT status, department, COUNT(*) as cnt FROM app_members WHERE department_id = ? GROUP BY status, department");
        $stmtMem->execute([$this->department['id']]);
        while($row = $stmtMem->fetch()) {
            $cnt = (int)$row['cnt'];
            $memberStats['total'] += $cnt;
            if($row['status'] === 'active') $memberStats['active'] += $cnt;
            else $memberStats['passive'] += $cnt;
            
            $dep = $row['department'] ?: 'Belirtilmemiş';
            if(!isset($memberStats['by_department'][$dep])) $memberStats['by_department'][$dep] = 0;
            $memberStats['by_department'][$dep] += $cnt;
        }
        arsort($memberStats['by_department']); // Büyükten küçüğe

        // 4. Envanter İstatistikleri
        $inventoryStats = ['total_items' => 0, 'total_value' => 0, 'broken' => 0, 'by_category' => []];
        $stmtInv = $this->pdo->prepare("SELECT status, price, category FROM app_inventory WHERE department_id = ?");
        $stmtInv->execute([$this->department['id']]);
        while($row = $stmtInv->fetch()) {
            $inventoryStats['total_items']++;
            $inventoryStats['total_value'] += (float)$row['price'];
            if($row['status'] === 'broken') $inventoryStats['broken']++;
            
            $cat = $row['category'] ?: 'Diğer';
            if(!isset($inventoryStats['by_category'][$cat])) $inventoryStats['by_category'][$cat] = 0;
            $inventoryStats['by_category'][$cat]++;
        }
        arsort($inventoryStats['by_category']);

        // 5. Bootcamp İstatistikleri
        $bootcampStats = ['total' => 0, 'active' => 0, 'by_category' => []];
        $stmtBc = $this->pdo->prepare("SELECT status, category FROM app_bootcamps WHERE department_id = ?");
        $stmtBc->execute([$this->department['id']]);
        while($row = $stmtBc->fetch()) {
            $bootcampStats['total']++;
            if((int)$row['status'] === 1) $bootcampStats['active']++;

            $cat = $row['category'] ?: 'Belirtilmemiş';
            if(!isset($bootcampStats['by_category'][$cat])) $bootcampStats['by_category'][$cat] = 0;
            $bootcampStats['by_category'][$cat]++;
        }
        arsort($bootcampStats['by_category']);

        // 6. Etkinlik İstatistikleri
        $eventStats = ['total' => 0, 'physical' => 0, 'online' => 0];
        $stmtEv = $this->pdo->prepare("SELECT type FROM app_events WHERE department_id = ?");
        $stmtEv->execute([$this->department['id']]);
        while($row = $stmtEv->fetch()) {
            $eventStats['total']++;
            if($row['type'] === 'physical') $eventStats['physical']++;
            else if($row['type'] === 'online') $eventStats['online']++;
        }

        // 7. Form İstatistikleri
        $formStats = [];
        $stmtForms = $this->pdo->prepare("
            SELECT a.name, COUNT(fe.id) as entry_count 
            FROM apps a 
            LEFT JOIN form_entries fe ON a.id = fe.app_id 
            WHERE a.department_id = ? AND a.module_type = 'form' AND a.is_active = 1
            GROUP BY a.id
            ORDER BY entry_count DESC
            LIMIT 10
        ");
        $stmtForms->execute([$this->department['id']]);
        while($row = $stmtForms->fetch()) {
            $formStats[$row['name']] = (int)$row['entry_count'];
        }

        // 8. Partner/Sponsor İstatistikleri
        $partnerStats = ['active' => 0, 'inactive' => 0];
        $stmtPt = $this->pdo->prepare("SELECT status FROM app_partners WHERE department_id = ?");
        $stmtPt->execute([$this->department['id']]);
        while($row = $stmtPt->fetch()) {
            if((int)$row['status'] === 1) $partnerStats['active']++;
            else $partnerStats['inactive']++;
        }

        return $this->renderView('report', compact('deptApps', 'financeStats', 'memberStats', 'inventoryStats', 'bootcampStats', 'eventStats', 'formStats', 'partnerStats'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        
        if ($action === 'export_csv') {
            $appId = (int)($postData['export_app_id'] ?? 0);
            if ($appId > 0) {
                $this->exportAppCsv($appId);
            }
        }
    }

    private function exportAppCsv(int $appId): void
    {
        // Yetki kontrolü (sadece bu departmana ait uygulamayı indirebilir)
        $stmt = $this->pdo->prepare("SELECT name, module_type FROM apps WHERE id = ? AND department_id = ? LIMIT 1");
        $stmt->execute([$appId, $this->department['id']]);
        $app = $stmt->fetch();

        if (!$app) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Uygulama bulunamadı.'];
            header("Location: " . BASE_URL . "/panel/{$this->department['slug']}/apps/{$this->app['slug']}");
            exit;
        }

        $type = $app['module_type'];
        $filename = "Export_{$type}_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        // UTF-8 BOM for Excel
        echo "\xEF\xBB\xBF"; 
        
        $output = fopen('php://output', 'w');

        if ($type === 'form') {
            fputcsv($output, ['ID', 'Tarih', 'Veriler (JSON)']);
            $q = $this->pdo->prepare("SELECT id, created_at, data FROM form_entries WHERE app_id = ? ORDER BY id DESC");
            $q->execute([$appId]);
            while($row = $q->fetch()) fputcsv($output, [$row['id'], $row['created_at'], $row['data']]);
            
        } elseif ($type === 'members') {
            fputcsv($output, ['Ad', 'Soyad', 'E-posta', 'Öğrenci No', 'Üniversite', 'Fakülte', 'Bölüm', 'Sınıf', 'Telefon', 'Durum']);
            $q = $this->pdo->prepare("SELECT first_name, last_name, email, student_id, university, faculty, department, grade, phone, status FROM app_members WHERE app_id = ? ORDER BY id DESC");
            $q->execute([$appId]);
            while($row = $q->fetch()) fputcsv($output, $row);

        } elseif ($type === 'finance') {
            fputcsv($output, ['Tip', 'Miktar', 'Kategori', 'Tarih', 'Açıklama']);
            $q = $this->pdo->prepare("SELECT type, amount, category, transaction_date, description FROM finance_transactions WHERE app_id = ? ORDER BY transaction_date DESC");
            $q->execute([$appId]);
            while($row = $q->fetch()) {
                $row['type'] = ($row['type'] === 'income') ? 'Gelir' : 'Gider';
                fputcsv($output, $row);
            }

        } elseif ($type === 'inventory') {
            fputcsv($output, ['Eşya Adı', 'Kategori', 'Seri No', 'Konum', 'Zimmet', 'Durum', 'Fiyat']);
            $q = $this->pdo->prepare("SELECT item_name, category, serial_number, location, assigned_to, status, price FROM app_inventory WHERE app_id = ? ORDER BY item_name ASC");
            $q->execute([$appId]);
            while($row = $q->fetch()) fputcsv($output, $row);
            
        } elseif ($type === 'bootcamp') {
            fputcsv($output, ['Başlık', 'Kategori', 'Eğitmen', 'Başlangıç', 'Bitiş', 'Durum']);
            $q = $this->pdo->prepare("SELECT title, category, instructor, start_date, end_date, status FROM app_bootcamps WHERE department_id = ? ORDER BY start_date DESC");
            $q->execute([$this->department['id']]);
            while($row = $q->fetch()) {
                $row['status'] = $row['status'] ? 'Aktif' : 'Pasif';
                fputcsv($output, $row);
            }
        } else {
            fputcsv($output, ['Bu modül için dışa aktarma henüz desteklenmiyor.']);
        }

        fclose($output);
        exit;
    }
}
