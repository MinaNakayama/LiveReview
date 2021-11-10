<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');
require_once(ROOT_PATH .'/Controllers/function.php');
$email = $_SESSION['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$token = $_POST['token'];
//CSRFエラー
if ($token != $_SESSION['token']) {
    $_SESSION['error_status'] = 2;
    header('Location: login_form.php');
    exit();
}
//パスワード不一致
if ($password != $confirm_password) {
    $_SESSION['error_status'] = 1;
    header('Location: pwd_reset_url.php?' . $_SESSION['url_pass']);
    exit();
}
//パスワード更新
try {
    //DB接続
    $dbh = new PDO(
        'mysql:dbname='.DB_NAME.
        ';host='.DB_HOST, DB_USER, DB_PASSWD
    );
    //プレースホルダでSQL作成
    $sql = "SELECT * FROM users WHERE email = ? AND reset = 1;";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($row)) {
        $_SESSION['error_status'] = 3;
        header('Location: pwd_reset.php');
        exit();
    }
    $mail = $row['email'];
    //プレースホルダでSQL作成
    $sql = "UPDATE users SET reset = 0, is_user = 1, password = ?, last_change_pass_time = ? WHERE email = ?;";
    $stmt = $dbh->prepare($sql);
    //パスワードハッシュ化
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    //トランザクションの開始
    $dbh->beginTransaction();
    try {
        $stmt->bindValue(1, $hash_password, PDO::PARAM_STR);
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
            <div class="completeMsg">
                <p>パスワードの再設定が完了しました。</p>
                <p>ログインページからログインしてください。</p>

                <div class="button-panel">
                    <a href="login_form.php"><input type="button" class="button" value="ログイン画面へ"></a>
                </div>
            </div>
        </div>
    </form>
</body>
</html>

