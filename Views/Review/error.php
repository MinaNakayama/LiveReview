<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>マイページ</title>
</head>

<body>
    <?php include ('header.php'); ?>
    
    <div class="contactBox">
        <h2>エラーが発生しました。</h2>
        <div class="completeMsg">
            <form action="" method="POST">
                <p>不正なアクセスです。</p>

                <a href="javascript:history.back();">前の画面に戻る</a>
            </form>
        </div>
    </div>
    <div class="button-panel">
        <a href="login_form.php"><input type="button" class="button" value="ログイン画面へ"></a>
    </div>
</body>
</html>