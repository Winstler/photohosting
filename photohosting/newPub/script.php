<?php
require "../php/connection.php";

session_start();
$nameErr = $descriptionErr = $fileErr = "";
$name = $description = "";


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION["id"];

    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (strlen($name) < 3 || strlen($name) > 50) {
            $nameErr = "Name must be from 3 to 50 characters in lenght";
        }
    }
    $description = test_input($_POST["description"]);
    if ($description > 500) {
        $descriptionErr = "Your description can't be more than 500 characters in length";
    }
    if (empty($_FILES["filename"]["name"])) {
        $fileErr = "Ви повинні завантажити файл для публікації";
    } else {
        if ($_FILES["filename"]["size"] > 10485760) {
            $fileErr = "Файл мусить бути до 10мб розміру";
        }
    }
    if ($nameErr == "" && $descriptionErr == "" && $fileErr == "" && $_SESSION["isAuth"] == true) {
        $username = $_SESSION["name"];
        $imageID = mysqli_query($dbcn, "SELECT pubID FROM publication ORDER BY pubID DESC LIMIT 1");
        $temp = mysqli_fetch_assoc($imageID);
        $imageID = $temp["pubID"] + 1;
        $imageType = substr($_FILES["filename"]["type"], 6);
        $image = $imageID . "." . $imageType;
        $tag = $_POST["categories"];
        echo $image;
        $q = "INSERT INTO publication (pubName, pubDescription, userID, nickname, postImage, tag) VALUES ('$name', '$description', '$id', '$username', '$image', '$tag')";
        if (mysqli_query($dbcn, $q)) {
            echo "A new record has been created!";
            if ($_FILES['filename']['error'] == 0) {
                if (move_uploaded_file($_FILES['filename']['tmp_name'], "../upload/$image")) {
                    echo "файл завантажен";
                }
            } else {
                echo "не загружен((";
            }
        } else {
            echo "Happened an error in creating record!";
        }
    }
}
echo $nameErr . "<br>" . $descriptionErr . "<br>" . $fileErr = "";
