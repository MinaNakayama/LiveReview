<?php
session_start();
if (empty($_SERVER["HTTP_REFERER"])) {
    header('Location: error.php');
}

require_once(ROOT_PATH .'/Models/User.php');
$result = User::loginCheck();
$login_user = $_SESSION['login_user']['id'];

require_once(ROOT_PATH .'/Controllers/function.php');
require_once(ROOT_PATH .'/Controllers/LiveController.php');
$live = new LiveController();
$params = $live->index();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/base.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>LiveReview</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(function() {
            $(window).on('load scroll', function() {
                if ($(window).scrollTop() > 500) {
                    $('.jump').fadeIn(500);
                } else {
                    $('.jump').fadeOut(500);
                }
            });
            $('.jump').click(function() {
                $('html,body').animate({scrollTop:0}, 'slow');
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
        <div class="upper">
            <h1 class="concept">好きを共有しよう</h1>
        </div>

        <section>
            <div class="upper2">
                <h2>投稿一覧</h2>
            </div>
            <div class="allposts">
                <?php foreach ($params['find_post'] as $post): ?>
                    <div class="posts">
                        <div class="user-name">
                            <?php if ($login_user == $post['user_id']) { ?>
                                <p class="name"><a href="mypage.php?id=<?= $login_user ?>"><?php echo $post['name']; ?></a></p>
                            <?php } else { ?>
                                <p class="name"><a href="user_page.php?puid=<?= $post['user_id'] ?>"><?php echo $post['name']; ?></a></p>
                            <?php } ?>
                        </div>
                        <p class="title"><?= $post['title'] ?></p>
                        <div class="image">
                            <img src="<?php echo '/../'."{$post['image_path']}"; ?>" width="100%" height="100%" alt="...">
                        </div>
                        <div class="post">
                            <p class="name"><?= $post['created_at'] ?></p>
                        </div>
                        <div class="post-end"><a href="postShow.php?pid=<?= $post['id'] ?>">詳細</a></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="pages">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i=0; $i<=$params['pages']; $i++): ?>
                        <?php if (isset($_GET['page']) && $_GET['page'] == $i): ?>
                            <li class="page-item"><span><a class="page-link" href="index.php?id=<?= $login_user ?>&page=<?= $i ?>"><?php echo $i+1; ?></a></span></li>
                        <?php else: ?>
                            <li class="page-item"><span><a class="page-link" href="index.php?id=<?= $login_user ?>&page=<?= $i ?>"><?php echo $i+1; ?></a></span></li>
                        <?php endif; ?>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
    <div class="jump">▲TOP</div>
</body>
</html>

