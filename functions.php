<?php
    function isAvailable($data) {
        if ((int)$data === 0) {
            echo "Yes";
        } elseif ((int)$data === 1) {
            echo "No";
        } else {
            echo "Invalid data";
        }
    }

    function sanitize_input($data) {
        return htmlentities(stripslashes(trim($data)));    
    }