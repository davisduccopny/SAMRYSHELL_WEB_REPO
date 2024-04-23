<?php
 require_once('admin-page/config/database.php');
 require_once('controller/cart_controller.php');
 require 'admin-page/model/blog_model.php';
 require 'admin-page/model/cagegoryproduct_model.php';
 require 'admin-page/model/general_model.php';
 $GeneralModel = new GeneralModel($conn);
 $generalListshow = $GeneralModel->getGeneral(1);
 $blogModel = new BlogModel($conn);
 $ListBlog = $blogModel->showBlog_publicinfo();
 $CategoryProductModelList = new CategoryProductModel($conn);
 $listcategoryMenu = $CategoryProductModelList->showCategoryProducts();
// Kiểm tra xem biến session 'email_customer' có tồn tại và có giá trị không
if (!isset($_SESSION['email_customer']) || empty($_SESSION['email_customer'])) {
    // Nếu không tồn tại hoặc không có giá trị, hiển thị thông báo và chuyển hướng
    echo "<script> alert('Bạn chưa đăng nhập!')</script>";
    header("refresh:1;url=login-register.php");
    exit(); // Kết thúc kịch bản để ngăn việc tiếp tục thực thi mã PHP
}
    require_once('admin-page/model/customer_model.php');
    require_once('admin-page/model/sale_model.php');
    $customerModel = new CustomerModel($conn);
    $SaleModel = new SaleModel($conn);
    $getCTM = $customerModel->getCustomer_email($_SESSION['email_customer']);
    $getSale = $SaleModel->getAllSales_byemail($_SESSION['email_customer']);

    if (isset($_POST['logout'])) {
        unset($_SESSION['email_customer']);
        header("location:login-register.php");
    }
?>
<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Samry - Your trusted partner in virtual button solutions. Explore our innovative seashell-based virtual buttons for a seamless user experience. Được thành lập từ năm 2010, Công ty TNHH Samry là một nhà sản xuất hàng đầu về nút ảo, sử dụng vỏ ốc biển làm nguyên liệu chính. Với sứ mệnh mang lại sự tiện lợi và độ tin cậy cho khách hàng, chúng tôi cam kết cung cấp sản phẩm chất lượng và dịch vụ tận tâm. Ghé thăm website samryvn.com để biết thêm thông tin chi tiết và các sản phẩm của chúng tôi.">


    <title>My account :: SamryShell - Company</title>

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
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="login-register.php" class="active">Dashboard</a></li>
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
            <div class="col-lg-12">
                <!-- My Account Page Start -->
                <div class="myaccount-page-wrapper">
                    <!-- My Account Tab Menu Start -->
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="myaccount-tab-menu nav" role="tablist">
                                <a href="#dashboad" class="active" data-toggle="tab"><i class="fa fa-dashboard"></i>
                                    Dashboard</a>

                                <a href="#orders" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders</a>

                                <a href="#download" data-toggle="tab"><i class="fa fa-cloud-download"></i> Download</a>

                                <a href="#payment-method" data-toggle="tab"><i class="fa fa-credit-card"></i> Payment
                                    Method</a>

                                <a href="#address" data-toggle="tab"><i class="fa fa-map-marker"></i> address</a>

                                <a href="#account-info" data-toggle="tab"><i class="fa fa-user"></i> Account Details</a>

                                <a onclick="logOut(event)"><i class="fa fa-sign-out"></i> Logout</a>
                            </div>
                        </div>
                        <!-- My Account Tab Menu End -->

                        <!-- My Account Tab Content Start -->
                        <div class="col-lg-9 mt-5 mt-lg-0">
                            <div class="tab-content" id="myaccountContent">
                                <!-- Single Tab Content Start -->
                                <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                    <div class="myaccount-content">
                                        <h3>Dashboard</h3>

                                        <div class="welcome">
                                            <p>Hello, <strong><?php echo $getCTM['name'] ?></strong> (If Not <strong><?php echo $getCTM['name'] ?> !</strong><a
                                                    href="login-register.php" class="logout"> Logout</a>)</p>
                                        </div>

                                        <p class="mb-0">From your account dashboard. you can easily check & view your
                                            recent orders, manage your shipping and billing addresses and edit your
                                            password and account details.</p>
                                    </div>
                                </div>
                                <!-- Single Tab Content End -->

                                <!-- Single Tab Content Start -->
                                <div class="tab-pane fade" id="orders" role="tabpanel">
                                    <div class="myaccount-content">
                                        <h3>Orders</h3>

                                        <div class="myaccount-table table-responsive text-center">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <?php if (count($getSale) > 0): ?>
                                                    <?php $count = 1; ?>
                                                    <?php foreach ($getSale as $getSale_show): ?>
                                                        <tr>
                                                            <td><?php echo $count++; ?></td>
                                                            <td>
                                                                <?php 
                                                                $datesale = date("d/F/Y", strtotime($getSale_show['created_at']));
                                                                echo $datesale;
                                                                ?>
                                                            </td>
                                                            <td><?php echo $getSale_show['status'] ?></td>
                                                            <td>$<?php echo $getSale_show['grand_total'] ?></td>
                                                            <td><a href="#" class="btn-add-to-cart">View</a></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else:?>
                                                    <tr>
                                                        <td colspan="5">No orders yet! Order now!</td>
                                                    </tr>
                                                <?php endif; ?>

                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Tab Content End -->

                                <!-- Single Tab Content Start -->
                                <div class="tab-pane fade" id="download" role="tabpanel">
                                    <div class="myaccount-content">
                                        <h3>Downloads</h3>

                                        <div class="myaccount-table table-responsive text-center">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Date</th>
                                                    <th>Expire</th>
                                                    <th>Download</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <tr>
                                                    <td>Haven - Free Real Estate PSD Template</td>
                                                    <td>Aug 22, 2018</td>
                                                    <td>Yes</td>
                                                    <td><a href="#" class="btn-add-to-cart">Download File</a></td>
                                                </tr>
                                                <tr>
                                                    <td>HasTech - Profolio Business Template</td>
                                                    <td>Sep 12, 2018</td>
                                                    <td>Never</td>
                                                    <td><a href="#" class="btn-add-to-cart">Download File</a></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Tab Content End -->

                                <!-- Single Tab Content Start -->
                                <div class="tab-pane fade" id="payment-method" role="tabpanel">
                                    <div class="myaccount-content">
                                        <h3>Payment Method</h3>

                                        <p class="saved-message">You Can't Saved Your Payment Method yet.</p>
                                    </div>
                                </div>
                                <!-- Single Tab Content End -->

                                <!-- Single Tab Content Start -->
                                <div class="tab-pane fade" id="address" role="tabpanel">
                                    <div class="myaccount-content">
                                        <h3>Billing Address</h3>

                                        <address>
                                            <p><strong><?php echo $getCTM['name'] ?></strong></p>
                                            <p><?php echo $getCTM['address'].'<br>'; 
                                                     echo  $getCTM['district'].'<br>';
                                                     echo  $getCTM['city'].'<br>';
                                                     echo  $getCTM['country'];?><br>
                                            </p>
                                            <p>Mobile: <?php echo $getCTM['phone']; ?> </p>
                                        </address>

                                        <a href="#account-info" class="btn-add-to-cart d-inline-block"><i class="fa fa-edit"></i>
                                            Edit Address</a>
                                    </div>
                                </div>
                                <!-- Single Tab Content End -->

                                <!-- Single Tab Content Start -->
                                <div class="tab-pane fade" id="account-info" role="tabpanel">
                                    <div class="myaccount-content">
                                        <h3>Account Details</h3>

                                        <div class="account-details-form">
                                            <form >
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="single-input-item">
                                                            <label for="fullname_update" class="required">Full name</label>
                                                            <input type="text" id="fullname_update"
                                                                   placeholder="First Name" value="<?php echo $getCTM['name']; ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 ">
                                                        <div class="single-input-item">
                                                            <label>Choose Country</label>
                                                            <select class="select countries" id="countryId" name="customercountries">
                                                                <option>Choose Country</option>
                                                                <option value="<?php echo $getCTM['country']; ?>"><?php echo $getCTM['country']; ?></option>
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 ">
                                                        <div class="single-input-item">
                                                            <label>State/City</label>
                                                            <select class="select states" id="stateId" name="customercity">
                                                                <option>Choose state/city</option>
                                                                <option value="<?php echo $getCTM['city']; ?>"><?php echo $getCTM['city']; ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="single-input-item">
                                                            <label>District</label>
                                                            <select class="select cities" id="cityId" name="customerdistrict">
                                                                <option>Choose District</option>
                                                            <option value="<?php echo $getCTM['district']; ?>"><?php echo $getCTM['district']; ?></option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="single-input-item">
                                                    <label for="Addess_id" class="required">Address</label>
                                                    <input type="text" id="Addess_id" placeholder="Address" value="<?php echo $getCTM['address']; ?>"/>
                                                </div>
                                                <div class="single-input-item">
                                                    <label for="ZipCode" class="required">ZipCode</label>
                                                    <input type="number" id="ZipCode_update" placeholder="ZipCode" value="<?php echo $getCTM['zipcode']; ?>"/>
                                                </div>
                                                

                                                <div class="single-input-item">
                                                    <label for="phone_update" class="required">Phone</label>
                                                    <input type="email" id="phone_update" placeholder="Phone" value="<?php echo $getCTM['phone']; ?>"/>
                                                </div>
                                                    <input type="hidden" name="email_login_update_info" id="email_login_update_info" value="<?php echo $_SESSION['email_customer'];?>">
                                                <fieldset>
                                                    <legend>Password change</legend>
                                                    <div class="single-input-item">
                                                        <label for="current-pwd" class="required">Current
                                                            Password</label>
                                                        <input type="password" id="current-pwd"
                                                               placeholder="Current Password"/>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="single-input-item">
                                                                <label for="new-pwd" class="required">New
                                                                    Password</label>
                                                                <input type="password" id="new-pwd"
                                                                       placeholder="New Password"/>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="single-input-item">
                                                                <label for="confirm-pwd" class="required">Confirm
                                                                    Password</label>
                                                                <input type="password" id="confirm-pwd"
                                                                       placeholder="Confirm Password"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                                <div class="single-input-item">
                                                    <button class="btn-login btn-add-to-cart" onclick=" UpdateInfoUser_customer(event)">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Tab Content End -->
                            </div>
                        </div>
                        <!-- My Account Tab Content End -->
                    </div>
                </div>
                <!-- My Account Page End -->
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
<script src="assets/js/templatecountry.js"></script>
</body>

</html>