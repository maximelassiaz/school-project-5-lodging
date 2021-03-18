<?php
    

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once 'vendor/autoload.php';
    require_once './functions.php';

    class Mailing {

        public function __construct($fname, $lname, $email) {
            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'd8a6e9b9a5fd9b'; 
            $mail->Password = '087e03c8669b5d'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;
        
            $mail->setFrom('info@mailtrap.io', 'Mailtrap');
            $mail->addReplyTo('info@mailtrap.io', 'Mailtrap');
            $mail->addAddress($email, $fname . ' ' . $lname);
            $mail->Subject = 'Darkbnb - Confirmation for your booking';
            $mail->isHTML(true);

            $date_arrival = htmlspecialchars($_POST['booking_date_arrival']);
            $date_departure = htmlspecialchars($_POST['booking_date_departure']);
            $name = strtoupper(htmlspecialchars($_POST['booking_gite_name']));
            $description = htmlspecialchars($_POST['booking_gite_description']);
            $street = strtoupper(htmlspecialchars($_POST['booking_gite_street']));
            $city = strtoupper(htmlspecialchars($_POST['booking_gite_city']));
            $postal = htmlspecialchars($_POST['booking_gite_postal']);
            $country = strtoupper(htmlspecialchars($_POST['booking_gite_country']));
            $price = htmlspecialchars($_POST['booking_gite_price']);
            $guest = htmlspecialchars($_POST['booking_gite_guest']);
            $bed = htmlspecialchars($_POST['booking_gite_bed']);
            $bathroom = htmlspecialchars($_POST['booking_gite_bathroom']);
            $wifi = isAvailable(htmlspecialchars($_POST['booking_gite_wifi']));
            $type = strtoupper(htmlspecialchars($_POST['booking_gite_type']));
            $pool = isAvailable(htmlspecialchars($_POST['booking_gite_pool']));
            $garden = isAvailable(htmlspecialchars($_POST['booking_gite_garden']));
            $kitchen = isAvailable(htmlspecialchars($_POST['booking_gite_kitchen']));

            $gite_id = htmlspecialchars($_POST['booking_gite_id']);

            $mailContent = "<div style='width: 100%; background: #343A40; padding: 30px; box-sizing: border-box;'> 
                            <p style='color: white; margin-left: 30px;'>Dear client,</p> <br>
                            <p style='color: white; margin-left: 30px;'>We sent you this email to confirm the following booking :</p>
                            <table style='border: 1px solid white; border-collapse: collapse; width: 80%; margin: 20px auto; color:white'>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Booking dates</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>From $date_arrival to $date_departure</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Property Name</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$name</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Property type</td>
                                    <td style='text-align:left; padding: 16px;'>$type</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Description</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$description</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Full Adress</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$street<br>$postal $city<br> $country</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Price / night</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$price €</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Guest(s)</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$guest</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Bed(s)</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$bed</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Bathroom(s)</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$bathroom</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>WiFi</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$wifi</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Pool</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$pool</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Garden</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$garden</td>
                                </tr>
                                <tr>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>Kitchen</td>
                                    <td style='text-align:left; padding: 16px; border: 1px solid white;'>$kitchen</td>
                                </tr>
                            </table>
                            </div>";
            $mail->Body = $mailContent;
            $mail->CharSet="UTF-8";

            if($mail->send()){
                header("Location: details.php?id=$gite_id&booking=success");
                exit();
            }else{
                header("Location: details.php?id=$gite_id&booking=failure");
                exit();
            }
        }
      
    }

   