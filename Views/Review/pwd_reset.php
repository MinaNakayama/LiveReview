<?php
require_once(ROOT_PATH .'/Controllers/function.php');
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
//CSRF トークン
$_SESSION['token'] = get_csrf_token();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>パスワード再設定</title>
</head>

<body>
    <?php include ('header.php'); ?>

    <section>
        <form action="pwd_reset_mail.php" method="POST">
            <div class="contactBox">
                <h2>パスワード再設定</h2>
                <div class="completeMsg">
                    <?php
                    if (!empty($_SESSION['error_status'])) {
                        if ($_SESSION['error_status'] == 1) {
                            echo "<p style='color:red;'>パスワードをリセットしてください。</p>";
                        }
                        if ($_SESSION['error_status'] == 2) {
                            echo "<p style='color:red;'>入力内容に誤りがあります。</p>";
                        }
                        if ($_SESSION['error_status'] == 3) {
                            echo "<p style='color:red;'>不正なリクエストです。</p>";
                        }
                        if ($_SESSION['error_status'] == 4) {
                            echo "<p style='color:red;'>タイムアウトか不正なURLです。</p>";
                        }
                        //エラー情報のリセット
                        $_SESSION['error_status'] = 0;
                    }
                    ?>

                    <p>登録されたメールアドレスを入力し「送信」ボタンを押してください。<br>
                    パスワード再設定用のアドレスを登録メールアドレスに送信いたします。</p>

                    <div class="form-item">
                        <label for="email">ご登録のメールアドレス</label>
                        <input type="text" name="email" id="email" aria-describedby="emailHelpInline">
                    </div>

                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token'], ENT_QUOTES, "UTF-8") ?>">

                    <div class="button-panel">
                        <button type="submit" class="sbmtBtn">送　信</button>
                        <a href="login_form.php"><input type="button" class="backBtn" value="戻　る"></a>
                    </div>
                </div>
            </div>
        </form>
    </section>
</body>
</html>