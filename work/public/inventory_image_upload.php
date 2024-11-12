<?php
require '../app/config.php';
session_start();

if (isset($_FILES['images']) && $_FILES['images']['error'][0] == UPLOAD_ERR_OK) {
    $item_id = $_POST['item_id'];

    $uploadDir = 'uploads/';
    
    foreach ($_FILES['images']['name'] as $key => $name) {
        $tmpName = $_FILES['images']['tmp_name'][$key];
        $fileName = basename($name);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $uploadPath)) {

            $stmt = $pdo->prepare('INSERT INTO inventory_images (item_id, image) VALUES (:item_id, :image)');
            $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $stmt->bindParam(':image', $fileName, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $_SESSION['error_message'] = 'ファイルのアップロードに失敗しました。';
        }
    }
    $_SESSION['success_message'] = '画像が正常にアップロードされました。';
} else {
    $_SESSION['error_message'] = 'アップロードされたファイルがありません。';
}

header("Location: inventory_manage.php?id=$item_id");
exit();
