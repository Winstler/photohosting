<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    unset($_SESSION['id']);
    unset($_SESSION['name']);
    $_SESSION['isAuth'] = false;
}

$host = 'localhost';
$db   = 'project';
$user = 'root';
$pass = 'dlit';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);
?>
<html>

<head>
    <link href="../css/nav.css" rel="stylesheet">
    <link href="../css/form.css" rel="stylesheet">
</head>

<body>
    <div class='container'>
        <div class='sucess main'>Ви успішно ввийшли з аккаунту
            <a href='../mainPage/main.php'>Головна</a>
        </div>
    </div>
</body>

</html>