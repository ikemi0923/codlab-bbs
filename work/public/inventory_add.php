<?php
require 'auth.php';
require '../app/config.php';
session_start();

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>在庫アイテム追加 - 在庫管理アプリ</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main>
    <h2>在庫アイテム追加</h2>

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

    <form action="inventory_add_process.php" method="post" enctype="multipart/form-data">
      <ul>
        <li>
          <label for="name">アイテム名:</label>
          <input type="text" id="name" name="name" required>
        </li>
        <li>
          <label for="category_id">カテゴリー:</label>
          <select id="category_id" name="category_id" required>
            <option value="">選択してください</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?php echo htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </li>
        <li>
          <label for="quantity">数量:</label>
          <input type="number" id="quantity" name="quantity" required>
        </li>
        <li>
          <label for="threshold">しきい値:</label>
          <input type="number" id="threshold" name="threshold" placeholder="任意">
        </li>
        <li>
      <label for="price">価格:</label> 
      <input type="text" id="price" name="price" required> 
    </li>
        <li>
          <label for="images">画像を追加:</label>
          <input type="file" name="images[]" id="images" multiple>
        </li>
        <li>
          <button type="submit" class="btn-primary">追加</button>
        </li>
      </ul>
    </form>

    <p><a href="inventory_list.php" class="btn-secondary">在庫リストに戻る</a></p>

  </main>

  <?php include 'footer.php'; ?>
</body>
</html>
