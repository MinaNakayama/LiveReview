<?php
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: err.php');
}

require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');

function check_goods_duplicate($user_id, $post_id) {
    $result = false;

    $dbh = new PDO(
        'mysql:dbname='.DB_NAME.
        ';host='.DB_HOST, DB_USER, DB_PASSWD
    );
    $sql = 'SELECT * FROM goods
            WHERE user_id = :user_id AND post_id = :post_id';
    $sth = $dbh->prepare($sql);
    $sth->execute(array(':user_id' => $user_id,
                        ':post_id' => $post_id));
    $like = $sth->fetch();
    if (!empty($like)) {
        $result = true;
    }
    return $result;

}


if (isset($_POST)) {
    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];
    
    //既に登録されているか確認
    if (check_goods_duplicate($user_id, $post_id)) {
        $action = '解除';
        $sql = 'DELETE FROM goods
                WHERE :user_id = user_id AND :post_id = post_id';
    } else {
        $action = '登録';
        $sql = 'INSERT INTO goods(user_id, post_id)
                VALUES(:user_id, :post_id)';
    }
    
    try {
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':user_id' => $user_id,
                            ':post_id' => $post_id));
    } catch (\Exception $e) {
        error_log('エラー発生:' .$e->getMessage());
        //set_flash('error', ERR_MSG1);
    }
}

?>