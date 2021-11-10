<?php
session_start();
require_once(ROOT_PATH .'/Models/User.php');

//バリデーション
$err = [];

if (!$email = filter_input(INPUT_POST, 'email')) {
    $err['email'] = 'メールアドレスを入力してください。';
}
if (!$password = filter_input(INPUT_POST, 'password')) {
    $err['password'] = 'パスワードを入力してください。';
}
if (count($err) > 0) {
    $_SESSION = $err;
    header('Location: login_form.php');
    return;
}

//ログイン成功
$result = User::login($email, $password);
//ログイン失敗
if (!$result) {
    header('Location: login_form.php');
    return;
}

$login_user = $_SESSION['login_user'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>ログイン完了画面</title>
</head>

<body>
    <?php include('header.php'); ?>
    <section>
        <div class="contactBox">
            <h2>ログイン完了</h2>
            <div class="completeMsg">
                <p>ログインしました。</p>
                <p>引き続きお楽しみください。</p>
                <a href="index.php?id=<?= $login_user['id'] ?>">ホーム画面へ</a>
            </div>
        </div>
    </section>
</body>
</html>