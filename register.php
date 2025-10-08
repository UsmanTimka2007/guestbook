<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (strlen($username) < 3) {
        $error = "Имя пользователя должно быть не короче 3 символов";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Некорректный email";
    } elseif (strlen($password) < 6) {
        $error = "Пароль должен быть не короче 6 символов";
    } elseif ($password !== $password_confirm) {
        $error = "Пароли не совпадают";
    } else {
        try {
            // Проверяем, не занят ли логин/email
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = "Пользователь с таким именем или email уже существует";
            } else {
                // Хешируем пароль
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Сохраняем в базу
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $password_hash]);

                $success = "Регистрация успешна! Теперь вы можете <a href='login.php'>войти</a>.";
            }
        } catch (Exception $e) {
            $error = "Ошибка регистрации: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>📝 Регистрация</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 50px; }
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #218838; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin: 15px 0; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 15px 0; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #007BFF; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Регистрация</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php else: ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Имя пользователя" required minlength="3">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль (мин. 6 символов)" required minlength="6">
                <input type="password" name="password_confirm" placeholder="Подтвердите пароль" required>
                <button type="submit">Зарегистрироваться</button>
            </form>
        <?php endif; ?>

        <div class="login-link">
            Уже есть аккаунт? <a href="login.php">Войти</a>
        </div>
    </div>
</body>
</html>
