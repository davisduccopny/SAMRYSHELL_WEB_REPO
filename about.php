<?php
require 'admin-page/config/database.php';
require 'admin-page/model/blog_model.php';
require 'controller/cart_controller.php';
require 'admin-page/model/cagegoryproduct_model.php';
require 'admin-page/model/general_model.php';
$BlogModel = new BlogModel($conn);
$ListBlog = $BlogModel->showBlog_publicinfo();
$CategoryProductModelList = new CategoryProductModel($conn);
$listcategoryMenu = $CategoryProductModelList->showCategoryProducts();
$GeneralModel = new GeneralModel($conn);
$generalListshow = $GeneralModel->getGeneral(1);
if (isset($_GET['blog_id'])) :

    $showBlog = $BlogModel->getBlog($_GET['blog_id']);
?>
    <!DOCTYPE html>
    <html class="no-js" lang="zxx">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Được thành lập từ năm 2010, Công ty TNHH Samry là một nhà sản xuất hàng đầu về nút ảo, sử dụng vỏ ốc biển làm nguyên liệu chính. Với sứ mệnh mang lại sự tiện lợi và độ tin cậy cho khách hàng, chúng tôi cam kết cung cấp sản phẩm chất lượng và dịch vụ tận tâm. Ghé thăm website samryvn.com để biết thêm thông tin chi tiết và các sản phẩm của chúng tôi.">
        <!-- Thẻ meta cho Facebook Open Graph -->
        <meta property="og:title" content="<?php echo  $showBlog['title']; ?>">
        <meta property="og:description" content="<?php echo  $showBlog['description']; ?>">
        <meta property="og:image" content="./assets/img/samryshell-logo.jpg">
        <meta property="og:url" content="<?php
                                            $schema = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
                                            $host = $_SERVER['HTTP_HOST'];
                                            $path = $_SERVER['REQUEST_URI'];
                                            $current_url = $schema . $host . $path;
                                            echo $current_url;
                                            ?>
">
        <meta property="og:type" content="website">
        <!-- Thẻ meta cho Twitter Cards -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo  $showBlog['title']; ?>">
        <meta name="twitter:description" content="<?php echo  $showBlog['description']; ?>">
        <meta name="twitter:image" content="./assets/img/samryshell-logo.jpg">
        <link rel="canonical" href="<?php
                                            $schema = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
                                            $host = $_SERVER['HTTP_HOST'];
                                            $path = $_SERVER['REQUEST_URI'];
                                            $current_url = $schema . $host . $path;
                                            echo $current_url;
                                            ?>">
        <title><?php echo  $showBlog['title']; ?> || Công ty TNHH SX-TM SamRy</title>
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
                            <h1><?php $showBlog['title']; ?></h1>
                            <ul class="breadcrumb">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="blog.php">Blog</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--== Page Title Area End ==-->
        <style>
            .post-content p span,
            .post-content h1 span,
            .post-content h2 span,
            .post-content h3 span,
            .post-content h4 span,
            .post-content h5 span,
            .post-content h6 span,
            .post-content blockquote {
                font-family: "TimesNewRoman" !important;
                font-size: 18px !important;
            }

            .post-content p,
            .post-content h1,
            .post-content h2,
            .post-content h3,
            .post-content h4,
            .post-content h5,
            .post-content h6,
            .post-content blockquote {
                font-family: "TimesNewRoman" !important;
                font-size: 18px !important;
            }

            .post-content p,
            .post-content blockquote,
            .post-content img {
                margin-bottom: 25px;
                width: 1140px !important;
            }

            .single-blog-content-wrap {
                width: 1140px !important;
            }

            .post-meta {
                width: 1140px !important;
            }

            @media (max-width: 767px) {

                .post-content p,
                .post-content blockquote,
                .post-content img {
                    width: 100% !important;
                    /* Sử dụng 100% chiều rộng cho thiết bị di động */
                }

                .post-meta {
                    width: 100% !important;
                }

                .single-blog-content-wrap {
                    width: 100% !important;
                }
            }

            /* Quy tắc cho các thiết bị có độ rộng màn hình từ 768px đến 1024px (máy tính bảng) */
            @media (min-width: 768px) and (max-width: 1024px) {

                .post-content p,
                .post-content blockquote,
                .post-content img {
                    width: 100% !important;
                    /* Sử dụng 100% chiều rộng cho máy tính bảng */
                }

                .post-meta {
                    width: 100% !important;
                }

                .single-blog-content-wrap {
                    width: 100% !important;
                }
            }
        </style>
        <!--== Page Content Wrapper Start ==-->
        <div id="page-content-wrapper" class="p-9">
            <div class="container">
                <div class="row">
                    <!-- Single Blog Page Content Start -->
                    <div class="col-lg-8">
                        <article class="single-blog-content-wrap">
                            <div class="post-header">
                                <!-- <figure class="post-thumbnail">
                                <img src="<?php echo 'admin-page' . mb_substr($showBlog['image'], 2); ?>" class="img-fluid" alt="Blog"/>
                                </figure> -->
                                <div class="post-meta text-center">
                                    <h2><?php echo $showBlog['title']; ?></h2>
                                    <div class="post-info">
                                        <a href="#"><i class="fa fa-user"></i> ShamryShell</a>
                                        <a href="#"><i class="fa fa-calendar"></i><?php $created_at = date('m/d/Y', strtotime($showBlog['date']));
                                                                                    echo $created_at; ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="post-content">
                                <?php echo $showBlog['content'];  ?>
                            </div>

                            <div class="post-footer d-block d-sm-flex justify-content-sm-between align-items-center">
                                <ul class="tags">
                                    <li><a href="#">Fashion</a></li>
                                    <li><a href="#">Sale</a></li>
                                    <li><a href="#">Investment</a></li>
                                </ul>

                                <div class="post-share mt-3 mt-sm-0">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-reddit"></i></a>
                                    <a href="#"><i class="fa fa-digg"></i></a>
                                </div>
                            </div>
                        </article>
                    </div>
                    <!-- Single Blog Page Content End -->
                </div>
            </div>
        </div>
        <!--== Page Content Wrapper End ==-->

        <!-- Footer Area Start -->
        <?php require_once('main/footer.php') ?>
        <!-- Footer Area End -->

        <!-- Scroll to Top Start -->
        <a href="#" class="scrolltotop"><i class="fa fa-angle-up"></i></a>
        <!-- Scroll to Top End -->


        <!--=======================Javascript============================-->
        <?php require_once('main/src_js.php'); ?>
    </body>

    </html>
<?php else : ?>
    <p>Không tìm thấy bài viết</p>
    <?php header('location:index.php'); ?>
<?php endif; ?>