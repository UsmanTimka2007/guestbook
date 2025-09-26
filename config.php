<?php
$host = 'localhost';
$db   = 'guestbook_db';
$user = 'root';
$pass = '123qwe!@#';

try {
	$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die('Connection err: ' . $e->getMessage());
}
?>
