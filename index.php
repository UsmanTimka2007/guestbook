<?php
require_once('config.php');

if ($_POST) {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);

    if ($name && $message) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, message) VALUES (?,?)");
        $stmt->execute([$name, $message]);

        header('Location: index.php');
        exit;
    } else {
        $error = 'Please, fill all fields';
    }
}

$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
   
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Guest book</h1>
        <form method = "POST">
            <?php if (isset($error)): ?>
                <p class="error"><?= $error ?></p>
                <?php endif; ?>
                <input type="text" name="name" placeholder="Name" required>
                <textarea name="message" rows="4" placeholder="Your message" required></textarea>
                <button type="submit"s>Submit</button>
        </form>

        <hr>

        <h3>Messages (<?= count($messages) ?>):</h3>
        <?php if (empty($messages)): ?>
            <p>No messages</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message">
                    <div class="name"><?= htmlspecialchars($msg['name']) ?></div>
                    <div><?= htmlspecialchars($msg['message']) ?></div>
                    <div class="date"><?= $msg['created_at'] ?></div>
                </div><br>
            <?php endforeach; ?>
        <?php endif; ?> 
    </div>       
</body>
</html>