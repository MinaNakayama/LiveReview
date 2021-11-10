<!--
管理ユーザー
メールアドレス：owner@owner.com
パスワード：owner1234

一般ユーザー
メールアドレス：local1@local.com
パスワード：local5678
-->

<?php
session_start();
require_once(ROOT_PATH .'/Models/User.php');

$result = User::loginCheck();
if ($result) {
    header('Location: login_form.php');
    return;
}

$err = $_SESSION;
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>ログイン画面</title>
</head>

<body>
    <?php include('header.php'); ?>
    <section>
        <div class="contactBox">
            <h2>ログイン</h2>
            <?php if (isset($err['msg'])): ?>
                <span><?php echo $err['msg']; ?></span>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <dt>
                    <label for="email">メールアドレス</label>
                </dt>
                <dd>
                    <?php if (isset($err['email'])): ?>
                        <span><?php echo $err['email']; ?></span>
                    <?php endif; ?>
                    <input type="email" name="email">
                </dd>
                <dt>
                    <label for="password">パスワード</label>
                </dt>
                <dd>
                    <?php if (isset($err['password'])): ?>
                        <span><?php echo $err['password']; ?></span>
                    <?php endif; ?>
                    <input type="password" name="password">
                </dd>
                <div class="button-panel">
                    <button type="submit" class="button">ログイン</button>
                </div>
            </form>
        </div>
    </section>

    <div class="passBtn">
        <a href="pwd_reset.php">パスワードを忘れた方はこちら</a>
    </div>
    
    <div class="button-panel">
        <a href="signup_form.php"><input type="button" class="button" value="新規登録はこちら"></a>
    </div>
</body>
</html>