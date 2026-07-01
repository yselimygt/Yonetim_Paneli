<?php

require_once __DIR__ . '/../models/SiteGallery.php';

class GlobalGalleryController extends Controller
{
    private $uploadDir;
    private SiteGallery $galleryModel;

    public function __construct()
    {
        // Root dizindeki uploads klasörü
        $this->uploadDir = __DIR__ . '/../../uploads/gallery';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
        $this->galleryModel = new SiteGallery();
    }

    public function index()
    {
        Auth::requireSuperAdmin();
        
        $folders = [];
        $selectedFolder = $_GET['folder'] ?? null;
        $files = [];

        // Scan directories
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

        $this->view('superadmin/global_gallery/index', [
            'folders' => $folders,
            'selectedFolder' => $selectedFolder,
            'files' => $files
        ], 'superadmin');
    }

    public function createFolder()
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $folderName = trim($_POST['folder_name'] ?? '');
        $folderName = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $folderName); // basic sanitize
        $folderName = trim($folderName);

        if (empty($folderName)) {
            $this->flashError('Geçersiz klasör adı.');
        } else {
            $path = $this->uploadDir . '/' . $folderName;
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                $this->flashSuccess('Klasör oluşturuldu.');
            } else {
                $this->flashError('Bu klasör zaten var.');
            }
        }

        $this->redirect('/superadmin/global-gallery');
    }

    public function uploadFiles()
    {
        Auth::requireSuperAdmin();
        
        $folderName = trim($_POST['folder_name'] ?? '');
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
                    // Add to SiteGallery
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

        echo json_encode(['success' => true, 'message' => "$uploadedCount dosya yüklendi ve site galerisine eklendi."]);
        exit;
    }

    public function deleteFile()
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $folderName = $_POST['folder_name'] ?? '';
        $fileName = $_POST['file_name'] ?? '';

        if (!empty($folderName) && !empty($fileName)) {
            $path = $this->uploadDir . '/' . $folderName . '/' . $fileName;
            if (file_exists($path)) {
                unlink($path);
                
                // Delete from SiteGallery db
                $dbImages = $this->galleryModel->getAllImages();
                foreach($dbImages as $img) {
                    if (strpos($img['image_url'], $fileName) !== false) {
                        $this->galleryModel->deleteImage($img['id']);
                    }
                }
                
                $this->flashSuccess('Görsel silindi.');
            }
        }

        $this->redirect('/superadmin/global-gallery?folder=' . urlencode($folderName));
    }
    
    public function deleteFolder()
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $folderName = $_POST['folder_name'] ?? '';
        
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
                $this->flashSuccess('Klasör ve içindeki görseller silindi.');
            }
        }

        $this->redirect('/superadmin/global-gallery');
    }
}
