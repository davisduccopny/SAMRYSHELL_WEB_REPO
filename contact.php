<?php
require('admin-page/config/database.php');
require('admin-page/model/general_model.php');
require('controller/cart_controller.php');
require('admin-page/model/blog_model.php');
require('admin-page/model/cagegoryproduct_model.php');

$generalModel = new GeneralModel($conn);
$showGenneral = $generalModel->getGeneral(1);
$generalListshow = $generalModel->getGeneral(1);
$categoryproductModel = new CategoryProductModel($conn);
$listcategoryMenu = $categoryproductModel->showCategoryProducts();
$blogModel = new BlogModel($conn);
$ListBlog = $blogModel->showBlog_publicinfo();


// LAY URL TRANG HIEN TAI

$schema_URL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$host_URL = $_SERVER['HTTP_HOST'];
$path_URL = $_SERVER['REQUEST_URI'];
$current_url_PAGE = $schema_URL . $host_URL . $path_URL;

// END LAY URL TRANG HIEN TAI
?>
<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liên hệ || Công ty TNHH SX-TM Samry</title>
    <meta name="description" content="Liên hệ Samryvn || Liên hệ hotline công ty 0936.095.515, email: support@samryvn.com, Cập nhật những thông tin mới nhất liên quan đến doanh nghiệp">
    <!-- Thẻ meta cho Facebook Open Graph -->
    <meta property="og:title" content="Liên hệ || Công ty TNHH SX-TM Samry">
    <meta property="og:description" content="Liên hệ hotline công ty 0936.095.515, email: support@samryvn.com, Cập nhật những thông tin mới nhất liên quan đến doanh nghiệp">
    <meta property="og:image" content="./assets/img/samryshell-logo.jpg">
    <meta property="og:url" content="<?php echo $current_url_PAGE; ?>">
    <meta property="og:type" content="website">
    <!-- Thẻ meta cho Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Liên hệ || Công ty TNHH SX-TM Samry">
    <meta name="twitter:description" content="Liên hệ hotline công ty 0936.095.515, email: support@samryvn.com, Cập nhật những thông tin mới nhất liên quan đến doanh nghiệp">
    <meta name="twitter:image" content="./assets/img/samryshell-logo.jpg">
    <link rel="canonical" href="<?php echo $current_url_PAGE; ?>">
    <link rel="amphtml" href="<?php echo $current_url_PAGE; ?>" />
        <!-- START SEO JSON -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Công ty TNHH sản xuất thương mại samry",
            "url": "https://samryvn.com/lien-he.html",
            "logo": "https://samryvn.com/assets/img/samryshell-logo.jpg",
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+84936-095-515",
                "contactType": "customer service"
            }
        }
    </script>
    <!-- END SEO JSON -->
    <?php require_once('main/head.php'); ?>


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
                        <h1>Liên hệ</h1>
                        <ul class="breadcrumb">
                            <li><a href="trang-chu.html">Trang chủ</a></li>
                            <li><a href="<?php echo $current_url_PAGE; ?>" class="active">liên hệ</a></li>
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
                <!-- Contact Page Content Start -->
                <div class="col-lg-12">
                    <!-- Contact Method Start -->
                    <div class="contact-method-wrap">
                        <div class="row">
                            <!-- Single Method Start -->
                            <div class="col-lg-3 col-sm-6 text-center">
                                <div class="contact-method-item">
                                    <span class="method-icon"><i class="fa fa-map-marker"></i></span>
                                    <div class="method-info">
                                        <h3>ĐỊA CHỈ</h3>
                                        <p><?php echo $showGenneral['address'] ?> <br> My Tho City, Tien Giang, Viet Nam</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Method End -->

                            <!-- Single Method Start -->
                            <div class="col-lg-3 col-sm-6 text-center">
                                <div class="contact-method-item">
                                    <span class="method-icon"><i class="fa fa-phone"></i></span>
                                    <div class="method-info">
                                        <h3>SỐ ĐIỆN THOẠI</h3>
                                        <a href="tel:<?php echo $showGenneral['phone'] ?>"><?php echo $showGenneral['phone'] ?></a>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Method End -->

                            <!-- Single Method Start -->
                            <div class="col-lg-3 col-sm-6 text-center">
                                <div class="contact-method-item">
                                    <span class="method-icon"><i class="fa fa-envelope-open-o"></i></span>
                                    <div class="method-info">
                                        <h3>FAX</h3>
                                        <p><?php echo $showGenneral['fax'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Method End -->

                            <!-- Single Method Start -->
                            <div class="col-lg-3 col-sm-6 text-center">
                                <div class="contact-method-item">
                                    <span class="method-icon"><i class="fa fa-envelope"></i></span>
                                    <div class="method-info">
                                        <h3>ĐỊA CHỈ EMAIL</h3>
                                        <a href="mailto:<?php echo $showGenneral['email'] ?>"><?php echo $showGenneral['email'] ?></a>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Method End -->
                        </div>
                    </div>
                    <!-- Contact Method End -->
                </div>
                <!-- Contact Page Content End -->
            </div>

            <div class="row">
                <!-- Contact Form Start -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125589.07050336173!2d106.18937453341675!3d10.369170147662675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x310aafc699a9be91%3A0x59df68ead5c2e7a7!2zVGjDoG5oIHBo4buRIE3hu7kgVGhvLCBUaeG7gW4gR2lhbmcsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1712846757688!5m2!1svi!2s" width="1140" height="450" style="border-radius:5%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                <div class="col-lg-9 m-auto">

                    <!-- <div class="contact-form-wrap">
                    <h2>Request a Quote</h2>

                    <form id="contact-form" action="https://d29u17ylf1ylz9.cloudfront.net/ruby-v2/ruby/assets/php/mail.php" method="post">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <input type="text" name="first_name" placeholder="First Name *" required/>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <input type="text" name="last_name" placeholder="Last Name *" required/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <input type="email" name="email_address" placeholder="Email Address *" required/>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <input type="text" name="contact_subject" placeholder="Subject *" required/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="single-input-item">
                                    <textarea name="message" id="message" cols="30" rows="6"
                                              placeholder="Message"></textarea>
                                </div>

                                <div class="single-input-item text-center">
                                    <button type="submit" name="submit" class="btn-add-to-cart">Send Meassage</button>
                                </div>

                               
                                <div class="form-messege"></div>
                            </div>
                        </div>
                    </form>
                </div> -->
                </div>
                <!-- Contact Form End -->
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