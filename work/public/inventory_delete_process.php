<?php
require '../app/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = (int)$_POST['id'];

        try {
            $stmt = $pdo->prepare('DELETE FROM activity_log WHERE item_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $pdo->prepare('DELETE FROM notifications WHERE item_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $pdo->prepare('DELETE FROM inventory_images WHERE item_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $pdo->prepare('DELETE FROM inventory_items WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success_message'] = "在庫アイテムを削除しました。";
            header("Location: inventory_list.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "在庫アイテムの削除に失敗しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            header("Location: inventory_list.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "無効なIDです。";
        header("Location: inventory_list.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "不正なリクエストです。";
    header("Location: inventory_list.php");
    exit();
}
