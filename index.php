<!-- index.php -->
<?php
require_once 'config.php';

// Получаем все сообщения с именем автора
$stmt = $pdo->query("
    SELECT m.*, u.username 
    FROM messages m 
    JOIN users u ON m.user_id = u.id 
    ORDER BY m.created_at DESC
");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title> Гостевая книга</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .container { max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        .auth-bar { text-align: right; margin-bottom: 20px; }
        .auth-bar a { margin-left: 15px; text-decoration: none; color: #007BFF; }
        .auth-bar a:hover { text-decoration: underline; }
        form { margin: 20px 0; }
        textarea { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { padding: 15px; border-bottom: 1px solid #eee; }
        .message-header { display: flex; justify-content: space-between; align-items: center; }
        .username { font-weight: bold; color: #333; }
        .date { font-size: 0.8em; color: #888; }
        .actions a { margin-left: 10px; color: #007BFF; text-decoration: none; }
        .actions a:hover { text-decoration: underline; }
        .admin-tag { background: #ffc107; padding: 2px 6px; border-radius: 3px; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="container">

        <!-- Панель авторизации -->
        <div class="auth-bar">
            <?php if (isUser()): ?>
                Привет, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>
                <?php if (isAdmin()): ?>
                    <span class="admin-tag">АДМИН</span>
                <?php endif; ?>
                <a href="logout.php"> Выйти</a>
            <?php else: ?>
                <a href="login.php">Войти</a>
                <a href="register.php"> Регистрация</a>
            <?php endif; ?>
        </div>

        <h1>Гостевая книга</h1>

        <!-- Форма добавления (только для авторизованных) -->
        <?php if (isUser()): ?>
            <form action="add.php" method="POST">
                <textarea name="message" rows="3" placeholder="Напишите сообщение..." required></textarea>
                <button type="submit">Отправить</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Войдите</a> или <a href="register.php">зарегистрируйтесь</a>, чтобы оставить сообщение.</p>
        <?php endif; ?>

        <hr>

        <!-- Список сообщений -->
        <h3>Сообщения (<?= count($messages) ?>):</h3>
        <?php if (empty($messages)): ?>
            <p>Пока нет сообщений. Будьте первым!</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message">
                    <div class="message-header">
                        <div>
                            <span class="username"><?= htmlspecialchars($msg['username']) ?></span>
                            <?php if ($msg['username'] === 'admin'): ?>
                                <span class="admin-tag">АДМИН</span>
                            <?php endif; ?>
                        </div>
                        <div class="date"> <?= $msg['created_at'] ?></div>
                    </div>
                    <div style="margin: 10px 0;"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>

                    <!-- Кнопки действий (если авторизован и это твоё сообщение или ты админ) -->
                    <?php if (isUser() && (isAdmin() || $msg['user_id'] == getCurrentUserId())): ?>
                        <div class="actions">
                            <a href="edit.php?id=<?= $msg['id'] ?>"> Редактировать</a>
                            <form action="delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Удалить?')">
                                <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                <button type="submit" style="background:none; border:none; color:red; cursor:pointer; padding:0;"> Удалить</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</body>
</html>
