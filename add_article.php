<?php
require_once('connection.php');
require_once('session_check.php');
$success_msg = false;
$error_msg = false;

if (isset($_POST['web_link']) && $_POST['keyword'] && $_POST['title'] && $_SESSION['user']) {
    $web_link = $_POST['web_link'];
    $keyword = $_POST['keyword'];
    $last_date = $_POST['last_date'];
    $user_id = $_SESSION['user']['user_id'];
    $titles = $_POST['title'];


    $query = "INSERT INTO articles (user_id, url_link, keyword,status,last_date) VALUES ('$user_id', '$web_link', '$keyword','SAVE','$last_date')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $last_id = mysqli_insert_id($conn);
        for ($i = 0; $i < count($titles); $i++) {
            $title = mysqli_real_escape_string($conn, $titles[$i]);
            $query2 = "INSERT INTO art_titles (art_id, title) VALUES ('$last_id', '$title')";
            mysqli_query($conn, $query2);
        }
        $success_msg = true;
    } else {
        $error_msg = true;
    }
}
date_default_timezone_set('Asia/Karachi');
$today = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container-fluid">
        <div class="container-fluid">
            <form action="add_article.php" method="POST">
            <div class="card mt-3">
                <div class="row m-0 py-2">
                    <div class="d-flex justify-content-between py-3">
                        <div class="text-secondary">
                            <b>Add Article Record</b>
                        </div>
                        <div>
                            <button class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Add Title"  type="button" id="add-input"><i class="bi bi-patch-plus"></i></button>
                            <button class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Remove Title" type="button" id="remove-input"><i class="bi bi-patch-minus"></i></button>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="web_link" id="web_link" required placeholder="Website Link" autocomplete="off">
                            <label for="web_link">Website Link</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control mb-3" name="keyword" id="keyword" required placeholder="keyword" autocomplete="off">
                            <label for="keyword">Keyword</label>
                        </div>
                        <div class="form-floating">
                            <input type="date" min="<?=$today?>" value="<?=$today?>" class="form-control mb-3" name="last_date" id="last_date" required placeholder="date" autocomplete="off">
                            <label for="last_date">Last Date of Submit Article</label>
                        </div>
                        <div>
                            <button class="btn btn-dark w-100 my-3">Save</button>
                        </div>
                        <?php
                        if ($success_msg) {
                        ?>
                            <div class="alert alert-success" role="alert">
                                Article added successfully!
                            </div>
                        <?php
                        }
                        if ($error_msg) {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                Error occurred while adding article!
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <div id="input-container">
                            <div class="form-floating my-2">
                                <input type="text" class="form-control" required name="title[]" id="title1" placeholder="Title" autocomplete="off">
                                <label for="title1">Title <span>1</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/index.js"></script>
</body>

</html>