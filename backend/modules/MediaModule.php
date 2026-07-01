<?php

require_once __DIR__ . '/BaseModule.php';
require_once __DIR__ . '/../models/SiteGallery.php';
require_once __DIR__ . '/../core/ImageOptimizer.php';

class MediaModule extends BaseModule
{
    private string $uploadDir;
    private string $thumbDir;
    private SiteGallery $galleryModel;

    public function __construct(array $app, array $department, array $user, string $userRole)
    {
        parent::__construct($app, $department, $user, $userRole);
        $this->uploadDir = __DIR__ . '/../public/uploads/gallery';
        $this->thumbDir = __DIR__ . '/../public/uploads/gallery_thumbs';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
        if (!is_dir($this->thumbDir)) {
            mkdir($this->thumbDir, 0777, true);
        }
        $this->galleryModel = new SiteGallery();
    }

    public function render(): string
    {
        $folders = [];
        $selectedFolder = $_GET['folder'] ?? null;
        $files = [];
        $mediaPage = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = ImageOptimizer::canResizeImages() ? 48 : 24;
        $totalFiles = 0;

        if (is_dir($this->uploadDir)) {
            foreach (scandir($this->uploadDir) as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                if (is_dir($this->uploadDir . '/' . $item)) {
                    $folders[] = $item;
                }
            }
        }

        if ($selectedFolder && in_array($selectedFolder, $folders, true)) {
            $folderPath = $this->uploadDir . '/' . $selectedFolder;
            $folderFiles = [];
            foreach (scandir($folderPath) as $fileName) {
                if ($fileName === '.' || $fileName === '..') {
                    continue;
                }
                if (is_file($folderPath . '/' . $fileName)) {
                    $folderFiles[] = [
                        'name' => $fileName,
                        'url' => $this->galleryUrl($selectedFolder, $fileName),
                        'thumb_url' => $this->thumbnailUrl($selectedFolder, $fileName),
                        'path' => $folderPath . '/' . $fileName,
                        'mtime' => filemtime($folderPath . '/' . $fileName) ?: 0,
                    ];
                }
            }

            usort($folderFiles, fn($a, $b) => $b['mtime'] <=> $a['mtime']);
            $totalFiles = count($folderFiles);
            $files = array_slice($folderFiles, ($mediaPage - 1) * $perPage, $perPage);
        }

        return $this->renderView('media_admin', [
            'folders' => $folders,
            'selectedFolder' => $selectedFolder,
            'files' => $files,
            'totalFiles' => $totalFiles,
            'mediaPage' => $mediaPage,
            'perPage' => $perPage,
            'hasMoreFiles' => ($mediaPage * $perPage) < $totalFiles,
            'canGenerateThumbnails' => ImageOptimizer::canResizeImages(),
            'department' => $this->department,
            'app' => $this->app,
        ]);
    }

    public function handlePost(array $postData): void
    {
        $action = $postData['action'] ?? '';
        $folderName = $this->sanitizeFolderName(trim($postData['folder_name'] ?? ''));

        if ($action === 'create_folder') {
            if ($folderName !== '') {
                $path = $this->uploadDir . '/' . $folderName;
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                    $_SESSION['success_msg'] = 'Klasor olusturuldu.';
                } else {
                    $_SESSION['error_msg'] = 'Bu klasor zaten var.';
                }
            }
            header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/media");
            exit;
        }

        if ($action === 'upload_files') {
            $this->handleUpload($folderName);
        }

        if ($action === 'delete_file') {
            $fileName = basename($postData['file_name'] ?? '');
            if ($folderName !== '' && $fileName !== '') {
                $path = $this->uploadDir . '/' . $folderName . '/' . $fileName;
                if (is_file($path)) {
                    unlink($path);
                    $this->deleteThumbnail($folderName, $fileName);
                    foreach ($this->galleryModel->getAllImages() as $image) {
                        if (strpos($image['image_url'], $fileName) !== false) {
                            $this->galleryModel->deleteImage((int) $image['id']);
                        }
                    }
                    $_SESSION['success_msg'] = 'Gorsel silindi.';
                }
            }
            header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/media?folder=" . urlencode($folderName));
            exit;
        }

        if ($action === 'delete_files_bulk') {
            $fileNames = $postData['files'] ?? [];
            if ($folderName !== '' && !empty($fileNames) && is_array($fileNames)) {
                $deletedCount = 0;
                foreach ($fileNames as $fName) {
                    $fileName = basename($fName);
                    if ($fileName !== '') {
                        $path = $this->uploadDir . '/' . $folderName . '/' . $fileName;
                        if (is_file($path)) {
                            unlink($path);
                            $this->deleteThumbnail($folderName, $fileName);
                            foreach ($this->galleryModel->getAllImages() as $image) {
                                if (strpos($image['image_url'], $fileName) !== false) {
                                    $this->galleryModel->deleteImage((int) $image['id']);
                                }
                            }
                            $deletedCount++;
                        }
                    }
                }
                $_SESSION['success_msg'] = $deletedCount . ' gorsel silindi.';
            }
            header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/media?folder=" . urlencode($folderName));
            exit;
        }

        if ($action === 'delete_folder') {
            if ($folderName !== '') {
                $path = $this->uploadDir . '/' . $folderName;
                if (is_dir($path)) {
                    foreach (scandir($path) as $fileName) {
                        if ($fileName === '.' || $fileName === '..') {
                            continue;
                        }
                        foreach ($this->galleryModel->getAllImages() as $image) {
                            if (strpos($image['image_url'], $fileName) !== false) {
                                $this->galleryModel->deleteImage((int) $image['id']);
                            }
                        }
                        $filePath = $path . '/' . $fileName;
                        if (is_file($filePath)) {
                            unlink($filePath);
                            $this->deleteThumbnail($folderName, $fileName);
                        }
                    }
                    rmdir($path);
                    $_SESSION['success_msg'] = 'Klasor silindi.';
                }
            }
            header("Location: " . BASE_URL . "/panel/" . $this->department['slug'] . "/apps/media");
            exit;
        }
    }

    private function handleUpload(string $folderName): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($folderName === '' || !is_dir($this->uploadDir . '/' . $folderName)) {
            echo json_encode(['success' => false, 'message' => 'Gecersiz klasor.']);
            exit;
        }

        if (empty($_FILES['files']['name'][0])) {
            echo json_encode(['success' => false, 'message' => 'Dosya secilmedi.']);
            exit;
        }

        $uploadedCount = 0;
        $failedCount = 0;
        $totalFiles = count($_FILES['files']['name']);

        for ($i = 0; $i < $totalFiles; $i++) {
            if ($_FILES['files']['error'][$i] !== UPLOAD_ERR_OK) {
                $failedCount++;
                continue;
            }

            $tmpName = $_FILES['files']['tmp_name'][$i];
            $originalName = basename($_FILES['files']['name'][$i]);

            if (!getimagesize($tmpName)) {
                $failedCount++;
                continue;
            }

            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $filenameNoExt = pathinfo($originalName, PATHINFO_FILENAME);
            $isSupportedImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
            $finalExt = ($isSupportedImage && ImageOptimizer::canOptimizeImages()) ? 'webp' : $ext;
            $finalName = StringUtils::slugify($filenameNoExt) . '-' . time() . rand(100, 999) . '.' . $finalExt;
            $destPath = $this->uploadDir . '/' . $folderName . '/' . $finalName;

            if (!ImageOptimizer::optimizeAndSave($tmpName, $destPath)) {
                $failedCount++;
                continue;
            }

            $uploadedCount++;
            $this->ensureThumbnail($folderName, $finalName);
            $this->galleryModel->createImage([
                'image_url' => $this->galleryUrl($folderName, $finalName),
                'category' => $folderName,
                'title' => '',
                'span_class' => '',
            ]);
        }

        $message = "$uploadedCount dosya yuklendi.";
        if ($failedCount > 0) {
            $message .= " $failedCount dosya yuklenemedi.";
        }

        echo json_encode(['success' => $uploadedCount > 0, 'message' => $message]);
        exit;
    }

    private function sanitizeFolderName(string $folderName): string
    {
        return trim(preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $folderName));
    }

    private function galleryUrl(string $folder, string $file): string
    {
        return BASE_URL . '/public/uploads/gallery/' . rawurlencode($folder) . '/' . rawurlencode($file);
    }

    private function thumbnailUrl(string $folder, string $file): string
    {
        if (!$this->ensureThumbnail($folder, $file)) {
            return $this->galleryUrl($folder, $file);
        }

        return BASE_URL . '/public/uploads/gallery_thumbs/' . rawurlencode($folder) . '/' . rawurlencode($this->thumbnailName($file));
    }

    private function ensureThumbnail(string $folder, string $file): bool
    {
        if (!ImageOptimizer::canResizeImages()) {
            return false;
        }

        $sourcePath = $this->uploadDir . '/' . $folder . '/' . $file;
        if (!is_file($sourcePath)) {
            return false;
        }

        $folderThumbDir = $this->thumbDir . '/' . $folder;
        if (!is_dir($folderThumbDir)) {
            mkdir($folderThumbDir, 0777, true);
        }

        $thumbPath = $folderThumbDir . '/' . $this->thumbnailName($file);
        if (is_file($thumbPath) && filemtime($thumbPath) >= filemtime($sourcePath)) {
            return true;
        }

        return ImageOptimizer::createThumbnail($sourcePath, $thumbPath);
    }

    private function thumbnailName(string $file): string
    {
        return pathinfo($file, PATHINFO_FILENAME) . '-thumb.' . (ImageOptimizer::canOptimizeImages() ? 'webp' : 'jpg');
    }

    private function deleteThumbnail(string $folder, string $file): void
    {
        $baseName = pathinfo($file, PATHINFO_FILENAME);
        foreach (['webp', 'jpg'] as $ext) {
            $thumbPath = $this->thumbDir . '/' . $folder . '/' . $baseName . '-thumb.' . $ext;
            if (is_file($thumbPath)) {
                unlink($thumbPath);
            }
        }
    }
}
