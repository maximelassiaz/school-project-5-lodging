<?php
        class Lodging extends Database {


            // get a lodging list based on on data from the search from
            public function getLodgingList($query = [], $parameters = null) {
                try {
                    $sql = "SELECT * FROM gite 
                            INNER JOIN category_gite ON gite.id_category_gite = category_gite.category_gite_id";  
                    $sql = empty($query) ? $sql : "$sql WHERE " . implode(" AND ", $query);
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
                    return $output;
                } catch (Exception $e) {
                    die("Error : " . $e->getMessage());
                }
            }
    
            // get a non available lodging list based on data from the search form
            public function getLodgingListNA($query, $parameters = null) {
                try {
                    $query = str_replace("*", "gite_id", $query);
                    $sql = "SELECT * FROM gite 
                            INNER JOIN category_gite ON gite.id_category_gite = category_gite.category_gite_id
                            WHERE gite_id NOT IN ($query)";
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
                    return $output;
                } catch (Exception $e) {
                    die("Error : " . $e->getMessage());
                }
            }
    
            // get data from database to use in the search form (min / max value for range type input)
            public function searchForm() {
                try {
                    $sql = "SELECT  MAX(gite_price) AS gite_price_max, 
                                    MIN(gite_price) AS gite_price_min, 
                                    MAX(gite_bedroom) AS gite_bedroom_max, 
                                    MIN(gite_bedroom) AS gite_bedroom_min, 
                                    MAX(gite_bathroom) AS gite_bathroom_max, 
                                    MIN(gite_bathroom) AS gite_bathroom_min
                            FROM gite  
                            INNER JOIN category_gite ON gite.id_category_gite = category_gite.category_gite_id 
                            LEFT JOIN booking ON gite.gite_id = booking.id_gite";              
                    $stmt = $this->conn->prepare($sql);               
                    $stmt->execute();
                    $rows = $stmt->fetch();
                    return $rows;
                } catch (PDOException $e) {
                    die("Error : " . $e->getMessage());
                }
            }
    
            // get all lodging category for the search form
            public function displayGiteCategory() {
                try {
                    $sql = "SELECT DISTINCT category_gite_name
                            FROM gite  
                            INNER JOIN category_gite ON gite.id_category_gite = category_gite.category_gite_id";              
                    $stmt = $this->conn->prepare($sql);               
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    return $rows;
                } catch (Exception $e) {
                    die("Error : " . $e->getMessage());
                }
            }
         
        }
        