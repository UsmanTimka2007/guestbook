<?php
require_once('config.php');

if ($_POST) {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);

    if ($name && $message) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, message) VALUES (?,?)");
        $stmt->execute([$name, $message]);

        header('Location: index.php');
        exit;
    } else {
        $error = 'Please, fill all fields';
    }
}
