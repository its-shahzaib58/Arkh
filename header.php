<?php

$url =  $_SERVER['REQUEST_URI'];
$clean_url = parse_url($url, PHP_URL_PATH);

// Use basename() to extract the file name
$file_name = basename($clean_url);

?>
<style>
    /* Scrollbar container */
::-webkit-scrollbar {
    width: 7px; /* Width of the scrollbar */
    height: 10px; /* Height of the scrollbar (for horizontal) */
}

/* Track (scrollbar background) */
::-webkit-scrollbar-track {
    background: #f1f1f1; /* Light background color */
    border-radius: 10px; /* Rounded edges */
}

/* Scrollbar thumb (draggable part) */
::-webkit-scrollbar-thumb {
    background: #888; /* Darker color for the thumb */
    border-radius: 10px; /* Rounded corners for the thumb */
}

/* Hover effect for the scrollbar thumb */
::-webkit-scrollbar-thumb:hover {
    background: #555; /* Change color when hovering */
}

</style>
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="row m-0">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid d-flex flex-row justify-content-space-between">
                <div class="col-lg-2"> <!-- Use d-flex to enable flexbox -->
                    <div class="bg-dark p-2 rounded-end">
                        <img src="assets/img/logo.svg" alt="logo" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6 text-center"> <!-- Use d-flex to enable flexbox -->
                    <b style="font-size:larger">
                        Welcome :
                        <?php

                        echo $_SESSION['user']['username'];
                        ?>
                    </b>
                </div>
                <div class="col-lg-2"> <!-- Use d-flex to enable flexbox -->
                    <button data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Main menu" style="float:inline-end" class="btn btn-dark ml-3" style="font-size: 18px;" id="toggleBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>
        <nav class="pt-3" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php
                if ($file_name == 'index.php') {
                ?>
                    <li class="breadcrumb-item active"><a href="index.php">Dashboard</a></li>
                <?php } elseif ($file_name == 'add_article.php') { ?>
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Article</li>
                <?php } elseif ($file_name == 'all_article.php') { ?>
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Article</li>
                <?php } elseif ($file_name == 'setting.php') { ?>
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                <?php } elseif ($file_name == 'users.php') { ?>
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mange Users</li>
                <?php } elseif ($file_name == 'art_titles.php') { ?>
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="all_article.php">All Article</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Titles</li>
                <?php } ?>
            </ol>
        </nav>
    </div>
</div>
<!-- Sidebar -->
<div class="sidebar bg-light" id="sidebar">
    <div class="bg-dark p-2 rounded-end">
        <img src="assets/img/logo.svg" alt="logo" class="img-fluid">
    </div>
    <hr>
    <a href="index.php"><i class="bi bi-clipboard-data"></i> Dashboard</a>

    <a href="add_article.php"><i class="bi bi-clipboard2-plus"></i> Add Article Record</a>
    <a href="all_article.php"><i class="bi bi-card-list"></i> All Articles</a>
    <hr>
    <?php
    if ($_SESSION['user']['role'] == 'admin') {
    ?>
        <a href="users.php"><i class="bi bi-people"></i> Mange Users</a>
    <?php
    }
    ?>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="logout.php?action=logout"><i class="bi bi-power"></i> Logout</a>
</div>