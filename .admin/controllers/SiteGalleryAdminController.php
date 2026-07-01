<?php

require_once __DIR__ . '/../models/SiteGallery.php';

class SiteGalleryAdminController extends Controller
{
    private SiteGallery $galleryModel;

    public function __construct()
    {
        $this->galleryModel = new SiteGallery();
    }

    // GET /superadmin/site-gallery
    public function index(): void
    {
        Auth::requireSuperAdmin();
        $images = $this->galleryModel->getAllImages();
        
        $this->view('superadmin/site_gallery/index', ['images' => $images], 'superadmin');
    }

    // POST /superadmin/site-gallery
    public function store(): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $data = [
            'image_url' => trim($_POST['image_url'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'span_class' => trim($_POST['span_class'] ?? '')
        ];

        if (empty($data['image_url'])) {
            $this->flashError('Resim URL si zorunludur.');
            $this->redirect('/superadmin/site-gallery');
            return;
        }

        $this->galleryModel->createImage($data);
        Logger::log('CREATE_SITE_GALLERY', 'SiteGallery', null, ['url' => $data['image_url']]);
        $this->flashSuccess('Resim başarıyla eklendi.');
        $this->redirect('/superadmin/site-gallery');
    }

    // POST /superadmin/site-gallery/{id}
    public function update(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $image = $this->galleryModel->getImageById($id);
        if (!$image) {
            $this->flashError('Resim bulunamadı.');
            $this->redirect('/superadmin/site-gallery');
            return;
        }

        $data = [
            'image_url' => trim($_POST['image_url'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'span_class' => trim($_POST['span_class'] ?? '')
        ];

        if (empty($data['image_url'])) {
            $this->flashError('Resim URL si zorunludur.');
            $this->redirect('/superadmin/site-gallery');
            return;
        }

        $this->galleryModel->updateImage($id, $data);
        Logger::log('UPDATE_SITE_GALLERY', 'SiteGallery', $id, ['url' => $data['image_url']]);
        $this->flashSuccess('Resim güncellendi.');
        $this->redirect('/superadmin/site-gallery');
    }

    // POST /superadmin/site-gallery/{id}/delete
    public function destroy(int $id): void
    {
        Auth::requireSuperAdmin();
        $this->csrfCheck();

        $image = $this->galleryModel->getImageById($id);
        if ($image) {
            $this->galleryModel->deleteImage($id);
            Logger::log('DELETE_SITE_GALLERY', 'SiteGallery', $id, ['url' => $image['image_url']]);
            $this->flashSuccess('Resim silindi.');
        } else {
            $this->flashError('Resim bulunamadı.');
        }

        $this->redirect('/superadmin/site-gallery');
    }
}
