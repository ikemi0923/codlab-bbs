<?php
require '../app/config.php';

$id = $_GET['id'];

try {
  $stmt = $pdo->prepare('DELETE FROM inventory_items WHERE id = :id');
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "在庫アイテムを削除しました。";
} catch (PDOException $e) {
  echo "在庫アイテムの削除に失敗しました: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>在庫アイテム削除</title>
  <link rel="stylesheet" href="path/to/your/styles.css">
</head>

<body>
  <header>
    <h1>Codlab BBS</h1>
    <nav>
      <ul>
        <li><a href="index.php">ホーム</a></li>
        <li><a href="inventory_list.php">在庫リスト</a></li>
        <li><a href="inventory_add.php">在庫追加</a></li>
        <li><a href="logout.php">ログアウト</a></li>
      </ul>
    </nav>
  </header>
  <main>
    <p><a href="inventory_list.php">在庫リストに戻る</a></p>
  </main>
  <footer>
    <p>&copy; 2024 在庫管理アプリ. All rights reserved.</p>
  </footer>
</body>

</html>