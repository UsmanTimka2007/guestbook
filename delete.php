<?php 

/* requiring config */
require_once("config.php");

/* debuging incorrect id's */
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
  die('Incorrext ID');
}

/* getting id */
$id = (int)$_GET['id'];

/* sending request to SQL */
$stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
$stmt->execute([$id]);

/* redirect to main page */
header('Location: index.php');
exit;
?>
