<?php

abstract class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function flashSuccess(string $message): void
    {
        $_SESSION['flash'] = ['type' => 'success', 'message' => $message];
    }

    protected function flashError(string $message): void
    {
        $_SESSION['flash'] = ['type' => 'error', 'message' => $message];
    }

    protected function flashWarning(string $message): void
    {
        $_SESSION['flash'] = ['type' => 'warning', 'message' => $message];
    }

    protected function csrfCheck(): void
    {
        if (
            empty($_POST['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
        ) {
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
            // fetch() API doesn't send X-Requested-With by default, but action=save_config is a strong hint
            if ($isAjax || (isset($_POST['action']) && in_array($_POST['action'], ['save_config', 'upload_banner']))) {
                $this->json(['success' => false, 'message' => 'Oturum süresi doldu veya geçersiz istek. Lütfen sayfayı yenileyin.'], 403);
            }
            $this->flashError('Geçersiz istek. Lütfen tekrar deneyin.');
            $this->redirect('/login');
        }
    }
}
