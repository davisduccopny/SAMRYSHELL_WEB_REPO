<?php
    // START CHECK LOGIN
    session_start();
    if (isset($_SESSION['email_manager']) || !empty($_SESSION['email_manager'])) {
        echo "<script> alert('Bạn đã đăng nhập!')</script>";
        header("refresh:0.5;url=index.php");
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        header("location:signin.php");
    }
    if (isset($_SESSION['role_manager']) && $_SESSION['role_manager'] !== 'admin'){
        $role_show_element = 'hidden';
        $role_active_element = 'disabled';
    }
    else {
        $role_show_element = '';
    }
    // END CHECK LOGIN


?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Login - Pos admin template</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="account-page">

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <form method="post" enctype="multipart/form-data" class="login-userset ">
                        <div class="login-logo">
                            <img src="../../assets/img/samryshell-logo.jpg" alt="img">
                        </div>
                        <div class="login-userheading">
                            <h3>Quên mật khẩu?</h3>
                            <h4>Đừng lo lắng! Hãy nhập địa chỉ email đã đăng ký<br>
                                liên kết với tài khoản của bạn.</h4>
                        </div>
                        <div class="form-login">
                            <label>Email</label>
                            <div class="form-addons">
                                <input type="text" placeholder="Enter your email address" name="email_forgot_access" >
                                <img src="assets/img/icons/mail.svg" alt="img">
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" name="access_sendmail" class="btn btn-login">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="login-img">
                    <img src="assets/img/login.jpg" alt="img">
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>
<?php
   require('../../phpspreadsheet/vendor/autoload.php');
   require_once('../view/assets/plugins/PHPMailer/src/Exception.php');
   require_once('../view/assets/plugins/PHPMailer/src/PHPMailer.php');
   require_once('../view/assets/plugins/PHPMailer/src/SMTP.php');
   require_once('../config/database.php');
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\Exception;
   if ($_SERVER["REQUEST_METHOD"] == "POST"){
       if (isset($_POST['access_sendmail'])){
           require '../model/email_model.php';
           $emailmodel = new EmailModal($conn);
           $getinfo_email = $emailmodel->get_email_settings();
           $toName = 'Khách hàng';
           $_SESSION['token_check'] = bin2hex(random_bytes(32));
           $_SESSION['email_check'] = $_POST['email_forgot_access'];
           $filepath = realpath(__DIR__.'/../../admin-page/view/assets/template/email_forgot/index.php');
           $content = $emailmodel->executeAndGetHTML($filepath);

           function sendmail($host, $port, $username, $password, $subject, $content, $fromEmail, $fromName, $toEmail, $toName) {
               $mail = new PHPMailer(true);
           
               try {
                   //Server settings
                   $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
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
                  echo "<script> alert('loiloi!')</script>";
               }
           }

           $email_forgot = $_POST['email_forgot_access'];
         $sendmail =  sendmail($getinfo_email['host'], $getinfo_email['port'],
            $getinfo_email['addressserver'], $getinfo_email['password'],$getinfo_email['subject_forgot'],$content, $getinfo_email['addressserver'],
            $getinfo_email['business_name'],$email_forgot,$toName);
           if ($sendmail){
               echo "<script> alert('Mail đã được gửi thành công!')</script>";
               header('refresh:0;url=signin.php');
           }
           else {
               echo "<script> alert('Địa chỉ mail không chính xác! Vui lòng nhập lại!')</script>";
               header('refresh:0;url=forgetpassword.php');
           }
       }
   }

?>