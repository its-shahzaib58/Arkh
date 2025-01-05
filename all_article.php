<?php
require_once('connection.php');
require_once('session_check.php');


if (isset($_POST['art_id'])) {
    $art_id = $_POST['art_id'];

    $sql = "DELETE FROM articles WHERE art_id = $art_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("Location: all_article.php");
    }
}
if (isset($_POST['change_status_art_id']) && isset($_POST['art_status'])) {
    $art_id = $_POST['change_status_art_id'];
    $art_status = $_POST['art_status'];

    $sql = "UPDATE `articles` SET status = '$art_status' WHERE art_id = $art_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("Location: all_article.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Articles</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
    <script>
        function searchArticle() {
            const search_article_text = document.getElementById('search_article_text').value;
            const all_article_tbody = document.getElementById('all_article_tbody');
            var tr = ``;
            $.ajax({
                url: 'get_search_article.php',
                method: 'POST',
                data: {
                    search_article_text
                },
                success: (response) => {
                    try {
                        const data = JSON.parse(response);
                        var sr = 1;
                        if (Array.isArray(data)) {
                            data.forEach((art) => {
                                // console.log(art)
                                tr += `
                        <tr>
                                <td class="text-center">${sr++}</td>
                                <td class="text-center"><a class="" href="art_titles.php?art_id=${art.title_id}">${art.url_link}</a></td>
                                <td class="text-center">${art.keyword}</td>
                                <?php

                                if ($_SESSION['user']['role'] == 'admin') {
                                ?>
                                    <td class="text-center">${art.username}</td>
                                <?php
                                }
                                ?>
                                <td class="text-center">${art.last_date}</td>
                                
                                <td class="text-center">
                                <span 
                                style="cursor: pointer;" 
                                onclick="${art.status == 'SAVE' ? `changeArticleStatusShowModal('${art.art_id}')` : ''}" 
                                class="badge ${art.status == 'SAVE' ? 'bg-warning' : 'bg-success'}" 
                                data-bs-toggle="tooltip" 
                                data-bs-placement="left" 
                                data-bs-title="Click to change status">
                                ${art.status}
                                </span>
                                </td>
                                <td class="text-center">
                                <form method="POST" action="all_article.php">
                                    <input type="hidden" name="art_id" value="${art.art_id}">
                                    <button ${art.status =='COMPLETE'?'disabled':''} class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete article" type="submit"><i class="bi bi-trash3"></i></button>
                                    </form>
                                    </td>
                            </tr>
                        `;
                            });
                            all_article_tbody.innerHTML = tr;
                        }
                    } catch (error) {
                        console.log(error);
                    }

                }
            });
        }

        function changeArticleStatusShowModal(art_id) {
            console.log(art_id);
            $('#changeArtStatusArtId').val(art_id);
            $('#changeArticleStatusModal').modal('show');
        }
    </script>
</head>

<body >
    <?php require_once('header.php') ?>
    <div class="container-fluid">
        <!-- changeArticleStatusModal -->
        <div class="modal fade" id="changeArticleStatusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Change Article Status</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="all_article.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" id="changeArtStatusArtId" name="change_status_art_id">
                            <select name="art_status" class="form-select">
                                <option value="SAVE">SAVE</option>
                                <option value="COMPLETE">COMPLETE</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
                            <button type="submit" class="btn btn-dark"><i class="bi bi-check2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-1 p-0">
            <div class="card-body">
                <div class="d-flex justify-content-between py-1 align-items-center">
                    <div class="text-secondary">
                        <b>All Articles</b>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="search_article_text" id="search_article_text" required placeholder="Search by Keyword" autocomplete="off" onkeyup="searchArticle()">
                            <label for="search_article_text">Search by Keyword</label>
                        </div>
                    </div>
                </div>
              <div class="table-responsive">
              <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Sr #</th>
                            <th class="text-center">Link</th>
                            <th class="text-center">Keyword</th>
                            <?php

                            if ($_SESSION['user']['role'] == 'admin') {
                            ?>
                                <th class="text-center">Added By</th>
                            <?php
                            }
                            ?>
                            <th class="text-center">Last Submit Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">All Articles File</th>
                        </tr>
                    </thead>
                    <?php
                    $limit = 10; // Number of records per page
                    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number from URL
                    $offset = ($page - 1) * $limit; // Calculate the offset

                    // SQL query for counting total records
                    $countSql = "SELECT COUNT(*) as total FROM articles";
                    if ($_SESSION['user']['role'] == 'user') {
                        $countSql .= " WHERE user_id = '" . $_SESSION['user']['user_id'] . "'";
                    }
                    $countResult = mysqli_query($conn, $countSql);
                    $rowCount = mysqli_fetch_assoc($countResult);
                    $totalRecords = $rowCount['total'];
                    $totalPages = ceil($totalRecords / $limit);

                    // SQL query to fetch paginated records
                    $sql = "";
                    if ($_SESSION['user']['role'] == 'admin') {
                        $sql = "SELECT a.*, u.username FROM articles a JOIN users u ON a.user_id = u.user_id ORDER by a.art_id DESC LIMIT $limit OFFSET $offset";
                    } else if ($_SESSION['user']['role'] == 'user') {
                        $sql = "SELECT * FROM articles WHERE user_id = '" . $_SESSION['user']['user_id'] . "' ORDER by art_id DESC LIMIT $limit OFFSET $offset";
                    }

                    $result = mysqli_query($conn, $sql);
                    $count = $offset + 1;
                    ?>

                    <tbody id="all_article_tbody">
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td class="text-center"><?= $count ?></td>
                                <td class="text-center"><a href="art_titles.php?art_id=<?= $row['art_id'] ?>"><?= $row['url_link'] ?></a></td>
                                <td class="text-center"><?= $row['keyword'] ?></td>
                                <?php if ($_SESSION['user']['role'] == 'admin') { ?>
                                    <td class="text-center"><?= $row['username'] ?></td>
                                <?php } ?>
                                <td class="text-center"><?= $row['last_date'] ?></td>
                                <td class="text-center">
                                    <span style="cursor: pointer;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?= $row['status'] == 'SAVE' ? 'Click to change status' : 'The status cannot be changed once it is set to \'Complete\'' ?>" onclick="<?= $row['status'] == 'SAVE' ? 'changeArticleStatusShowModal(\'' . $row['art_id'] . '\')' : '' ?>" class="badge <?= $row['status'] == 'SAVE' ? 'bg-warning' : 'bg-success disabled' ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="all_article.php">
                                        <input type="hidden" name="art_id" value="<?= $row['art_id'] ?>">
                                        <button <?= $row['status'] == 'SAVE' ? '' : 'disabled' ?> data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete article" class="btn btn-danger btn-sm" type="submit">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                   <a class="btn btn-primary btn-sm" target="_blank" href="all_title_article_file.php?art_id=<?=$row['art_id']?>"><i class="bi bi-printer"></i></a>
                                </td>
                            </tr>
                        <?php $count++;
                        } ?>
                    </tbody>

                </table>
              </div>
                <div class="col-lg-12 d-flex justify-content-center">
                    <!-- Pagination Links -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <!-- Previous Button -->
                            <?php if ($page > 1) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            <?php } ?>

                            <!-- Page Numbers -->
                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php } ?>

                            <!-- Next Button -->
                            <?php if ($page < $totalPages) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&raquo;</span>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and Popper.js -->
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/index.js"></script>

</body>

</html>