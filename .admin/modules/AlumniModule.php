<?php

require_once __DIR__ . '/BaseModule.php';

class AlumniModule extends BaseModule
{
    public function render(): string
    {
        $search = trim($_GET['q'] ?? '');
        $mentorOnly = isset($_GET['mentor']) && $_GET['mentor'] == '1';
        $company = trim($_GET['company'] ?? '');
        $gradYear = trim($_GET['year'] ?? '');

        $params = [];
        $whereSql = "1=1";

        if ($search !== '') {
            $whereSql .= " AND (full_name LIKE ? OR email LIKE ? OR university LIKE ? OR major LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($mentorOnly) {
            $whereSql .= " AND is_mentor = 1";
        }
        if ($company !== '') {
            $whereSql .= " AND company LIKE ?";
            $params[] = "%$company%";
        }
        if ($gradYear !== '') {
            $whereSql .= " AND graduation_year = ?";
            $params[] = $gradYear;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM alumni_profiles WHERE $whereSql ORDER BY graduation_year DESC, full_name ASC");
        $stmt->execute($params);
        $alumni = $stmt->fetchAll();

        // Benzersiz şirket ve mezuniyet yıllarını filtreleme için alalım
        $compStmt = $this->pdo->prepare("SELECT DISTINCT company FROM alumni_profiles WHERE company IS NOT NULL AND company != '' ORDER BY company ASC");
        $compStmt->execute();
        $companies = $compStmt->fetchAll(PDO::FETCH_COLUMN);

        $yearStmt = $this->pdo->prepare("SELECT DISTINCT graduation_year FROM alumni_profiles WHERE graduation_year IS NOT NULL ORDER BY graduation_year DESC");
        $yearStmt->execute();
        $years = $yearStmt->fetchAll(PDO::FETCH_COLUMN);

        // Fetch jobs
        $jobStmt = $this->pdo->prepare("SELECT j.*, a.full_name as posted_by_name, a.avatar_path as posted_by_avatar FROM alumni_jobs j LEFT JOIN alumni_profiles a ON j.posted_by_alumni_id = a.id ORDER BY j.created_at DESC");
        $jobStmt->execute();
        $jobs = $jobStmt->fetchAll();

        return $this->renderView('alumni_admin', compact('alumni', 'search', 'mentorOnly', 'company', 'gradYear', 'companies', 'years', 'jobs'));
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';

        if ($action === 'create_alumni' || $action === 'update_alumni') {
            $id = !empty($postData['id']) ? (int)$postData['id'] : null;
            $fullName = trim($postData['full_name'] ?? '');
            $email = trim($postData['email'] ?? '');
            $phone = trim($postData['phone'] ?? '');
            $gradYear = !empty($postData['graduation_year']) ? (int)$postData['graduation_year'] : null;
            $university = trim($postData['university'] ?? '');
            $major = trim($postData['major'] ?? '');
            $company = trim($postData['company'] ?? '');
            $jobTitle = trim($postData['job_title'] ?? '');
            $location = trim($postData['location'] ?? '');
            $linkedinUrl = trim($postData['linkedin_url'] ?? '');
            $bio = trim($postData['bio'] ?? '');
            $isMentor = isset($postData['is_mentor']) ? 1 : 0;

            // Handle Avatar Upload
            $avatarPath = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../public/uploads/alumni/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileName = $_FILES['avatar']['name'];
                $fileTmp = $_FILES['avatar']['tmp_name'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($ext, $allowed)) {
                    $newFileName = uniqid() . '_' . rand(1000, 9999) . '.' . $ext;
                    $destPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmp, $destPath)) {
                        $avatarPath = 'uploads/alumni/' . $newFileName;
                    }
                }
            }

            if ($action === 'create_alumni') {
                $stmt = $this->pdo->prepare("INSERT INTO alumni_profiles (app_id, department_id, full_name, email, phone, graduation_year, university, major, company, job_title, location, linkedin_url, bio, is_mentor, avatar_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $this->app['id'],
                    $this->department['id'],
                    $fullName, $email, $phone, $gradYear, $university, $major, $company, $jobTitle, $location, $linkedinUrl, $bio, $isMentor, $avatarPath
                ]);
                $_SESSION['success_msg'] = "Mezun profili başarıyla eklendi.";
            } else {
                $params = [
                    $fullName, $email, $phone, $gradYear, $university, $major, $company, $jobTitle, $location, $linkedinUrl, $bio, $isMentor
                ];
                
                $sql = "UPDATE alumni_profiles SET full_name=?, email=?, phone=?, graduation_year=?, university=?, major=?, company=?, job_title=?, location=?, linkedin_url=?, bio=?, is_mentor=?";
                
                if ($avatarPath !== null) {
                    $sql .= ", avatar_path=?";
                    $params[] = $avatarPath;
                }
                
                $sql .= " WHERE id=?";
                $params[] = $id;

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
                $_SESSION['success_msg'] = "Mezun profili güncellendi.";
            }
        } 
        elseif ($action === 'delete_alumni') {
            $id = (int)($postData['item_id'] ?? 0);
            
            // Delete avatar physical file if exists
            $stmt = $this->pdo->prepare("SELECT avatar_path FROM alumni_profiles WHERE id = ?");
            $stmt->execute([$id]);
            $avatar = $stmt->fetchColumn();
            if ($avatar && file_exists(__DIR__ . '/../public/' . $avatar)) {
                unlink(__DIR__ . '/../public/' . $avatar);
            }

            $stmt = $this->pdo->prepare("DELETE FROM alumni_profiles WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success_msg'] = "Mezun kaydı silindi.";
        }
        elseif ($action === 'create_job') {
            $title = trim($postData['title'] ?? '');
            $company = trim($postData['company'] ?? '');
            $jobType = trim($postData['job_type'] ?? 'İş');
            $location = trim($postData['location'] ?? '');
            $applyUrl = trim($postData['apply_url'] ?? '');
            $desc = trim($postData['description'] ?? '');
            $postedBy = !empty($postData['posted_by_alumni_id']) ? (int)$postData['posted_by_alumni_id'] : null;

            $stmt = $this->pdo->prepare("INSERT INTO alumni_jobs (app_id, department_id, posted_by_alumni_id, title, company, job_type, location, apply_url, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$this->app['id'], $this->department['id'], $postedBy, $title, $company, $jobType, $location, $applyUrl, $desc]);
            $_SESSION['success_msg'] = "İlan başarıyla eklendi.";
        }
        elseif ($action === 'delete_job') {
            $id = (int)($postData['item_id'] ?? 0);
            $stmt = $this->pdo->prepare("DELETE FROM alumni_jobs WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success_msg'] = "İlan silindi.";
        }
        elseif ($action === 'toggle_spotlight') {
            $id = (int)($postData['item_id'] ?? 0);
            $val = (int)($postData['spotlight_val'] ?? 0);
            $stmt = $this->pdo->prepare("UPDATE alumni_profiles SET is_spotlight = ? WHERE id = ?");
            $stmt->execute([$val, $id]);
            $_SESSION['success_msg'] = "Başarı hikayesi durumu güncellendi.";
        }
        elseif ($action === 'send_message') {
            $receiverId = (int)($postData['receiver_id'] ?? 0);
            $subject = trim($postData['subject'] ?? '');
            $message = trim($postData['message'] ?? '');
            $senderId = $this->user['id'] ?? null;

            $stmt = $this->pdo->prepare("INSERT INTO alumni_messages (app_id, sender_user_id, receiver_alumni_id, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$this->app['id'], $senderId, $receiverId, $subject, $message]);
            $_SESSION['success_msg'] = "Mesajınız mezuna iletildi.";
        }

        header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/" . $this->app['slug']);
        exit;
    }
}
