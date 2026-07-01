<?php

abstract class BaseModule
{
    protected array $app;
    protected array $department;
    protected array $user;
    protected string $userRole;
    protected PDO $pdo;

    public function __construct(array $app, array $department, array $user, string $userRole)
    {
        $this->app        = $app;
        $this->department = $department;
        $this->user       = $user;
        $this->userRole   = $userRole;
        $this->pdo        = Database::getInstance();
    }

    /**
     * Render the module view content.
     */
    abstract public function render(): string;

    /**
     * Handle POST action submissions.
     */
    abstract public function handlePost(array $postData): void;

    /**
     * Get JSON configuration parsed as array.
     */
    protected function getConfig(): array
    {
        if (empty($this->app['config'])) {
            return [];
        }
        $config = json_decode($this->app['config'], true);
        return is_array($config) ? $config : [];
    }

    /**
     * Helper to check if current user is director or super admin.
     */
    protected function isDirectorOrAdmin(): bool
    {
        return $this->userRole === 'director' || !empty($this->user['is_super_admin']);
    }

    /**
     * Render a module view file from views/modules.
     */
    protected function renderView(string $view, array $data = []): string
    {
        // Add common data
        $data['app'] = $this->app;
        $data['department'] = $this->department;
        $data['user'] = $this->user;
        $data['userRole'] = $this->userRole;
        $data['isDirectorOrAdmin'] = $this->isDirectorOrAdmin();

        extract($data, EXTR_SKIP);
        ob_start();
        $viewFile = __DIR__ . '/../views/modules/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View file not found: " . htmlspecialchars($view);
        }
        return ob_get_clean();
    }
}
