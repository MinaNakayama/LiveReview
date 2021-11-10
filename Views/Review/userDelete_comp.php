<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];

require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$result = $live->delete();

if ($result) {
    header('Location: login_form.php');
}
?>