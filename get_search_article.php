<?php
require_once("connection.php");
session_start();
$sql = "";
if ($_SESSION['user']['role'] == 'admin') {
    $sql = "SELECT a.*, u.username FROM articles a JOIN users u ON a.user_id = u.user_id WHERE keyword LIKE '%".$_REQUEST['search_article_text']."%' ORDER by a.art_id DESC";
} else if ($_SESSION['user']['role'] == 'user') {
    $sql = "SELECT * FROM articles WHERE user_id = '" . $_SESSION['user']['user_id'] . "' AND keyword LIKE '%".$_REQUEST['search_article_text']."%' ORDER by art_id DESC";
}
$result = mysqli_query($conn, $sql);

$response = []; // To store the result

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response[] = $row; // Add each row to the response array
    }
} else {
    $response['error'] = "No users found";
}

// Return response as JSON
echo json_encode($response);

?>
