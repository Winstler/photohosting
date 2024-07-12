<?php
// Start the session
session_start();
if ($_SESSION["isAuth"] == true) {
    $isAuth = "true";
} else {
    $_SESSION["isAuth"] = false;
    $isAuth = "false";
}
?>
<html>

<head>
    <script defer src="../js/jquerry.js"></script>
    <script defer src="../js/navMargin.js"></script>
    <script defer src="../js/login.js"></script>
    <link href="../css/nav.css" rel="stylesheet">
    <link href="../css/form.css" rel="stylesheet">
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title>Вхід</title>
</head>

<body>
    <?php
    require "../php/connection.php";
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $email = $password = "";
    $emailErr = $passwordErr = "";
    $passEmailErr = "";

    $host = 'localhost';
    $db   = 'project';
    $user = 'root';
    $pass = 'dlit';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["email"])) {
            $emailErr = "Електронна пошта необхідна";
        } else {
            $email = test_input($_POST["email"]);
            if (strlen($email) > 50) {
                $emailErr = "Електронна пошта мусить бути до 50 символів";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Неправильний формат пошти";
            }
        }
        if (empty($_POST["password"])) {
            $passwordErr = "Пароль необхідний";
        } else {
            $password = test_input($_POST["password"]);
            if (strlen($password) < 8 || strlen($password) > 32) {
                $passwordErr = "Пароль мусить бути від 8 до 32 символів";
            }
            if (!preg_match("/^([a-zA-Z0-9@*#_$]{8,32})$/", $password)) {
                $passwordErr = "Тільки латинські букви, цифри, та спец. символи дозволені (@*#_$)";
            }
        }
        if ($emailErr == "" && $passwordErr == "" && $passEmailErr == "") {
            $q = "SELECT salt, userPassword, userStatus, userID, nickname FROM usersOfSite WHERE email = '$email'";
            $query = mysqli_query($dbcn, $q);
            $data = mysqli_fetch_assoc($query);
            $salt = $data["salt"];
            $dbPassword = $data["userPassword"];
            $status = $data["userStatus"];
            $id = $data["userID"];
            $name = $data["nickname"];
            if (sha1($password . $salt) == $dbPassword) {
                if ($status == 0) {
                    $passEmailErr = "Firstly you need to confirm your email";
                } else {
                    $_SESSION["name"] = $name;
                    $_SESSION["id"] = $id;
                    $_SESSION["isAuth"] = true;
                    echo "<div class='container'>
        <div class='sucess main'>Ви успішно ввійшли в аккаунт!
            <a href='../mainPage/main.php'>Головна</a>
            <a href='../newPub/newPub.php'>+ Створити публікацію</a>
        </div>
    </div>";
                }
            } else {
                $passEmailErr = "Неправильна пошта чи пароль";
            }
        }
    }
    ?>
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
        <div><a href="../mainPage/main.php">Головна</a></div>
        <div class="accStatus">
            <?php
            if ($_SESSION["isAuth"] == true) {
                echo "<div class = 'logReg'><a href = '../user/user.php?id=" . $_SESSION["id"] . "'>" . $_SESSION['name'] . "</a><form method = 'post' action = '../login/logout.php'><input type = 'submit' class = 'navButton' style = 'font-size: 1.5em' value = '✖ Вийти'></form></div>";
            }
            ?>

        </div>

    </nav>
    <div class="container">
        <form class="main" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" isAuth="<?php echo $isAuth ?>">
            <span class="error"><?php echo $passEmailErr; ?></span>
            Електронна пошта: <span class="error"> <?php echo $emailErr; ?></span><input type="text" name="email">
            Пароль: <span class="error"><?php echo $passwordErr; ?></span><input class="pass" type="password" name="password">
            <input type="submit" value="Увійти">
            <div class="hint"><span>Немає акаунту? <a href="../registration/reg.php">Реєстрація</a></span></div>
        </form>
    </div>
</body>

</html>