<?php
$host = 'db';
$dbname = 'codlab_bbs_db';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

    $pdo->exec("DELETE FROM inventory_images");
    $pdo->exec("DELETE FROM inventory_notifications");
    $pdo->exec("DELETE FROM inventory_items");
    $pdo->exec("DELETE FROM categories");

    $pdo->exec("ALTER TABLE categories AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE inventory_items AUTO_INCREMENT = 1");
    $pdo->exec("ALTER TABLE inventory_images AUTO_INCREMENT = 1");

    $categories = ['Tops', 'Bottoms', 'Accessories', 'Shoes', 'Hats', 'Outerwear', 'Jewelry'];
    foreach ($categories as $name) {
        $pdo->exec("INSERT INTO categories (name) VALUES ('$name')");
    }

    $items = [
        ['Vintage T-Shirt', 1, 10, 25.00, 5],
        ['Denim Jeans', 2, 3, 40.00, 5], 
        ['Leather Belt', 3, 15, 15.00, 5],
        ['Running Shoes', 4, 12, 60.00, 5],
        ['Baseball Cap', 5, 18, 10.00, 5],
        ['Winter Coat', 6, 8, 120.00, 5],
        ['Silver Necklace', 7, 25, 50.00, 5]
    ];
    $itemIds = [];
    foreach ($items as $item) {
        $pdo->exec("INSERT INTO inventory_items (name, category_id, quantity, price, threshold) 
                    VALUES ('{$item[0]}', {$item[1]}, {$item[2]}, {$item[3]}, {$item[4]})");
        $itemIds[] = $pdo->lastInsertId(); 
    }

    foreach ($itemIds as $id) {
        $pdo->exec("INSERT INTO inventory_images (item_id, image) 
                    VALUES ($id, 'image_$id.jpg')");
    }

    foreach ($itemIds as $id) {
        $notify = $id == $itemIds[1] ? "TRUE" : "FALSE"; 
        $pdo->exec("INSERT INTO inventory_notifications (item_id, threshold, notify) 
                    VALUES ($id, 5, $notify)");
    }

    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    echo "ページネーション用データが正常に追加されました。";

} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit();
}
