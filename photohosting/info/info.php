<?php session_start() ?>
<html>

<head>
    <script defer src="../js/jquerry.js"></script>
    <script defer src="../js/navMargin.js"></script>
    <script defer src="../js/main.js"></script>
    <link href="../css/main.css" rel="stylesheet">
    <link href="../css/nav.css" rel="stylesheet">
    <link rel="shortcut icon" href="../icons/logo.ico" type="image/x-icon">
    <title>Інформація та умови</title>
</head>

<body>
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
        <div class="newPost">
            <div><a href="../mainPage/main.php">Головна</a></div>
        </div>
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
            <h1 style="margin-top: 50px;">Інформація та умови</h1>
        </div>
        <div style="width: 50%; background: white; font-size: 1.2em; padding: 20px; border-radius: 20px; box-shadow: 0px 0px 10px 10px rgb(233, 233, 233);">
            <p style="margin: 0;">Фотохостинг надає можливість безкоштовного та швиидкого перегляду, завантаження, пошуку фотографій. Для користування усіма функціями сайту необхідно зареєструватися. Кожен користувач може завантажувати до 500 фото з розміром кожного до 10 мегабайт у форматі .png, .jpg., .jfif. Є можливість пошуку та сортування серед усіх публікацій на сайті та окремо публікацій користувача. Якщо сподобалась публікація то можна зберегти її до свого "Альбому" щоб не втратити її.<b> Усі завантажені фотографії на Фотохостинг будуть мати безкоштовне та вільне користування для будь-кого</b>. <span style="color: red; font-weight: bold;">При порушені авторських прав або публікуванні непристойного контенту публікація буде видалена. При подальшому зловживанні можливе видалення акаунту з усіми публікаціями та інформацією.</span> При виникненні будь-яких проблем зв'яжіться з адмінстратором сайту по пошті: <b>nazarm741852@gmail.com</b>. Автор вебсайту: Михайлець Назар</p>
        </div>
    </div>
</body>

</html>