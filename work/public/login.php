<?php
require_once '../app/config.php';
session_start();

if (isset($_GET['logout'])) {
    $_SESSION['logout_message'] = "ログアウトしました。";
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error_message'] = "不正なリクエストです。";
        header("Location: login.php");
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['success_message'] = "ログインに成功しました！";
            header("Location: inventory_list.php");
            exit();
        } else {
            $_SESSION['error_message'] = "ユーザー名またはパスワードが間違っています。";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "エラー: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="login-main">
        <div class="login-container">
            <h2 class="login-title">ログイン</h2>

            <?php if (isset($_SESSION['logout_message'])): ?>
                <p class="logout-message"><?php echo $_SESSION['logout_message']; ?></p>
                <?php unset($_SESSION['logout_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="login.php" method="post" class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                <label for="username">ユーザー名:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn-submit">ログイン</button>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
