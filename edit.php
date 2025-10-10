<!-- edit.php -->
<?php
require_once 'config.php';

// Получаем ID из URL (например: edit.php?id=5)
$id = $_GET['id'] ?? null;

// Проверяем, что ID — число
if (!$id || !is_numeric($id)) {
    die("Некорректный ID сообщения");
}

// Запрашиваем сообщение из базы
$stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->execute([$id]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);

// Если сообщение не найдено
if (!$message) {
    die("Сообщение не найдено");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>✏️ Редактировать сообщение</title>
    <link rel="stylesheet" href="Styles/edit_style.css">
</head>

<body>
    <div class="container">
        <h2>Редактировать сообщение</h2>

        <!-- Форма отправки на update.php -->
        <form action="update.php" method="POST">
            <!-- Скрытый ID — чтобы знать, какую запись обновлять -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($message['id']) ?>">

            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($message['name']) ?>" required>

            <label for="message">Сообщение:</label>
            <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($message['message']) ?></textarea>

            <button type="submit" class="btn btn-save">Сохранить изменения</button>
            <a href="index.php" class="btn btn-cancel">Отмена</a>
        </form>
    </div>
</body>
</html>
