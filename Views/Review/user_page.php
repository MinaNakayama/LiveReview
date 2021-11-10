<?php
session_start();
if ($_SERVER["HTTP_REFERER"]) {
    header('Location: error.php');
}
require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];

require_once(ROOT_PATH .'/Controllers/function.php');
require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$params = $live->userpage();

require_once(ROOT_PATH .'/database.php');
require_once(ROOT_PATH .'/Models/Db.php');

function get_user($user_id) {
    try {
        $dbh = new PDO(
            'mysql:dbname='.DB_NAME.
            ';host='.DB_HOST, DB_USER, DB_PASSWD
        );
        $sql = 'SELECT * FROM users
                WHERE id = :id';
        $sth = $dbh->prepare($sql);
        $sth->execute(array(':id' => $user_id));
        return $sth->fetch();
    } catch (\Exception $e) {
        error_log('エラー発生'.$e->getMessage());
        //set_flash('error', ERR_MSG1);
    }
}

$current_user = get_user($login_user);
$profile_user = get_user($_GET['puid']);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>ユーザーぺージ</title>
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
        <div class="upper2">
            <h2><?= $params['find_user']['name'] ?></h2>
            <input type="hidden" name="current_user_id" value="<?= $current_user['id'] ?>">
        </div>
            
        <div class="upper2">
            <p>生年月日</p>
            <?= $params['find_user']['birth'] ?>
        </div>

        <div class="upper2">
            <p>好きなアーティスト</p>
            <?= $params['find_user']['person'] ?>
        </div>

        <div class="upper2">
            <p><?= $params['find_user']['name'] ?>の投稿一覧</p>
        </div>

        <section>
            <div class="allposts">
                <?php foreach ($params['find_post'] as $post): ?>
                    <div class="posts">
                        <div class="image">
                            <img src="<?php echo '/../'."{$post['image_path']}"; ?>" width="100%" height="100%" alt="...">
                            <div class="post">
                                <h5 class="title"><?= $post['title'] ?></h5>
                                <div class="user-name">
                                    <?php if ($login_user == $post['user_id']) { ?>
                                        <p class="name"><a href="mypage.php?id=<?= $login_user ?>"><?php echo $post['name']; ?></a></p>
                                    <?php } else { ?>
                                        <p class="name"><a href="user_page.php?puid=<?= $post['user_id'] ?>"><?php echo $post['name']; ?></a></p>
                                    <?php } ?>
                                </div>
                                <p class="name"><?= $post['created_at'] ?></p>
                            </div>
                            <div class="post-end"><a href="postSow.php?pid=<?= $post['id'] ?>">詳細</a></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="pages">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i=0; $i<=$params['pages']; $i++): ?>
                        <?php if (isset($_GET['page']) && $_GET['page'] == $i): ?>
                            <li class="page-item"><span><a class="page-link" href="user_page.php?id=<?= $login_user ?>&page=<?= $i ?>"><?php echo $i+1; ?></a></span></li>
                        <?php else: ?>
                            <li class="page-item"><span><a class="page-link" href="user_page.php?id=<?= $login_user ?>&page=<?= $i ?>"><?php echo $i+1; ?></a></span></li>
                        <?php endif; ?>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</body>
</html>