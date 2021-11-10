<?php
require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');
require_once(ROOT_PATH .'/Controllers/function.php');

class Goods extends Db {

    public function __construct($dbh = null) {
        parent::__construct($dbh);
    }

    /**
     * 指定ユーザーがいいねをした投稿のデータを取得
     * 1ページ15件ごとの表示
     * @param integer $page ページ番号
     * @param Void
     * @return Array $result
     */
    public static function findGoodsByTlId($page = 0, $user_id):Array {
        $result = false;

        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        $sql = 'SELECT p.*, u.name
                FROM post AS p
                JOIN users AS u ON p.user_id = u.id
                JOIN goods AS go ON go.post_id = p.id
                WHERE go.user_id = :id
                LIMIT 15 OFFSET '.(15 * $page);
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 指定ユーザーのいいね数を取得
     * @param integer $id ユーザーid
     * @return Int $result カウントした数
     */
    public static function GoodsCountByUserId($id = 0) {
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );
        $sql = 'SELECT COUNT(*)
                FROM goods 
                WHERE user_id= :id';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchColumn();
        return $result;
    }

    /**
     * 指定の投稿idのいいね数を取得
     * @param integer $id ユーザーid
     * @return Int $result カウントした数
     */
    public static function GoodsCount($pid = 0) {
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );
        $sql = 'SELECT COUNT(*)
                FROM goods
                WHERE post_id = :id';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':id', $pid, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchColumn();
        return $result;
    }
}

?>