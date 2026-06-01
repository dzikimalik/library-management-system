<?php
require_once __DIR__ . '/../config/database.php';

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        self::checkTimeout();
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }

    public static function checkTimeout()
    {
        if (isset($_SESSION['last_activity'])) {
            $inactive = time() - $_SESSION['last_activity'];
            if ($inactive > SESSION_TIMEOUT) {
                self::destroy();
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
        }
        $_SESSION['last_activity'] = time();
    }

    public static function regenerate()
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_regenerate_id(true);
        }
    }
}
