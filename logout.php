<?php 
    session_start();
    if (!isset($_POST['logout-submit'])) {
        header("Location: index.php");
        exit();
    } else {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
   