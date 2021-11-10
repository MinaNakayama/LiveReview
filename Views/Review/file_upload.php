<?php

//ファイル関連の取得
$file = $_FILES['img'];
$filename = basename($file['name']);
$tmp_path = $file['tmp_name'];
$file_err = $file['error'];
$filesize = $file['size'];
$upload_dir = 'upload/';
$save_filename = date('YmdHis') . $filename;
$err_msgs = [];
$save_path = $upload_dir . $save_filename;
$id = 0;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

//ファイルのバリデーション
//ファイルサイズが1MB未満か
if ($filesize > 1048576 || $file_err == 2) {
    $err_msgs[] = 'ファイルサイズは1MB未満にしてください。';
}

//拡張は画像形式か
$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

if (!in_array(strtolower($file_ext), $allow_ext)) {
    $err_msgs[] = '画像ファイルを添付してください。';
}

if (count($err_msgs) === 0) {
    //ファイルはあるかどうか
    if (is_uploaded_file($tmp_path)) {
        if (move_uploaded_file($tmp_path, $save_path)) {
            //echo $filename . 'を' . $upload_dir . 'にアップしました。<br>';
            //DBに画像を保存
            $result = Image::fileSave($filename, $save_path, $id);
            if ($result) {
                //echo 'データベースに保存しました。';
            } else {
                $err_msgs[] = 'データベースへの保存に失敗しました。';
            }
        } else {
            $err_msgs[] = 'ファイルが保存できませんでした。';
        }
    } else {
        $err_msgs[] = 'ファイルが選択されていません。';
    }
}

$url = "mypage.php?id=" . $id;
$result = Image::fileSave($filename, $save_path, $id);
if ($result) {
    header('Location: ' . $url);
    return;
}

?>