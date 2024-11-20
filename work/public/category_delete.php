<?php
require '../app/config.php';
session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "カテゴリーを削除しました。";
        header('Location: category_manage.php');
        exit;
    } else {
        $_SESSION['error_message'] = "削除に失敗しました。";
        header('Location: category_manage.php');
        exit;
    }
} else {
    $_SESSION['error_message'] = "IDが指定されていないか、不正なIDです。";
    header('Location: category_manage.php');
    exit;
}
