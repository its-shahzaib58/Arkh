<?php
require_once("connection.php");

if (isset($_REQUEST['user_id']) && isset($_REQUEST['status'])) {
    // Sanitize and validate input
    $user_id = intval($_REQUEST['user_id']);
    $status = intval($_REQUEST['status']); // Expecting status to be 1 or 0

    // Prepare an SQL query to avoid SQL injection
    $query = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
    $query->bind_param('ii', $status, $user_id); // 'ii' means two integers

    if ($query->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Failed to update status";
    }

    // Close the statement
    $query->close();
} else {
    echo "Invalid parameters";
}

// Close the connection
$conn->close();
?>
