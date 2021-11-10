<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];
//var_dump($_SESSION);

//バリデーション
$err = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once(ROOT_PATH .'/Models/User.php');
    $result = User::signupCheck($_POST['email']);

    if (empty($_POST['password'])) {

        $_POST['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
        if (empty($_POST['name'])) {
            $err['name'] = 'アカウント名は必須入力です。20文字以内でご入力ください。';
        } elseif (mb_strlen($_POST['name']) > 20) {
            $err['name'] = 'アカウント名は20文字以内でご入力ください。';
        }
        $_POST['email'] = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
        if (empty($_POST['email'])) {
            $err['email'] = 'メールアドレスは必須入力です。';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $err['email'] = 'メールアドレスは正しくご入力ください。';  
        } elseif ((!$result && $login_user) != $_POST['email']) {
            $err['email'] = 'このメールアドレスは既に使われています。';
        }
        $_POST['birth'] = htmlspecialchars($_POST['birth'], ENT_QUOTES, "UTF-8");
        if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['birth'])) {
            $err['birth'] = '存在しない日にちです。';
        }
        $_POST['person'] = htmlspecialchars($_POST['person'], ENT_QUOTES,"UTF-8");
        if (mb_strlen($_POST['person']) > 50) {
            $err['person'] = '50文字以内でご入力ください。';
        }
        if (count($err) > 0) {
            //エラーがあれば戻す
            $_SESSION['u_err'] = $err;
            $url = "userEdit.php?id=" . $login_user;
            header('Location: ' . $url);
            return;
        } else {
            require_once(ROOT_PATH .'/Controllers/LiveController.php');
            $live = new LiveController();
            $result = $live->userEdit();

            $url = "mypage.php?id=" . $login_user;
            if ($result) {
                header('Location: ' . $url);
            }
        }
    } else {
        //パスワード処理判定
        require_once(ROOT_PATH .'/Models/User.php');
        $result2 = User::pwdCheck($login_user, $_POST['current_password']);

        $_POST['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
        if (empty($_POST['name'])) {
            $err['name'] = 'アカウント名は必須入力です。20文字以内でご入力ください。';
        } elseif (mb_strlen($_POST['name']) > 20) {
            $err['name'] = 'アカウント名は20文字以内でご入力ください。';
        }
        $_POST['email'] = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");
        if (empty($_POST['email'])) {
            $err['email'] = 'メールアドレスは必須入力です。';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $err['email'] = 'メールアドレスは正しくご入力ください。';  
        } elseif (!$result && $login_user['email'] != $_POST['email']) {
            $err['email'] = 'このメールアドレスは既に使われています。';
        }
        $_POST['current_password'] = htmlspecialchars($_POST['current_password'], ENT_QUOTES, "UTF-8");
        if (empty($_POST['current_password'])) {
            $err['current_password'] = 'パスワードを変更する場合、現在のパスワードは必須入力です。';
        } elseif (!$result2) {
            $err['current_password'] = 'ご登録のパスワードと一致しません。';
        }
        $_POST['password'] = htmlspecialchars($_POST['password'], ENT_QUOTES, "UTF-8");
        if (!preg_match("/\A[a-z\d]{8,20}+\z/i", $_POST['password'])) {
            $err['password'] = 'パスワードは8文字以上20文字以下にしてください。';
        }
        $_POST['birth'] = htmlspecialchars($_POST['birth'], ENT_QUOTES, "UTF-8");
        if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['birth'])) {
            $err['birth'] = '存在しない日にちです。';
        }
        $_POST['person'] = htmlspecialchars($_POST['person'], ENT_QUOTES,"UTF-8");
        if (mb_strlen($_POST['person']) > 50) {
            $err['person'] = '50文字以内でご入力ください。';
        }

        if (count($err) > 0) {
            //エラーがあれば戻す
            $_SESSION['u_err'] = $err;
            $url = "userEdit.php?id=" . $login_user;
            header('Location: ' . $url);
            return;
        } else {
            require_once(ROOT_PATH .'/Controllers/LiveController.php');
            $live = new LiveController();
            $result = $live->userEdit2();

            $url = "mypage.php?id=" . $login_user;
            if ($result) {
                header('Location: ' . $url);
            }
        }
    }
}
?>
