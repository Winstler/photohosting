<?php
session_start();
$host = 'localhost';
$db   = 'project';
$user = 'root';
$pass = 'dlit';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

$limit = 10;


$page = intval(@$_GET['page']);
$page = (empty($page)) ? 1 : $page;
$start = ($page != 1) ? $page * $limit - $limit : 0;
$q = $pdo->prepare("SELECT * FROM publication LIMIT {$start}, {$limit}");
$q->execute();
$posts = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $row) {
?>
    <div class="item">
        <h3 class="imgName unselectable"><?php echo $row["pubName"] ?></h3>
        <div class="top">
            <button author="<?php echo $row["userID"] ?>" class="delete" style="display: <?php if ($row["userID"] == $_SESSION['id'] || $_SESSION['id'] == 3) {
                                                                                                echo "block";
                                                                                            } else {
                                                                                                echo "none";
                                                                                            } ?>;" onclick="location.href = '../delete/delete.php?id=<?php echo $row['pubID'] ?>'" class="but" style="margin-left: 10px;"><svg xmlns="http://www.w3.org/2000/svg" style="height: 1.5em; width: 1.5em;" viewBox="0 0 48 48">
                    <path d="M12.8 42.7q-1.65 0-2.8-1.15t-1.15-2.8v-28H6.3v-4h10.6v-2h14.15v2H41.7v4h-2.55v28q0 1.6-1.175 2.775Q36.8 42.7 35.2 42.7Zm22.4-31.95H12.8v28h22.4ZM18 34.65h3.45V14.7H18Zm8.55 0h3.5V14.7h-3.5ZM12.8 10.75v28Z" />
                </svg></button>
            <div class="growBlock"></div>
            <div class="views"><?php echo $row["views"] ?><img src="../icons/eye.png" style="margin-left: 4px; "></div>
        </div>
        <div class="download unselectable" onclick="download(event.target.closest('.item').querySelector('.image').getAttribute('src'), event.target.closest('.item').querySelector('.imgName').innerText)"><img src="../icons/download-circular-button.png"></div>
        <a class="imgLink" href="../publication/publication.php?id=<?php echo $row["pubID"] ?>"><img class="image" src="../upload/<?php echo $row['postImage'] ?>"></a>
    </div>
<?php
}
?>