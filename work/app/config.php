<?php

$db_host = 'db';
$db_name = 'codlab_bbs_db';
$db_user = 'root';
$db_pass = 'root';

try {
  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("データベース接続に失敗しました: " . $e->getMessage());
}
