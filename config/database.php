<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'perpusatadei');

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . $base . '/');
define('SESSION_TIMEOUT', 3600);
