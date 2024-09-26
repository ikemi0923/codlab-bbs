<?php
require '../app/config.php';

if (isset($_GET['id'])) {
  $id = (int)$_GET['id'];

  $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);

  if ($stmt->execute()) {
    header('Location: category_list.php');
    exit;
  } else {
    echo "削除に失敗しました。";
  }
} else {
  echo "IDが指定されていません。";
}
