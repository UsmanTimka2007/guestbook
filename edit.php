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
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #333; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-save {
            background-color: #28a745;
            color: white;
        }
        .btn-save:hover {
            background-color: #218838;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            margin-left: 10px;
        }
        .btn-cancel:hover {
            background-color: #5a6268;
        }
    </style>
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
