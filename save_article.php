<?php
require_once('connection.php');
if(isset($_REQUEST['article'])&& isset($_REQUEST['article_doc']) && isset($_REQUEST['title_id']))
{
    $article = mysqli_real_escape_string($conn, $_REQUEST['article']);
    $article_doc = mysqli_real_escape_string($conn, $_REQUEST['article_doc']);
    $title_id = $_REQUEST['title_id'];
    $sql = "INSERT INTO `title_article_d`(`title_id`, `art_html`,`article_doc`) VALUES ('$title_id','$article','$article_doc')";
    $result = mysqli_query($conn, $sql);
    if($result)
    {
        echo "Article saved successfully";
    }else{
        echo "Error: ". mysqli_error($conn);
    }
}
?>