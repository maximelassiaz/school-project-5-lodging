<?php
    // TODO : rewrite login after registration for hashing
    if (!isset($_POST['login-submit'])) {
        // TODO : rewrite path
        header("Location : index.php");
        exit();
    } else {
        require_once "classes/Logging.php";
        $login = new Logging();
        $login->CheckLogin();
    }