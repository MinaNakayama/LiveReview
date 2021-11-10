<?php
session_start();
require_once(ROOT_PATH .'/Models/User.php');

User::logout();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>ログアウト</title>
</head>

<body>
    <?php include('header.php'); ?>
    <div class="contactBox">
        <h2>ログアウト完了</h2>
        <div class="completeMsg">
            <p>ログアウトしました。</p>
            <a href="login_form.php">ログイン画面へ</a>
        </div>
    </div>
</body>
</html>