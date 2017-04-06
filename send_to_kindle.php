<?php
/**
 * you might want to overwrite these configs through include a config.php file.
 */
$kindle_ad = '';
$email_ad = '';
$email_pass = '';
$email_port = 0;
$email_smtp_ad = '';

include 'config.php';

// send to kindle
if (!empty($kindle_ad)) {
    include 'vendor/autoload.php';
    $mail = new PHPMailer();
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $email_smtp_ad;  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $email_ad;                 // SMTP username
    $mail->Password = $email_pass;                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $email_port;                                    // TCP port to connect to

    $mail->setFrom($email_ad, $email_ad);
    $mail->addAddress($kindle_ad, 'kindle');     // Add a recipient
    $att_ret = $mail->addAttachment('zetianji.txt');
    if (!$att_ret) {
        die('att fail');
    }
    $mail->isHTML(true);
    $mail->Subject = 'zetianji';
    $mail->Body    = 'zetianji';
    $mail->AltBody = 'zetianji';
    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
} else {
    echo 'empty kindle address';
}
