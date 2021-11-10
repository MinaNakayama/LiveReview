<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];

$file = $_FILES['img'];
$filename = basename($file['name']);
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];
$upload_dir = 'photodata/';
$save_filename = date('YmdHis') . $filename;
$err_msgs = [];
$save_path = $upload_dir . $save_filename;

//ファイルのバリデーション
//ファイルサイズが1MB未満か
if ($filesize > 1048576 || $file_err == 2) {
    $err_msgs[] = 'ファイルサイズは1MB未満にしてください。';
}

//拡張は画像形式か
$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

if (count($err_msgs) === 0) {
    //ファイルはあるかどうか
    if (is_uploaded_file($tmp_path)) {
        if (move_uploaded_file($tmp_path, $save_path)) {
            //echo $filename . 'を' . $upload_dir . 'にアップしました。<br>';
        } else {
            $err_msgs[] = 'ファイルが保存できませんでした。';
        }
    } else {
        $err_msgs[] = 'ファイルが選択されていません。';
    }
}

require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$params = $live->index();

//バリデーション
$err = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $_POST['title'] = htmlspecialchars($_POST['title'], ENT_QUOTES, "UTF-8");
    if (empty($_POST['title'])) {
        $err['title'] = "必須入力です。";
    }

    $_POST['description'] = htmlspecialchars($_POST['description'], ENT_QUOTES,"UTF-8");
    if (empty($_POST['description'])) {
        $err['description'] = "必須入力です。";
    }

    if (count($err) > 0) {
        //エラーがあった場合は戻す
        $_SESSION['p_err'] = $err;
        $url = "newpost.php?id=" . $login_user;
        header('Location: ' . $url);
    }
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
    <title>新規投稿確認</title>
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
        <h1>新規投稿 内容確認</h1>
        <form enctype="multipart/form-data" action="newpostComp.php?id=<?= $login_user ?>" method="POST">
            <div class="form-item">
                <label for="image">画像</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                <!--<input name="img" type="file" accept="image/*">-->
                <input type="text" name="name" value="<?php echo $_FILES['img']['name']; ?>" readonly onFocus="this.blur()" onSelectStart="return false">
                <input type="hidden" name="tmp_name" value="<?php echo $_FILES['img']['tmp_name']; ?>">
                <input type="hidden" name="error" value="<?php echo $_FILES['img']['error']; ?>">
                <input type="hidden" name="size" value="<?php echo $_FILES['img']['size']; ?>">
                <input type="hidden" name="save_path" value="<?php echo $save_path; ?>">
            </div>
            <div class="form-item">
                <label for="genre_name">カテゴリー<span class="required">*</span></label>
                <input type="text" name="genre_id" value="<?php echo $_POST['genre_id']; ?>"> 
            </div>
            <div class="form-item">
                <label for="title">タイトル<span class="required">*</span></label>
                <input type="text" name="title" value="<?php echo $_POST['title']; ?>" readonly onFocus="this.blur()" onSelectStart="return false">
            </div>
            <div class="form-item">
                <label for="description">推しポイント<span class="required">*</span></label>
                <textarea name="description" id="description"><?php echo nl2br($_POST['description']);  ?></textarea>
            </div>
            <div class="button-panel">
                <button type="submit" class="sbmtBtn">投稿する</button>
                <button class="backBtn"><a href="newpost.php?id=<?= $params['find_user']['id'] ?>">戻　る</a></button>
            </div>
        </form>
    </div>
</body>
</html>
