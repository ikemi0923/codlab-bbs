<?php
require_once '../app/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if ($password != $confirm_password) {
    echo "パスワードが一致しません。";
    exit;
  }

  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  try {
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created, modified) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->execute([$username, $email, $password_hash]);
    echo "ユーザー登録に成功しました。";
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
  <title>ユーザー登録</title>
</head>

<body>
  <h2>ユーザー登録</h2>
  <form action="signup.php" method="post">
    <label for="username">ユーザー名:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <label for="confirm_password">パスワード（確認）:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <br><br>

    <button type="submit">登録</button>
  </form>
</body>

</html>