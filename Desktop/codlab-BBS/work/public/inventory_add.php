<?php
require '../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $quantity = $_POST['quantity'];
    $image = $_FILES['image']['name'];

    if ($image) {
        $target_dir = 'uploads/';
        $target_file = $target_dir . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "画像のアップロードに成功しました。";
        } else {
            echo "画像のアップロードに失敗しました。";
        }
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO inventory_items (name, category_id, quantity, image, created, modified) VALUES (:name, :category_id, :quantity, :image, NOW(), NOW())');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':image', $image);
        $stmt->execute();
        echo "在庫アイテムを追加しました。";
    } catch (PDOException $e) {
        echo "在庫アイテムの追加に失敗しました: " . $e->getMessage();
    }
}
include 'header.php';
?>

<main>
    <h2>在庫アイテム追加</h2>
    <form method="post" enctype="multipart/form-data">
        <ul>
            <li>
                <label for="name">アイテム名:</label>
                <input type="text" name="name" id="name" required>
            </li>
            <li>
                <label for="category_id">カテゴリー:</label>
                <select name="category_id" id="category_id" required>
                    <?php
                    $categories = $pdo->query('SELECT id, name FROM categories');
                    while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endwhile; ?>
                </select>
            </li>
            <li>
                <label for="quantity">数量:</label>
                <input type="number" name="quantity" id="quantity" required>
            </li>
            <li>
                <label for="image">画像:</label>
                <input type="file" name="image" id="image">
            </li>
            <li>
                <button type="submit">追加</button>
            </li>
        </ul>
    </form>
    <p><a href="inventory_list.php">在庫リストに戻る</a></p>
</main>

<?php include 'footer.php'; ?>
