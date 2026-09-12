<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    $valid_users = [
        ['username' => 'admin', 'password' => '123'],
        ['username' => 'user1', 'password' => 'password1']
    ];
    
    $authenticated = false;
    foreach ($valid_users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $authenticated = true;
            break;
        }
    }
    
    if ($authenticated) {
        $_SESSION['user'] = $username;
        echo "success";
    } else {
        echo "error";
    }
}
?>