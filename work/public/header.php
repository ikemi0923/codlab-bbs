<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') : 'Codlab BBS'; ?></title>
  <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
<header>
  <nav>
    <ul>
      <li><a href="inventory_list.php">在庫リスト</a></li>
      <li><a href="inventory_add.php">在庫追加</a></li>
      <li><a href="category_manage.php">カテゴリー管理</a></li>
      <li><a href="logout.php">ログアウト</a></li>
    </ul>
  </nav>
</header>

