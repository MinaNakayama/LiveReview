<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];

if ($_SERVER["HTTP_REFERER"] == 'http://localhost:8888/Review/mypage.php?id='.$login_user) {
    unset($_SESSION['u_err']);
}
if (isset($_SESSION['u_err'])) {
    $err = $_SESSION['u_err'];
}
if (!empty($_POST)) {
    require_once(ROOT_PATH .'/Controllers/LiveController.php');
    $live = new LiveController();
    $params = $live->userEdit();

    $url = "mypage.php?id=" . $login_user;
    if ($result = true) {
        header('Location: ' . $url);
    }
}

require_once(ROOT_PATH .'/Controllers/function.php');
require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$params = $live->userEditData();
$user_data = $params['find_user'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>プロフィール編集</title>
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
        <h1 style="padding:30px 0;">プロフィール編集</h1>
        <form action="userEdit_comp.php?id=<?= $login_user ?>" method="POST">

                <div class="form-item">
                    <label for="name">アカウント名<span class="required">*</span></label>
                    <input type="text" name="name" id="name" aria-describedby="nameHelpInline" value="<?= $user_data['name'] ?>">
                    <span class="error">
                        <?php if (!empty($err['name'])): ?>
                            <?php echo $err['name']; ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="form-item">
                    <label for="email">メールアドレス<span class="required">*</span></label>
                    <input type="text" name="email" id="email" aria-describedby="emailHelpInline" value="<?= $user_data['email'] ?>">
                    <span class="error">
                        <?php if (!empty($err['email'])): ?>
                            <?php echo $err['email']; ?>
                        <?php endif; ?>
                    </span>
                </div>

                <p>パスワード変更</p>
                <div class="form-item">
                    <label for="current_password">現在のパスワード</label>
                    <input type="password" name="current_password" id="current_password" aria-describedby="current_passwordHelpInline">
                    <span class="error">
                        <?php if (!empty($err['current_password'])): ?>
                            <?php echo $err['current_password']; ?>
                        <?php endif; ?>
                    </span> 
                </div>

                <div class="form-item">
                    <label for="password">新しいパスワード</label>
                    <input type="password" name="password" id="password" aria-describedby="passwordHelpInline">
                    <span class="error">
                        <?php if (!empty($err['password'])): ?>
                            <?php echo $err['password']; ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="form-item">
                    <label for="birth">生年月日</label>
                    <input type="date" name="birth" id="birth" aria-describedby="birthHelpInline" value="<?= $user_data['birth'] ?>">
                    <span class="error">
                        <?php if (!empty($err['birth'])): ?>
                            <?php echo $err['birth']; ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="form-item">
                    <label for="person">好きなアーティスト</label>
                    <input type="text" name="person" id="person" aria-describedby="personHeliInline" value="<?= $user_data['person'] ?>">
                    <span class="error">
                        <?php if (!empty($err['person'])): ?>
                            <?php echo $err['person']; ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="button-panel">
                    <button type="submit" class="sbmtBtn" onclick="return confirm('この内容でよろしいですか？')">更　新</button>
                    <a href="mypage.php?id=<?= $login_user ?>"><input type="button" class="backBtn" value="戻　る"></a>
                </div>

                <div class="button-panel">
                    <a href="userDelete_conf.php?id=<?= $login_user ?>"><input type="button" class="dltBtn" value="アカウントを削除する"></a>
                </div>
        </form>
    </div>
</body>
</html>