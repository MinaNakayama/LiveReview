<?php
require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');
require_once(ROOT_PATH .'/Controllers/function.php');

class Image extends Db {

    public function __construct($dbh = null) {
        parent::__construct($dbh);
    }

    /**
     * ファイルデータを保存
     * (user_idの値がなければUPDATE、なければINSERT)
     * @param string $filename ファイル名
     * @param string $save_path 保存先のパス
     * @param string $id ユーザーid
     * @return bool $result
     */
    public static function fileSave($filename, $save_path, $id) {
        $result = false;
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        try {
            $dbh->beginTransaction();

            $sql = 'INSERT INTO images (user_id, name, path) VALUES (:id, :name, :path)
                    ON DUPLICATE KEY UPDATE name = :name, path = :path';
            
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':name', $filename, PDO::PARAM_STR);
            $sth->bindParam(':path', $save_path, PDO::PARAM_STR);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);

            $result = $sth->execute();
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            die('データ更新失敗'.$e -> getMessage());
            exit();
        }
        return $result;
    }

    /**
     * ファイルデータを取得
     * @return array $fileData
     */
    public function getAllFile() {
        $sql = 'SELECT * FROM images';

        $fileData = $this->dbh->prepare($sql);
        return $fileData;
    }

    /**
     * 投稿画像データの削除
     * @param Void
     * @return Array $result
     */
    public function DeleteImage():Array {
        try {
            $this->dbh->beginTransaction();

            $sql = 'DELETE FROM images WHERE user_id = :id';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->dbh->commit();
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            die('データ更新失敗'.$e -> getMessage());
            exit();
        }
        return $result;
    }
}
?>