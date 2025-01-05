<?php
require_once('connection.php');
require_once('session_check.php');
$c_pass_success_msg = false;
$c_pass_error_msg = false;

if (isset($_POST['old_password']) && $_POST['password'] && $_POST['c_password']) {
    $username = $_SESSION['user']['username'];
    $old_password = $_POST['old_password'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];

    // Check if password and confirm password match
    if ($password != $c_password) {
        $c_pass_error_msg = "Passwords do not match";
    }
    // Check if user exists in the database
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$old_password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // Update password in the database
        $sql = "UPDATE users SET password='$password' WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $c_pass_success_msg = true;
            $c_pass_error_msg = false;
        } else {
            $c_pass_success_msg = false;
            $c_pass_error_msg = "Failed to update password";
        }
    } else {
        $c_pass_error_msg = "Old password is invalid";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
</head>

<body>
    <?php require_once('header.php') ?>
    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="row m-0">
                <div class="col-lg-4 offset-lg-4 card mt-3 p-0">
                    <div class="card-header">
                        <b>Change Password</b>
                    </div>
                    <div class="row m-0 p-2">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form action="setting.php" method="post">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" name="old_password" id="old_password" required placeholder="Old Password" autocomplete="off">
                                    <label for="old_password">Old Password</label>
                                </div>
                                <div class="form-floating">
                                    <input type="password" class="form-control mb-3" name="password" id="password" required placeholder="password" autocomplete="off">
                                    <label for="password">Password</label>
                                </div>
                                <div class="form-floating">
                                    <input type="password" class="form-control mb-3" name="c_password" id="c_password" required placeholder="c_password" autocomplete="off">
                                    <label for="c_password">Confirm Password</label>
                                </div>
                                <div>
                                    <button class="btn btn-dark w-100 my-3" type="submit">Change Password</button>
                                </div>
                            </form>
                            <?php
                            if ($c_pass_success_msg) {
                            ?>
                                <div class="alert alert-success" role="alert">
                                    Password update successfully!
                                </div>
                            <?php
                            }
                            if ($c_pass_error_msg) {
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $c_pass_error_msg ?>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and Popper.js -->
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/index.js"></script>
</body>

</html>