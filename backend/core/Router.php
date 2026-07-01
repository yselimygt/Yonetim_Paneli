<?php

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes[] = ['GET', $path, $handler];
    }

    public function post(string $path, array $handler): void
    {
        $this->routes[] = ['POST', $path, $handler];
    }

    public function dispatch(string $uri, string $method): void
    {
        // Query string'i kaldır
        $uri = parse_url($uri, PHP_URL_PATH);

        // BASE_URL veya PUBLIC_URL prefix'ini kaldır
        $basePath = parse_url(BASE_URL, PHP_URL_PATH);
        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        } else {
            $publicPath = parse_url(PUBLIC_URL, PHP_URL_PATH);
            if ($publicPath && str_starts_with($uri, $publicPath)) {
                $uri = substr($uri, strlen($publicPath));
            }
        }

        $uri = '/' . ltrim($uri, '/');

        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            // Dinamik segment dönüşümü: {slug} → regex grubu
            $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $routePath);
            $pattern = '@^' . $pattern . '$@';

            if ($routeMethod === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // tam eşleşmeyi kaldır
                [$controllerClass, $methodName] = $handler;

                require_once __DIR__ . '/../controllers/' . $controllerClass . '.php';
                $controller = new $controllerClass();
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        // 404
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
    }
}
