<?php
require '../app/config.php';
session_start();
include 'header.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = (int)$_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
    $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        $_SESSION['error_message'] = "カテゴリーが見つかりません。";
        header('Location: category_manage.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = "IDが指定されていないか、不正なIDです。";
    header('Location: category_manage.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);
    
    if (!empty($category_name)) {
        try {
            $stmt = $pdo->prepare('UPDATE categories SET name = :name WHERE id = :id');
            $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
            $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $_SESSION['success_message'] = "カテゴリーを更新しました。";
            header('Location: category_manage.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "カテゴリーの更新に失敗しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            header('Location: category_manage.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "カテゴリー名を入力してください。";
        header('Location: category_edit.php?id=' . $category_id);
        exit();
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

  <p><strong>現在のカテゴリ名:</strong> <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></p>


  <form method="post">
    <label for="category_name">カテゴリー名:</label>
    <input type="text" name="category_name" id="category_name" value="<?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
    <button type="submit" class="btn-show-form">更新</button>
  </form>

  <p><a href="category_manage.php">カテゴリー管理に戻る</a></p>

  <?php include 'footer.php'; ?>
</body>
</html>
