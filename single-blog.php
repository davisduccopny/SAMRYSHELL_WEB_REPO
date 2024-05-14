<?php
require './admin-page/config/database.php';
require './admin-page/model/product_model.php';
require './controller/cart_controller.php';
require './admin-page/model/blog_model.php';
require './admin-page/model/comment_model.php';
require './admin-page/model/customer_model.php';
require 'admin-page/model/cagegoryproduct_model.php';
require 'admin-page/model/general_model.php';
$GeneralModel = new GeneralModel($conn);
$generalListshow = $GeneralModel->getGeneral(1);

$BlogModel = new BlogModel($conn);
$showBlog_recent = $BlogModel->showBlog_foruser(1, 3);
$categoryblog = $BlogModel->showCategory_blog();
$ListBlog = $BlogModel->showBlog_publicinfo();
$CategoryProductModelList = new CategoryProductModel($conn);
$listcategoryMenu = $CategoryProductModelList->showCategoryProducts();
if (isset($_GET['blog_id'])) :
    $blog_id_show = $_GET['blog_id'];

    $getBlog = $BlogModel->getBlog($blog_id_show);
    $categoryblog = $BlogModel->showCategory_blog();
    $CommentModel = new CommentModel($conn);
    $showcomment = $CommentModel->ShowComment($blog_id_show, 3);
    $showcomment_count = $CommentModel->ShowComment_count($blog_id_show);
    $customerModel = new CustomerModel($conn);
    if (isset($_SESSION['email_customer'])) {
        $getCTmer = $customerModel->getCustomer_email($_SESSION['email_customer']);
    }
    // LAY URL TRANG HIEN TAI

    $schema_URL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $host_URL = $_SERVER['HTTP_HOST'];
    $path_URL = $_SERVER['REQUEST_URI'];
    $current_url_PAGE = $schema_URL . $host_URL . $path_URL;
    $current_file_PAGE = 'bai-viet.html';

    // END LAY URL TRANG HIEN TAI
?>
    <!DOCTYPE html>
    <html class="no-js" lang="zxx">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $getBlog['title']; ?></title>
        <meta name="description" content="<?php echo $getBlog['description']; ?>">
        <meta name="keywords" content="<?php echo $getBlog['title']; ?>">
        <!-- Thẻ meta cho Facebook Open Graph -->
        <meta property="og:title" content="<?php echo $getBlog['title']; ?>">
        <meta property="og:description" content="<?php echo $getBlog['description']; ?>">
        <meta property="og:image" content="<?php echo $getBlog['image']; ?>">
        <meta property="og:url" content="<?php echo $current_url_PAGE; ?>">
        <meta property="og:type" content="article" />
        <!-- Thẻ meta cho Twitter Cards -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo $getBlog['title']; ?>">
        <meta name="twitter:description" content="<?php echo $getBlog['description']; ?>">
        <meta name="twitter:image" content="<?php echo $getBlog['image']; ?>">
        <link rel="canonical" href="<?php echo $current_url_PAGE; ?>">
       
        <!-- START SEO JSON -->
        <script type="application/ld+json">
            [{
                "@context": "https://schema.org",
                "@type": "BlogPosting",
                "headline": "<?php echo $getBlog['title']; ?>",
                "datePublished": "<?php echo $getBlog['date']; ?>",
                "dateModified": "<?php echo $getBlog['date']; ?>",
                "author": {
                    "@type": "Person",
                    "name": "samry",
                    "url": "https://samryvn.com/"
                },
                "image": "<?php echo _WEB_HOST . '/admin-page' . mb_substr($getBlog['image'], 2); ?>",
                "publisher": {
                    "@type": "Organization",
                    "name": "Công ty TNHH SẢN XUẤT THƯƠNG MẠI Samry",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "https://samryvn.com/assets/img/samryshell-logo.jpg"
                    }
                }
            }, {
                "@context": "https://schema.org",
                "@type": "ClaimReview",
                "url": "<?php echo $current_url_PAGE; ?>",
                "claimReviewed": "<?php echo $getBlog['title']; ?>",
                "itemReviewed": {
                    "@type": "Claim",
                    "author": {
                        "@type": "Organization",
                        "name": "Công ty TNHH SẢN XUẤT THƯƠNG MẠI Samry"
                    },
                    "datePublished": "<?php echo $getBlog['date']; ?>"
                },
                "author": {
                    "@type": "Organization",
                    "name": "Công ty TNHH SẢN XUẤT THƯƠNG MẠI Samry"
                },
                "reviewRating": {
                    "@type": "Rating",
                    "ratingValue": 5,
                    "bestRating": "5",
                    "worstRating": "1",
                    "alternateName": "False"
                }
            }]
        </script>
        <!-- END SEO JSON -->

        <?php require_once('main/head.php') ?>

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
                            <h1>Chi tiết Bài Viết</h1>
                            <ul class="breadcrumb">
                                <li><a href="trang-chu.html">TRANG CHỦ</a></li>
                                <li><a href="<?php echo $current_file_PAGE; ?>">TIN TỨC</a></li>
                                <li><a href="#" class="active"><?php echo $getBlog['title']; ?></a></li>
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
        </style>
        <!--== Page Content Wrapper Start ==-->
        <div id="page-content-wrapper" class="p-9">
            <div class="container">
                <div class="row">
                    <!-- Single Blog Page Content Start -->
                    <div class="col-lg-8">

                        <article class="single-blog-content-wrap">
                            <div class="post-header">
                                <figure class="post-thumbnail">
                                    <img src="<?php echo 'admin-page' . mb_substr($getBlog['image'], 2); ?>" class="img-fluid" alt="Blog" />
                                </figure>

                                <div class="post-meta">
                                    <h2><?php echo $getBlog['title']; ?></h2>
                                    <div class="post-info">
                                        <a href="#"><i class="fa fa-user"></i>Sam Ry</a>
                                        <a href="#"><i class="fa fa-calendar"></i><?php $created_at = date('m/d/Y', strtotime($getBlog['date']));
                                                                                    echo $created_at; ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="post-content">
                                <?php echo $getBlog['content'];  ?>
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
                        <!-- Comment Area Start -->
                        <div class="comment-area-wrapper">
                            <div class="comments-preview-area comment-single-item">
                                <h2 id="countcomment">Bình luận (<?php echo count($showcomment_count); ?>)</h2>
                                <input type="hidden" id="inputcountcomment" value="<?php echo count($showcomment_count); ?>">
                                <?php foreach ($showcomment as $show) : ?>
                                    <div class="single-comment d-block d-md-flex">
                                        <div class="comment-author">
                                            <a href="#"><img src="assets/img/user-comment_81638.png" class="img-fluid" alt="<?php echo $show['name']; ?>" /></a>
                                        </div>
                                        <div class="comment-info mt-3 mt-md-0">
                                            <div class="comment-info-top d-flex justify-content-between">
                                                <h3><?php echo $show['name']; ?></h3>
                                                <a href="#" class="btn-add-to-cart"><i class="fa fa-reply"></i>Phản hồi</a>
                                            </div>
                                            <a href="#" class="comment-date">
                                                <?php
                                                $created_at = $show['created_at'] ?? null;
                                                if (is_string($created_at)) {
                                                    $formattedDateTime = date("d F Y, h:i A", strtotime($created_at));
                                                    echo $formattedDateTime;
                                                } else {
                                                    echo 'No date available';
                                                }
                                                ?>

                                            </a>
                                            <p>
                                                <?php
                                                echo $show['content'];
                                                $lastCommentId = $show['id'];
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="single-input-item">
                                    <input type="hidden" id="Comment_id_last" value="<?php echo $lastCommentId; ?>">
                                    <input type="hidden" id="blog_id_inset_comment" value="<?php echo $blog_id_show; ?>">
                                    <button onclick="loadMoreComments(event)" class="btn-add-to-cart">Tải thêm</button>
                                </div>
                            </div>
                            <?php if (isset($_SESSION['email_customer']) && $_SESSION['email_customer'] !== '') : ?>
                                <div class="leave-comment-area comment-single-item">
                                    <h2>Để lại bình luận!</h2>
                                    <div class="comment-form-wrap">
                                        <form method="get">
                                            <div class="single-input-item">
                                                <textarea name="comment" id="content_comment" cols="30" rows="6" placeholder="Viết bình luận của bạn" required></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="single-input-item">
                                                        <input type="text" id="name_comment" placeholder="Name" required value="<?php echo $getCTmer['name']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="single-input-item">
                                                        <input type="hidden" id="email_comment" placeholder="Email Address" disabled value="<?php echo $_SESSION['email_customer']; ?>" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="single-input-item">
                                                <button onclick="AddBlog_comment(event)" class="btn-add-to-cart">Gửi bình luận</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="single-input-item">
                                    <a href="/login-register.php" class="btn-add-to-cart">Đăng nhập để bình luận!</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Single Blog Page Content End -->

                    <!-- Sidebar Area Start -->
                    <div class="col-lg-4 mt-5 mt-lg-0">
                        <div id="sidebar-area-wrap">
                            <!-- Single Sidebar Item Start -->
                            <div class="single-sidebar-wrap">
                                <h2 class="sidebar-title">TÌM KIẾM BÀI VIẾT</h2>
                                <div class="sidebar-body">
                                    <div class="sidebar-search">
                                        <form method="post" action="<?php echo $current_file_PAGE; ?>">
                                            <input type="search" name="content_blog_search" placeholder="Viết một từ khóa" />
                                            <button type="submit" name="submit_search_blog"><i class="fa fa-search"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Sidebar Item End -->

                            <!-- Single Sidebar Item Start -->
                            <div class="single-sidebar-wrap">
                                <h2 class="sidebar-title">BÀI VIẾT GẦN ĐÂY</h2>
                                <div class="sidebar-body">
                                    <div class="small-product-list recent-post">
                                        <?php foreach ($showBlog_recent as $showBlog_r) : ?>
                                            <div class="single-product-item">
                                                <figure class="product-thumb">
                                                    <a href="bai-viet/<?php echo $showBlog_r['id'] . '/' . $showBlog_r['slug'] . '.html'; ?>"><img class="img-fluid" src="<?php echo 'admin-page' . mb_substr($showBlog_r['image'], 2); ?>" alt="Products" /></a>
                                                </figure>
                                                <div class="product-details">
                                                    <h2><a href="bai-viet/<?php echo $showBlog_r['id'] . '/' . $showBlog_r['slug'] . '.html'; ?>"><?php echo $showBlog_r['title']; ?></a></h2>
                                                    <span class="price">
                                                        <?php
                                                        $date_show_single = $showBlog_r['date'] ?? null;
                                                        if (is_string($date_show_single)) {
                                                            $formatted_date_2 = date("d/F/Y", strtotime($date_show_single));
                                                            echo $formatted_date_2;
                                                        } else {
                                                            echo 'No date available';
                                                        }
                                                        ?>
                                                    </span>
                                                    <a href="bai-viet/<?php echo $showBlog_r['id'] . '/' . $showBlog_r['slug'] . '.html'; ?>" class="btn-add-to-cart">Read More <i class="fa fa-long-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Sidebar Item End -->

                            <!-- Single Sidebar Item Start -->
                            <div class="single-sidebar-wrap">
                                <h2 class="sidebar-title">DANH MỤC</h2>
                                <div class="sidebar-body">
                                    <ul class="sidebar-list">
                                        <?php foreach ($categoryblog as $categoryshow) : ?>
                                            <li><a href="<?php echo $current_file_PAGE; ?>?category_blog=<?php echo $categoryshow['id']; ?>"><?php echo $categoryshow['name']; ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- Single Sidebar Item End -->

                            <!-- Single Sidebar Item Start -->
                            <div class="single-sidebar-wrap">
                                <h2 class="sidebar-title">Tags nổi bật</h2>
                                <div class="sidebar-body">
                                    <ul class="tags">
                                        <li><a href="<?php echo $current_url_PAGE; ?>">Tahoma</a></li>
                                        <li><a href="<?php echo $current_url_PAGE; ?>">Trocas</a></li>
                                        <li><a href="<?php echo $current_url_PAGE; ?>">Sò điệp</a></li>
                                        <li><a href="<?php echo $current_url_PAGE; ?>">Vỏ ốc</a></li>
                                        <li><a href="<?php echo $current_url_PAGE; ?>">Nút áo</a></li>
                                        <li><a href="<?php echo $current_url_PAGE; ?>">Mop</a></li>
                                        <li><a href="<?php echo $current_url_PAGE; ?>">RiverShell</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Single Sidebar Item End -->
                        </div>
                    </div>
                    <!-- Sidebar Area End -->
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
    </body>

    </html>
<?php else : ?>
    <p>Post is not available</p>
<?php endif; ?>