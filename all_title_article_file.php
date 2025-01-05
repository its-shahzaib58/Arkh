<?php
require_once('connection.php');
// require_once('session.php');
$art_id =  $_GET['art_id'];
$articles = [];
$query  = "SELECT TAD.*, AT.title FROM title_article_d TAD
JOIN art_titles AT ON AT.title_id = TAD.title_id
WHERE AT.art_id = " . $art_id . "";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert(`Select all 'CTRL + A' and past in doc file.`)</script>";
    $articles = $result;
} else {
    echo "<script>alert(`No article found! Please generate one first.`)</script>";

    echo "<script>window.close();</script>";
   die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Titles Article</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
</head>
<?php

foreach ($articles as $index => $article) {
?>
    <h1 class="text-primary"><?= $index + 1 ?>. <?= $article['title'] ?></h1>
    <div class="col-lg-7 offset-lg-1">
        <?= $article['art_html'] ?>
    </div>
    <!-- <div class="articles" style="display:none;">
        ## <?= $index + 1 ?>. <?= $article['title'] ?>
        <br>
        <?= $article['article_doc'] ?>
    </div> -->
<?php
}
?>

<body>
    <!-- Bootstrap JS and Popper.js -->
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/index.js"></script>
</body>

</html>