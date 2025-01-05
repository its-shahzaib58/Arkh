<?php
require_once('connection.php');
require_once('session_check.php');
$success_msg = false;
$error_msg = false;

if (isset($_POST['username']) && isset($_POST['password']) && $_POST['c_password']) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];

    // Check if password and confirm password match
    if ($password != $c_password) {
        $error_msg = "Passwords do not match";
    }
    // Check if user exists in the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $error_msg = "Username already exists";
    } else {

        // Insert user into the database
        $sql = "INSERT INTO users (username, password, role, status) VALUES('$username','$password','user',1)";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $success_msg = true;
            $error_msg = false;
            echo "<script> getUsers();</script>";
        } else {
            $success_msg = false;
            $error_msg = "Failed to add user";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mange Users</title>
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
                <div class="col-lg-4 card mt-3 p-0" style="overflow-y:auto; height:430px">
                    <div class="card-header">
                        <b>Add New User</b>
                    </div>
                    <div class="row m-0 p-2">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form action="users.php" method="post">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="username" id="usernames" required placeholder="Username" autocomplete="off">
                                    <label for="username">Username</label>
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
                                    <button class="btn btn-dark w-100 my-3" type="submit">Add</button>
                                </div>
                            </form>
                            <?php
                            if ($success_msg) {
                            ?>
                                <div class="alert alert-success" role="alert">
                                    User added successfully!
                                </div>
                            <?php
                            }
                            if ($error_msg) {
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $error_msg ?>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 offset-lg-1 card mt-3 p-0">
                    <div class="card-header">
                        <b>Users List</b>
                    </div>
                    <div class="row m-0 p-2">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <input type="text" onkeyup="searchUsers()" class="form-control" name="search_user" id="search_user" placeholder="Search users">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-1" style="overflow-y:auto; height:400px">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sr #</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                </tbody>
                            </table>
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
    <script>
        function updateUserStatus(id) {
            const switchElement = document.getElementById(`switch_${id}`);
            const isChecked = switchElement.checked;
      
            // You can send an AJAX request here to update the status in the database
            // console.log("User ID: " + id + " Status: " + (isChecked ? "Active" : "Inactive"));

            // Example AJAX request to update the status
            $.ajax({
                url: 'update_user_status.php',
                type: 'POST',
                data: {
                    user_id: id,
                    status: isChecked ? 1 : 0
                },
                success: function(response) {
                    console.log(response);
                    getUsers();
                }
            });
        }

        function searchUsers() {
            const search = $('#search_user').val();
            $.ajax({
                url: 'search_user.php',
                method: 'POST',
                data: {
                    search: search
                },
                success: function(response) {
                    let users = JSON.parse(response);
                    let html = '';
                    users.forEach((user, index) => {
                        html += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${user.username}</td>
                                        <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" onchange="updateUserStatus(${user.user_id})" type="checkbox" role="switch" id="switch_${user.user_id}" ${user.status == 1? 'checked' : 'unchecked'} >
                                        </div>
                                        </td>
                                    </tr>`;
                    });
                    $('tbody').html(html);
                }
            });
        }
        function getUsers() {
                $.ajax({
                url: 'get_users.php',
                method: 'GET',
                success: function(response) {
                    let users = JSON.parse(response);
                    let html = '';
                    users.forEach((user, index) => {
                        html += `<tr>
                                    <td>${index + 1}</td>
                                    <td>${user.username}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" onchange="updateUserStatus(${user.user_id})" type="checkbox" id="switch_${user.user_id}" role="switch" ${user.status == 1? 'checked' : 'unchecked'} >
                                        </div>
                                        </td>
                                </tr>`;
                    });
                    $('tbody').html(html);
                }

            });
            }
        $(document).ready(() => {
            getUsers();
        });
    </script>
</body>

</html>