<?php
require_once("connection.php");

if(isset($_REQUEST['search'])) 
{
    $search = $_REQUEST['search'];
    $sql = "SELECT * FROM `users` WHERE `username` LIKE '%$search%' and role = 'user'";
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
}

?>
