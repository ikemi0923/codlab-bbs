<?php
require_once '../app/config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      echo "ログインに成功しました！";
      header("Location: index.php");
    } else {
      echo "ユーザー名またはパスワードが間違っています。";
    }

  } catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
</head>

<body>
  <h2>ログイン</h2>
  <form action="login.php" method="post">
    <label for="username">ユーザー名:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <button type="submit">ログイン</button>
  </form>
</body>

</html>