<?php
require '../app/config.php';
session_start();

if (isset($_POST['id'])) {
    $item_id = (int)$_POST['id'];
    $notification_enabled = isset($_POST['notification_enabled']) ? 1 : 0; 
    $price = $_POST['price'];

    $stmt = $pdo->prepare("UPDATE inventory_items SET notify = :notify, price = :price WHERE id = :id");
    
    $stmt->bindParam(':notify', $notification_enabled, PDO::PARAM_INT);
    $stmt->bindParam(':price', $price, PDO::PARAM_STR);
    $stmt->bindParam(':id', $item_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "通知設定が更新されました。";
    } else {
        $_SESSION['error_message'] = "通知設定の更新に失敗しました。";
    }
} else {
    $_SESSION['error_message'] = "不正なリクエストです。";
}

header("Location: inventory_manage.php?id=" . $_POST['id']);
exit();
