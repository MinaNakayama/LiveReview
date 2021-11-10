<?php
session_start();
require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');
require_once(ROOT_PATH .'/Controllers/function.php');

//URLからパラメータを取得
$url_pass = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
//CSRF
$_SESSION['token'] = get_csrf_token();
//ユーザー正式登録
try {
    //DB接続
    $dbh = new PDO(
        'mysql:dbname='.DB_NAME.
        ';host='.DB_HOST, DB_USER, DB_PASSWD
    );
    //10分前の時刻を取得
    $datetime = new DateTime('- 10 min');
    //プレースホルダでSQL作成
    $sql = "SELECT * FROM users WHERE temp_pass = ? AND temp_limit_time >= ?;";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $url_pass, PDO::PARAM_STR);
    $stmt->bindValue(2, $datetime->format('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //URLが不正か期限切れ
    if (empty($row)) {
        $_SESSION['error_status'] = 4;
        header('Location: pwd_reset.php');
        exit();
    }
    $_SESSION['id'] = $row['id'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['url_pass'] = $url_pass; //エラー制御のため格納
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
    <title>サインアップ</title>
</head>

<body>
    <?php include ('header.php'); ?>
    
    <div class="form-wrapper">
        <h1>パスワード再設定</h1>
        <form action="pwd_reset_comp.php" method="POST">

            <div class="form-item">
                <label for="password">新しいパスワード</label>
                <input type="password" name="password" id="password" aria-describedby="passwordHelpInline">
                <span class="required">8文字以上20文字以内</span>
            </div>

            <div class="form-item">
                <label for="confirm_password">パスワード確認</label>
                <input type="password" name="confirm_password" id="confirm_password" aria-describedby="passwordHelpInline">
                <span class="requiresd"></span>
            </div>

            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token'], ENT_QUOTES, "UTF-8"); ?>">

            <div class="button-panel">
                <button type="submit" class="button">登　録</button>
            </div>
        </form>
    </div>
</body>
</html>