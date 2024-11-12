<?php
require '../app/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
    $threshold = isset($_POST['threshold']) ? (int)$_POST['threshold'] : null;
    $notify = isset($_POST['notify']) ? 1 : 0;
    
    if ($item_id === null || $threshold === null) {
        $_SESSION['error_message'] = "無効なデータが送信されました。";
        header("Location: inventory_manage.php");
        exit();
    }

    try {
        $stmt = $pdo->prepare('
            INSERT INTO inventory_notifications (item_id, threshold, notify, created_at) 
            VALUES (:item_id, :threshold, :notify, NOW())
            ON DUPLICATE KEY UPDATE threshold = :threshold, notify = :notify, updated_at = NOW()');

        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->bindParam(':notify', $notify, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['success_message'] = "通知設定を保存しました。";
        header("Location: inventory_manage.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "通知設定の保存に失敗しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        header("Location: inventory_manage.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "不正なリクエストです。";
    header("Location: inventory_manage.php");
    exit();
}
