<?php
    require_once "functions.php";

    $query = [];
    $parameters = [];
    $errors = [];

    $query[] = "gite_maintenance = :gite_maintenance";
    $parameters[] = [":gite_maintenance", 1, PDO::PARAM_INT];

    $gite_available = empty($_GET['available']) ? false : "Yes";

    if (isset($_GET['query-submit'])) {
        $date_arrival = sanitize_input($_GET['date_arrival']);
        $date_departure = sanitize_input($_GET['date_departure']);
        $gite_name = sanitize_input($_GET['name']);
        $gite_type = sanitize_input($_GET['type']);
        $gite_city = sanitize_input($_GET['city']);
        $gite_postal = sanitize_input($_GET['postal']);
        $gite_price = sanitize_input($_GET['price']);
        $gite_bedroom = sanitize_input($_GET['bedroom']);
        $gite_bathroom = sanitize_input($_GET['bathroom']);
        $gite_garden = empty($_GET['garden']) ? false : "Yes";
        $gite_pool = empty($_GET['pool']) ? false : "Yes";        
        $gite_kitchen = empty($_GET['kitchen']) ? false : "Yes";

        if (!empty($date_arrival) || !empty($date_departure)) {
            if ((!strtotime($date_arrival) && !empty($date_arrival)) || (!strtotime($date_departure) && !empty($date_departure))) {
                $errors[] = "Les champ \"Date d'arrivée\" et \"Date de départ\" ne doivent contenir que des dates valides";
            } else {
                if (!empty($date_arrival) && empty($date_departure)) {
                    $date_departure_tmp = new DateTime($date_arrival);
                    $date_departure = $date_departure_tmp->modify('+ 1 day')->format('Y-m-d');
                }
                if (empty($date_arrival) && !empty($date_departure)) {
                    $date_arrival_tmp = new DateTime($date_departure);
                    $date_arrival = $date_arrival_tmp->modify('- 1 day')->format('Y-m-d');
                }
                $query[] = "gite_id NOT IN (SELECT DISTINCT id_gite FROM booking WHERE booking_date_arrival <= :booking_date_departure AND booking_date_departure >= :booking_date_arrival)";
                $parameters[] = [':booking_date_departure', $date_departure, PDO::PARAM_STR];
                $parameters[] = [':booking_date_arrival', $date_arrival, PDO::PARAM_STR];
            }            
        }


        if (!empty($gite_name)) {
            if (preg_match("/[^a-zA-Z']/", $gite_name)) {
                $errors[] = "Le champ \"Nom du logement\" ne doit contenir que des caractères alphanumériques et les apostrophes";
            } else {
                $query[] = "gite_name LIKE :gite_name";
                $parameters[] = [":gite_name", "%{$gite_name}%", PDO::PARAM_STR];
            }            
        }

        if (!empty($gite_type)) {
            if (preg_match("/[^a-zA-Z]/", $gite_name)) {
                $errors[] = "Le champ \"Type de logement\" ne doit contenir que des caractères alphabétiques";
            } else {
                $query[] = "category_gite_name = :gite_type";
                $parameters[] = [":gite_type", $gite_type, PDO::PARAM_STR];
            }            
        }

        if (!empty($gite_city)) {
            if (preg_match("/[^a-zA-Z'-]/", $gite_name)) {
                $errors[] = "Le champ \"Ville\" ne doit contenir que des caractères alphanumériques, des tirets et des apostrophes";
            } else {
                $query[] = "gite_city LIKE :gite_city";
                $parameters[] = [":gite_city", "%{$gite_city}%", PDO::PARAM_STR];
            }            
        }

        if (!empty($gite_postal)) {
            $gite_postal = str_replace(" ", "", $gite_postal);
            if (preg_match("/\D/", $gite_name)) {
                $errors[] = "Le champ \"Code postal\" ne doit contenir que des caractères numériques entiers positifs";
            } elseif (strlen($gite_postal) !== 5 && strlen($gite_postal) !== 2) {
                $errors[] = "Le champ \"Code postal\" doit contenir exactement 2 chiffres ou 5 chiffres";
            } else {
            $query[] = "gite_postal LIKE :gite_postal";
            $parameters[] = [":gite_postal", "{$gite_postal}%", PDO::PARAM_STR];
            }
        }

        if (!empty($gite_price)) {
            if (!is_numeric($gite_price) && $gite_price >= 0) {
                $errors[] = "Le champ \"Prix par nuit\" doit contenir uniquement des valeurs numériques positives";
            } else {
                $query[] = "gite_price <= :gite_price";
                $parameters[] = [":gite_price", $gite_price, PDO::PARAM_INT];
            }           
        }

        if (!empty($gite_bedroom)) {
            $gite_bedroom = (int)$gite_bedroom;
            if (!is_int($gite_bedroom) && $gite_bedroom >= 0) {
                $errors[] = "Le champ \"Nombre de chambres\" doit contenir uniquement des valeurs entières positives";
            } else {
                $query[] = "gite_bedroom <= :gite_bedroom";
                $parameters[] = [":gite_bedroom", $gite_bedroom, PDO::PARAM_INT];
            }           
        }

        if (!empty($gite_bathroom)) {
            $gite_bathroom = (int)$gite_bathroom;
            if (!is_int($gite_bathroom) && $gite_bathroom >= 0) {
                $errors[] = "Le champ \"Nombre de salles de bain\" doit contenir uniquement des valeurs entières positives";
            } else {
            $query[] = "gite_bathroom <= :gite_bathroom";
            $parameters[] = [":gite_bathroom", $gite_bathroom, PDO::PARAM_INT];
            }
        }

        if ($gite_garden === "Yes") {
            $query[] = "category_gite_garden = :category_gite_garden";
            $parameters[] = [":category_gite_garden", 1, PDO::PARAM_INT];
        }

        if (!$gite_pool === "Yes") {
            $query[] = "category_gite_pool = :category_gite_pool";
            $parameters[] = [":category_gite_pool", 1, PDO::PARAM_INT];
        }

        if ($gite_kitchen === "Yes") {
            $query[] = "category_gite_kitchen = :category_gite_kitchen";
            $parameters[] = [":category_gite_kitchen", 1, PDO::PARAM_INT];
        }        
    }

    // if data is invalid, return all lodging
    if (count($errors) > 0) {
        $query = "";
        $parameters = null;
    } else {
        $query = empty($query) ? [] : $query;
        $parameters = empty($parameters) ? null : $parameters; 
    }
      