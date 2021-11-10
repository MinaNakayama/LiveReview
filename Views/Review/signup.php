<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}

require_once(ROOT_PATH .'/Models/User.php');

$err = [];
$_POST['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
if (empty($_POST['name'])) {
    $err['name'] = 'アカウント名は必須入力です。20文字以内でご入力ください。';
} elseif (mb_strlen($_POST['name']) > 20) {
    $err['name'] = 'アカウント名は20文字以内でご入力ください。';
}
if (!$email = filter_input(INPUT_POST, 'email')) {
    $err['email'] = 'メールアドレスを入力してください。';
}
$password = filter_input(INPUT_POST, 'password');
if (!preg_match("/\A[a-z\d]{8,20}+\z/i", $password)) {
    $err['password'] = 'パスワードは8文字以上20文字以下にしてください。';
}
$password_conf = filter_input(INPUT_POST, 'password_conf');
if ($password !== $password_conf) {
    $err['password_conf'] = '設定したパスワードと異なっています。';
}
$_POST['birth'] = htmlspecialchars($_POST['birth'], ENT_QUOTES, "UTF-8");
if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['birth'])) {
    $err['birth'] = '存在しない日にちです。';
}
$_POST['person'] = htmlspecialchars($_POST['person'], ENT_QUOTES,"UTF-8");
if (mb_strlen($_POST['person']) > 50) {
    $err['person'] = '50文字以内でご入力ください。';
}
if (count($err) === 0) {
    $hasCreated = User::createUser($_POST);
    if (!$hasCreated) {
        $err[] = 'ユーザー登録に失敗しました。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>新規アカウント登録完了画面</title>
</head>

<body>
    <?php include('header.php'); ?>
    <section>
        <div class="contactBox">
            <h2>アカウント登録完了</h2>
            <div class="completeMsg">
                <?php if (count($err) > 0): ?>
                    <?php foreach ($err as $e): ?>
                        <p><?php echo $e; ?></p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>新規ご登録ありがとうございます。</p>
                    <p>ご登録が完了いたしました。</p>
                <?php endif; ?>
                <a href="login_form.php">ログインする</a>
            </div>
        </div>
    </section>
</body>
</html>
