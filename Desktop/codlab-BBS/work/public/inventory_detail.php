<?php
require '../app/config.php';
include 'header.php';

$id = $_GET['id'];

$stmt = $pdo->prepare('
    SELECT inventory_items.id, inventory_items.name, inventory_items.quantity, inventory_items.threshold, categories.name as category
    FROM inventory_items
    JOIN categories ON inventory_items.category_id = categories.id
    WHERE inventory_items.id = :id
');
$stmt->bindParam(':id', $id);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);
$images_stmt = $pdo->prepare('SELECT image FROM inventory_images WHERE item_id = :id');
$images_stmt->bindParam(':id', $id);
$images_stmt->execute();
$images = $images_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>の詳細</h2>
<p>カテゴリー: <?php echo htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8'); ?></p>
<p>数量: <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></p>
<p>しきい値: <?php echo htmlspecialchars($item['threshold'], ENT_QUOTES, 'UTF-8'); ?></p>

<h3>画像ギャラリー</h3>
<div class="image-gallery">
  <?php foreach ($images as $image): ?>
    <img src="uploads/<?php echo htmlspecialchars($image['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Item Image" class="gallery-image">
  <?php endforeach; ?>
</div>

<p><a href="inventory_list.php">在庫リストに戻る</a></p>

<?php include 'footer.php'; ?>