<?php
session_start();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>新規アカウント登録画面</title>
</head>

<body>
    <?php include('header.php'); ?>

    <div class="form-wrapper">
        <h1 style="padding:30px 0;">新規アカウント登録</h1>
        <form action="signup.php" method="POST">

            <div class="form-item">
                <label for="name">アカウント名<span class="required">*</span></label>
                <input type="text" name="name" id="name" aria-describedby="nameHelpInline">
                <span class="error">
                    <?php if (!empty($err['name'])): ?>
                        <?php echo $err['name']; ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="form-item">
                <label for="email">メールアドレス<span class="required">*</span></label>
                <input type="text" name="email" id="email" aria-describedby="emailHelpInline">
                <span class="error">
                    <?php if (!empty($err['email'])): ?>
                        <?php echo $err['email']; ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="form-item">
                <label for="current_password">パスワード</label>
                <input type="password" name="password" id="password" aria-describedby="passwordHelpInline">
                <span class="error">
                    <?php if (!empty($err['password'])): ?>
                        <?php echo $err['password']; ?>
                    <?php endif; ?>
                </span> 
            </div>

            <div class="form-item">
                <label for="password_conf">パスワード確認</label>
                <input type="password" name="password_conf" id="password_conf" aria-describedby="passwordHelpInline">
                <span class="error">
                    <?php if (!empty($err['password'])): ?>
                        <?php echo $err['password']; ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="form-item">
                <label for="birth">生年月日</label>
                <input type="date" name="birth" id="birth" aria-describedby="birthHelpInline">
                <span class="error">
                    <?php if (!empty($err['birth'])): ?>
                        <?php echo $err['birth']; ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="form-item">
                <label for="person">好きなアーティスト</label>
                <input type="text" name="person" id="person" aria-describedby="personHeliInline">
                <span class="error">
                    <?php if (!empty($err['person'])): ?>
                        <?php echo $err['person']; ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="button-panel">
                <button type="submit" class="button" onclick="return confirm('この内容でよろしいですか？')">登録する</button>
            </div>
        </form>
    </div>
</body>
</html>