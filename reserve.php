<?php
    session_start();
    if (!isset($_POST['booking-submit'])) {
        // TODO : do a proper redirection
        header("Location: index.php");
        exit();
    } else {
        require "classes/Booking.php";
        $booking = new Booking();
        $booking->reserveProperty();
    }
?>