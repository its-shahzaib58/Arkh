<?php
require_once('connection.php');
require_once('session_check.php');


$article = [];
$art_id = $_GET['art_id'];
$sql = "SELECT * FROM articles WHERE art_id = $art_id";
$result = mysqli_query($conn, $sql);
$article = mysqli_fetch_assoc($result);
// echo '<pre>';
// print_r($row);
// echo '</pre>';
// die();

// Get article titles for the selected article
if (isset($_POST['title'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    // echo '<pre>';
    // print_r($title);
    // echo '</pre>';
    // die();
    $query = "INSERT INTO art_titles (art_id, title) VALUES('$art_id','$title')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header('Location: art_titles.php?art_id=' . $art_id);
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
if (isset($_POST['title_detele'])) {
    $title_id = $_POST['title_detele'];
    $query = "DELETE FROM art_titles WHERE title_id = $title_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $sql = "SELECT * from title_article_d WHERE title_id = $title_id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $q = "DELETE FROM title_article_d WHERE title_id = $title_id";
            $result = mysqli_query($conn, $q);
        }
        header('Location: art_titles.php?art_id=' . $art_id);
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Titles</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="assets/js/jquery.js"></script>
    <script src="dist/bundle.js"></script>
    <script>
        // Import the required components from docx


        function copyToClipboard(text) {
            console.log(text);
            // Create a temporary textarea element
            const textarea = document.createElement('input');
            textarea.value = text; // Set the text to be copied
            document.body.appendChild(textarea); // Append it to the body

            textarea.select();
            console.log("Select") // Select the text
            // textarea.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text to the clipboard
            document.execCommand('copy');

            // Remove the temporary textarea
            document.body.removeChild(textarea);

            // Optional: Notify the user that the text has been copied
            $('#copyToast').toast({
                autohide: true,
                delay: 3000,

            }); // Optional: Set autohide and delay
            $('#copyToast').toast('show'); // Show the toast
        }

        function convertToHeadings(text) {
            // Use a regular expression to replace double hashes with <h1> tags
            // Replace ## for <h2> headings
            text = text.replace(/## (.+?)(?=\n|$)/g, '<h2>$1</h2>');

            // Replace # for <h1> headings
            text = text.replace(/# (.+?)(?=\n|$)/g, '<h1>$1</h1>');

            // Replace **text** for bold text
            text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

            // Replace *text* for italic text
            text = text.replace(/\*(.+?)\*/g, '<em>$1</em>');

            return text;
        }

        function checkArticleExistTitle(titleId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: 'check_title_exist.php',
                    type: 'POST',
                    data: {
                        'title_id': titleId
                    },
                    success: function(response) {
                        try {
                            const data = response;
                            resolve(data);
                        } catch (e) {
                            console.error("Parsing error:", e);
                            reject("Error parsing response.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                        reject("Error occurred while checking title existence.");
                    }
                });
            });
        }
        // Usage with async/await
        async function getArticleExistence(titleId) {
            try {
                const data = await checkArticleExistTitle(titleId);
                const result = JSON.parse(data);
                return result;
            } catch (error) {
                console.log("Error:", error);
            }
        }

        async function getGoogleGeminiAI(prompt, titleId, from) {
            console.log(from);
            $('#genArticleModal').modal('show');
            const pastArticleText = document.getElementById('pastArticleText');
            const reloadArticleBtn = document.getElementById('reloadArticleBtn');
            const saveArticleBtn = document.getElementById('saveArticleBtn');
            const downloadWordFileBtn = document.getElementById('downloadWordFileBtn');
            const title_id = titleId;
            const result = await getArticleExistence(titleId);


            if (result.error) {
                reloadArticleBtn.style.display = 'block';
                saveArticleBtn.style.display = 'block';
                downloadWordFileBtn.style.display = 'none';
                pastArticleText.innerHTML = 'Generating...';


                const apiKey = 'AIzaSyBT92KAP-QUUAasGvgiDd4fGSjNenwOws0';
                const url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=${apiKey}`;

                const data = {
                    contents: [{
                        parts: [{
                            text: prompt
                        }]
                    }]
                };

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        const message = data.candidates[0].content.parts[0].text;
                        // console.log('Result message:', message);
                        const finalResult = convertToHeadings(message);
                        pastArticleText.innerHTML = finalResult
                        reloadArticleBtn.addEventListener("click", function() {
                            getGoogleGeminiAI(`${prompt}`, `${titleId}`, 'reloadArticleBtn'); //
                        });
                        saveArticleBtn.addEventListener("click", function() {
                            saveArticle(`${finalResult}`, `${message}`, `${title_id}`); //
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

            } else {
                pastArticleText.innerHTML = result.art_html;
                reloadArticleBtn.style.display = 'none';
                saveArticleBtn.style.display = 'none';
                downloadWordFileBtn.style.display = 'block';
                MyLibrary.createDocxFile(result.article_doc);

            }
        }

        async function saveArticle(article, message, titleId) {
            const reloadArticleBtn = document.getElementById('reloadArticleBtn');
            const saveArticleBtn = document.getElementById('saveArticleBtn');
            $.ajax({
                url: 'save_article.php',
                type: 'POST',
                data: {
                    'article': article,
                    'article_doc': message,
                    'title_id': titleId
                },
                success: function(response) {
                    reloadArticleBtn.style.display = 'none';
                    saveArticleBtn.style.display = 'none';
                    getGoogleGeminiAI('', titleId, 'SaveArticleFunction')

                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        }
    </script>
</head>

<body>
    <?php require_once('header.php') ?>
    <div class="container-fluid">
        <!-- Copy Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 100">
            <div id="copyToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">

                    <strong class="me-auto"><i class="bi bi-copy"> Copy</i></strong>
                    <small>Just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Copied to clipboard
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="genArticleModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Generate Article with AI <i class="bi bi-robot"></i></h1>

                    </div>
                    <div class="modal-body">
                        <article id="pastArticleText">Generating...</article>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button id="modalCloseBtn" type="button" class="btn btn-danger  btn-sm my-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Cancel">
                            <i class="bi bi-x-circle"></i>
                        </button>
                        <button id="reloadArticleBtn" type="button" class="btn btn-light  btn-sm my-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Try Another">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                        <button id="saveArticleBtn" type="button" class="btn btn-dark  btn-sm my-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Save">
                            <i class="bi bi-floppy"></i>
                        </button>
                        <input type="hidden" id="title_id_input">
                        <a download href="" id="downloadWordFileBtn" type="button" class="btn btn-primary  btn-sm my-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Download Word File">
                            <i class="bi bi-filetype-docx"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-0">
            <div class="col-lg-10 offset-1 card mt-1 p-0">

                <div class="card-body">
                    <div class="d-flex justify-content-between py-2">
                        <div class="text-secondary">
                            <b>Article Titles</b>
                        </div>
                        <div>
                            <b><span class="badge bg-dark"><?= $article['url_link'] ?></span> - <span class="badge bg-dark"><?= $article['keyword'] ?></span> - <span  class="badge <?= $article['status'] == 'SAVE' ? 'bg-warning' : 'bg-success' ?>">
                                <?= $article['status'] ?>
                                </span></b>
                        </div>
                        <div>
                            <button <?= $article['status'] == 'COMPLETE' ? 'disabled' : '' ?> type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMoreTitleModal">
                                <i class="bi bi-plus-circle"> Add More Title</i>
                            </button>
                        </div>
                    </div>

                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <?php
                        $art_id = $_GET['art_id'];
                        $query = "SELECT * FROM art_titles WHERE art_id = $art_id";
                        $result = mysqli_query($conn, $query);
                        $count = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <div class="accordion-item">
                                <div class="accordion-header" id="flush-headingOne<?= $count ?>">
                                    <div class="row">

                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne<?= $count ?>" aria-expanded="false" aria-controls="flush-collapseOne">
                                            <?= $count . ":" . "&nbsp;<b>  " . $row['title'] . "</b>" ?>
                                            &nbsp;
                                            <?php
                                            $title_id = $row['title_id'];
                                            $sql2 = "SELECT * from title_article_d WHERE title_id = $title_id";
                                            $result2 = mysqli_query($conn, $sql2);
                                            if (mysqli_num_rows($result2) > 0) {
                                            ?>
                                                <span id="markTitle" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Article Generated" class="badge bg-success text-light float-end">
                                                    <i class='bi bi-check-circle'></i>
                                                </span>
                                            <?php
                                            } else {
                                            ?>

                                            <?php

                                            }
                                            ?>


                                        </button>

                                    </div>
                                </div>
                                <div id="flush-collapseOne<?= $count ?>" class="accordion-collapse collapse" aria-labelledby="flush-headingOne<?= $count ?>" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <?php
                                        $parString =  "Write a complete blog article of a minimum of 800 words based on the following Title: '" . $row['title'] . "'(H1). please put my keyword: '" . $article['keyword'] . "' and Please give me a unique article in 7 paragraphs with the conclusion all paragraph heading.(H18) do not show numbering in the starting of the paragraph titles. all paragraphs titles. (H2) show more words conclusion title.";
                                        ?>
                                        <textarea class="form-control mt-2" id="copyText<?= $count ?>" rows="5"><?php echo ltrim($parString); ?></textarea>


                                        <button type="button" onclick="copyToClipboard(`<?= $parString ?>`)" class="btn btn-light  btn-sm my-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Copy">
                                            <i class="bi bi-copy"></i>
                                        </button>

                                        <button type="button" onclick="getGoogleGeminiAI(`<?= $parString ?>`,'<?= $row['title_id'] ?>','MainTitiles')" class="btn btn-dark btn-sm my-2" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Write an article with AI">
                                            <i class="bi bi-robot"></i>
                                        </button>
                                        <?php
                                        if ($article['status'] == 'SAVE') {
                                        ?>
                                            <form action="art_titles.php?art_id=<?= $article['art_id'] ?>" method="POST">
                                                <input type="hidden" name="title_detele" value="<?= $row['title_id'] ?>">

                                                <button type="submit" class="btn btn-danger btn-sm my-2 w-100" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Delete this title">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        <?php
                                        }
                                        ?>
                                        <!-- <textarea class="form-control mt-2" id="printTile<?= $count ?>">

                                       </textarea> -->
                                        <!-- <?php
                                                echo "<script>getChatGPT4MiniResponse(`" . $parString . "`,`printTile" . $count . "`);</script>";
                                                ?> -->
                                    </div>
                                </div>
                            </div>
                        <?php
                            $count++;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            if ($article['status'] == 'SAVE') {
            ?>
                <!-- Add More Title Modal -->
                <div class="modal fade" id="addMoreTitleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add More Title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="input-container">
                                    <form action="art_titles.php?art_id=<?= $art_id ?>" method="post">
                                        <div class="form-floating my-2">
                                            <input type="text" class="form-control" required name="title" id="title1" placeholder="Title" autocomplete="off">
                                            <label for="title1">Title</label>
                                        </div>
                                        <button class="btn btn-dark w-100" type="submit">Add</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/docx/7.1.0/docx.min.js"></script> -->
    <script src="assets/js/index.js"></script>
    <script>
        $("#modalCloseBtn").click(() => {
            const pastArticleText = document.getElementById('pastArticleText');
            pastArticleText.innerHTML = 'Generating...';
            $('#genArticleModal').modal('hide');
        });
    </script>

</body>

</html>