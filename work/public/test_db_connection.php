<?php
require '../app/config.php';


try {
  $stmt = $pdo->query('SELECT 1');
  echo "データベース接続に成功しました";
} catch (PDOException $e) {
  echo "データベース接続に失敗しました: " . $e->getMessage();
}


