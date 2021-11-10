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

require_once(ROOT_PATH .'database.php');
require_once(ROOT_PATH .'/Models/Db.php');

if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
    $search_value = $search;
    //var_dump($search);

    $dbh = new PDO(
        'mysql:dbname='.DB_NAME.
        ';host='.DB_HOST, DB_USER, DB_PASSWD
    );

    $sql = "SELECT p.*, u.name, ge.name
            FROM post AS p
            JOIN users AS u ON p.user_id = u.id
            JOIN genre AS ge ON p.genre_id = ge.id
            WHERE p.title LIKE '%$search%' OR ge.name LIKE '%$search%'
            ORDER BY id DESC";
    $stmt = array();
    //echo $sql;
    foreach ($dbh->query($sql) as $row) {
        array_push($stmt,$row);
    }
} else {
    $search = '';
    $search_value = '';
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
    <title>検索結果画面</title>
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
                <div class="menu"><a href="logout.php">ログアウト</a></div>
            </div>
        </nav>
    </header>

    <div class="search">
        <form action="" method="get">
            <h2>キーワードを検索</h2>
            <div class="search2">
                <input type="hidden" name="id" value="<?= $login_user ?>">
                <input type="text" name="search">
                <button type="submit" class="btn">検索</button>
            </div>
        </form>
    </div>


    <section>
        <div class="allposts">
            <?php if (isset($_GET['search'])): ?>
                <?php foreach ($stmt as $row): ?>
                    <div class="posts">
                        <div class="post">
                            <div class="user-name">
                                <?php if ($login_user == $row['user_id']) { ?>
                                    <p class="name"><a href="mypage.php?id=<?= $login_user ?>"><?php echo $row['name']; ?></a></p>
                                <?php } else { ?>
                                    <p class="name"><a href="user_page.php?puid=<?= $row['user_id'] ?>"><?php echo $row['name']; ?></a></p>
                                <?php } ?>
                            </div>
                            <p class="title"><?= $row['title'] ?></p>
                        </div>
                        <div class="image">
                            <img src="<?php echo '/../'."{$row['image_path']}"; ?>" width="100%" height="100%" alt="...">
                        </div>
                        <div class="post-end"><a href="postShow.php?pid=<?= $row['id'] ?>">詳細</a></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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

    <div class="jump">▲TOP</div>
</body>
</html>