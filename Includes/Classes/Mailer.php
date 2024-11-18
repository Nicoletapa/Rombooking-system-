<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');




class Mailer
{
    public function sendEmail($to, $subject, $body)
    {
        require($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/PHPMailer/src/PHPMailer.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/PHPMailer/src/SMTP.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/PHPMailer/src/Exception.php');

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $config = include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Private/config.private.php');
        $mail->Username = $config['mail_username'];
        $mail->Password = $config['mail_password'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('thevithach@gmail.com', 'Password Reset');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
            throw new Exception("Kunne ikke sende e-posten.");
        }
    }
}
