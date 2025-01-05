<?php
session_start();
if (!isset($_SESSION['user']['username'])) {
    header('Location: https://nmtechnicalsvc.com/error_logs/arkh/login.php');
}
?>