<?php
require '../app/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image_id'])) {
    $image_id = (int)$_POST['image_id'];

    try {
        $stmt = $pdo->prepare('SELECT image FROM inventory_images WHERE id = :id');
        $stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
        $stmt->execute();
        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($image) {
            $imagePath = 'uploads/' . $image['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $deleteStmt = $pdo->prepare('DELETE FROM inventory_images WHERE id = :id');
            $deleteStmt->bindParam(':id', $image_id, PDO::PARAM_INT);
            $deleteStmt->execute();
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => '画像が見つかりません。']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => '不正なリクエストです。']);
}
