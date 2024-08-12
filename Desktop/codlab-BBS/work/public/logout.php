<?php
session_start();
session_destroy(); // セッションを破棄

// ログインページにリダイレクト
header("Location: login.php");
exit();
