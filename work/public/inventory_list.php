<?php
require 'auth.php';
require '../app/config.php';

$itemsPerPage = 8;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

$totalItems = $pdo->query('SELECT COUNT(*) FROM inventory_items')->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

$query = '
    SELECT inventory_items.*,
           categories.name AS category_name,
           (SELECT image FROM inventory_images WHERE inventory_images.item_id = inventory_items.id LIMIT 1) AS first_image,
           inventory_items.threshold,inventory_items.price
    FROM inventory_items
    LEFT JOIN categories ON inventory_items.category_id = categories.id
    LIMIT :offset, :itemsPerPage
';
$stmt = $pdo->prepare($query);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
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
                    echo !empty($item['threshold']) ? htmlspecialchars($item['threshold'], ENT_QUOTES, 'UTF-8') : '未設定';
                    ?>
                </p>
                <p>価格:
                    <?php
                    echo !empty($item['price']) ? htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8') : '未設定';
                    ?>
                </p>

                <div class="button-group">
                    <a href="inventory_manage.php?id=<?php echo $item['id']; ?>" class="btn-primary">編集</a>
                    <a href="inventory_delete.php?id=<?php echo $item['id']; ?>" class="btn-danger" onclick="return confirm('本当に削除しますか？');">削除</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>" class="btn-secondary">前へ</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>" class="btn-secondary">次へ</a>
        <?php endif; ?>
    </div>

    <div class="add-item-button">
        <a href="inventory_add.php" class="btn-primary">在庫アイテムを追加</a>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>

