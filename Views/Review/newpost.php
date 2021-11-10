<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
if (isset($_SESSION['p_err'])) {
    $err = $_SESSION['p_err'];
}

require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];

require_once(ROOT_PATH .'/Controllers/function.php');
require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$params = $live->index();

if ($_SERVER["HTTP_REFERER"] = "http://localhost:8888/Review/mypage.php?id=".$login_user) {
    unset($_SESSION['p_err']);
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
    <title>新規投稿</title>
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
        <h1>新規投稿</h1>
        <form enctype="multipart/form-data" action="newpostConf.php?id=<?= $login_user ?>" method="POST">
            
            <input type="hidden" name="user_id" value="<?php echo $login_user; ?>">
            
            <div class="form-item">
                <label for="image">画像</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                <input type="file" name="img" accept="image/*" />
            </div>

            <div class="form-item">
                <label for="genre_id">カテゴリー<span class="required">*</span></label>
                <select id="genre_id" name="genre_id">
                    <option value="">選択してください</option>
                    <?php foreach ($params['genre'] as $genre): ?>
                        <option value="<?= $genre['id'] ?>">
                        <?= $genre['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-item">
                <label for="title">タイトル<span class="required">*</span></label>
                <input type="text" name="title" value="<?php if (!empty($_POST['title'])) {echo $_POST['title']; } ?>">
                <?php if (!empty($err['title'])): ?>
                    <span><?php echo $err['title']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-item">
                <label for="description">推しポイント<span class="required">*</span></label>
                <textarea name="description" id="description"><?php if (!empty($_POST['description'])) {echo $_POST['description']; } ?></textarea>
                <?php if (!empty($err['description'])): ?>
                    <span><?php echo $err['description']; ?></span>
                <?php endif; ?>
            </div>

            <div class="button-panel">
                <button type="submit" class="sbmtBtn">投稿する</button>
            </div>
        </form>
    </div>
</body>
</html>