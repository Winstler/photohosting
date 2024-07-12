<?php session_start(); ?>
<html>

<head>
    <script defer src="../js/jquerry.js"></script>
    <script defer src="../js/navMargin.js"></script>
    <link href="../css/nav.css" rel="stylesheet">
    <link href="../css/form.css" rel="stylesheet">
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title>Реєстрація</title>
</head>


<body>
    <?php
    require "../php/connection.php";
    $nameErr = $emailErr = $passwordErr = $confirmPasswordErr = $descriptionErr = "";
    $name = $email = $password = $confirmPassword = $description = "";

    $host = 'localhost';
    $db   = 'project';
    $user = 'root';
    $pass = 'dlit';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "Ім'я необхідно";
        } else {
            $name = test_input($_POST["name"]);
            if (strlen($name) < 3 || strlen($name) > 20) {
                $nameErr = "Ім'я мусить бути від 3 до 20 символів";
            }
        }
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
            $count = mysqli_query($dbcn, "SELECT userID FROM usersOfSite WHERE email = '$email'");
            if (mysqli_num_rows($count) > 0) {
                $emailErr = "Ця пошта вже зареєстрована на сайті";
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
        if (empty($_POST["confirmPassword"])) {
            $confirmPasswordErr = "Підтвердження паролю необхідне";
        } else {
            $confirmPassword = test_input($_POST["confirmPassword"]);
            if ($confirmPassword != $password) {
                $confirmPasswordErr = "Паролі не свіпадають";
            }
        }

        $description = test_input($_POST["description"]);
        if ($description > 500) {
            $descriptionErr = "Опис мусить бути не більше чим 500 символів";
        }

        if ($nameErr == "" && $emailErr == "" && $passwordErr == "" && $confirmPasswordErr == "" && $descriptionErr == "") {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            function generate_string($input, $strength = 16)
            {
                $input_length = strlen($input);
                $random_string = '';
                for ($i = 0; $i < $strength; $i++) {
                    $random_character = $input[mt_rand(0, $input_length - 1)];
                    $random_string .= $random_character;
                }
                return $random_string;
            }
            $salt = generate_string($permitted_chars, 10);
            $password = sha1($password . $salt);
            $activeHex = "1"; //I will make confirmation later, but for now I'll just send this value, 'cause it can't be null in db
            $q = "INSERT INTO usersOfSite (nickname, email, userPassword, salt, activeHex, userDescription) VALUES ('$name', '$email', '$password', '$salt', '$activeHex', '$description')";
            if (mysqli_query($dbcn, $q)) {
                echo "<div class='container'>
        <div class='sucess main'>Ви успішно зареєструвалися!
            <a href='../mainPage/main.php'>Головна</a>
        </div>
    </div>";
                $_SESSION["name"] = $name;
                $_SESSION["id"] = $id;
                $_SESSION["isAuth"] = true;
            } else {
                echo "Трапилася помилка! Спробуйте ще раз.";
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
                echo "<div class = 'logReg'><a href = '../user/user.php?id=" . $_SESSION["id"] . "'>" . $_SESSION['name'] . "</a><form method = 'post' action = '../login/logout.php'><input type = 'submit' class = 'navButton' value = '✖ Вийти'></form></div>";
            }
            ?>
            <div class="newPost"><a href="../info/info.php">ⓘ</a></div>
        </div>

    </nav>
    <div class="container">
        <form class="main" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            Ім'я*: <span class="error"><?php echo $nameErr; ?></span><input type="text" name="name">
            Електронна пошта*: <span class="error"><?php echo $emailErr; ?></span> <input type="text" name="email">
            Пароль*: <span class="error"><?php echo $passwordErr; ?></span><input type="text" name="password">
            Підтвердіть пароль*: <span class="error"><?php echo $confirmPasswordErr; ?></span><input type="text" name="confirmPassword">
            <div style="margin-bottom: 10px;"><i>Перед реєстрацією на сайті рекомендовано прочитати умови та інформацію.</i></div>
            <div class="navButton"><a href="../info/info.php">ⓘ Інформація</a></div>
            <input type="submit" value="Зареєструватися">
            <div class="hint"><span>Вже є акаунт? <a href="../login/login.php">Вхід</a></span></div>
        </form>
    </div>
</body>

</html>