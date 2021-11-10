<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>投稿削除確認画面</title>
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
                <form action="postDelete_comp.php?pid=<?= $_GET['pid'] ?>" method="POST">
                    <p>投稿を削除しますか？</p>
                    <p>一度削除すると復元できません。</p>
                    <div class="button-panel">
                        <button type="submit" class="dltBtn">削　除</button>
                        <button class="backBtn"><a href="javascript:history.back();">戻　る</a></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>