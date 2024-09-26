<?php
require '../app/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $item_id = $_POST['item_id'];
  $threshold = $_POST['threshold'];
  $notify = isset($_POST['notify']) ? 1 : 0;

  try {
    $stmt = $pdo->prepare('
      INSERT INTO inventory_notifications (item_id, threshold, notify, created_at) 
      VALUES (:item_id, :threshold, :notify, NOW())
      ON DUPLICATE KEY UPDATE threshold = :threshold, notify = :notify, updated_at = NOW()');

    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':threshold', $threshold);
    $stmt->bindParam(':notify', $notify);
    $stmt->execute();

    header("Location: inventory_list.php");
    exit();
  } catch (PDOException $e) {
    echo "通知設定の保存に失敗しました: " . $e->getMessage();
  }
}
