<?php

class View
{
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // Veriyi kapsama aç
        extract($data, EXTR_SKIP);

        // View içeriğini buffer'a al
        ob_start();
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            ob_end_clean();
            die('View bulunamadı: ' . htmlspecialchars($view));
        }
        require $viewFile;
        $content = ob_get_clean();

        // Layout'a göm
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    public static function escape(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
