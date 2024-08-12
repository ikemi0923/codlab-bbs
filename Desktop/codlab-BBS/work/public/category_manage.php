<?php
require 'header.php';
require '../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];

  try {
    $stmt = $pdo->prepare('INSERT INTO categories (name, created, modified) VALUES (:name, NOW(), NOW())');
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    echo "カテゴリを追加しました。";
  } catch (PDOException $e) {
    echo "カテゴリの追加に失敗しました: " . $e->getMessage();
  }
}
?>

<h2>カテゴリ管理</h2>
<form method="post" action="category_manage.php">
  <label for="name">カテゴリ名:</label>
  <input type="text" name="name" id="name" required>
  <button type="submit">追加</button>
</form>
<a href="inventory_add.php">在庫アイテムの追加ページに戻る</a>
<?php
require 'footer.php';
?>