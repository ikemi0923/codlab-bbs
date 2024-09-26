<?php
require '../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare('DELETE FROM activity_log WHERE item_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt = $pdo->prepare('DELETE FROM notifications WHERE item_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt = $pdo->prepare('DELETE FROM inventory_images WHERE item_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt = $pdo->prepare('DELETE FROM inventory_items WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "在庫アイテムを削除しました。";
        header("Location: inventory_list.php");
        exit();
    } catch (PDOException $e) {
        echo "在庫アイテムの削除に失敗しました: " . $e->getMessage();
    }
}
