<?php

    class Database {
        private $dbName = "lodging";
        private $dbHost = "localhost";
        private $dbUsername = "root";
        private $dbPwd = "";
        protected $conn;
        
        // Connect to database when an Database object is created
        public function __construct() {
            $this->connexionPDO();
        }

        // Make the connexion via PDO to Database lodging
        private function connexionPDO() {
            try {
                $this->conn = new PDO("mysql:host={$this->dbHost};dbname={$this->dbName};charset=utf8", $this->dbUsername, $this->dbPwd);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Error : " . $e->getMessage());
            }
        }     
    }