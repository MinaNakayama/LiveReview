<?php
require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');
require_once(ROOT_PATH .'/Controllers/function.php');

class User extends Db {

    public function __construct($dbh = null) {
        parent::__construct($dbh);
    }

    /**
     * ユーザーを登録する
     * @param array $userDate
     * @return bool $result
     */
    public static function createUser($userDate) {
        $result = false;

        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        try {
            $dbh->beginTransaction();

            $sql = 'INSERT INTO users (name, email, password)
                    VALUES (:name, :email, :password)';
            $sth = $dbh->prepare($sql);

            $pass_hash = password_hash($userDate['password'], PASSWORD_DEFAULT);

            $sth->bindParam(':name', $userDate['name'], PDO::PARAM_STR);
            $sth->bindParam(':email', $userDate['email'], PDO::PARAM_STR);
            $sth->bindParam(':password', $pass_hash, PDO::PARAM_STR);
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
     * ログイン処理
     * @param string $email
     * @param string $password
     * @return bool $result
     */
    public static function login($email, $password) {
        $result = false;
        //ユーザーをemailから検索して取得
        $user = self::getUserByEmail($email);

        if (!$user) {
            $_SESSION['msg'] = 'メールアドレスが一致しません。';
            return $result;
        }

        //パスワードの照会
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['login_user'] = $user;
            $result = true;
            return $result;
        } else {
            $_SESSION['msg'] = 'パスワードが一致しません。';
            return $result;
        }
    }

    /**
     * emailからユーザーを取得
     * @param string $email
     * @return array|bool $user|false
     */
    public static function getUserByEmail($email) {
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        $sql = ' SELECT * FROM users WHERE email = :email ';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':email', $email, PDO::PARAM_STR);

        try {
            $sth->execute();
            $user = $sth->fetch();
            return $user;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * ログインチェック
     * @param void
     * @return bool $result
     */
    public static function loginCheck() {
        $result = false;

        if (isset($_SESSION['login_user']) && isset($_SESSION['login_user']['id']) > 0) {
            return $result = true;
        }

        return $result;
    }

    /**
     * ログアウト
     */
    public static function logout() {
        $_SESSION = array();
        session_destroy();
    }

    /**
     * サインアップ時のメールアドレス重複チェック
     * @param string $email
     * @return bool $result
     */
    public static function signupCheck($email) {
        $result = false;
        $user = self::getUserByEmail($email);
        if (!$user) {
            //$_SESSION['msg'] = 'メールアドレスが一致しません。';
            $result = true;
            return $result;
        } else {
            //$_SESSION['msg'] = 'パスワードが一致しません。';
            return $result;
        }
    }

    /**
     * プロフィール編集時id取得
     * @param integer $id ユーザーid
     * @return Array $result 指定ユーザーの全データ
     */
    public static function findAllById($id = 0):Array {
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );

        $sql = 'SELECT * FROM users
                WHERE id = :id';
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * プロフィール編集(パスワードなし)
     * @param integer $id ユーザーid
     * @param
     */
    public function userUpdate($id) {
        try {
            $this->dbh->beginTransaction();

            $sql = 'UPDATE users SET name = :name, email = :email, birth = :birth, person = :person WHERE id = :id';
            $sth = $this->dbh->prepare($sql);

            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $sth->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
            $sth->bindParam(':birth', $_POST['birth'], PDO::PARAM_STR);
            $sth->bindParam(':person', $_POST['person'], PDO::PARAM_STR);

            $result = $sth->execute();
            $this->dbh->commit();
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            die('データ更新失敗'.$e -> getMessage());
            exit();
        }
    }

    /**
     * プロフィール編集(パスワード込み)
     * @param integer $id ユーザーid
     * @param
     */
    public function userUpdatePwd($id) {
        try {
            $this->dbh->beginTransaction();

            $sql = 'UPDATE users SET id = :id, name = :name, email = :email, password = :password, birth = :birth, person = :person WHERE id = :id';
            $sth = $this->dbh->prepare($sql);

            $pass_hash = password_hash($id['password'], PASSWORD_DEFAULT);

            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $sth->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
            $sth->bindParam(':password', $pass_hash, PDO::PARAM_STR);
            $sth->bindParam(':birth', $_POST['birth'], PDO::PARAM_STR);
            $sth->bindParam(':person', $_POST['person'], PDO::PARAM_STR);
            $result = $sth->execute();
            $this->dbh->commit();
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            die('データ更新失敗'.$e -> getMessage());
            exit();
        }
        return $result;
    }

    /**
     * ユーザーデータの削除
     * @param Void
     * @return Array $result
     */
    public function userDelete():Array {
        try {
            $this->dbh->beginTransaction();

            $sql = 'DELETE FROM users WHERE id = :id';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->dbh->commit();
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            die('データ更新失敗'.$e ->getMessage());
            exit();
        }
        return $result;
    }

    /**
     * ログイン処理
     * @param string $id ユーザーid
     * @param string $password
     * @return bool $result
     */
    public static function pwdCheck($id, $password) {
        $result = false;
        //ユーザーをidから検索して取得
        $user = self::findAllById($id);

        //パスワードの照会
        if (password_verify($password, $user['password'])) {
            $result = true;
            return $result;
        } else {
            //$_SESSION['msg'] = 'パスワードが一致しません。';
            return $result;
        }
    }
}
?>