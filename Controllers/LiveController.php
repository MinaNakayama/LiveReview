<?php
require_once(ROOT_PATH .'/Models/User.php');
require_once(ROOT_PATH .'/Models/Live.php');
require_once(ROOT_PATH .'/Models/Image.php');
require_once(ROOT_PATH .'/Models/Goods.php');

class LiveController {
    private $request;
    private $User;
    private $Live;
    private $Image;
    private $Goods;

    public function __construct() {
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        $this->User = new User();
        $this->Live = new Live();
        $this->Image = new Image();
        $this->Goods = new Goods();
    }

    public function mypage() {
        if (empty($this->request['get']['id'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }
        $page = 0;
        if (isset($this->request['get']['page'])) {
            $page = $this->request['get']['page'];
        }

        $find_user = $this->User->findAllById($this->request['get']['id']);
        $post_count = $this->Live->countById();
        $find_post = $this->Live->findByTlId($page, $this->request['get']['id']);
        $find_goods = $this->Goods->findGoodsByTlId($page, $this->request['get']['id']);
        $user_goods_count = $this->Goods->GoodsCountByUserId($this->request['get']['id']);
        $params = [
            'find_user' => $find_user,
            'find_post' => $find_post,
            'find_goods' => $find_goods,
            'pages' => $post_count / 15,
            'user_goods_count' => $user_goods_count
        ];
        return $params;
    }

    public function userpage() {
        if (empty($this->request['get']['puid'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }
        $page = 0;
        if (isset($this->request['get']['page'])) {
            $page = $this->request['get']['page'];
        }

        $find_user = $this->User->findAllById($this->request['get']['puid']);
        $post_count = $this->Live->countById();
        $find_post = $this->Live->findByTlId($page, $this->request['get']['puid']);
        $find_goods = $this->Goods->findGoodsByTlId($page, $this->request['get']['puid']);
        $user_goods_count = $this->Goods->GoodsCountByUserId($this->request['get']['puid']);
        $params = [
            'find_user' => $find_user,
            'pages' => $post_count / 15,
            'find_post' => $find_post,
            'find_goods' => $find_goods,
            'user_goods_count' => $user_goods_count
        ];
        return $params;
    }

    public function postEdit() {
        if (empty($this->request['get']['pid'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }
        $post_data = $this->Live->findById();
        $params = [
            'post_data' => $post_data
        ];
        return $params;
    }

    public function postDelete() {
        if (empty($this->request['get']['pid'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }
        $post_delete = $this->Live->postDelete();
        $params = [
            'post_delete' => $post_delete,
        ];
        return $params;
    }

    public function userEdit() {
        if (empty($this->request['get']['id'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }
        if ($_POST) {
            $this->User->userUpdate($this->request['get']['id']);
            //echo $this->request['get']['id'];
            return true;
        } else {
            echo '失敗';
        }
    }

    public function userEdit2() {
        if (empty($this->request['get']['id'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }
        if ($_POST) {
            $this->User->userUpdatePwd($this->request['get']['id']);
            //echo $this->request['get']['id'];
            return true;
        } else {
            echo '失敗';
        }
    }

    public function delete() {
        if (empty($this->request['get']['id'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }

        $delete_user = $this->User->userDelete();
        $delete_image = $this->Image->DeleteImage();
        $params = [
            'delete_user' => $delete_user,
            'delete_image' => $delete_image
        ];
        return $params;
    }

    public function index() {
        if (empty($this->request['get']['id'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }

        $page = 0;
        if (isset($this->request['get']['page'])) {
            $page = $this->request['get']['page'];
        }

        $find_user = $this->User->findAllById($this->request['get']['id']);
        $find_post = $this->Live->findAllByTlId($page);
        $post_count = $this->Live->countAllById();
        $genre = $this->Live->genreAll();
        $params = [
            'find_user' => $find_user,
            'find_post' => $find_post,
            'pages' => $post_count / 15,
            'genre' => $genre
        ];
        return $params;
    }

    public function postShow() {
        if (empty($this->request['get']['pid'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }

        $post_data = $this->Live->findById();
        $goods_count = $this->Goods->GoodsCount($this->request['get']['pid']);
        $params = [
            'post_data' => $post_data,
            'goods_count' => $goods_count
        ];
        return $params;
    }

    public function userEditData() {
        if (empty($this->request['get']['id'])) {
            echo '指定のパラメータが不正です。このページを表示できません。';
            exit;
        }
        
        $find_user = $this->User->findAllById($this->request['get']['id']);
        $params = [
            'find_user' => $find_user
        ];
        return $params;
    }
}