<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Composer autoload

// Maak een instantie van PHPMailer
$mail = new PHPMailer(true);

try {
    // Server instellingen
    $mail->isSMTP();                                     
    $mail->Host       = 'smtp.example.com';  // Gebruik je SMTP-server
    $mail->SMTPAuth   = true;                             
    $mail->Username   = 'je_email@example.com';             
    $mail->Password   = 'je_wachtwoord';                     
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    
    $mail->Port       = 587;                                 

    // Ontvanger(s)
    $mail->setFrom('je_email@example.com', 'GeoFort Team');
    $mail->addAddress('aanvrager@example.com', 'Aanvrager');     

    // Inhoud van de e-mail
    $mail->isHTML(true);                                
    $mail->Subject = 'Bevestiging van je GeoFort aanvraag';
    $mail->Body    = 'Hier is een overzicht van je aanvraag...';
    $mail->AltBody = 'Dit is de tekstversie van de e-mail zonder HTML';

    // Verstuur de e-mail
    $mail->send();
    echo 'E-mail verstuurd';
} catch (Exception $e) {
    echo "E-mail kon niet worden verstuurd. Mailer Error: {$mail->ErrorInfo}";
}
