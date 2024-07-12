<?php
session_start();

$id = $_GET['pubID'];
$userID = $_SESSION['id'];

$host = 'localhost';
$db   = 'project';
$user = 'root';
$pass = 'dlit';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

function request($q){
    global $pdo;
    $q = $pdo->prepare($q);
    $q->execute();	
    return $q->fetch(PDO::FETCH_LAZY);
}
echo $_REQUEST['event'];
if($_REQUEST['event'] == "view"){
    request("UPDATE publication SET views = views + 1 WHERE pubID = {$id}");
}
if($_REQUEST['event'] == "like"){
    request("INSERT INTO likes (pubID, userID) VALUES ('{$id}','{$userID}')");
}
if($_REQUEST['event'] == "unlike"){
    request("DELETE FROM likes WHERE pubID = {$id} AND userID = {$userID}");
}

?>