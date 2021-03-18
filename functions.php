<?php
    function isAvailable($data) {
        if ((int)$data === 0) {
            return "Yes";
        } elseif ((int)$data === 1) {
            return "No";
        } else {
            return "Invalid data";
        }
    }

    function sanitize_input($data) {
        return htmlentities(stripslashes(trim($data)));    
    }