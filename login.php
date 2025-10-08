<!-- login.php -->
<?php
require_once 'config.php';

$error = '';

if ($_POST) {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        // Ищем пользователя по username или email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Успешный вход — сохраняем данные в сессию
            unset($user['password_hash']); // удаляем хеш из сессии
            $_SESSION['user'] = $user;

            // Редирект на запрошенную страницу или на главную
            $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = "Неверный логин или пароль";
        }
    } catch (Exception $e) {
        $error = "Ошибка входа: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
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
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin: 15px 0; }
        .register-link { text-align: center; margin-top: 20px; }
        .register-link a { color: #007BFF; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Вход</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="login" placeholder="Имя пользователя или email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>

        <div class="register-link">
            Нет аккаунта? <a href="register.php">Регистрация</a>
        </div>
    </div>
</body>
</html>
