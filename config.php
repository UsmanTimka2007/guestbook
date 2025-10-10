<?php

session_start();


try {
	$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die('Connection err: ' . $e->getMessage());
}

function isAdmin() {
  return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function isUser() {
  return isset($_SESSION['user']);
}

function requireLogin() {
    if (!isUser()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
}
function requireOwnerOrAdmin($user_id) {
    if (!isUser()) {
        header('Location: login.php');
        exit;
    }
    if (!isAdmin() && $_SESSION['user']['id'] != $user_id) {
        die("У вас нет прав на это действие");
    }
}

function getCurrentUserId() {
    return isUser() ? $_SESSION['user']['id'] : null;
}

?>
