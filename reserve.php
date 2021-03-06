<?php
    session_start();
    if (!isset($_POST['booking-submit'])) {
        header("Location: index.php");
        exit();
    } else {

        require "classes/Booking.php";
        // register booking in database
        $booking = new Booking();
        if ($booking->reserveProperty()) {

            $email = $_SESSION['client-email'];
            $fname = ucfirst($_SESSION['client-fname']);
            $lname = ucfirst($_SESSION['client-lname']);

            // send confirmation mail
            require "classes/Mailing.php";
            $confirmationMail = new Mailing($fname, $lname, $email);
        }             
    }
?>