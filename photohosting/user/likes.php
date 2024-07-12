<?php
session_start();

$id = $_SESSION["id"];

$host = 'localhost';
$db   = 'project';
$user = 'root';
$pass = 'dlit';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

$limit = 10;
$q = $pdo->prepare("SELECT * FROM likes WHERE userID = {$id} LIMIT {$limit}");
$q->execute();
$posts = $q->fetchAll(PDO::FETCH_ASSOC);

$idMass;
foreach ($posts as $row) {
    $idMass .= $row["pubID"];
    $idMass .= ", ";
}

$idMass = substr($idMass, 0, -2);
$q = $pdo->prepare("SELECT * FROM publication WHERE pubID IN ($idMass) LIMIT {$limit}");
$q->execute();
$likesposts = $q->fetchAll(PDO::FETCH_ASSOC);

$q = $pdo->prepare("SELECT COUNT(pubID) FROM likes WHERE pubID IN ($idMass) LIMIT {$limit}");
$q->execute();
$pages = $q->fetch(PDO::FETCH_COLUMN);
$maxPage = ceil($pages / $limit);
?>
<html>

<head>
    <script defer src="../js/jquerry.js"></script>
    <script defer src="../js/navMargin.js"></script>
    <script defer src="../js/main.js"></script>
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/nav.css" rel="stylesheet">
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title>Мій альбом</title>
</head>

<body>
    <nav>
        <div class="newPost">
            <div><a href="../newPub/newPub.php">+ Створити публікацію</a></div>
        </div>
        <div class="growblock"></div>
        <div class="logo" onclick="location.href = '../mainPage/main.php'"></div>
        <div class="growblock"></div>
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
        <div>
            <h1>Мій альбом</h1>
        </div>
    </div>
    <?php

    if (empty($posts)) {
        echo " <div class='container'><div class= 'sucess main'>Тут нічого немає</div></div>";
    }
    ?>
    <div class="gallery" currentuser="<?php echo $_SESSION["id"] ?>">
        <?php foreach ($likesposts as $row) : ?>
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