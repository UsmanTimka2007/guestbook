<?php
// update.php
require_once 'config.php';

// Проверяем, что форма отправлена
if ($_POST) {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Валидация
    if (!$id || !is_numeric($id)) {
        die("Некорректный ID");
    }
    if (empty($name) || empty($message)) {
        die("Имя и сообщение не могут быть пустыми");
    }

    // Обновляем запись в базе
    $stmt = $pdo->prepare("UPDATE messages SET name = ?, message = ? WHERE id = ?");
    $result = $stmt->execute([$name, $message, $id]);

    if ($result) {
        // Успешно → перенаправляем на главную
        header('Location: index.php?success=1');
        exit;
    } else {
        die("Ошибка при обновлении");
    }
} else {
    // Если не POST — перенаправляем на главную
    header('Location: index.php');
    exit;
}
?>
