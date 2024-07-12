<?php
session_start();
require "../php/connection.php";
$currentUserID = $_SESSION["id"];
$userID = $_GET['id'];
$host = 'localhost';
$db   = 'project';
$user = 'root';
$pass = 'dlit';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);


$limit = 10;
$q = $pdo->prepare("SELECT * FROM publication WHERE userID = $userID");
$q->execute();
$posts = $q->fetchAll(PDO::FETCH_ASSOC);

$q = $pdo->prepare("SELECT COUNT(pubID) FROM publication WHERE userID = $userID");
$q->execute();
$pages = $q->fetch(PDO::FETCH_COLUMN);
$maxPage = ceil($pages / $limit);

$q = "SELECT * FROM usersOfSite WHERE userID = $userID";
$query = mysqli_query($dbcn, $q);
$data = mysqli_fetch_assoc($query);
?>

<html>

<head>
    <script defer src="../js/jquerry.js"></script>
    <script defer src="../js/navMargin.js"></script>
    <script defer src="../js/main.js"></script>
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/nav.css" rel="stylesheet">
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title>Користувач <?php echo $data["nickname"]; ?></title>
</head>

<body>
    <nav>
        <div class="newPost">
            <div><a href="../newPub/newPub.php">+ Створити публікацію</a></div>
        </div>
        <?php if ($_SESSION["isAuth"] == true) {
            echo "<div><a href = '../user/likes.php'>★ Мій альбом</a></div>";
        } ?>
        <div class="growBlock"></div>
        <div class="logo" onclick="location.href = '../mainPage/main.php'"></div>
        <div class="growBlock"></div>
        <div><a href="../mainPage/main.php">Головна</a></div>
        <div class="accStatus">
            <?php
            if ($_SESSION["isAuth"] == true) {
                echo "<div class = 'logReg'><a href = '../user/user.php?id=" . $_SESSION["id"] . "'>" . $_SESSION['name'] . "</a><form method = 'post' action = '../login/logout.php'><input type = 'submit' class = 'navButton' value = '✖ Вийти'></form></div>";
            } else {
                echo "<div class='logReg'><div><a href='../login/login.php'>Вхід</a></div><div><a href='../registration/reg.php'>Реєстрація</a></div></div>";
            }
            ?>
        </div>

    </nav>
    <div class="infoUp">
        <h1 style="margin-bottom: 0;">Публікації користувача</h1>
        <h1 style="margin-bottom: 0; margin-top: 10px;"><?php echo $data["nickname"]; ?></h1>
        <div class="postsCount">Усього <?php echo $pages ?>/500 публікацій</div>
        <div class="search" style="display: <?php if (empty($posts)) {
                                                echo 'none';
                                            } ?>">

            <form action="../search/userSearch.php" method="get">
                <div class="searchOptions">
                    <input type="text" name="search">
                    <input type="submit" class="navButton" value="🔍︎ Пошук">
                    <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                    <input type="hidden" name="nickname" value="<?php echo $data["nickname"]; ?>">
                </div>
                <div class="searchOptions">
                    <select name="categories">
                        <option value="0" selected="selected">Будь-яка категорія</option>
                        <?php
                        $query = $pdo->prepare("SELECT * FROM tags ORDER BY tagText");
                        $query->execute();
                        $list = $query->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <?php foreach ($list as $row) : ?>
                            <option value="<?php echo $row["tID"] ?>"><?php echo $row["tagText"] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="order">
                        <option value="1">По даті створення(спочатку нові)</option>
                        <option value="2">По даті створення(спочатку старі)</option>
                        <option value="3">По назві(від А до Я)</option>
                        <option value="4">По назві(від Я до А)</option>
                        <option value="5">По переглядам (спочатку більше)</option>
                        <option value="6">По переглядам (спочатку менше)</option>
                    </select>
                </div>
            </form>

        </div>
    </div>

    <?php

    if (empty($posts)) {
        echo " <div class='container'><div class= 'sucess main'>Тут нічого немає</div></div>";
    }
    ?>
    <div class="gallery" currentuser="<?php echo $_SESSION["id"] ?>" style="margin-top: 10px;">
        <?php foreach ($posts as $row) : ?>
            <div class="item">
                <h3 class="imgName unselectable"><?php echo $row["pubName"] ?></h3>
                <div class="top">
                    <button author="<?php echo $row["userID"] ?>" class="delete" style="display: <?php if ($row["userID"] == $_SESSION['id']) {
                                                                                                        echo "block";
                                                                                                    } else {
                                                                                                        echo "none";
                                                                                                    } ?>;" onclick="location.href = '../delete/delete.php?id=<?php echo $row['pubID'] ?>'" class="but" style="margin-left: 10px;"><svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5em; width: 1.5em;" viewBox="0 0 48 48">
                            <path d="M12.8 42.7q-1.65 0-2.8-1.15t-1.15-2.8v-28H6.3v-4h10.6v-2h14.15v2H41.7v4h-2.55v28q0 1.6-1.175 2.775Q36.8 42.7 35.2 42.7Zm22.4-31.95H12.8v28h22.4ZM18 34.65h3.45V14.7H18Zm8.55 0h3.5V14.7h-3.5ZM12.8 10.75v28Z" />
                        </svg></button>
                    <div class="growBlock"></div>
                    <div class="views"><?php echo $row["views"] ?><img src="../icons/eye.png" style="margin-left: 4px; "></div>
                </div>
                <div class="download unselectable" onclick="download(event.target.closest('.item').querySelector('.image').getAttribute('src'), event.target.closest('.item').querySelector('.imgName').innerText)"><img src="../icons/download-circular-button.png"></div>
                <a class="imgLink" href="../publication/publication.php?id=<?php echo $row["pubID"] ?>"><img class="image" src="../upload/<?php echo $row['postImage'] ?>"></a>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>