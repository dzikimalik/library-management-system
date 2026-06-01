<?php
require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/Middleware.php';

class Router
{
    private static $routes = [];

    public static function add($method, $route, $controller, $action)
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'route' => $route,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public static function run()
    {
        $url = self::getUrl();
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            $pattern = self::buildPattern($route['route']);
            if ($route['method'] === strtoupper($method) && preg_match($pattern, $url, $matches)) {
                array_shift($matches);
                self::dispatch($route['controller'], $route['action'], $matches);
                return;
            }
        }

        header('HTTP/1.0 404 Not Found');
        echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
        echo '<p>Halaman yang Anda cari tidak tersedia.</p>';
        echo '<a href="' . BASE_URL . '">Kembali ke Beranda</a>';
        exit;
    }

    public static function url($path = '')
    {
        return BASE_URL . ltrim($path, '/');
    }

    private static function getUrl()
    {
        $url = '';
        if (isset($_GET['url']) && $_GET['url'] !== '') {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return $url;
        }

        $uri = $_SERVER['REQUEST_URI'];
        $base = parse_url(BASE_URL, PHP_URL_PATH);
        $base = rtrim($base, '/');
        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');
        return $uri;
    }

    private static function buildPattern($route)
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    private static function dispatch($controller, $action, $params = [])
    {
        $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $instance = new $controller();
            call_user_func_array([$instance, $action], $params);
        } else {
            header('HTTP/1.0 500 Internal Server Error');
            die("Controller '$controller' tidak ditemukan");
        }
    }
}
