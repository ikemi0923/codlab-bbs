<?php
require '../app/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM inventory_items WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $quantity = $_POST['quantity'];
    $threshold = $_POST['threshold'];
    $images = $_FILES['images'];

    try {
        $stmt = $pdo->prepare('UPDATE inventory_items SET name = :name, category_id = :category_id, quantity = :quantity, threshold = :threshold, modified = NOW() WHERE id = :id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':threshold', $threshold);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if (!empty($images['name'][0])) {
            foreach ($images['tmp_name'] as $key => $tmp_name) {
                $image = $images['name'][$key];
                if ($image) {
                    $target_dir = 'uploads/';
                    $target_file = $target_dir . basename($image);
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $image_stmt = $pdo->prepare('INSERT INTO inventory_images (item_id, image) VALUES (:item_id, :image)');
                        $image_stmt->bindParam(':item_id', $id);
                        $image_stmt->bindParam(':image', $image);
                        $image_stmt->execute();
                    }
                }
            }
        }

        echo "在庫アイテムを更新しました。";
        header("Location: inventory_list.php");
        exit();
    } catch (PDOException $e) {
        echo "在庫アイテムの更新に失敗しました: " . $e->getMessage();
    }
}
$images_stmt = $pdo->prepare('SELECT image FROM inventory_images WHERE item_id = :id');
$images_stmt->bindParam(':id', $id);
$images_stmt->execute();
$images = $images_stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<main>
    <h2>在庫アイテム編集</h2>
    <form method="post" enctype="multipart/form-data">
        <ul>
            <li>
                <label for="name">アイテム名:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </li>
            <li>
                <label for="category_id">カテゴリー:</label>
                <select name="category_id" id="category_id" required>
                    <?php
                    $categories = $pdo->query('SELECT id, name FROM categories');
                    while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php if ($item['category_id'] == $row['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </li>
            <li>
                <label for="quantity">数量:</label>
                <input type="number" name="quantity" id="quantity" value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </li>
            <li>
                <label for="threshold">しきい値:</label>
                <input type="number" name="threshold" id="threshold" value="<?php echo htmlspecialchars($item['threshold'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </li>
<li>
    <label for="images">画像を追加:</label>
    <input type="file" name="images[]" id="images" multiple style="background-color: transparent; border: none;">
</li>

            <li>
                <button type="submit">更新</button>
            </li>
        </ul>
    </form>

    <h3>現在の画像一覧</h3>
    <div class="image-gallery">
        <?php foreach ($images as $image): ?>
            <img src="uploads/<?php echo htmlspecialchars($image['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Item Image" class="gallery-image">
        <?php endforeach; ?>
    </div>

    <p><a href="inventory_list.php">在庫リストに戻る</a></p>
</main>

<?php include 'footer.php'; ?>