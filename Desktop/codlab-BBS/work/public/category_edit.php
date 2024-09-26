<?php
require '../app/config.php';
include 'header.php';

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
    $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        echo "<p>カテゴリーが見つかりません。</p>";
        exit;
    }
} else {
    echo "<p>IDが指定されていません。</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    if (!empty($category_name)) {
        try {
            $stmt = $pdo->prepare('UPDATE categories SET name = :name WHERE id = :id');
            $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
            $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
            $stmt->execute();
            echo "<p>カテゴリーを更新しました。</p>";
            header('Location: category_manage.php');
            exit;
        } catch (PDOException $e) {
            echo "<p>カテゴリーの更新に失敗しました: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>カテゴリー名を入力してください。</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カテゴリー編集</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
  <h2>カテゴリー編集</h2>

  <form method="post">
    <label for="category_name">カテゴリー名:</label>
    <input type="text" name="category_name" id="category_name" value="<?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
    <button type="submit" class="btn-show-form">更新</button>
  </form>

  <p><a href="category_manage.php">カテゴリー管理に戻る</a></p>

  <?php include 'footer.php';?>
</body>

</html>
