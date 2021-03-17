<?php 
    require_once "Database.php";
    require_once "./functions.php";

    class Logging extends Database {        

        //Log admin or client if login details are correct
        public function CheckLogin() {
            $sql = "SELECT * FROM `admin` WHERE admin_email = :admin_email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":admin_email", $_POST['login-email'],PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
    
            if ($count > 0) {
                $res = $stmt->fetch();
                if($res['admin_password'] === $_POST['login-password']) {
                    session_start();
                    $_SESSION['admin-connected'] = true;
                    $_SESSION['admin-id'] = $res['admin_id'];
                    $_SESSION['admin-email'] = $res['admin_email'];
                    header('Location: index.php');
                    exit();
                } else {
                    session_start();
                    $_SESSION['login-error'] = "Your login details are not correct, try again.";
                    header('Location: index.php');
                    exit();
                }
            // Check if client is trying to connect
            } else {
                $sql = "SELECT * FROM `client` WHERE client_email = :client_email";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(":client_email", $_POST['login-email'], PDO::PARAM_STR);
                $stmt->execute();
                $count = $stmt->rowCount();

                if ($count > 0) {
                    $res = $stmt->fetch();
                    if(password_verify($_POST['login-password'], $res['client_password'])) {
                        session_start();
                        $_SESSION['client-connected'] = true;
                        $_SESSION['client-id'] = $res['client_id'];
                        $_SESSION['client-email'] = $res['client_email'];
                        $_SESSION['client-fname'] = $res['client_fname'];
                        $_SESSION['client-lname'] = $res['client_lname'];
                        header("Location: index.php");
                        exit();
                    } else {
                        session_start();
                        $_SESSION['login-error'] = "Your login details are not correct, try again.";
                        header('Location: index.php');
                        exit();
                    }
                } else {
                    session_start();
                    $_SESSION['login-error'] = "Your login details are not correct, try again.";
                    header('Location: index.php');
                    exit();
                }            
        }
    } 

        // Register new client in database
        public function signUp() {
            if(isset($_POST['signup-submit'])) {

                $errorsSignup = [];
                $parameters = [];

                $fname = sanitize_input($_POST['signup-fname']);
                $lname = sanitize_input($_POST['signup-lname']);
                $email = sanitize_input($_POST['signup-email']);
                $pwd = sanitize_input($_POST['signup-password']);
                $pwd2 = sanitize_input($_POST['signup-password2']);
                $street = sanitize_input($_POST['signup-street']);
                $city = sanitize_input($_POST['signup-city']);
                $postal = (int)str_replace(" ", "", sanitize_input($_POST['signup-postal']));
                $country = sanitize_input($_POST['signup-country']);

                if (empty($fname) || empty($lname) || empty($email) || empty($pwd) || empty($pwd2) || empty($street) || empty($city) || empty($postal) || empty($country)) {
                    $errorsSignup[] = "All fields must be filled.";
                } else {

                    if(!is_string($fname)) {
                        $errorsSignup[] = "\"First name\" field is invalid.";
                    } else {
                        $parameters[] = [":client_fname", $fname, PDO::PARAM_STR];
                    }

                    if(!is_string($lname)) {
                        $errorsSignup[] = "\"Last name\" field is invalid.";
                    } else {
                        $parameters[] = [":client_lname", $lname, PDO::PARAM_STR];
                    }

                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errorsSignup[] = "\"Email\" field is invalid.";
                    } else {
                        $parameters[] = [":client_email", $email, PDO::PARAM_STR];
                    }

                    if($pwd !== $pwd2) {
                        $errorsSignup[] = "Passwords do not match.";
                    } else {
                        $pwd = password_hash($pwd, PASSWORD_DEFAULT);
                        $parameters[] = [":client_password", $pwd, PDO::PARAM_STR];
                    }

                    if(!is_string($street)) {
                        $errorsSignup[] = "\"Street\" field is invalid.";
                    } else {
                        $parameters[] = [":client_street", $street, PDO::PARAM_STR];
                    }

                
                    if (preg_match("/[^a-zA-Z'àé-]/", $city)) {
                        $errorsSignup[] = "\"City\" field must only contain valid input (letters, hyphens, etc.).";
                    } else {
                        $parameters[] = [":client_city", $city, PDO::PARAM_STR];
                    }            
                
            
                    if (preg_match("/\D/", $postal)) {
                        $errorsSignup[] = "\"Postal code\" field must only contain non null, positive, whole number.";
                    } elseif (strlen($postal) !== 5) {
                        $errorsSignup[] = "\"Postal code\" field must contain exactly 5 digits.";
                    } else {
                    $parameters[] = [":client_postal", $postal, PDO::PARAM_INT];
                    }
                
                    if (preg_match("/[^a-zA-Z'àé-]/", $country)) {
                        $errorsSignup[] = "\"Country\" field must only contain valid input (letters, hyphens, etc.).";
                    } else {
                        $parameters[] = [":client_country", $country, PDO::PARAM_STR];
                    } 

                    if(count($errorsSignup) > 0) {
                        session_start();
                        $_SESSION['signup-error'] = $errorsSignup;
                        header("Location: index.php");
                        exit();  
                    } else {
                        $sql = "INSERT INTO client (client_fname, client_lname, client_email, client_password, client_street, client_city, client_postal, client_country)
                                VALUES (:client_fname, :client_lname, :client_email, :client_password, :client_street, :client_city, :client_postal, :client_country)";
                        $stmt = $this->conn->prepare($sql);
                        if ($parameters) {
                            foreach($parameters as $p) {
                                $stmt->bindValue($p[0], $p[1], $p[2]);
                            } 
                        } 
                        if ($stmt->execute()) {
                            session_start();
                            header("Location: index.php?signup=success");
                            exit();     
                        } else {
                            header("Location: index.php?signup=failure");
                            exit();
                        }
                    }
                    
                }
            }
        }        
    }