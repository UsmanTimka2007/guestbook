<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  require_once 'config.php';
  unset($_SESSION['user']); // удаляем хеш из сессии
  $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
  header('Location: ' . $redirect);
} else {
  $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';

  header('Location: ' . $redirect);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Вы уверены?</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
   <form>
    <button>Yes</button>
    <button>No</button>
   </form> 
</body>
</html>
