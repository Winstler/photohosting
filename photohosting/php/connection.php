<?php
$dblocation = "localhost";
$dbuser = "root";
$dbpassword = "dlit";
$dbcn = mysqli_connect($dblocation, $dbuser, $dbpassword);
if(!$dbcn){
    exit("<p>Oh! Happened an error in connecting to the database!</p>");
}
$q = "CREATE DATABASE IF NOT EXISTS project";
mysqli_select_db($dbcn, "project");
?>