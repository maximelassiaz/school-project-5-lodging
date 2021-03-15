<?php
    session_start();
    if (!isset($_POST['create-submit'])) {
        header("Location: dashboard.php");
        exit();
    } else {
        require_once "classes/LodgingManager.php";
        // TODO change collation in PHPMyAdmin
        $addProperty = new LodgingManager();
        $addProperty->addLodging();
    }
    
