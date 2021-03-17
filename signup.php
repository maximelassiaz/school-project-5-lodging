<?php
    session_start();
    require_once "classes/Logging.php";
    if(!isset($_POST['signup-submit'])) {
        header("Location : index.php");
        exit();
    } else {
        $signup = new Logging();
        $signup->signUp();
    }
    
    