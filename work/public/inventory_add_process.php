<?php
require '../app/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error_message'] = "不正なリクエストです。";
        header("Location: inventory_add.php");
        exit();
    }

    $name = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $quantity = (int)$_POST['quantity'];
    $threshold = isset($_POST['threshold']) ? (int)$_POST['threshold'] : null;
    $price = isset($_POST['price']) ? (float)$_POST['price'] : null;
    $images = $_FILES['images'];

    $errors = [];
    if (empty($name) || strlen($name) > 255) {
        $errors[] = "アイテム名は必須で、255文字以内で入力してください。";
    }
    if ($category_id <= 0) {
        $errors[] = "有効なカテゴリーを選択してください。";
    }
    if ($quantity < 0) {
        $errors[] = "数量は0以上の整数を入力してください。";
    }
    if ($price !== null && $price < 0) {
        $errors[] = "価格は0以上の数値を入力してください。";
    }
    if (!empty($images['name'][0])) {
        foreach ($images['tmp_name'] as $key => $tmp_name) {
            $file_size = $images['size'][$key];
            $file_type = mime_content_type($tmp_name);

            if (!in_array($file_type, ['image/jpeg', 'image/png', 'image/gif'])) {
                $errors[] = "画像はJPEG、PNG、GIF形式である必要があります。";
            }
            if ($file_size > 2 * 1024 * 1024) {
                $errors[] = "画像サイズは2MB以下にしてください。";
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['error_message'] = implode("<br>", $errors);
        header("Location: inventory_add.php");
        exit();
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('INSERT INTO inventory_items (name, category_id, quantity, threshold, price, created, modified)
                               VALUES (:name, :category_id, :quantity, :threshold, :price, NOW(), NOW())');
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->execute();
        $item_id = $pdo->lastInsertId();

        if (!empty($images['name'][0])) {
            $target_dir = 'uploads/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            foreach ($images['tmp_name'] as $key => $tmp_name) {
                $image = $images['name'][$key];
                $unique_name = uniqid() . '_' . basename($image);
                $target_file = $target_dir . $unique_name;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $image_stmt = $pdo->prepare('INSERT INTO inventory_images (item_id, image) VALUES (:item_id, :image)');
                    $image_stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
                    $image_stmt->bindParam(':image', $unique_name, PDO::PARAM_STR);
                    $image_stmt->execute();
                } else {
                    throw new Exception("画像の保存に失敗しました。");
                }
            }
        }

        $pdo->commit();
        $_SESSION['success_message'] = "在庫アイテムを追加しました。";
        header("Location: inventory_list.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();

        error_log("エラー内容: " . $e->getMessage());

        $_SESSION['error_message'] = "処理中にエラーが発生しました。";
        header("Location: inventory_add.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "不正なリクエストです。";
    header("Location: inventory_add.php");
    exit();
}
