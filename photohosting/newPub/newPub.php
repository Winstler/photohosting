<?php
session_start();


$host = 'localhost';
$db   = 'project';
$user = 'root';
$pass = 'dlit';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

require "../php/connection.php";

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
        $nameErr = "Назва необхідна";
    } else {
        $name = test_input($_POST["name"]);
        if (strlen($name) < 3 || strlen($name) > 50) {
            $nameErr = "Назва мусить бути довжиною від 3 до 50 символів ";
        }
    }
    $description = test_input($_POST["description"]);
    if ($description > 500) {
        $descriptionErr = "Опис не може бути довжиною більше ніж 500 символів ";
    }
    if (empty($_FILES["filename"]["name"])) {
        $fileErr = "Ви повинні завантажити файл для публікації";
    } else {
        if ($_FILES["filename"]["size"] > 10485760) {
            $fileErr = "Файл мусить бути до 10мб розміру";
        }
    }
    if ($_POST["categories"] == "0") {
        $categorieError = "Необхідно обрати категорію";
    }
    if ($nameErr == "" && $descriptionErr == "" && $fileErr == "" && $categorieError == "" && $_SESSION["isAuth"] == true) {
        $username = $_SESSION["name"];
        $imageID = mysqli_query($dbcn, "SELECT pubID FROM publication ORDER BY pubID DESC LIMIT 1");
        $temp = mysqli_fetch_assoc($imageID);
        $imageID = $temp["pubID"] + 1;
        $imageType = substr($_FILES["filename"]["type"], 6);
        $image = $imageID . "." . $imageType;
        $tag = $_POST["categories"];
        $q = "INSERT INTO publication (pubName, pubDescription, userID, nickname, postImage, tag) VALUES ('$name', '$description', '$id', '$username', '$image', '$tag')";
        if (mysqli_query($dbcn, $q)) {
            if ($_FILES['filename']['error'] == 0) {
                if (move_uploaded_file($_FILES['filename']['tmp_name'], "../upload/$image")) {
                    echo " <div class='container'>
        <div class='sucess main'>Ви успішно створили публікацію!
            <a href='../mainPage/main.php'>Головна</a>
    
        </div>
    </div>";
                }
            } else {
                echo "Трапилась помилка! Спробуйте ще раз";
            }
        } else {
            echo "Трапилась помилка бази даних!";
            echo $_SESSION["isAuth"];
        }
    }
}
?>
<html>

<head>
    <script defer src="../js/jquerry.js"></script>
    <script defer src="../js/navMargin.js"></script>
    <link href="../css/nav.css" rel="stylesheet">
    <link href="../css/form.css" rel="stylesheet">
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title>Створити публікацію</title>
</head>

<body>
    <nav>
        <?php if ($_SESSION["isAuth"] == true) {
            echo "<div class = 'likesA' ><a href = '../user/likes.php'>★ Мій альбом</a></div>";
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
    <div class="container">
        <?php
        if ($_SESSION["isAuth"] == false) {
            echo "<div class='container'>
        <div class='sucess main'>Для створення публікацій війдіть в акаунт 
            <a href='../login/login.php'>Вхід</a>
        </div>
    </div>";
        } else {
            $id = $_SESSION["id"];
            $dsd = $_SESSION["name"];
            $q = $pdo->prepare("SELECT COUNT(pubID) FROM publication WHERE userID = '$id'");
            $q->execute();
            $pages = $q->fetch(PDO::FETCH_COLUMN);
            $pubLimit = 500;
            if ($pages >= $pubLimit) {
                echo "<div class='container'>
        <div class='sucess main'>У вас усього $pages публікацій, що перевищує ліміт. Будь ласка, видаліть зайві фото.
            <a href = '../user/user.php?id=" . $_SESSION["id"] . "'>Моя сторінка</a>
        </div>
    </div>";
            }
        }


        ?>
        <form class="main <?php if ($_SESSION["isAuth"] == false || $pages >= $pubLimit) echo "invisible" ?>" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            Виберіть файл*: <span class="error"><?php echo $fileErr; ?></span><input type="file" id="files" name="filename" accept="image/png, image/jpeg, image/jfif" value="Оберіть зображення" />
            Ім'я публікації*: <span class="error"><?php echo $nameErr; ?></span><input type="text" name="name">
            Категорія публікації* : <select name="categories">
                <option value="0" selected="selected">Оберіть категорію</option>
                <?php
                $query = $pdo->prepare("SELECT * FROM tags ORDER BY tagText");
                $query->execute();
                $list = $query->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php foreach ($list as $row) : ?>
                    <option value="<?php echo $row["tID"] ?>"><?php echo $row["tagText"] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="error"><?php echo $categorieError; ?></span>
            Опис публікації: <span class="error"> <?php echo $descriptionErr; ?></span><textarea name="description" rows="5" cols="40"></textarea>
            <input type="submit" value="Створити">
            <span style="font-size: 0.8em; margin-top: 20px;" align="center"><i>Поля з * обов'язкові</i></span>
        </form>
    </div>
</body>
<script>
    document.getElementById('files').value = 'Оберіть зображення';
</script>

</html>