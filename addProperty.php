<?php
    session_start();
    if (!isset($_POST['create-submit'])) {
        header("Location: dashboard.php");
        exit();
    } else {
        require_once "classes/LodgingManager.php";
        $addProperty = new LodgingManager();
        $addProperty->addLodging();
    }
    
