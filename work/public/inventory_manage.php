<?php
require '../app/config.php';
session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $item_id = (int)$_GET['id'];

    $stmt = $pdo->prepare('SELECT * FROM inventory_items WHERE id = :id');
    $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        $_SESSION['error_message'] = "指定された在庫アイテムが見つかりません。";
        header("Location: inventory_list.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "不正なアクセスです。";
    header("Location: inventory_list.php");
    exit();
}

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);

$image_stmt = $pdo->prepare('SELECT * FROM inventory_images WHERE item_id = :item_id');
$image_stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
$image_stmt->execute();
$images = $image_stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>在庫アイテム管理</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<main>
  <h2>在庫アイテム管理</h2>

<?php
if (isset($_SESSION['success_message'])) {
    echo "<p class='success-message'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']);
}

if ($item['quantity'] < $item['threshold'] && $item['notify']) {
    echo "<p style='color: red;'>在庫が少なくなっています！</p>";
}
?>

  <section>
    <h3>基本情報</h3>
    <form action="inventory_update_process.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?>">
        <ul>
            <li>
                <label for="name">アイテム名:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </li>
            <li>
                <label for="category_id">カテゴリー:</label>
                <select id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php if ($item['category_id'] == $category['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li>
                <label for="quantity">数量:</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </li>
            <li>
                <label for="threshold">しきい値:</label>
                <input type="number" id="threshold" name="threshold" value="<?php echo htmlspecialchars($item['threshold'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </li>
            <li>
                <label for="remarks">備考:</label>
                <input type="text" id="remarks" name="remarks" value="<?php echo htmlspecialchars($item['remarks'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="任意">
            </li>
            <li>
                <label for="price">価格:</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8'); ?>" required> 
            </li>
            <li>
                <button type="submit" class="btn-primary">保存</button>
            </li>
        </ul>
    </form>
</section>

  <section>
    <h3>在庫・通知設定</h3>
    <p>在庫がしきい値を下回った場合に通知を行います。通知を有効にするには、通知のON/OFFを設定してください。</p>
    <form action="inventory_notification_process.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?>">
        <label>
            <input type="checkbox" name="notification_enabled" <?php echo $item['notify'] ? 'checked' : ''; ?>> 通知を有効にする
        </label>
        <button type="submit" class="btn-manage-secondary">設定を保存</button>
    </form>
</section>


  <section>
    <h3>画像管理</h3>
    <form action="inventory_image_upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8'); ?>">
        <label for="images">画像を追加:</label>
        <input type="file" name="images[]" id="images" multiple>
        <button type="submit" class="btn-manage-secondary">画像をアップロード</button>
    </form>

    <div id="image-gallery" class="image-gallery">
    <?php if (!empty($images)): ?>
        <?php foreach ($images as $image): ?>
            <div class="image-item" data-id="<?php echo htmlspecialchars($image['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <img src="uploads/<?php echo htmlspecialchars($image['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Image">
                <button type="button" class="image-item-delete-btn" data-image-id="<?php echo htmlspecialchars($image['id'], ENT_QUOTES, 'UTF-8'); ?>">削除</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>画像はありません。</p>
    <?php endif; ?>
</div>



</div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    const gallery = document.getElementById('image-gallery');
    new Sortable(gallery, {
        animation: 150,
        onEnd: function (evt) {
            const order = [];
            document.querySelectorAll('.image-item').forEach(item => {
                order.push(item.getAttribute('data-id'));
            });
            fetch('save_image_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order: order })
            });
        }
    });
</script>
<script>
    document.querySelectorAll('.image-item-delete-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            const confirmation = confirm("本当に削除しますか？");

            if (!confirmation) {
                event.preventDefault();
            } else {
                const imageId = this.getAttribute('data-image-id');
                const formData = new FormData();
                formData.append('image_id', imageId);
                
                fetch('inventory_image_delete.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.image-item').remove();
                    } else {
                        alert('削除に失敗しました。');
                    }
                })
                .catch(error => {
                    console.error('エラー:', error);
                    alert('エラーが発生しました。');
                });
            }
        });
    });
</script>



<form action="inventory_update_process.php" method="post">
    <button type="submit" class="btn-save-center">全体の編集内容を保存</button>
</form>


<p><a href="inventory_list.php" class="btn-secondary">在庫リストに戻る</a></p>

</main>

<?php include 'footer.php'; ?>
</body>
</html>
