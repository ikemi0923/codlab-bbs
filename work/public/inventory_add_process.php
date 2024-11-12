<?php
require '../app/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $quantity = (int)$_POST['quantity'];
    $threshold = isset($_POST['threshold']) ? (int)$_POST['threshold'] : null;
    $images = $_FILES['images'];

    try {
        $stmt = $pdo->prepare('INSERT INTO inventory_items (name, category_id, quantity, threshold, created, modified) 
                               VALUES (:name, :category_id, :quantity, :threshold, NOW(), NOW())');
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->execute();
        $item_id = $pdo->lastInsertId();

        if (!empty($images['name'][0])) {
            $target_dir = 'uploads/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            foreach ($images['tmp_name'] as $key => $tmp_name) {
                $image = $images['name'][$key];
                $unique_name = uniqid() . '_' . basename($image); 
                $target_file = $target_dir . $unique_name;
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $image_stmt = $pdo->prepare('INSERT INTO inventory_images (item_id, image) VALUES (:item_id, :image)');
                    $image_stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
                    $image_stmt->bindParam(':image', $unique_name, PDO::PARAM_STR);
                    $image_stmt->execute();
                }
            }
        }
        $_SESSION['success_message'] = "在庫アイテムを追加しました。";
        header("Location: inventory_list.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "在庫アイテムの追加に失敗しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header("Location: inventory_add.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "不正なリクエストです。";
    header("Location: inventory_add.php");
    exit();
}
