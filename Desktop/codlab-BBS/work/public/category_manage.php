<?php
require '../app/config.php';
include 'header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category_name = $_POST['category_name'];
  if (!empty($category_name)) {
    try {
      $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (:name)');
      $stmt->bindParam(':name', $category_name);
      $stmt->execute();
      echo "<p>カテゴリーを追加しました。</p>";
    } catch (PDOException $e) {
      echo "<p>カテゴリーの追加に失敗しました: " . $e->getMessage() . "</p>";
    }
  } else {
    echo "<p>カテゴリー名を入力してください。</p>";
  }
}

$stmt = $pdo->prepare('SELECT * FROM categories');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カテゴリー管理</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
  <h2>カテゴリー管理</h2>

  <form method="post">
    <label for="category_name">新しいカテゴリー名:</label>
    <input type="text" name="category_name" id="category_name" required>
    <button type="submit" class="btn-show-form">追加</button>
  </form>

  <h3>カテゴリー一覧</h3>
  <ul>
    <?php foreach ($categories as $category): ?>
      <li class="category-item">
        <p><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="button-group">
          <a href="category_edit.php?id=<?php echo $category['id']; ?>" class="edit-btn">編集</a>
          <a href="category_delete.php?id=<?php echo $category['id']; ?>" class="delete-btn" onclick="return confirm('本当に削除しますか？');">削除</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
  <p><a href="inventory_list.php">在庫リストに戻る</a></p>

  <?php include 'footer.php';
  ?>
</body>

</html>