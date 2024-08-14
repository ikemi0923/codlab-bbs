<?php
require '../app/config.php';

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare('DELETE FROM inventory_items WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo "在庫アイテムを削除しました。";
} catch (PDOException $e) {
    echo "在庫アイテムの削除に失敗しました: " . $e->getMessage();
}
include 'header.php';
?>

<main>
    <p><a href="inventory_list.php">在庫リストに戻る</a></p>
</main>

<?php include 'footer.php'; ?>
