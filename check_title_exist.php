<?php
require_once('connection.php');
if(isset($_REQUEST['title_id']))
{
    $title_id = $_REQUEST['title_id'];
    $sql = "SELECT art_html,article_doc from title_article_d WHERE title_id = $title_id";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    }else{
        echo json_encode(['error' => "No article found"]);
    }
}
?>