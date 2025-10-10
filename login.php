<!-- login.php -->
<?php
require_once 'config.php';
require_once 'passwords.php';
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
    <link rel="stylesheet" href="Styles/login_style.css">
    <title>Вход</title>
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
