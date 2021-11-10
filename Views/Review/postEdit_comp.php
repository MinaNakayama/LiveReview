<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];

require_once(ROOT_PATH .'/Models/Live.php');
//var_dump($_POST);
$lid = $login_user;
$pid = $_GET['pid'];

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

//バリデーション
$err = [];

if (empty($_SERVER["REQUEST_METHOD"] === "POST")) {
    
    $_POST['title'] = htmlspecialchars($_POST['title'], ENT_QUOTES, "UTF-8");
    if (empty($_POST['title'])) {
        $err['title'] = "必須入力です。";
    }

    $_POST['description'] = htmlspecialchars($_POST['description'], ENT_QUOTES, "UTF-8");
    if (empty($_POST['description'])) {
        $err['description'] = "必須入力ですS。";
    }

    if (count($err) > 0) {
        //エラーがあれば戻す
        $_SESSION['pe_err'] = $err;
        $url = "postEdit.php?id=" .$login_user ."&pid=" . $pid;
        header('Location: ' . $url);
    } else {
        if (count($err_msgs) === 0) {
            //ファイルはあるかどうか
            if (is_uploaded_file($tmp_path)) {
                if (move_uploaded_file($tmp_path, $save_path)) {
                    //echo $filename . 'を' . $upload_dir . 'にアップしました。<br>';
                    //DBに画像を保存
                    $result = Live::editPost($_POST, $filename, $save_path, $lid, $pid);
                    if ($result) {
                        //echo 'データベースに保存しました。';
                        $url = "postShow.php?pid=" . $pid;
                        header('Location: ' . $url);
                        return;
                    } else {
                        $err_msgs[] = 'データベースへの保存に失敗しました。';
                    }
                } else {
                    $err_msgs[] = 'ファイルが保存できませんでした。';
                }
            } else {
                $err_msgs[] = 'ファイルが選択されていません。';

                //データベースに保存(ファイルなし)
                $result = Live::editNoFilePost($_POST, $lid, $pid);
                if ($result) {
                    echo 'データベースに保存しました。';
                    $url = "postShow.php?pid=" . $pid;
                    header('Location: ' . $url);
                    return;
                } else {
                    $err_msgs[] = 'データベースへの保存に失敗しました。';
                }
            }
        }
    }
}

?>
