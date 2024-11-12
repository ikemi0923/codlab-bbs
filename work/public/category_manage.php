<?php
require 'auth.php';
require '../app/config.php';
session_start();
include 'header.php';

$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カテゴリー管理</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main>
    <h2>カテゴリー管理</h2>
    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<p class='success-message'>" . $_SESSION['success_message'] . "</p>";
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
        unset($_SESSION['error_message']);
    }
    ?>
<form action="category_process.php" method="post" class="category-form">
  <ul>
    <li>
      <label for="category_name">新しいカテゴリー名:</label>
      <input type="text" name="category_name" id="category_name" required>
    </li>
    <li>
      <button type="submit" class="btn-primary">追加</button>
    </li>
  </ul>
</form>

<h3>カテゴリー一覧</h3>
<ul class="category-list">
  <?php foreach ($categories as $category): ?>
    <li class="category-item">
      <p><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></p>
      <div class="button-group">
        <a href="category_edit.php?id=<?php echo $category['id']; ?>" class="btn-primary">編集</a>
        <a href="category_delete.php?id=<?php echo $category['id']; ?>" class="btn-danger" onclick="return confirm('本当に削除しますか？');">削除</a>
      </div>
    </li>
  <?php endforeach; ?>
</ul>

<div class="add-category-button">
  <a href="inventory_list.php" class="btn-secondary">在庫リストに戻る</a>
</div>


  </main>

  <?php include 'footer.php'; ?>
</body>
</html>
