<?php
require '../app/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $item_id = $_POST['id'];
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $quantity = $_POST['quantity'];
    $threshold = $_POST['threshold'];
    $remarks = $_POST['remarks'] ?? '';
    $price = $_POST['price']; 

    $stmt = $pdo->prepare("UPDATE inventory_items SET name = :name, category_id = :category_id, quantity = :quantity, threshold = :threshold, remarks = :remarks, price = :price, modified = NOW() WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':threshold', $threshold);
    $stmt->bindParam(':remarks', $remarks);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':id', $item_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "在庫アイテムが更新されました。";
    } else {
        $_SESSION['error_message'] = "更新に失敗しました。";
    }
} else {
    $_SESSION['error_message'] = "不正なアクセスです。";
}

header("Location: inventory_list.php");
exit();
