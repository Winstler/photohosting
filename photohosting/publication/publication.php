<?php
session_start();
$userID = $_SESSION["id"];
$id = $_GET['id'];
$host = 'localhost';
$db   = 'project';
$user = 'root';
$pass = 'dlit';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

function request($q)
{
    global $pdo;
    $q = $pdo->prepare($q);
    $q->execute();
    return $q->fetch(PDO::FETCH_LAZY);
}
$post = request("SELECT * FROM publication WHERE pubID = {$id}");

$isLiked;
if (isset($userID)) {
    $q = request("SELECT COUNT(likeID) FROM likes WHERE pubID = {$id} AND userID = {$userID}");
    if ($q[0] > 0) {
        $isLiked = "true";
    } else {
        $isLiked = "false";
    }
}


$q = request("SELECT COUNT(likeID) FROM likes WHERE pubID = {$id}");
$likes = $q[0];
$tagT = $post['tag'];
$h = request("SELECT * FROM tags WHERE tID = {$tagT}");


?>
<html lang="ua">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/pub.css" rel="stylesheet">
    <script defer src="../js/jquerry.js"></script>
    <script defer src="../js/publication.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title><?php echo $post['pubName']; ?></title>
</head>

<body>
    <a onclick="history.back()" class="back">Повернутися</a>
    <div class="container">
        <div class='pub' userID="<?php echo $userID ?>" authorID="<?php echo $post['userID'] ?>" pubID="<?php echo $post['pubID'] ?>" views="<?php echo $post['views'] ?>" isLiked="<?php echo $isLiked ?>" likes="<?php echo $likes ?>" canBeDeleted="<?php if ($post['userID'] == $userID || $_SESSION['id'] == 3) echo "true"; ?>">
            <img class="image" src="../upload/<?php echo $post['postImage'] ?>">
            <div class="info">
                <div class="pubname"><?php echo $post['pubName'] ?></div>
                <p class="desc"><?php echo $post['pubDescription'] ?></p>
                <div class="author">Автор: <span class="innerH"><a href="../user/user.php?id=<?php echo $post['userID'] ?>"><?php echo $post['nickname'] ?></a></span></div>
                <div class="time">Категорія: <span class="innerH"><?php echo $h["tagText"]; ?></span></div>
                <div class="views">Перегляди: <span class="innerH"><?php echo $post['views'] ?></div></span>
                <div class="time">Час створення: <span class="innerH"><?php echo $post['creationTime'] ?></span></div>
                <span class="errorL" style="margin-left: 10px;"></span>
                <div class="buttons" style="display: flex;"><button name="like" class="like "><span class="isLiked"></span></button>
                    <button style="display: none; margin-left: 10px;" onclick="location.href = '../delete/delete.php?id=<?php echo $post['pubID'] ?>'" class="but" style="margin-left: 10px;"><svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5em; width: 1.5em;" viewBox="0 0 48 48">
                            <path d="M12.8 42.7q-1.65 0-2.8-1.15t-1.15-2.8v-28H6.3v-4h10.6v-2h14.15v2H41.7v4h-2.55v28q0 1.6-1.175 2.775Q36.8 42.7 35.2 42.7Zm22.4-31.95H12.8v28h22.4ZM18 34.65h3.45V14.7H18Zm8.55 0h3.5V14.7h-3.5ZM12.8 10.75v28Z" />
                        </svg></button>
                </div>
                <button class="but" style="margin-left: 10px;" onclick="download('../upload/<?php echo $post['postImage']; ?>', '<?php echo $post['pubName'] ?>')">⇩ Завантажити</button>
                <button class="but" id="copyl" style="margin-left: 10px;" onclick="copylink('localhost/photohosting/publication/publication.php?id=<?php echo $post['pubID'] ?>')">Скопіювати посилання</button>
            </div>
        </div>
    </div>
</body>

</html>