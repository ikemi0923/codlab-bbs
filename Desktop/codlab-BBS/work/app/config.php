<?php
define('DB_HOST', 'db');
define('DB_NAME', 'codlab_bbs_db');
define('DB_USER', 'root');
define('DB_PASS', 'root');

$db_host = 'db'; // Docker環境では通常これを使用します
$db_name = 'codlab_bbs_db'; // データベース名
$db_user = 'root'; // ユーザー名
$db_pass = 'root'; // パスワード

try {
  // PDOを使用してデータベースに接続
  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
  // エラーモードを例外に設定
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("データベース接続に失敗しました: " . $e->getMessage());
}
