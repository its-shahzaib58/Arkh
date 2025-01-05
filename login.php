<?php
require_once 'connection.php';
session_start();


if (isset($_SESSION['user']['username'])) {
    header('Location: index.php');
}


if (isset($_POST['username']) && isset($_POST['password'])) {
    // Escape special characters in a string for use in an SQL statement
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // SQL query using prepared statements
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
       $user = mysqli_fetch_assoc($result);
       if($user['status'] == 1){  
        // Successful login, start session
        $_SESSION['user'] = $user;
        $_SESSION['error'] = '';
        header('Location: index.php');
        die();
       }else{
        $_SESSION['error'] = 'You are not allowed to login!';    
       }
    } else {
        // Display error message in case of failed login
        $_SESSION['error'] = 'Incorrect username or password!';
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="col-lg-12" style="height:92vh;">
        <div class="row m-0 p-2">
            <form action="login.php" method="post" id="loginForm">
                <div class="col-lg-4 offset-lg-4 col-md-12 col-sm-12 shadow-sm p-3 my-5 rounded">
                    <div class="bg-dark p-2 rounded-end">
                        <img src="assets/img/logo.svg" alt="logo" class="img-fluid">
                    </div>
                    <h5 class="text-center pt-4">LOGIN TO YOUR ACCOUNT</h5>
                    <p class="text-center py-1 text-secondary">Enter your credentials below</p>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" autocomplete="off">
                        <label for="username">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control mb-1" name="password" id="password" placeholder="password">
                        <label for="password">Password</label>
                    </div>
                    <div>
                        <button class="btn btn-dark w-100 my-3">Login</button>
                        <?php
                        if (!empty($_SESSION['error'])) {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $_SESSION['error'] ?>
                            </div>
                        <?php
                            session_destroy();
                        } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <?php require_once('footer.php'); ?>
    <!-- Bootstrap JS and Popper.js -->
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/index.js"></script>

</body>

</html>