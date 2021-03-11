<?php
    function isAvailable($data) {
        if ((int)$data === 0) {
            echo "Oui";
        } elseif ((int)$data === 1) {
            echo "Non";
        } else {
            echo "Donnée invalide";
        }
    }

    function sanitize_input($data) {
        return htmlentities(stripslashes(trim($data)));    
    }