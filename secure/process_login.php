<?php
include_once 'functions.php';
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (login($username, $password) == true) {
        header("Location: ../index.php");
        exit;
    } else {
        header("Location: ../login.php?error=1");
        exit;
    }
} else {
    echo 'Invalid Request';
}