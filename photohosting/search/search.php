<?php
session_start();
require "../php/connection.php";
$search = $_GET["search"];
$categorie = $_GET["categories"];
$order = $_GET["order"];
$isError = false;

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$q = "SELECT * FROM publication WHERE ";
$search = test_input($search);
if ($categorie == "0") {
    $q .= "pubName LIKE '%$search%' OR pubDescription LIKE '%$search%' OR nickname LIKE '%$search%' ";
    $count = "SELECT COUNT(pubID) FROM publication WHERE pubName LIKE '%$search%' OR pubDescription LIKE '%$search%' OR nickname LIKE '%$search%'";
} else {
    $q .= "tag = '$categorie' AND (pubName LIKE '%$search%' OR pubDescription LIKE '%$search%' OR nickname LIKE '%$search%') ";
    $count = "SELECT COUNT(pubID) FROM publication WHERE tag = '$categorie' AND (pubName LIKE '%$search%' OR pubDescription LIKE '%$search%' OR nickname LIKE '%$search%')";
}


switch ($order) {
    case "1":
        $q .= "ORDER BY creationTime DESC";
        break;
    case "2":
        $q .= "ORDER BY creationTime ASC";
        break;
    case "3":
        $q .= "ORDER BY pubName ASC";
        break;
    case "4":
        $q .= "ORDER BY pubName DESC";
        break;
    case "5":
        $q .= "ORDER BY views DESC";
        break;
    case "6":
        $q .= "ORDER BY views ASC";
        break;
}

$host = 'localhost';
$db   = 'project';
$user = 'root';
$pass = 'dlit';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

$limit = 10;
if ($isError == false) {
    $q = $pdo->prepare($q);
    $q->execute();
    $posts = $q->fetchAll(PDO::FETCH_ASSOC);
}

$q = $pdo->prepare($count);
$q->execute();
$pages = $q->fetch(PDO::FETCH_COLUMN);


$maxPage = ceil($pages / $limit);

?>

<html>

<head>
    <script defer src="../js/jquerry.js"></script>
    <script defer src="../js/main.js"></script>
    <script defer src="../js/navMargin.js"></script>
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/nav.css" rel="stylesheet">
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title>Пошук: <?php echo $search; ?></title>
</head>

<body>
    <nav>
        <div class="newPost">
            <div><a href="../newPub/newPub.php">+ Створити публікацію</a></div>
        </div>
        <?php if ($_SESSION["isAuth"] == true) {
            echo "<div class = 'likesA' ><a href = '../user/likes.php'>★ Мій альбом</a></div>";
        } ?>
        <div class="growBlock"></div>
        <div class="logo" onclick="location.href = '../mainPage/main.php'"></div>
        <div class="growBlock"></div>
        <div class="newPost">
            <div><a href="../mainPage/main.php">Головна</a></div>
        </div>
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
        <div>
            <h1>Пошук</h1>
        </div>
        <div class="postsCount">Знайдено <?php echo $pages ?> публікацій</div>
        <div class="search">
            <form action="../search/search.php" method="get">
                <div class="searchOptions">
                    <input type="text" name="search" value="<?php echo $search ?>">
                    <input type="submit" class="navButton" value="🔍︎ Пошук">
                </div>
                <div class="searchOptions">
                    <select name="categories" id="select">
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
                    <select name="order" id="select2">
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
    <div class="gallery" currentuser="<?php echo $_SESSION["id"] ?>">
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
<script>
    var select = document.getElementById("select");
    select.value = "<?php echo $categorie ?>";

    var select2 = document.getElementById("select2");
    select2.value = "<?php echo $order ?>";
</script>

</html>