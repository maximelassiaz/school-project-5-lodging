<?php
    session_start();
    if (!isset($_POST['update-submit'])) {
        header("Location: dashboard.php");
        exit();
    } else {
        require_once "classes/LodgingManager.php";
        $updateProperty = new LodgingManager();
        $updateProperty->updateLodging();
    }