<?php

    require_once "Lodging.php";

    class LodgingManager extends Lodging {

        // create a new property
        public function addLodging() {
            if(isset($_POST['create-submit'])) {

                require_once "functions.php";
                $name = sanitize_input($_POST['create-name']);
                $description = sanitize_input($_POST['create-description']);
                $type = (int)sanitize_input($_POST['create-type']);
                // $image = sanitize_input($_POST['create-image']);
                $street = sanitize_input($_POST['create-street']);
                $postal = (int)str_replace(" ", "",sanitize_input($_POST['create-postal']));
                $city = sanitize_input($_POST['create-city']);
                $country = sanitize_input($_POST['create-country']);
                $price = (float)sanitize_input($_POST['create-price']);
                $guest = (int)sanitize_input($_POST['create-guest']);
                $bed = (int)sanitize_input($_POST['create-bed']);
                $bathroom = (int)sanitize_input($_POST['create-bathroom']);
                $wifi = isset($_POST['create-wifi']) ? "Yes" : "No";


                // var_dump($name);
                // var_dump($description);
                // var_dump($type);
                // var_dump($street);
                // var_dump($postal);
                // var_dump($city);
                // var_dump($country);
                // var_dump($price);
                // var_dump($guest);
                // var_dump($bed);
                // var_dump($bathroom);
                // var_dump($wifi);

                $parameters = [];
                $errorsCreate = [];

                if(empty($name) || empty($description) || empty($type) || empty($street) || empty($postal) || empty($city) || empty($country) || empty($price) || empty($guest) || empty($bed) || empty($bathroom)) {
                    $errorsCreate[] = "Tous les champs doivent être remplis";
                } else {
                    // TODO : rewrite all fields in English
                    if (preg_match("/[^a-zA-Z0-9'-.\"]/", $name)) {
                        $errorsCreate[] = "Le champ \"Nom du logement\" ne doit contenir que des caractères alphanumériques et les apostrophes";
                    } 

                    if (preg_match("/[^a-zA-Z0-9'.]/", $description)) {
                        $errorsCreate[] = "Le champ \"Description du logement\" ne doit contenir que des caractères alphanumériques et les apostrophes";
                    } 

                    if (!is_int($type) || $type < 0) {
                        $errorsCreate[] = "Le champ \"Nombre de chambres\" doit contenir uniquement des valeurs entières positives et non nul";
                    }

                    // TODO : do image function
                    // $parameters[] = [":gite_image", $image, PDO::PARAM_STR];

                    if (preg_match("/[^a-zA-Z0-9'-.]/", $street)) {
                        $errorsCreate[] = "Le champ \"Nom du logement\" ne doit contenir que des caractères alphanumériques et les apostrophes";
                    }

                    if (preg_match("/\D/", $postal)) {
                        $errorsCreate[] = "Le champ \"Code postal\" ne doit contenir que des caractères numériques entiers positifs";
                    } 
                    
                    if (strlen($postal) !== 5) {
                        $errorsCreate[] = "Le champ \"Code postal\" doit contenir exactement 5 chiffres";
                    }

                    if (preg_match("/[^a-zA-Z'-]/", $city)) {
                        $errorsCreate[] = "Le champ \"Ville\" ne doit contenir que des caractères alphanumériques, des tirets et des apostrophes";
                    }

                    if (preg_match("/[^a-zA-Z'-]/", $country)) {
                        $errorsCreate[] = "Le champ \"Pays\" ne doit contenir que des caractères alphanumériques, des tirets et des apostrophes";
                    }

                    if (!is_numeric($price) || $price < 0) {
                        $errorsCreate[] = "Le champ \"Prix par nuit\" doit contenir uniquement des valeurs numériques positives et non nul";
                    }
                    
                    if (!is_int($guest) || $guest < 0) {
                        $errorsCreate[] = "Le champ \"Nombre de chambres\" doit contenir uniquement des valeurs entières positives et non nul";
                    }

                    if (!is_int($bed) || $bed < 0) {
                        $errorsCreate[] = "Le champ \"Nombre de chambres\" doit contenir uniquement des valeurs entières positives et non nul";
                    }

                    if (!is_int($bathroom) || $bathroom < 0) {
                        $errorsCreate[] = "Le champ \"Nombre de salles de bain\" doit contenir uniquement des valeurs entières positives";
                    }

                    if (!isset($wifi)) {
                        $errorsCreate[] = "Wifi checkbox has a problem";
                    }

                    if (count($errorsCreate) > 0) {
                        echo implode("<br>", $errorsCreate);
                    } else {
                        $sql = "INSERT INTO gite (gite_name, gite_description, id_gite_category_gite, gite_street, gite_postal,gite_city, gite_country, gite_price, gite_guest, gite_bed, gite_bathroom, gite_wifi)
                                VALUES (:gite_name, :gite_description, :gite_type, :gite_street, :gite_postal, :gite_city, :gite_country, :gite_price, :gite_guest, :gite_bed, :gite_bathroom, :gite_wifi)";
                        $stmt = $this->conn->prepare($sql);

                        $parameters[] = [":gite_name", $name, PDO::PARAM_STR];
                        $parameters[] = [":gite_description", $description, PDO::PARAM_STR];
                        $parameters[] = [":gite_type", $type, PDO::PARAM_INT];
                        $parameters[] = [":gite_street", $street, PDO::PARAM_STR];
                        $parameters[] = [":gite_postal", $postal, PDO::PARAM_INT];
                        $parameters[] = [":gite_city", $city, PDO::PARAM_STR];
                        $parameters[] = [":gite_country", $country, PDO::PARAM_STR];
                        $parameters[] = [":gite_price", $price, PDO::PARAM_INT];
                        $parameters[] = [":gite_guest", $guest, PDO::PARAM_INT];
                        $parameters[] = [":gite_bed", $bed, PDO::PARAM_INT];
                        $parameters[] = [":gite_bathroom", $bathroom, PDO::PARAM_INT];
                        if ($wifi === "Yes") {
                            $parameters[] = [":gite_wifi", 1, PDO::PARAM_INT];
                        }  elseif ($wifi === "No") {
                            $parameters[] = [":gite_wifi", 0, PDO::PARAM_INT];
                        }

                        foreach ($parameters as $p) {
                            $stmt->bindValue($p[0], $p[1], $p[2]);
                        }

                        if ($stmt->execute()) {
                            echo "success";
                        } else {
                            echo "failure";
                        }
                    }
                }
            }
        }
    }