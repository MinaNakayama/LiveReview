<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');
require_once(ROOT_PATH .'/Controllers/function.php');

$email = $_POST['email'];
$token = $_POST['token'];
//CSRFチェック
if ($_SESSION['token'] != $token) {
    $_SESSION['error_status'] = 3;
    header('Location: pwd_reset.php');
    exit();
}
try {
    //DB接続
    $dbh = new PDO(
        'mysql:dbname='.DB_NAME.
        ';host='.DB_HOST, DB_USER, DB_PASSWD
    );
    //プレースホルダでSQL作成
    $sql = "SELECT * FROM users WHERE email = ?;";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //IDが存在しない
    if (empty($row)) {
        $_SESSION['error_status'] = 2;
        header('Location: pwd_reset.php');
        exit();
    }
    //リセット処理
    $mail = $row['email'];
    //URLパスワードを作成
    $url_pass = get_url_password();
    //プレースホルダでSQL作成
    $sql = "UPDATE users SET reset = 1, temp_pass = ?, temp_limit_time = ? WHERE email = ?;";
    $stmt = $dbh->prepare($sql);
    //トランザクションの開始
    $dbh->beginTransaction();
    try {
        $stmt->bindValue(1, $url_pass, PDO::PARAM_STR);
        $stmt->bindValue(2, date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(3, $email, PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
    } catch (PDOException $e) {
        $dbh->rollBack();
        throw $e;
    }
} catch (PDOException $e) {
    die($e->getMessage());
}
//メール送信
//メールヘッダーインジェクション対策
$mail = str_replace(array('\r\n','\r','\n'), '', $mail);
$msg = '以下のアドレスからパスワードのリセットを行ってください。' . PHP_EOL;
$msg .= 'アドレスの有効時間は10分間です。' . PHP_EOL . PHP_EOL;
$msg .= 'http://localhost:8888/Review/pwd_reset_url.php?' . $url_pass;
$result = mb_send_mail($mail, 'パスワードの再設定', $msg, ' From : 【メールアドレス】');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>パスワード再設定完了画面</title>
</head>

<body>
    <?php include ('header.php'); ?>

    <form action="" method="POST">
        <div class="contactBox">
            <h2>メール送信完了</h2>
            <div class="completeMsg">
                <p>受信したメールの案内に従い、パスワードの再設定をお願いします。</p>

                <div class="button-panel">
                    <a href="login_form.php"><input type="button" class="button" value="ログイン画面へ"></a>
                </div>
            </div>
        </div>
    </form>
</body>
</html>