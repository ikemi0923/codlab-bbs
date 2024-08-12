<?php
require '../app/config.php';

$inventory_items = $pdo->query('SELECT inventory_items.id, inventory_items.name, inventory_items.quantity, inventory_items.image, categories.name AS category_name FROM inventory_items LEFT JOIN categories ON inventory_items.category_id = categories.id');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>在庫アイテム一覧</title>
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
    <h2>在庫アイテム一覧</h2>
    <ul>
      <?php while ($row = $inventory_items->fetch(PDO::FETCH_ASSOC)): ?>
        <li>
          <?php if ($row['image']): ?>
            <img src="uploads/<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>">
          <?php endif; ?>
          <strong>アイテム名:</strong> <?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?><br>
          <strong>カテゴリー:</strong> <?php echo htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8'); ?><br>
          <strong>数量:</strong> <?php echo htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8'); ?><br>
          <a href="inventory_edit.php?id=<?php echo $row['id']; ?>">編集</a> |
          <a href="inventory_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('削除してもよろしいですか？');">削除</a>
        </li>
      <?php endwhile; ?>
    </ul>
    <p><a href="inventory_add.php">在庫アイテムを追加</a></p>
  </main>
  <footer>
    <p>&copy; 2024 在庫管理アプリ. All rights reserved.</p>
  </footer>
</body>

</html>