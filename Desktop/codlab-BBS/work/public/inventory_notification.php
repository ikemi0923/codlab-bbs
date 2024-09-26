<?php
require '../app/config.php';
include 'header.php';

$stmt = $pdo->prepare('
    SELECT inventory_items.id, inventory_items.name, inventory_items.quantity, 
           inventory_items.threshold AS item_threshold, 
           (SELECT image FROM inventory_images WHERE inventory_images.item_id = inventory_items.id LIMIT 1) AS first_image
    FROM inventory_items
    WHERE inventory_items.quantity < inventory_items.threshold
');
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>在庫レベルアラート</h2>

<?php if (count($rows) > 0): ?>
  <?php foreach ($rows as $row): ?>
    <div class="alert-item">
      <img src="<?php echo !empty($row['first_image']) ? 'uploads/' . htmlspecialchars($row['first_image'], ENT_QUOTES, 'UTF-8') : 'no_image.png'; ?>" alt="Item Image" style="width: 150px; height: 150px;">
      <p>アイテム名: <?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></p>
      <p>在庫数: <?php echo htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8'); ?></p>
      <p>しきい値: <?php echo htmlspecialchars($row['item_threshold'], ENT_QUOTES, 'UTF-8'); ?></p>
      <p style="color: red;">このアイテムは在庫が不足しています！</p>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p>現在、しきい値を下回っているアイテムはありません。</p>
<?php endif; ?>

<a href="inventory_list.php">在庫アイテム一覧に戻る</a>

<?php include 'footer.php'; ?>