<?php
require '../app/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['order']) && is_array($data['order'])) {
    foreach ($data['order'] as $position => $id) {
        $stmt = $pdo->prepare('UPDATE inventory_images SET position = :position WHERE id = :id');
        $stmt->bindParam(':position', $position, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
