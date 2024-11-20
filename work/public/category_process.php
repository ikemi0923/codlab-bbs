<?php
require '../app/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);

    if (!empty($category_name)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (:name)');
            $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['success_message'] = "カテゴリーを追加しました。";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "カテゴリーの追加に失敗しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        }
    } else {
        $_SESSION['error_message'] = "カテゴリー名を入力してください。";
    }
}

header('Location: category_manage.php');
exit();
