<?php
// Конфигурация подключения к БД
// Для XAMPP / OpenServer / phpMyAdmin по умолчанию:
define('DB_HOST', 'localhost');
define('DB_NAME', 'surfclub');
define('DB_USER', 'root');
define('DB_PASS', ''); // пустой пароль для локального XAMPP

session_start();

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die('Ошибка подключения к базе данных: ' . $e->getMessage() . 
                '<br><br>Проверьте, что база <b>surfclub</b> создана и импортирован файл sql/schema.sql в phpMyAdmin.');
        }
    }
    return $pdo;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function currentUser() {
    if (!isLoggedIn()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'login' => $_SESSION['login'],
        'fio' => $_SESSION['fio'],
        'role' => $_SESSION['role']
    ];
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
?>
