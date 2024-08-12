<?php
require 'header.php';
session_start();

// ログイン状態のチェック
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

?>

<body>
  <h2>ダッシュボード</h2>
  <p>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん</p>
  <p><a href="logout.php">ログアウト</a></p>
  <?php
  require 'footer.php';
  ?>