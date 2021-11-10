<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];
$post_id = $_GET['pid'];

require_once(ROOT_PATH .'/Models/Live.php');
$result2 = Live::deleteFile();
if ($result2) {
    $url = "postEdit.php?id=" .$login_user ."&pid=" .$post_id;
    header('Location: ' . $url);
}

?>