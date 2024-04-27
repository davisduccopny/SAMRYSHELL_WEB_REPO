<?php
require_once('../view/assets/plugins/PHPMailer/src/Exception.php');
require_once('../view/assets/plugins/PHPMailer/src/PHPMailer.php');
require_once('../view/assets/plugins/PHPMailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../model/email_model.php';
$emailmodel = new EmailModal($conn);
$getinfo_email = $emailmodel->get_email_settings();
$toName = 'Khách hàng';
// $filepath = realpath(__DIR__.'/../../admin-page/view/assets/template/email_forgot/index.php');
$content = $_SESSION['email_customer'].' vừa tạo một liên hệ mới! <br>'.
            '<a href="'._WEB_HOST.'admin-page/view/'.'" style="color:red;">Xem chi tiết</a>';

function sendmail($host, $port, $username, $password, $subject, $content, $fromEmail, $fromName, $toEmail, $toName)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $host;                                  //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $username;                              //SMTP username
        $mail->Password   = $password;                              //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
        $mail->Port       = $port;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);                      //Add a recipient

        //Content
        $mail->isHTML(true);                                       //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

$email_forgot = 'hxqduccopny@gmail.com';
$sendmail =  sendmail(
    $getinfo_email['host'],
    $getinfo_email['port'],
    $getinfo_email['addressserver'],
    $getinfo_email['password'],
    $getinfo_email['subject_forgot'],
    $content,
    $getinfo_email['addressserver'],
    $getinfo_email['business_name'],
    $email_forgot,
    $toName
);
