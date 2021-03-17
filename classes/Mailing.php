<?php
    

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    class Mailing {

        public function __construct() {
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
            $mail->addAddress('recipient1@mailtrap.io', 'Tim'); 
            $mail->Subject = 'Test Email via Mailtrap SMTP using PHPMailer';
            $mail->isHTML(true);
            $mailContent = "<h1>Send HTML Email using SMTP in PHP</h1>
                            <p>This is a test email I’m sending using SMTP mail server with PHPMailer.</p>";
            $mail->Body = $mailContent;

            if($mail->send()){
                echo 'Message has been sent';
            }else{
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }
      
    }

   