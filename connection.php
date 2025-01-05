<?php


// Set session timeout (in seconds)
// $timeout = 600; // 30 minutes

// // Check if the timeout has been set
// if (isset($_SESSION['LAST_ACTIVITY'])) {
//     // Calculate the session lifetime
//     $session_lifetime = time() - $_SESSION['LAST_ACTIVITY'];

//     // If session has expired, destroy it
//     if ($session_lifetime > $timeout) {
//         // session_unset();     // Unset $_SESSION variable
//         session_destroy();   // Destroy session
//         echo "Session expired. Please log in again.";
//     }
// }

// // Update last activity time stamp
// $_SESSION['LAST_ACTIVITY'] = time();

// Your session code continues here...

// Database credentials
$servername = "localhost:3306";  // Server where your database is hosted
$username = "nmtechnicalsvc_arkh";         // Username for the database
$password = "Hacker@5837";             // Password for the database
$dbname = "nmtechnicalsvc_arkh";  // Name of the database you want to connect to

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully";
?>
