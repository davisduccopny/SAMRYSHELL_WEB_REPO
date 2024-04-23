<?php
     require 'admin-page/config/database.php';
     require 'admin-page/model/usercustomer_model.php';
     require 'admin-page/model/customer_model.php';
     require 'admin-page/model/general_model.php';
     $GeneralModel = new GeneralModel($conn);
     $generalListshow = $GeneralModel->getGeneral(1);
     $usercustomermodel = new UserCustomerModel($conn);
     $customerModel = new CustomerModel($conn);
     require 'admin-page/model/blog_model.php';
     require './controller/cart_controller.php';
     require 'admin-page/model/cagegoryproduct_model.php';
     $blogModel = new BlogModel($conn);
     $CategoryProductModelList = new CategoryProductModel($conn);
     $ListBlog = $blogModel->showBlog_publicinfo();
     $listcategoryMenu = $CategoryProductModelList->showCategoryProducts();
    if (isset($_SESSION['email_customer']) &&  $_SESSION['email_customer']) {
        echo "<script> alert('Bạn đã đăng nhập!')</script>";
        header("refresh:1.5;url=index.php");
    }


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['register_customer_user'])) {
            $fullName = $_POST['fullname'];
            $lastSpacePosition = strrpos($fullName, " ");
            $firstName = substr($fullName, 0, $lastSpacePosition);
            $lastName = substr($fullName, $lastSpacePosition + 1);

            $emailcustomeruser = $_POST['emailcustomeruser'];
            $passworduser = $_POST['passworduser'];
            $repassworduser = $_POST['repassworduser'];
            if ($passworduser ===$repassworduser ) {
            $statususer = 1 ;
            // CHECK EMAIL 
            $checkcustomer = $customerModel->listCustomers();
            $checkarraymail = [];
            foreach ($checkcustomer as $check) {
                $checkarraymail[] = $check['email'];
            }
            
            function checkEmail($email, $checkemail) {
                $found = false;
            
                for ($i = 0; $i < count($email); $i++) {
                    if ($email[$i] === $checkemail) {

                        $found = true;
                        break;
                    }
                }
        
                return $found;
            }
              // Check trùng email
            $result = checkEmail($checkarraymail, $emailcustomeruser);
            if ($result){
                echo "<script>alert('Email đã tồn tại!');</script>";
            }
            else {
            $customerphone=$customerimage=$customerimage_tmp=$customertype=$customercountries=$customercity=$customerdistrict=$customeraddress=$customerzipcode=$customerdescription = '';
            $addcustomer =   $customerModel->insertCustomer($fullName, $customerphone, $customerimage,$customerimage_tmp, $emailcustomeruser, $customertype, $customercountries, $customercity, $customerdistrict, $customeraddress, $customerzipcode, $customerdescription);
            $userinsert = $usercustomermodel-> insertUserCustomer($firstName, $emailcustomeruser, $lastName, $passworduser, $statususer) ;
            if ($userinsert && $addcustomer) {
                $_SESSION['email_customer'] = $emailcustomeruser;
                echo "<script>alert('Tạo tài khoản thành công');</script>";
                header("refresh:1.5;url=index.php");
            } else {
                
                echo "<script>alert('Thêm thất bại.');</script>";
            }
        }
           
        }
        else {
            echo "<script>alert('Password không trùng nhau.');</script>";
        }
    }
    }
?>
<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Samry - Your trusted partner in virtual button solutions. Explore our innovative seashell-based virtual buttons for a seamless user experience. Được thành lập từ năm 2010, Công ty TNHH Samry là một nhà sản xuất hàng đầu về nút ảo, sử dụng vỏ ốc biển làm nguyên liệu chính. Với sứ mệnh mang lại sự tiện lợi và độ tin cậy cho khách hàng, chúng tôi cam kết cung cấp sản phẩm chất lượng và dịch vụ tận tâm. Ghé thăm website samryvn.com để biết thêm thông tin chi tiết và các sản phẩm của chúng tôi.">


    <title>Login || ShamryShell - Company</title>

    <?php require_once('main/head.php'); ?>

            <!-- START SEO JSON -->
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Organization",
                "name": "Công ty TNHH sản xuất thương mại samry",
                "url": "https://samryvn.com",
                "logo": "https://samryvn.com/assets/img/samryshell-logo.jpg",
                "contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "+84936-095-515",
                    "contactType": "customer service"
                }
            }
        </script>
        <!-- END SEO JSON -->
</head>
<body>
<!--== Header Area Start ==-->
<?php require_once('main/header.php'); ?>
<!--== Header Area End ==-->

<!--== Search Box Area Start ==-->
<?php require_once('main/search_box.php'); ?>
<!--== Search Box Area End ==-->

<!--== Page Title Area Start ==-->
<div id="page-title-area">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="page-title-content">
                    <h1>Member Area</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="login-register.php" class="active">Login & Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== Page Title Area End ==-->

<!--== Page Content Wrapper Start ==-->
<div id="page-content-wrapper" class="p-9">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 m-auto">
                <!-- Login & Register Content Start -->
                <div class="login-register-wrapper">
                    <!-- Login & Register tab Menu -->
                    <nav class="nav login-reg-tab-menu">
                        <a class="active" id="login-tab" data-toggle="tab" href="#login">Login</a>
                        <a id="register-tab" data-toggle="tab" href="#register">Register</a>
                    </nav>
                    <!-- Login & Register tab Menu -->

                    <div class="tab-content" id="login-reg-tabcontent">
                        <div class="tab-pane fade show active" id="login" role="tabpanel">
                            <div class="login-reg-form-wrap">
                                <form enctype="multipart/form-data" method="post" onsubmit="LoginCustomer(event)">
                                    <div class="single-input-item">
                                        <input type="email" name="email" id="email_login" placeholder="Enter your Email" required/>
                                    </div>

                                    <div class="single-input-item">
                                        <input type="password" name="password" id="pass_login" placeholder="Enter your Password" required/>
                                    </div>

                                    <div class="single-input-item">
                                        <div class="login-reg-form-meta d-flex align-items-center justify-content-between">
                                            <div class="remember-meta">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="rememberMe">
                                                    <label class="custom-control-label" for="rememberMe">Remember
                                                        Me</label>
                                                </div>
                                            </div>

                                            <a href="#" class="forget-pwd">Forget Password?</a>
                                        </div>
                                    </div>

                                    <div class="single-input-item">
                                        <button class="btn-login" type="submit" name="submit_login_customer">Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="register" role="tabpanel">
                            <div class="login-reg-form-wrap">
                                <form enctype="multipart/form-data" method="post">
                                    <div class="single-input-item">
                                        <input type="text" name="fullname" placeholder="Full Name" required/>
                                    </div>

                                    <div class="single-input-item">
                                        <input type="email" name="emailcustomeruser" placeholder="Enter your Email" required/>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="single-input-item">
                                                <input type="password" name="passworduser" placeholder="Enter your Password" required/>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="single-input-item">
                                                <input type="password" name="repassworduser" placeholder="Repeat your Password" required/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-input-item">
                                        <div class="login-reg-form-meta">
                                            <div class="remember-meta">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           id="subnewsletter">
                                                    <label class="custom-control-label" for="subnewsletter">Subscribe
                                                        Our Newsletter</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-input-item">
                                        <button class="btn-login" name="register_customer_user" type="submit">Register</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Login & Register Content End -->
            </div>
        </div>
    </div>
</div>
<!--== Page Content Wrapper End ==-->

<!-- Footer Area Start -->
<?php require_once('main/footer.php'); ?>
<!-- Footer Area End -->

<!-- Scroll to Top Start -->
<a href="#" class="scrolltotop"><i class="fa fa-angle-up"></i></a>
<!-- Scroll to Top End -->


<!--=======================Javascript============================-->
<?php require_once('main/src_js.php'); ?>
<script src="assets/js/main.js"></script>
</body>

</html>