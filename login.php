<?php
    if (!isset($_POST['login-submit'])) {
        header("Location : index.php");
        exit();
    } else {
        require_once "classes/Logging.php";
        $login = new Logging();
        $login->CheckLogin();
    }