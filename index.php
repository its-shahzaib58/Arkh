<?php 
require_once('connection.php');
require_once('session_check.php');
// echo time() - $_SESSION['LAST_ACTIVITY'];

$message = "Dashboard working is pending you are redirecting to articles.";
$url = "https://nmtechnicalsvc.com/error_logs/arkh/all_article.php";

// Display an alert and redirect using JavaScript
echo "<script type='text/javascript'>
    alert('$message');
    window.location.href = '$url';
</script>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARKH</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
</head>

<body>
    <?php require_once('header.php') ?>
    
 

    <!-- Bootstrap JS and Popper.js -->
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/index.js"></script>
</body>

</html>