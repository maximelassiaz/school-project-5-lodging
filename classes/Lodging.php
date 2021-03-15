<?php

    require_once "Database.php";
    require_once "./functions.php";

    class Lodging extends Database {

         // Get one lodging info by its id
         public function getLodging($id) {
            try {
                $id = sanitize_input($id);
                $sql = "SELECT * FROM gite 
                        INNER JOIN category_gite ON gite.id_gite_category_gite = category_gite.category_gite_id
                        WHERE gite_id = :gite_id";  
                $stmt = $this->conn->prepare($sql);  
                $stmt->bindValue("gite_id", $id, PDO::PARAM_INT);   
                $stmt->execute();
                $row = $stmt->fetch();
                return $row;
            } catch (PDOException $e) {
                die("Error : " . $e->getMessage());
            }
        }

        // Get all lodgings depending on search form input
        public function getLodgingList() {
            try {

                $query = [];
                $parameters = [];
                $errorsFilter = [];
            
                if (isset($_GET['query-submit'])) {
                    $date_arrival = sanitize_input($_GET['date_arrival']);
                    $date_departure = sanitize_input($_GET['date_departure']);
                    $gite_name = sanitize_input($_GET['name']);
                    $gite_type = sanitize_input($_GET['type']);
                    $gite_city = sanitize_input($_GET['city']);
                    $gite_postal = (int)str_replace(" ", "", sanitize_input($_GET['postal']));
                    $gite_price = (int)sanitize_input($_GET['price']);
                    $gite_guest = (int)sanitize_input($_GET['guest']);
                    $gite_bed = (int)sanitize_input($_GET['bed']);
                    $gite_bathroom = (int)sanitize_input($_GET['bathroom']);
                    $gite_wifi = empty($_GET['wifi']) ? false : "Yes";
                    $gite_garden = empty($_GET['garden']) ? false : "Yes";
                    $gite_pool = empty($_GET['pool']) ? false : "Yes";        
                    $gite_kitchen = empty($_GET['kitchen']) ? false : "Yes";


                    if (!empty($date_arrival) || !empty($date_departure)) {
                        if ((!strtotime($date_arrival) && !empty($date_arrival)) || (!strtotime($date_departure) && !empty($date_departure))) {
                            $errorsFilter[] = "\"Check in\" and \"Check out\" fiels must contain only valid date.";
                        } else {
                            if (!empty($date_arrival) && empty($date_departure)) {
                                $date_departure_tmp = new DateTime($date_arrival);
                                $date_departure = $date_departure_tmp->modify('+ 1 day')->format('Y-m-d');
                            }
                            if (empty($date_arrival) && !empty($date_departure)) {
                                $date_arrival_tmp = new DateTime($date_departure);
                                $date_arrival = $date_arrival_tmp->modify('- 1 day')->format('Y-m-d');
                            }
                            $query[] = "gite_id NOT IN (SELECT DISTINCT id_booking_gite FROM booking WHERE booking_date_arrival <= :booking_date_departure AND booking_date_departure >= :booking_date_arrival)";
                            $parameters[] = [':booking_date_departure', $date_departure, PDO::PARAM_STR];
                            $parameters[] = [':booking_date_arrival', $date_arrival, PDO::PARAM_STR];
                        }            
                    }
            
                    if (!empty($gite_name)) {
                        if (preg_match("/[^a-zA-Z'\"éà-]/", $gite_name)) {
                            $errorsFilter[] = "\"Property infos\" field must only contain valid input (letters, numbers, hyphens, etc.).";
                        } else {
                            $query[] = "gite_name LIKE :gite_name";
                            $parameters[] = [":gite_name", "%{$gite_name}%", PDO::PARAM_STR];
                        }            
                    }
            
                    if (!empty($gite_type)) {
                        if (preg_match("/[^a-zA-Z'àé-]/", $gite_type)) {
                            $errorsFilter[] = "\"Property type\" field must only contain valid input (letters, numbers, hyphens, etc.).";
                        } else {
                            $query[] = "category_gite_name = :gite_type";
                            $parameters[] = [":gite_type", $gite_type, PDO::PARAM_STR];
                        }            
                    }
            
                    if (!empty($gite_city)) {
                        if (preg_match("/[^a-zA-Z'àé-]/", $gite_city)) {
                            $errorsFilter[] = "\"City\" field must only contain valid input (letters, hyphens, etc.).";
                        } else {
                            $query[] = "gite_city LIKE :gite_city";
                            $parameters[] = [":gite_city", "%{$gite_city}%", PDO::PARAM_STR];
                        }            
                    }
            
                    if (!empty($gite_postal)) {
                        if (preg_match("/\D/", $gite_postal)) {
                            $errorsFilter[] = "\"Postal code\" field must only contain non null, positive, whole number.";
                        } elseif (strlen($gite_postal) !== 5 && strlen($gite_postal) !== 2) {
                            $errorsFilter[] = "\"Postal code\" field must only contain 2 or 5 digits.";
                        } else {
                        $query[] = "gite_postal LIKE :gite_postal";
                        $parameters[] = [":gite_postal", "{$gite_postal}%", PDO::PARAM_STR];
                        }
                    }
            
                    if (!empty($gite_price)) {
                        if (!is_numeric($gite_price) || $gite_price < 0) {
                            $errorsFilter[] = "\"Price per night\" field must only contain non null, positive, whole number.";
                        } else {
                            $query[] = "gite_price <= :gite_price";
                            $parameters[] = [":gite_price", $gite_price, PDO::PARAM_INT];
                        }           
                    }

                    if (!empty($gite_guest)) {
                        if (!is_int($gite_guest) || $gite_guest < 0) {
                            $errorsFilter[] = "\"Guest\" field must only contain non null, positive, whole number.";
                        } else {
                            $query[] = "gite_guest <= :gite_guest";
                            $parameters[] = [":gite_guest", $gite_guest, PDO::PARAM_INT];
                        }           
                    }
            
                    if (!empty($gite_bed)) {
                        if (!is_int($gite_bed) || $gite_bed < 0) {
                            $errorsFilter[] = "\"Bed\" field must only contain non null, positive, whole number.";
                        } else {
                            $query[] = "gite_bed <= :gite_bed";
                            $parameters[] = [":gite_bed", $gite_bed, PDO::PARAM_INT];
                        }           
                    }
            
                    if (!empty($gite_bathroom)) {
                        $gite_bathroom = (int)$gite_bathroom;
                        if (!is_int($gite_bathroom) || $gite_bathroom < 0) {
                            $errorsFilter[] = "Le champ \"Nombre de salles de bain\" doit contenir uniquement des valeurs entières positives";
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
            
                // if data is invalid, print errors and return all lodging
                if (count($errorsFilter) > 0) {
                    $query = "";
                    $parameters = null;
                    // TODO : print errors
                } else {
                    $query = empty($query) ? [] : $query;
                    $parameters = empty($parameters) ? null : $parameters; 
                }

                $sql = "SELECT * FROM gite 
                INNER JOIN category_gite ON gite.id_gite_category_gite = category_gite.category_gite_id";  
                $sql = empty($query) ? $sql : "$sql WHERE " . implode(" AND ", $query) . " ORDER BY gite_name";
                $stmt = $this->conn->prepare($sql);  
                if ($parameters) {
                    foreach($parameters as $p) {
                        $stmt->bindValue($p[0], $p[1], $p[2]);
                    } 
                }         
                $stmt->execute();
                $count = $stmt->rowCount();
                $rows = $stmt->fetchAll();
                $output = [];
                $output['rows'] = $rows;
                $output['count'] = $count;
                $output['sql'] = $sql;
                $output['parameters'] = $parameters;
                return $output;

            } catch (PDOException $e) {
                die("Error : " . $e->getMessage());
            }
        }

        // Get all lodgings not available, depending on search form input (complementary of previous function)
        public function getLodgingListNonAvailable($query = "", $parameters = null) {
            try {
                $query = str_replace("*", "gite_id", $query);
                $sqlNA = "SELECT * FROM gite 
                        INNER JOIN category_gite ON gite.id_gite_category_gite = category_gite.category_gite_id
                        WHERE gite_id";
                $sqlNA = empty($query) ? $sqlNA : $sqlNA . " NOT IN ($query) ORDER BY gite_name";   
                $stmt = $this->conn->prepare($sqlNA);       
                if ($parameters) {
                    foreach($parameters as $p) {
                        $stmt->bindValue($p[0], $p[1], $p[2]);
                    } 
                } 
                $stmt->execute();
                $countNA = $stmt->rowCount();
                $rowsNA = $stmt->fetchAll();
                $output = [];
                $output['rows'] = $rowsNA;
                $output['count'] = $countNA;
                $output['sql'] = $sqlNA;
                return $output;
            } catch (Exception $e) {
                die("Error : " . $e->getMessage());
            }
        }

        // Retrieve informations for range input values in the search form
        public function searchForm() {
            try {
                $sql = "SELECT  MAX(gite_price) AS gite_price_max, 
                                MIN(gite_price) AS gite_price_min, 
                                MAX(gite_guest) AS gite_guest_max, 
                                MIN(gite_guest) AS gite_guest_min, 
                                MAX(gite_bed) AS gite_bed_max, 
                                MIN(gite_bed) AS gite_bed_min, 
                                MAX(gite_bathroom) AS gite_bathroom_max, 
                                MIN(gite_bathroom) AS gite_bathroom_min
                        FROM gite  
                        INNER JOIN category_gite ON gite.id_gite_category_gite = category_gite.category_gite_id 
                        LEFT JOIN booking ON gite.gite_id = booking.id_booking_gite";              
                $stmt = $this->conn->prepare($sql);               
                $stmt->execute();
                $rows = $stmt->fetch();
                return $rows;
            } catch (PDOException $e) {
                die("Error : " . $e->getMessage());
            }
        }

        // Retrieve all lodging category from database
        public function displayGiteCategory() {
            try {
                $sql = "SELECT DISTINCT category_gite_id, category_gite_name
                        FROM category_gite  
                        LEFT JOIN gite ON gite.id_gite_category_gite = category_gite.category_gite_id
                        ORDER BY category_gite_name";              
                $stmt = $this->conn->prepare($sql);               
                $stmt->execute();
                $rows = $stmt->fetchAll();
                return $rows;
            } catch (PDOException $e) {
                die("Error : " . $e->getMessage());
            }
        }

        public function displayGiteCategorySearch() {
            try {
                $sql = "SELECT DISTINCT category_gite_name
                        FROM category_gite 
                        INNER JOIN gite ON gite.id_gite_category_gite = category_gite.category_gite_id
                        ORDER BY category_gite_name";              
                $stmt = $this->conn->prepare($sql);               
                $stmt->execute();
                $rows = $stmt->fetchAll();
                return $rows;
            } catch (PDOException $e) {
                die("Error : " . $e->getMessage());
            }
        }
            
    }