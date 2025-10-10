<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  require_once 'config.php';
  unset($_SESSION['user']); // удаляем хеш из сессии
  $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
  header('Location: ' . $redirect);
  $_SESSION['flash_success'] = 'Вы успешно вышли из системы!';
} else {
  $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
  header('Location: ' . $redirect);
}
?>

