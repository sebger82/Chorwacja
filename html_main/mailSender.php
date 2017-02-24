<?php
define('ADMIN_EMAIL', 'enemyunknown@wp.pl');
define('ADMIN_NAME', 'Sebastian Urbański');
//sprawdzenie metody post
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //zaladowanie biblioteki phpmailer
    require_once '../phpmailer/PHPMailerAutoload.php';
    //walidacja pól formularza
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    //sprawdzanie czy pola formularza nie sa puste
    if(!empty($name) && !empty($email) && !empty($subject) && !empty($message)){
        
        $subject = "Chorwacja 2013 - $subject";
        $message = "Wiadomość od $name ($email)<br>Treść wiadomości: $message";
        
        //konfiguracja mailera php
        $mail = new PHPMailer;
        $mail->SMTPDebug = 1;
        $mail->isSMTP();
        $mail->Host = 'smtp.wp.pl';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = 'chorwacja.2013@wp.pl';
        $mail->Password = 'coderslab2013';
        $mail->setFrom( 'chorwacja.2013@wp.pl');
        $mail->addAddress( ADMIN_EMAIL, ADMIN_NAME );
        $mail->addReplyTo( $email, $name );
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->CharSet = 'UTF-8';
        //urluncode sprawia ze nie bedziemy miec spacji, bo wiadomosc ma sie pojawic tez w pasku adresu przegladarki
        if(!$mail->send()){
            $msg = urlencode( 'Błąd w wysłaniu wiadomości: ' . $mail->ErrorInfo );
            $mode = 'error';
        } else {
            $msg = urlencode( 'Dziękujemy za kontakt z nami!' );
            $mode = 'success';
        }
    } else {
        $msg = urlencode( 'Nie zostały podane wszystkie wymagane dane' );
        $mode = 'error';
    }
    //przenosi nas na wlasciwa strone
    header( 'Location: kontakt.php?msg=' . $msg . '&mode=' . $mode);  
}