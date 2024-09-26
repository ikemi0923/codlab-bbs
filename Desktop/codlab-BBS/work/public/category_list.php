<?php
require '../app/config.php';
include 'header.php';

$stmt = $pdo->query('SELECT id, name FROM categories');
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
  <div id="category-list">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <div class="category-item">
        <p><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="button-group">
          <a href="category_edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">編集</a>
          <a href="category_delete.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('本当に削除しますか？');">削除</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="form-container">
    <button class="btn-show-form" id="showFormBtn">新しいカテゴリーを追加</button>
  </div>

  <div class="form-container" id="categoryForm" style="display: none;">
    <form method="post" action="category_process.php">
      <label for="category_name">新しいカテゴリー:</label>
      <input type="text" id="category_name" name="category_name" placeholder="カテゴリー名を入力" required>
      <button type="submit">追加</button>
    </form>
  </div>

  <script>
    document.getElementById("showFormBtn").addEventListener("click", function() {
      var formContainer = document.getElementById("categoryForm");
      if (formContainer.style.display === "none" || formContainer.style.display === "") {
        formContainer.style.display = "block";
      } else {
        formContainer.style.display = "none";
      }
    });
  </script>

  <?php include 'footer.php';
  ?>
</body>

</html>