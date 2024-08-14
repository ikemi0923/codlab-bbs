<?php
require '../app/config.php';

$inventory_items = $pdo->query('SELECT inventory_items.id, inventory_items.name, inventory_items.quantity, inventory_items.image, categories.name AS category_name FROM inventory_items LEFT JOIN categories ON inventory_items.category_id = categories.id');
include 'header.php';
?>

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

<?php include 'footer.php'; ?>
