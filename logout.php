<?php 
    session_start();
    session_unset();
    session_destroy();
    // TODO : rename properly path
    header("Location: index.php");
    exit();