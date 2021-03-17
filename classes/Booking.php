<?php

    require_once "Database.php";
    require_once "./functions.php";

    class Booking extends Database {
        public function reserveProperty() {

                
            
                $errorsBooking = [];
                $parameters = [];

                $date_arrival = sanitize_input($_POST['booking_date_arrival']);
                $date_departure = sanitize_input($_POST['booking_date_departure']);
                $client_id = (int)sanitize_input($_POST['booking_client_id']);
                $gite_id = (int)sanitize_input($_POST['booking_gite_id']);

                if (empty($date_arrival) || empty($date_departure) || empty($client_id) || empty($gite_id)) {
                    header("Location: details.php?id=$gite_id&booking=emptyfields");
                    exit();
                } else {

                    if($date_arrival >= $date_departure) {
                        $errorsBooking[] = "\"Date of arrival\" must be set before \"date of departure\"";
                    }

                    if ((!strtotime($date_arrival) && !empty($date_arrival)) || (!strtotime($date_departure) && !empty($date_departure))) {
                        $errorsBooking[] = "\"Check in\" and \"Check out\" fiels must contain only valid date.";
                    } else {
                        $parameters[] = [':booking_date_arrival', $date_arrival, PDO::PARAM_STR];
                        $parameters[] = [':booking_date_departure', $date_departure, PDO::PARAM_STR];
                    } 
                    
                    if(!is_int($client_id) || $client_id <= 0) {
                        $errorsBooking[] = "There is a problem with your login details (error : wrong client id), please contact an administrator";
                    } else {
                        $parameters[] = [':id_booking_client', $client_id, PDO::PARAM_INT];
                    }

                    if(!is_int($gite_id) || $gite_id <= 0) {
                        $errorsBooking[] = "There is a problem with your login details (error : wrong property id), please contact an administrator";
                    } else {
                        $parameters[] = [':id_booking_gite', $gite_id, PDO::PARAM_INT];
                    }

                    if (count($errorsBooking) > 0) {
                        session_start();
                        $_SESSION['booking-error'] = $errorsBooking;
                        header("Location: details.php?id=$gite_id");
                        exit();
                    } else {
                        $sql = "INSERT INTO booking (id_booking_gite, id_booking_client, booking_date_arrival, booking_date_departure)
                                VALUES (:id_booking_gite, :id_booking_client, :booking_date_arrival, :booking_date_departure)";
                        $stmt = $this->conn->prepare($sql);
                        if ($parameters) {
                            foreach ($parameters as $p) {
                                $stmt->bindValue($p[0], $p[1], $p[2]);
                            }
                        }
                        if ($stmt->execute()) {
                            return true;
                        } else {
                            header("Location: details.php?id=$gite_id&booking=failure");
                            exit();
                        }
                    }
                }
            }
        }

    