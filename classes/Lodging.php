<?php

    require_once "Database.php";
    require_once "./functions.php";

    class Lodging extends Database {

         // Get one lodging info
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
        public function getLodgingList($query = [], $parameters = null) {
            try {
                $sql = "SELECT * FROM gite 
                        INNER JOIN category_gite ON gite.id_gite_category_gite = category_gite.category_gite_id";  
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
            } catch (PDOException $e) {
                die("Error : " . $e->getMessage());
            }
        }

        // Get all lodgings not available, depending on search form input (complementary of previous function)
        public function getLodgingListNonAvailable($query) {
            try {
                $query = str_replace("*", "gite_id", $query);
                $sql = "SELECT * FROM gite 
                        INNER JOIN category_gite ON gite.id_gite_category_gite = category_gite.category_gite_id
                        WHERE gite_id NOT IN ($query)";
                $stmt = $this->conn->prepare($sql);       
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

        // Retrieve informations for range input values in the search form
        public function searchForm() {
            try {
                $sql = "SELECT  MAX(gite_price) AS gite_price_max, 
                                MIN(gite_price) AS gite_price_min, 
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
                $sql = "SELECT category_gite_id, category_gite_name
                        FROM category_gite  
                        LEFT JOIN gite ON gite.id_gite_category_gite = category_gite.category_gite_id";              
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
                $sql = "SELECT *
                        FROM category_gite 
                        INNER JOIN gite ON gite.id_gite_category_gite = category_gite.category_gite_id";              
                $stmt = $this->conn->prepare($sql);               
                $stmt->execute();
                $rows = $stmt->fetchAll();
                return $rows;
            } catch (PDOException $e) {
                die("Error : " . $e->getMessage());
            }
        }
            
    }