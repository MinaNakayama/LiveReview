<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];
$post_id = $_GET['pid'];

require_once(ROOT_PATH .'/Controllers/function.php');
require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$params = $live->index();
$params2 = $live->postShow();
$post_data = $params2['post_data'][0];

if (isset($_SESSION['pe_err'])) {
    $err = $_SESSION['pe_err'];
}

if ($_SERVER["HTTP_REFERER"] = "http://localhost:8888/Review/postEdit.php?id=".$login_user ."&pid=" .$post_id) {
    unset($_SESSION['pe_err']);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>投稿編集画面</title>
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

    <div class="form-wrapper">
        <h1>投稿編集</h1>
        <form enctype="multipart/form-data" action="postEdit_comp.php?pid=<?= $post_id ?>" method="post">
                <div class="form-item">
                    <p class="span">現在の画像</p>
                    <img src="<?php echo '/../'."{$post_data['image_path']}"; ?>" alt=".." style="width: 300px;">
                    <div class="Btn">
                        <button class="btn"><a href="post_fileDelete.php?id=<?= $login_user ?>&pid=<?= $post_data['id'] ?>">写真を削除</a></button>
                    </div>

                    <div class="image">
                        <label for="image">画像変更</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                        <input type="file" name="img" id="img" accept="image/*">
                    </div>
                </div>
                <div class="form-item">
                    <label for="title">タイトル</label>
                    <input type="text" name="title" value="<?= $post_data['title'] ?>">
                    <?php if (!empty($err['title'])): ?>
                        <span><?php echo $err['title']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-item">
                    <label for="description">推しポイント</label>
                    <textarea name="description" id="description"><?= $post_data['description'] ?>"></textarea>
                    <?php if (!empty($err['description'])): ?>
                        <span><?php echo $err['description']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="button-panel">
                    <button type="submit" class="sbmtBtn">更　新</button>
                    <a href="/Review/postShow.php?pid=<?= $post_data['id'] ?>"><input type="button" class="backBtn" value="戻　る"></a>
                </div>

                <div class="button-panel">
                    <a href="postDelete_conf.php?pid=<?= $post_data['id'] ?>"><input type="button" class="dltBtn" value="この投稿を削除する"></a>
                </div>
        </form>
    </div>
</body>
</html>