<?php
require 'Connections/localhost.php';

function send_mail1($to, $subject, $body)
{
    require 'PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                                  // Enable verbose debug output
//    $mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
//    $mail->SMTPSecure = 'ssl';
//    $mail->Port = 465;
//    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->setLanguage('ar', 'PHPMailer/language/');
    $mail->Host = 'smtp.gmail.com';        // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = env('MAIL_EMAIL');                  // SMTP username
    echo 'sdf';
    $mail->Password = env('MAIL_PASSWORD');               // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom(env('MAIL_EMAIL'), env('MAIL_LABEL'));
    $mail->addAddress($to);                               // Name is optional

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body = $body;
//    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
//        return false;
    } else {
        echo 'Message has been sent';
//        return true;
    }
}

//echo '34';
var_dump(send_mail1('zezont@gmail.com', 'نقل طالب', 'نرجوا نقل الطالب : '));
