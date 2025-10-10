<?php 
  require_once 'config.php';
  unset($_SESSION['user']); // удаляем хеш из сессии
  $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
  header('Location: ' . $redirect);

?>
