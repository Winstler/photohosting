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

$data = request("SELECT * FROM publication WHERE pubID = '$id'");
$authorID = $data['userID'];
if ($authorID == $userID || $_SESSION['id'] == 3) {
    request("DELETE FROM `publication` WHERE `pubID` = '$id'");
    unlink("../upload/" . $data['postImage']);
    echo "<div class='container'>
        <div class='sucess main'>Ви успішно видалили цю публікацію
            <a href='../mainPage/main.php'>Головна</a>
            <a href='../newPub/newPub.php'>+ Створити публікацію</a>
        </div>
    </div>";
} else {
    echo "<div class='container'>
        <div class='sucess main'>Ви немаєте права це видалити
            <a href='../mainPage/main.php'>Головна</a>
            <a href='../newPub/newPub.php'>+ Створити публікацію</a>
        </div>
    </div>";
}
?>
<html>

<head>
    <link href="../css/nav.css" rel="stylesheet">
    <link href="../css/form.css" rel="stylesheet">
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title>Видалення</title>
</head>

</html>