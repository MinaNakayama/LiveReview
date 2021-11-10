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
    <title>アカウント削除確認</title>
</head>

<body>
    <?php include ('header.php'); ?>
    <section>
        <div class="contactBox">
            <div class="completeMsg">
                <form action="userDelete_comp.php?id=<?= $login_user ?>" method="POST">
                    <h2>アカウントを削除しますか？</h2>
                    <p>アカウントを削除すると今までの投稿はすべて消えてしまいます。</p>
                    <p>一度削除すると復元できません。</p>

                    <div class="button-panel">
                        <button type="submit" class="dltBtn">削　除</button>
                        <button class="backBtn"><a href="userEdit.php?id=<?= $login_user ?>">戻　る</a></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>