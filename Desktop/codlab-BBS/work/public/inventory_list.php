<?php
require '../app/config.php';

$items = $pdo->query('
    SELECT inventory_items.*, 
           categories.name AS category_name, 
           (SELECT image FROM inventory_images WHERE inventory_images.item_id = inventory_items.id LIMIT 1) AS first_image,
           inventory_items.threshold
    FROM inventory_items 
    LEFT JOIN categories ON inventory_items.category_id = categories.id
')->fetchAll(PDO::FETCH_ASSOC);
if (!defined('HEADER_INCLUDED')) {
    define('HEADER_INCLUDED', true);
    include 'header.php';
}
?>

<main>
    <h2>在庫アイテム一覧</h2>
    <div class="item-container">
        <?php foreach ($items as $item): ?>
            <div class="item-card">
                <img src="<?php echo !empty($item['first_image']) ? 'uploads/' . htmlspecialchars($item['first_image'], ENT_QUOTES, 'UTF-8') : 'no_image.png'; ?>" alt="Item Image">
                <p>アイテム名: <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>カテゴリー: <?php echo !empty($item['category_name']) ? htmlspecialchars($item['category_name'], ENT_QUOTES, 'UTF-8') : 'Unknown Category'; ?></p>
                <p>数量: <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>しきい値: 
                    <?php 
                    if (!empty($item['threshold'])) {
                        echo htmlspecialchars($item['threshold'], ENT_QUOTES, 'UTF-8'); 
                    } else {
                        echo '未設定';
                    }
                    ?>
                </p>
                <p>
                    <a href="inventory_edit.php?id=<?php echo $item['id']; ?>">編集</a> |
                    <a href="inventory_delete.php?id=<?php echo $item['id']; ?>" onclick="return confirm('本当に削除しますか？');">削除</a> |
                    <a href="inventory_detail.php?id=<?php echo $item['id']; ?>">詳細を見る</a>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="add-item-button">
        <a href="inventory_add.php" class="btn">在庫アイテムを追加</a>
    </div>
</main>

<footer class="footer">
    <p>&copy; 2024 Codlab BBS. All rights reserved.</p>
</footer>

