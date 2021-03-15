<?php
    session_start();
    if (!isset($_POST['delete-gite-submit'])) {
        header("Location: dashboard.php");
        exit();
    } else {
        require_once "classes/LodgingManager.php";
        $deleteProperty = new LodgingManager();
        $deleteProperty->deleteLodging();
    }
  
    