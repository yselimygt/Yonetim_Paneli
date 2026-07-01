<?php

require_once __DIR__ . '/BaseModule.php';
require_once __DIR__ . '/../models/SiteGallery.php';
require_once __DIR__ . '/../core/ImageOptimizer.php';

class MediaModule extends BaseModule
{
    private $uploadDir;
    private SiteGallery $galleryModel;

    public function __construct(array $app, array $department, array $user, string $userRole)
    {
        parent::__construct($app, $department, $user, $userRole);
        $this->uploadDir = __DIR__ . '/../../uploads/gallery';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
        $this->galleryModel = new SiteGallery();
    }

    public function render(): string
    {
        $folders = [];
        $selectedFolder = $_GET['folder'] ?? null;
        $files = [];

        if (is_dir($this->uploadDir)) {
            $items = scandir($this->uploadDir);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                if (is_dir($this->uploadDir . '/' . $item)) {
                    $folders[] = $item;
                }
            }
        }

        if ($selectedFolder && in_array($selectedFolder, $folders)) {
            $folderPath = $this->uploadDir . '/' . $selectedFolder;
            $fItems = scandir($folderPath);
            foreach ($fItems as $fItem) {
                if ($fItem === '.' || $fItem === '..') continue;
                if (is_file($folderPath . '/' . $fItem)) {
                    $files[] = [
                        'name' => $fItem,
                        'url' => BASE_URL . '/../uploads/gallery/' . rawurlencode($selectedFolder) . '/' . rawurlencode($fItem),
                        'path' => $folderPath . '/' . $fItem
                    ];
                }
            }
        }

        return $this->renderView('media_admin', [
            'folders' => $folders,
            'selectedFolder' => $selectedFolder,
            'files' => $files,
            'department' => $this->department,
            'app' => $this->app
        ]);
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $folderName = trim($postData['folder_name'] ?? '');

        if ($action === 'create_folder') {
            $folderName = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $folderName);
            $folderName = trim($folderName);
            if (!empty($folderName)) {
                $path = $this->uploadDir . '/' . $folderName;
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                    $_SESSION['success_msg'] = 'Klasör oluşturuldu.';
                } else {
                    $_SESSION['error_msg'] = 'Bu klasör zaten var.';
                }
            }
            header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/media");
            exit;
        } 
        elseif ($action === 'upload_files') {
            // Dropzone AJAX upload
            if (empty($folderName) || !is_dir($this->uploadDir . '/' . $folderName)) {
                echo json_encode(['success' => false, 'message' => 'Geçersiz klasör.']);
                exit;
            }

            if (empty($_FILES['files']['name'][0])) {
                echo json_encode(['success' => false, 'message' => 'Dosya seçilmedi.']);
                exit;
            }

            $uploadedCount = 0;
            $totalFiles = count($_FILES['files']['name']);
            
            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES['files']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['files']['tmp_name'][$i];
                    $name = basename($_FILES['files']['name'][$i]);
                    
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $filenameNoExt = pathinfo($name, PATHINFO_FILENAME);
                    
                    $finalExt = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? 'webp' : $ext;
                    $finalName = StringUtils::slugify($filenameNoExt) . '-' . time() . rand(100,999) . '.' . $finalExt;
                    $destPath = $this->uploadDir . '/' . $folderName . '/' . $finalName;
                    
                    if (ImageOptimizer::optimizeAndSave($tmpName, $destPath)) {
                        $uploadedCount++;
                        $imageUrl = BASE_URL . '/../uploads/gallery/' . rawurlencode($folderName) . '/' . rawurlencode($finalName);
                        $this->galleryModel->createImage([
                            'image_url' => $imageUrl,
                            'category' => $folderName,
                            'title' => '',
                            'span_class' => ''
                        ]);
                    }
                }
            }
            echo json_encode(['success' => true, 'message' => "$uploadedCount dosya yüklendi."]);
            exit;
        }
        elseif ($action === 'delete_file') {
            $fileName = $postData['file_name'] ?? '';
            if (!empty($folderName) && !empty($fileName)) {
                $path = $this->uploadDir . '/' . $folderName . '/' . $fileName;
                if (file_exists($path)) {
                    unlink($path);
                    $dbImages = $this->galleryModel->getAllImages();
                    foreach($dbImages as $img) {
                        if (strpos($img['image_url'], $fileName) !== false) {
                            $this->galleryModel->deleteImage($img['id']);
                        }
                    }
                    $_SESSION['success_msg'] = 'Görsel silindi.';
                }
            }
            header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/media?folder=" . urlencode($folderName));
            exit;
        }
        elseif ($action === 'delete_folder') {
            if (!empty($folderName)) {
                $path = $this->uploadDir . '/' . $folderName;
                if (is_dir($path)) {
                    $files = scandir($path);
                    foreach ($files as $file) {
                        if ($file !== '.' && $file !== '..') {
                            $dbImages = $this->galleryModel->getAllImages();
                            foreach($dbImages as $img) {
                                if (strpos($img['image_url'], $file) !== false) {
                                    $this->galleryModel->deleteImage($img['id']);
                                }
                            }
                            unlink($path . '/' . $file);
                        }
                    }
                    rmdir($path);
                    $_SESSION['success_msg'] = 'Klasör silindi.';
                }
            }
            header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/media");
            exit;
        }
    }
}
