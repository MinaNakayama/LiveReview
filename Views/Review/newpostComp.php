<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}

require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];

require_once(ROOT_PATH .'/Models/Live.php');

$filename = basename($_POST['name']);
$save_path = $_POST['save_path'];

$id = $_GET['id'];

if (!empty($filename)) {
    //データベースに保存(ファイルあり)
    $result = Live::createPost($_POST, $filename, $save_path, $id);
    if ($result) {
        //echo 'データベースに保存しました。';
        $url = "mypage.php?id=" . $id;
        header('Location: ' . $url);
        return;
    } else {
        $err_msgs[] = 'データベースへの保存に失敗しました。';
    }
} else {
    //データベースに保存(ファイルなし)
    $result = Live::createNoFilePost($_POST, $id);
    if ($result) {
        //echo 'データベースに保存しました。';
        $url = "mypage.php?id=" . $id;
        header('Location: ' . $url);
        return;
    } else {
        $err_msgs[] = 'データベースへの保存に失敗しました。';
    }
}

require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$params = $live->postShow();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>投稿完了画面</title>
</head>

<body>
    <header class="contact">
        <nav class="navigation">
            <div class="logo">
                <a href="index.php?id=<?= $login_user ?>"><img src="/img/logo.png" alt="logo"></a>
            </div>
            <div class="navMenu">
                <div class="menu"><a href="index.php?id=<?= $login_user ?>">ホーム</a></div>
                <div class="menu"><a href="mypage.php?id=<?= $login_user ?>">マイページ</a></div>
                <div class="menu"><a href="newpost.php?id=<?= $login_user ?>">新規投稿</a></div>
                <div class="menu"><a href="search.php?id=<?= $login_user ?>">投稿検索</a></div>
                <div class="menu"><a href="logout.php">ログアウト</a></div>
            </div>
        </nav>
    </header>

    <section>
        <div class="contactBox">
            <div class="completeMsg">
                <p>投稿が完了しました。</p>
                <a href="index.php?id=<?= $login_user ?>">投稿一覧へ</a>
            </div>
        </div>
    </section>
</body>
</html>