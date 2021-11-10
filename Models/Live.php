<?php
require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');
require_once(ROOT_PATH .'/Controllers/function.php');

class Live extends Db {
    public function __construct($dbh = null) {
        parent::__construct($dbh);
    }

    /**
     * 新規投稿(ファイルあり)
     * @param array $postData
     * @param string $id ユーザーid
     * @return bool $result
     */
    public static function createPost($postData, $filename, $save_path, $id) {
        $result = false;
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );
        try {
            $dbh->beginTransaction();

            $sql = 'INSERT INTO post (user_id, image_name, image_path, genre_id, title, description)
                    VALUES (:id, :image_name, :image_path, :genre_id, :title, :description)';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':image_name', $filename, PDO::PARAM_STR);
            $sth->bindParam(':image_path', $save_path, PDO::PARAM_STR);
            $sth->bindParam(':genre_id', $postData['genre_id'], PDO::PARAM_INT);
            $sth->bindParam(':title', $postData['title'], PDO::PARAM_STR);
            $sth->bindParam(':description', $postData['description'], PDO::PARAM_STR);
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
     * 新規投稿(ファイルなし)
     * @param array $postData
     * @param string $id ユーザーid
     * @return $result
     */
    public static function createNoFilePost($postData, $id) {
        $result = false;

        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        try {
            $dbh->beginTransaction();

            $sql = 'INSERT INTO post (user_id, genre_id, title, description)
                    VALUES (:id, :genre_id, :title, :description)';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':genre_id', $postData['genre_id'], PDO::PARAM_INT);
            $sth->bindParam(':title', $postData['title'], PDO::PARAM_STR);
            $sth->bindParam(':description', $postData['description'], PDO::PARAM_STR);

            $result = $sth->execute();
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            die('データ更新失敗'.$e -> getMessage());
            exit();
        }
        return $result;
    }

    //カテゴリー取得
    public function genreAll():Array {
        $sql = 'SELECT * FROM genre';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //マイページ一覧表示時投稿取得
    public function findAll():Array {
        $sql = 'SELECT p.*, u.name
                FROM post AS p
                JOIN users AS u ON p.user_id = u.id';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * postテーブルから指定ユーザーidの表示データを取得
     * 1ページ15件ごとの表示
     * @param integer $page ページ番号
     * @param Void
     * @return Array $result
     */
    public function findByTlId($page = 0, $user_id):Array {
        $result = false;

        $sql = 'SELECT p.*, u.name
                FROM post AS p
                JOIN users AS u ON p.user_id = u.id
                WHERE u.id = :id
                ORDER BY id DESC
                LIMIT 15 OFFSET '.(15 * $page);
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * postテーブルから全ユーザーのTL表示データを取得
     * 1ページ15件ごとの表示
     * @param integer $page ページ番号
     * @param Void
     * @return Array $result
     */
    public function findAllByTlId($page = 0):Array {
        $result = false;

        $sql = 'SELECT p.*, u.name
                FROM post AS p
                JOIN users AS u ON p.user_id = u.id
                WHERE u.id = :id
                ORDER BY id DESC
                LIMIT 15 OFFSET '.(15 * $page);
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
    * postテーブルから指定ユーザーidのデータ数を取得
    *
    *@return INT $count 全ユーザーの件数
    */
    public function countById():Int {
        $sql = 'SELECT count(*) as count FROM post
                WHERE user_id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    /**
    * postテーブルから全ユーザーのデータ数を取得
    * @return INT $count 全ユーザーの件数
    */
    public function countAllById():Int {
        $sql = 'SELECT count(*) as count FROM post
                WHERE user_id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $sth->execute();
        $count = $sth->fetchColumn();
        return $count;
    }

    //マイページいいね一覧
    public function findGoods($user_id):Array {
        $sql = 'SELECT go.*, p.*,u.name, go.id FROM goods AS go
                JOIN post p ON go.post_id = p.id
                JOIN users u ON go.user_id = u.id
                WHERE go.user_id = :user_id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //アカウント名取得
    public function findName():Array {
        $sql = 'SELECT u.name FROM users AS u
                JOIN post AS p ON u.id = p.user_id';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * postテーブルから指定idの全データとユーザー名を取得
     * @param Void
     * @return Array $result
     */
    public function findById():Array {
        $sql = 'SELECT p.*, u.name FROM post AS p
                JOIN users AS u ON p.user_id = u.id
                WHERE p.id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $_GET['pid'], PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * postテーブルから指定ユーザーidの全データを取得
     * @param Void
     * @return Array $result
     */
    public static function findByUserId():Array {
        $result = false;

        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        $sql = 'SELECT * FROM post
                WHERE user_id = :id';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 指定idの投稿編集(ファイルあり)
     * @param integer $id ユーザーid
     * @param
     */
    public static function editPost($postData, $filename, $save_path, $lid, $pid) {
        $result = false;

        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        try {
            $sql = 'UPDATE post SET title = :title, description = :description, image_name = :image_name, image_path = :image_path, user_id = :user_id, genre_id = :genre_id 
                    WHERE id = :pid';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':pid', $pid, PDO::PARAM_INT);
            $sth->bindParam(':title', $postData['title'], PDO::PARAM_STR);
            $sth->bindParam(':description', $postData['description'], PDO::PARAM_STR);
            $sth->bindParam(':image_name', $filename, PDO::PARAM_STR);
            $sth->bindParam(':image_path', $save_path, PDO::PARAM_STR);
            $sth->bindParam(':user_id', $lid, PDO::PARAM_INT);
            $sth->bindParam(':genre_id', $postData['genre_id'], PDO::PARAM_INT);
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
     * 指定idの投稿編集(ファイルなし)
     * @param integer $id ユーザーid
     * @param
     */
    public static function editNoFilePost($postData, $lid, $pid) {
        $result = false;

        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        try {
            $sql = 'UPDATE post SET title = :title, description = :description, user_id = :user_id, genre_id = :genre_id 
                    WHERE id = :pid';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':pid', $pid, PDO::PARAM_INT);
            $sth->bindParam(':title', $postData['title'], PDO::PARAM_STR);
            $sth->bindParam(':description', $postData['description'], PDO::PARAM_STR);
            $sth->bindParam(':user_id', $lid, PDO::PARAM_INT);
            $sth->bindParam(':genre_id', $postData['genre_id'], PDO::PARAM_INT);
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
     * postテーブルから一覧表示データを取得
     * 1ページ30件ごとの表示
     * @param integer $page ページ番号
     * @param Void
     * @return Array $result
     */
    public function findAllList($page = 0):Array {
        $result = false;

        $sql = 'SELECT p.id, p.user_id, u.name, p.title, p.created_at, p.update_at
                FROM post AS p
                JOIN users u ON p.user_id = u.id
                ORDER BY p.id DESC
                LIMIT 30 OFFSET '.(30 * $page);
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 投稿削除
     * @param Void
     * @return Array $result
     */
    public function postDelete():Array {
        try {
            $this->dbh->beginTransaction();

            $sql = 'DELETE FROM post WHERE id = :id';
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

    /**
     * 指定idの画像データを削除
     * @param Void
     * @return Array $result
     */
    public static function deleteFile():Array {
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );
        try {
            $dbh->beginTransaction();

            $sql = 'UPDATE post
                    SET image_name = "", image_path = ""
                    WHERE id = :id';
            $sth = $dbh->prepare($sql);
            $sth->bindParam(':id', $_GET['pid'], PDO::PARAM_INT);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            die('データ更新失敗'.$e -> getMessage());
            exit();
        }
        return $result;
    }

    //検索
    public function search($search_name):Array {
        $sql = 'SELECT p.*, u.name, ge.name
                FROM post AS p
                JOIN users AS u ON p.user_id = u.id
                JOIN genre AS ge ON p.genre_id = ge.id
                WHERE (u.name LIKE '%$search_name%' OR ge.name LIKE '%$search_name%')
                ORDER BY id DESC';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //投稿詳細表示
    public function findByPostId():Array {
        $sql = 'SELECT *, u.name, p.genre_id, p.id FROM post AS p
                JOIN users u ON p.user_id = u.id
                WHERE p.id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    
}