<?php
$passwords = ['password123', 'ike3hiro4'];

foreach ($passwords as $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    echo "Original: $password, Hashed: $hashed_password\n";
}
