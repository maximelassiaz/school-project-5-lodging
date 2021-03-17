<?php

    require_once "Lodging.php";
    require_once "functions.php";

    class LodgingManager extends Lodging {

        // create a new property
        public function addLodging() {
            if(!isset($_POST['create-submit'])) {
                header("Location: dashboard.php");
                exit();
            } else {
                
                $name = sanitize_input($_POST['create-name']);
                $description = sanitize_input($_POST['create-description']);
                $type = (int)sanitize_input($_POST['create-type']);
                $image = $_FILES['create-image'];
                $street = sanitize_input($_POST['create-street']);
                $postal = (int)str_replace(" ", "",sanitize_input($_POST['create-postal']));
                $city = sanitize_input($_POST['create-city']);
                $country = sanitize_input($_POST['create-country']);
                $price = (float)sanitize_input($_POST['create-price']);
                $guest = (int)sanitize_input($_POST['create-guest']);
                $bed = (int)sanitize_input($_POST['create-bed']);
                $bathroom = (int)sanitize_input($_POST['create-bathroom']);
                $wifi = isset($_POST['create-wifi']) ? "Yes" : "No";

                $parameters = [];
                $errorsCreate = [];

                if(empty($name) || empty($description) || empty($type) || empty($street) || empty($postal) || empty($city) || empty($country) || empty($image) || empty($price) || empty($guest) || empty($bed) || empty($bathroom)) {
                    header("Location: dashboard.php?create=emptyfields");
                    exit();
                } else {
                    if (!is_string($name)) {
                        $errorsCreate[] = "\"Property name\" field must only contain letters, numbers, etc.";
                    } 

                    if (!is_string($description)) {
                        $errorsCreate[] = "\"Property description\" field must only contain letters, numbers, etc.";
                    } 

                    if (!is_int($type) || $type < 0) {
                        $errorsCreate[] = "\"Property type\" field must only contain non null, positive, whole numbers";
                    }

                        $imageName = $image['name'];
                        $imageTmpName = $image['tmp_name'];
                        $imageSize = $image['size'];
                        $imageError = $image['error'];

                        $imageExt = explode('.', $imageName);
                        $imageActualExt = strtolower(end($imageExt));
                        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];

                    if(!in_array($imageActualExt, $allowedExt)) {
                        $errorsCreate[] = "Image extension must be \"jpg\", \"png\", \"jpeg\" or \"pdf\""; 
                    } elseif ($imageError != 0) {
                        $errorsCreate[] = "An error occured with your image, please try again";
                    } elseif ($imageSize > 10000000) {
                        $errorsCreate[] = "Image size is too large";
                    } else {
                        $imageNameNew = uniqid('', true) . "." . $imageActualExt;
                        $imageDestination = "./public/images-property/" . $imageNameNew;
                        move_uploaded_file($imageTmpName, $imageDestination);
                        $parameters[] = [":gite_image", $imageNameNew, PDO::PARAM_STR];
                    }

                    if (!is_string($street)) {
                        $errorsCreate[] = "\"Property street\" field must only contain letters, numbers, etc.";
                    }

                    if (preg_match("/\D/", $postal)) {
                        $errorsCreate[] = "\"Postal code\" field must only contain non null, positive, whole numbers";
                    } 
                    
                    if (strlen($postal) !== 5) {
                        $errorsCreate[] = "\"Postal code\" field must be 5 digits long exactly";
                    }

                    if (!is_string($city)) {
                        $errorsCreate[] = "\"Property city\" field must only contain letters, hyphens, etc.";
                    }

                    if (preg_match("/[^a-zA-Z'-]/", $country)) {
                        $errorsCreate[] = "\"Property country\" field must only contain letters, hyphens, etc.";
                    }

                    if (!is_numeric($price) || $price < 0) {
                        $errorsCreate[] = "\"Price per night\" field must only contain non null, positive numbers";
                    }
                    
                    if (!is_int($guest) || $guest < 0) {
                        $errorsCreate[] = "\"Guest(s)\" field must only contain non null, positive, whole numbers";
                    }

                    if (!is_int($bed) || $bed < 0) {
                        $errorsCreate[] = "\"Bed(s)\" field must only contain non null, positive, whole numbers";
                    }

                    if (!is_int($bathroom) || $bathroom < 0) {
                        $errorsCreate[] = "\"Bathroom(s)\" field must only contain non null, positive, whole numbers";
                    }

                    if (!isset($wifi)) {
                        $errorsCreate[] = "Wifi checkbox has a problem";
                    }

                    if (count($errorsCreate) > 0) {
                        session_start();
                        $_SESSION['create-error'] = $errorsCreate;
                        header("Location: dashboard.php?create=failure");
                        exit();
                    } else {
                        $sql = "INSERT INTO gite (gite_name, gite_description, id_gite_category_gite, gite_image, gite_street, gite_postal,gite_city, gite_country, gite_price, gite_guest, gite_bed, gite_bathroom, gite_wifi)
                                VALUES (:gite_name, :gite_description, :gite_type, :gite_image, :gite_street, :gite_postal, :gite_city, :gite_country, :gite_price, :gite_guest, :gite_bed, :gite_bathroom, :gite_wifi)";
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
                            header("Location: dashboard.php?create=success");
                            exit();
                        } else {
                            header("Location: dashboard.php?create=failure");
                            exit();
                        }
                    }
                }
            }
        }

        // delete a property
        // nww/b
        public function deleteLodging() {

            if(!isset($_POST['delete-gite-submit'])) {
                header("Location: dashboard.php");
                exit();
            } else {

                if(isset($_POST['delete-gite-id']) && (int)$_POST['delete-gite-id'] > 0) {
                    
                    $sql = "DELETE FROM gite WHERE gite_id = :gite_id";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindValue(":gite_id", $_POST['delete-gite-id'], PDO::PARAM_INT);
                    
                    if ($stmt->execute()) {
                        header("Location: dashboard.php?delete=success");
                        exit();
                    } else {
                        header("Location: dashboard.php?delete=success");
                        exit();
                    }
                } else {
                    header("Location: dashboard.php");
                    exit();
                }
            } 
        }

        // update a new property
        public function updateLodging() {
            if(!isset($_POST['update-submit'])) {
                header("Location: dashboard.php");
                exit();
            } else {
                
                $name = sanitize_input($_POST['update-name']);
                $description = sanitize_input($_POST['update-description']);
                $type = (int)sanitize_input($_POST['update-type']);
                $street = sanitize_input($_POST['update-street']);
                $postal = (int)str_replace(" ", "",sanitize_input($_POST['update-postal']));
                $city = sanitize_input($_POST['update-city']);
                $country = sanitize_input($_POST['update-country']);
                $price = (float)sanitize_input($_POST['update-price']);
                $guest = (int)sanitize_input($_POST['update-guest']);
                $bed = (int)sanitize_input($_POST['update-bed']);
                $bathroom = (int)sanitize_input($_POST['update-bathroom']);
                $wifi = isset($_POST['update-wifi']) ? "Yes" : "No";

                $parameters = [];
                $errorsUpdate = [];

                if(empty($name) || empty($description) || empty($type) || empty($street) || empty($postal) || empty($city) || empty($country) || empty($price) || empty($guest) || empty($bed) || empty($bathroom)) {
                    header("Location: dashboard.php?update=emptyfields");
                    exit();
                } else {
                    if (!is_string($name)) {
                        $errorsUpdate[] = "\"Property name\" field must only contain letters, numbers, etc.";
                    } 

                    if (!is_string($description)) {
                        $errorsUpdate[] = "\"Property description\" field must only contain letters, numbers, etc.";
                    } 

                    if (!is_int($type) || $type < 0) {
                        $errorsUpdate[] = "\"Property type\" field must only contain non null, positive, whole numbers";
                    }

                    if (!empty($_FILES['update-image']['name'])) {
                        $image = $_FILES['update-image'];
                        $imageName = $image['name'];
                        $imageTmpName = $image['tmp_name'];
                        $imageSize = $image['size'];
                        $imageError = $image['error'];

                        $imageExt = explode('.', $imageName);
                        $imageActualExt = strtolower(end($imageExt));
                        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];

                        if(!in_array($imageActualExt, $allowedExt)) {
                            $errorsUpdate[] = "Image extension must be \"jpg\", \"png\", \"jpeg\" or \"pdf\""; 
                        } elseif ($imageError != 0) {
                            $errorsUpdate[] = "An error occured with your image, please try again";
                        } elseif ($imageSize > 10000000) {
                            $errorsUpdate[] = "Image size is too large";
                        } else {
                            $imageNameNew = uniqid('', true) . "." . $imageActualExt;
                            $imageDestination = "./public/images-property/" . $imageNameNew;
                            move_uploaded_file($imageTmpName, $imageDestination);
                            $parameters[] = [":gite_image", $imageNameNew, PDO::PARAM_STR];
                        }             
                    }                       

                    if (!is_string($street)) {
                        $errorsUpdate[] = "\"Property street\" field must only contain letters, numbers, etc.";
                    }

                    if (preg_match("/\D/", $postal)) {
                        $errorsUpdate[] = "\"Postal code\" field must only contain non null, positive, whole numbers";
                    } 
                    
                    if (strlen($postal) !== 5) {
                        $errorsUpdate[] = "\"Postal code\" field must be 5 digits long exactly";
                    }

                    if (!is_string($city)) {
                        $errorsUpdate[] = "\"Property city\" field must only contain letters, hyphens, etc.";
                    }

                    if (preg_match("/[^a-zA-Z'-]/", $country)) {
                        $errorsUpdate[] = "\"Property country\" field must only contain letters, hyphens, etc.";
                    }

                    if (!is_numeric($price) || $price < 0) {
                        $errorsUpdate[] = "\"Price per night\" field must only contain non null, positive numbers";
                    }
                    
                    if (!is_int($guest) || $guest < 0) {
                        $errorsUpdate[] = "\"Guest(s)\" field must only contain non null, positive, whole numbers";
                    }

                    if (!is_int($bed) || $bed < 0) {
                        $errorsUpdate[] = "\"Bed(s)\" field must only contain non null, positive, whole numbers";
                    }

                    if (!is_int($bathroom) || $bathroom < 0) {
                        $errorsUpdate[] = "\"Bathroom(s)\" field must only contain non null, positive, whole numbers";
                    }

                    if (!isset($wifi)) {
                        $errorsUpdate[] = "Wifi checkbox has a problem";
                    }

                    if (count($errorsUpdate) > 0) {
                        session_start();
                        $_SESSION['create-update'] = $errorsUpdate;
                        header("Location: dashboard.php?update=failure");
                        exit();
                    } else {
                        $giteImage = empty($_FILES['update-image']['name']) ? "" : ", gite_image = :gite_image";
                        $id = $_POST['update-id'];
                        $sql = "UPDATE gite 
                                SET gite_name = :gite_name,
                                    gite_description = :gite_description,
                                    id_gite_category_gite = :gite_type,
                                    gite_street = :gite_street,
                                    gite_postal = :gite_postal,
                                    gite_city = :gite_city,
                                    gite_country = :gite_country,
                                    gite_price = :gite_price,
                                    gite_guest = :gite_guest,
                                    gite_bed = :gite_bed, 
                                    gite_bathroom = :gite_bathroom,
                                    gite_wifi = :gite_wifi
                                    $giteImage
                                WHERE gite_id = $id";
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
                            header("Location: dashboard.php?update=success");
                            exit();
                        } else {
                            header("Location: dashboard.php?update=failure");
                            exit();
                        }
                    }
                }
            }
        }
    }