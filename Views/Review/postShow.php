<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];
$post_id = $_GET['pid'];

require_once(ROOT_PATH .'/Controllers/function.php');
require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$params = $live->postShow();
$post_data = $params['post_data'][0];

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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>投稿詳細画面</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(function() {
            $(window).on('load scroll', function() {
                if ($(window).scrollTop() > 700) {
                    $('.jump').fadeIn(700);
                } else {
                    $('.jump').fadeOut(700);
                }
            });
            $('.jump').click(function() {
                $('html,body').animate({scrollTop:0}, 'slow');
            });
        });
    </script>
    <script>
        var user_id = <?php echo $login_user; ?>;
        var post_id = <?php echo $post_id; ?>;
        

        $(document).on('click', '.goods_btn', function(e) {
            e.preventDefault();
            var $this = $(this);

            if ($this.hasClass('.fas2')) {
                $this.removeClass('.fas2');
                $this.addClass('.fas1');
                //$this.css("color", "red");
            } else {
                $this.removeClass('.fas1');
                $this.addClass('.fas2');
                //$this.css("color", "blue");
            }
            
            $.ajax({
                type: 'POST',
                url: 'ajax_goods_process.php',
                dataType: 'text',
                data: { user_id: user_id,
                        post_id: post_id}
            }).done(function(data) {
                location.reload();
                console.log(data);
                //window.alert(post_id);
            }).fail(function() {
                //location.reload();
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
                //window.alert('失敗');
            });
        });
    </script>
</head>

<body>
    <header class="contact">
        <nav class="navigation">
            <div class="logo">
                <a href="index.php?id=<?= $login_user ?>"><img src="/img/logo.png" alt="logo"></a>
            </div>
            <div class="navMenu">
                <div class="menu"><a href="index.php?id=<?= $login_user ?>">ホーム</a></div>
                <div class="menu"><a href="mypage.php?id=<?= $login_user ?>">マイページ</a></div>
                <div class="menu"><a href="newpost.php?id=<?= $login_user ?>">新規投稿</a></div>
                <div class="menu"><a href="search.php?id=<?= $login_user ?>">投稿検索</a></div>
                <div class="menu"><a href="logout.php">ログアウト</a></div>
            </div>
        </nav>
    </header>

    <div class="mpBox">
        <h2>投稿詳細</h2>
        <div class="upper2">
            <?php if ($login_user == $post_data['user_id']): ?>
                <a href="postEdit.php?id=<?= $login_user ?>&pid=<?= $post_data['id'] ?>"><input type="button" class="editBtn" value="投稿編集"></a>
            <?php endif; ?>
        </div>
        <div class="allposts">
            <div class="posts">
                <div class="user-name">
                    <?php if ($login_user == $post_data['user_id']) { ?>
                        <p><a href="mypage.php?id=<?= $login_user ?>"><?php echo $post_data['name']; ?></a></p>
                    <?php } ?>
                </div>
                <p class="post"><?php echo $post_data['title']; ?></p>
                

                <div class="image">
                    <img src="<?php echo '/../../'."{$post_data['image_path']}"; ?>" width="100%" height="100%" alt="...">
                </div>

                <div class="post">
                    <p>推しポイント</p>
                </div>
                <div class="post">
                    <p><?php echo nl2br($post_data['description']); ?></p>
                </div>

                <form class="goods_count" action="" method="post">
                    <input type="hidden" name="user_id" value="<?= $login_user ?>">
                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                    <button type="button" name="goods" class="goods_btn">
                        <?php if (check_goods_duplicate($login_user, $post_id)): ?>
                            <i class="fas fa-heart fas1 fa-2x">♡</i><?= $params['goods_count'] ?>
                        <?php else: ?>
                            <i class="fas fa-heart fas2 fa-2x">♡</i><?= $params['goods_count'] ?>
                        <?php endif; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="jump">▲TOP</div>
</body>
</html>