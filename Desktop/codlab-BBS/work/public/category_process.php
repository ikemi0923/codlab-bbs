<?php
require '../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_name = $_POST['category_name'];

  if (!empty($category_name)) {
    try {
      $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (:name)');
      $stmt->bindParam(':name', $category_name);
      $stmt->execute();
      header('Location: category_list.php');
      exit();
    } catch (PDOException $e) {
      echo "カテゴリーの追加に失敗しました: " . $e->getMessage();
    }
  } else {
    echo "カテゴリー名を入力してください。";
  }
} else {
  echo "不正なリクエストです。";
}
