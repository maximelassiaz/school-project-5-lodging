<?php 

    require_once "Database.php";

    class Logging extends Database {        

        //Check if admin is trying to connect
        public function CheckLogin() {
            $sql = "SELECT * FROM admin WHERE admin_email = :admin_email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":admin_email", $_POST['login-email'],PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
    
            if ($count > 0) {
                $res = $stmt->fetch();
                if($res['admin_password'] === $_POST['login-password']) {
                    session_start();
                    $_SESSION['admin-connected'] = true;
                    $_SESSION['admin-email'] = $res['admin_email'];
                    // TODO : rewrite path
                    header('Location: index.php');
                    exit();
                } else {
                    // TODO : rewrite path
                    header('Location: index.php?error=logindetails');
                    exit();
                }
            // Check if client is trying to connect
            } else {
                $sql = "SELECT * FROM client WHERE client_email = :client_email";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(":client_email", $_POST['login-email'], PDO::PARAM_STR);
                $stmt->execute();
                $count = $stmt->rowCount();
        
                if ($count > 0) {
                    $res = $stmt->fetch();
                    if($res['client_password'] === $_POST['login-password']) {
                        session_start();
                        $_SESSION['client-connected'] = true;
                        $_SESSION['client-email'] = $res['client_email'];
                        $_SESSION['client-fname'] = $res['client_fname'];
                        $_SESSION['client-lname'] = $res['client_lname'];
                        // TODO : rewrite path
                        header('Location: index.php');
                        exit();
                    } else {
                        // TODO : rewrite path
                        header('Location: index.php?error=logindetail');
                        exit();
                    }
                }
            }
        } 

        public function signUp() {
            
        }
        
        
        
    }