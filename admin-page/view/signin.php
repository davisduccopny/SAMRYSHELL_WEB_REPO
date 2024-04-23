<!DOCTYPE html>
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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="account-page">

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <form method="post" onsubmit="LoginManager(event)" enctype="multipart/form-data" class="login-userset">
                        <div class="login-logo">
                            <img src="../../assets/img/samryshell-logo.jpg" alt="img">
                        </div>
                        <div class="login-userheading">
                            <h3>Đăng nhập</h3>
                            <h4>Hãy đăng nhập vào tài khoản của bạn</h4>
                        </div>
                        <div class="form-login">
                            <label>Email</label>
                            <div class="form-addons">
                                <input type="text" id="EmailLoginManager" placeholder="Nhập email" required>
                                <img src="assets/img/icons/mail.svg" alt="img">
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Mật khẩu</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input" id="passloginManager" placeholder="Nhập mật khẩu" required>
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="form-login">
                            <div class="alreadyuser">
                                <h4><a href="forgetpassword.php" class="hover-a">Quên mật khẩu?</a></h4>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" name="signin_manager" class="btn btn-login">Đăng nhập</button>
                        </div>
                        <div class="signinform text-center">
                            <h4>Bạn có tài khoản chưa? <a href="./forgetpassword.php" class="hover-a">Đăng ký</a></h4>
                        </div>
                        <div class="form-setlogin">
                            <h4>Hoặc đăng nhập với</h4>
                        </div>
                        <div class="form-sociallink">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="assets/img/icons/google.png" class="me-2" alt="google">
                                        Google
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="assets/img/icons/facebook.png" class="me-2" alt="google">
                                        Facebook
                                    </a>
                                </li>
                            </ul>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        
    <script src="assets/js/script.js"></script>
    <script>
        function  onpendore() {
            
        }
        function LoginManager(event){
            var EmailLoginManager = $('#EmailLoginManager').val();
            var passloginManager = $('#passloginManager').val();
            event.preventDefault();
            $.ajax({
                url: '../controller/signin_controller.php',
                type: 'POST',
                data: {
                    email: EmailLoginManager,
                    password: passloginManager,
                    login_manager: true
                },
                success: function(response){
                    console.log(response);
                    if(response == 'success'){
                        toastr.success('Đăng nhập thành công!', 'Thành công', {
                        timeOut: 1500, 
                        progressBar: true, 
                        positionClass: 'toast-top-right'
                    });
                        setTimeout(() => {
                            window.location.href = 'index.php';
                        }, 1500);
                    }
                    else{
                        toastr.error('Lỗi trong quá trình đăng nhập!', 'Lỗi', {
                            timeOut: 3000, 
                            progressBar: true, 
                            positionClass: 'toast-top-right'
                        });
                        return;
                    }
                }
            });
            
        }

            
       
    </script>
</body>

</html>