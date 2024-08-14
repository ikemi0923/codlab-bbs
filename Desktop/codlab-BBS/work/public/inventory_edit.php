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
    $image = $item['image'];

    if ($_FILES['image']['name']) {
        $target_dir = 'uploads/';
        $target_file = $target_dir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $_FILES['image']['name'];
            echo "画像のアップロードに成功しました。";
        } else {
            echo "画像のアップロードに失敗しました。";
        }
    }

    try {
        $stmt = $pdo->prepare('UPDATE inventory_items SET name = :name, category_id = :category_id, quantity = :quantity, image = :image, modified = NOW() WHERE id = :id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        echo "在庫アイテムを更新しました。";
    } catch (PDOException $e) {
        echo "在庫アイテムの更新に失敗しました: " . $e->getMessage();
    }
}
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
                        <option value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php if ($row['id'] == $item['category_id']) echo 'selected'; ?>>
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
                <label for="image">画像:</label>
                <input type="file" name="image" id="image">
                <?php if ($item['image']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100px;">
                <?php endif; ?>
            </li>
            <li>
                <button type="submit">更新</button>
            </li>
        </ul>
    </form>
    <p><a href="inventory_list.php">戻る</a></p>
</main>

<?php include 'footer.php'; ?>
