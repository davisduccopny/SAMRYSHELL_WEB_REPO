<?php
require 'admin-page/config/database.php';
require 'admin-page/model/blog_model.php';
require './controller/cart_controller.php';
require 'admin-page/model/cagegoryproduct_model.php';
require 'admin-page/model/general_model.php';

$GeneralModel = new GeneralModel($conn);
$generalListshow = $GeneralModel->getGeneral(1);
$blogModel = new BlogModel($conn);
$categoryblog = $blogModel->showCategory_blog();
$ListBlog = $blogModel->showBlog_publicinfo();
$CategoryProductModelList = new CategoryProductModel($conn);
$listcategoryMenu = $CategoryProductModelList->showCategoryProducts();
// XÁC ĐỊNH SỐ TRANG


$perPage = 2;
$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
}
$countItem = num_rowsCount_item("SELECT * FROM blog WHERE type !=1", $conn);
$totalPages = ceil($countItem / $perPage);

$page = max(1, min($page, $totalPages));
$start = ($page - 1) * $perPage;
$showBlog = $blogModel->showBlog_foruser($start, $perPage);
$showBlog_recent = $blogModel->showBlog_foruser(1, 3);
if (isset($_GET['category_blog'])) {
    $category_for_id = $_GET['category_blog'];
    $countItem = num_rowsCount_item("SELECT * FROM blog WHERE type !=1 AND category_id=$category_for_id", $conn);
    $totalPages = ceil($countItem / $perPage);

    $page = max(1, min($page, $totalPages));
    $start = ($page - 1) * $perPage;
    $showBlog = $blogModel->showBlog_filter($category_for_id, $start, $perPage);
}


// END XÁC ĐỊNH SỐ TRANG
// SEARCH
if (isset($_POST['submit_search_blog'])) {
    $showBlogsearch = $_POST['content_blog_search'];
    $showBlog = $blogModel->showBlog_forsearch($showBlogsearch);
}

// END SEARCH

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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bài viết - chia sẻ kiến thức || Công ty TNHH SX-TM Samry</title>
    <meta name="description" content="Trang bài viết, tin tức, giới thiệu những tin tức mới nhất về công ty sản xuất- thương mại Samry">
    <!-- Thẻ meta cho Facebook Open Graph -->
    <meta property="og:title" content="Bài viết - chia sẻ kiến thức || Công ty TNHH SX-TM Samry">
    <meta property="og:description" content="Trang bài viết, tin tức, giới thiệu những tin tức mới nhất về công ty sản xuất- thương mại Samry">
    <meta property="og:image" content="./assets/img/samryshell-logo.jpg">
    <meta property="og:url" content="<?php echo $current_url_PAGE; ?>">
    <meta property="og:type" content="website">
    <!-- Thẻ meta cho Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Bài viết - chia sẻ kiến thức || Công ty TNHH SX-TM Samry">
    <meta name="twitter:description" content="Trang bài viết, tin tức, giới thiệu những tin tức mới nhất về công ty sản xuất- thương mại Samry">
    <meta name="twitter:image" content="./assets/img/samryshell-logo.jpg">
    
    <link rel="canonical" href="<?php echo $current_url_PAGE; ?>">
  
    <!-- START SEO JSON -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Công ty TNHH sản xuất thương mại samry",
            "url": "https://samryvn.com/bai-viet.html",
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
                        <h1>Blog</h1>
                        <ul class="breadcrumb">
                            <li><a href="trang-chu.html">TRANG CHỦ</a></li>
                            <li><a href="<?php echo $current_file_PAGE; ?>" class="active">Blog</a></li>
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
                <!-- Blog Page Content Start -->
                <div class="col-lg-8">
                    <div class="blog-content-wrap">
                        <!-- Single Blog Item Start -->
                        <?php if (count($showBlog) > 0) : ?>
                            <?php foreach ($showBlog as $Blogitem) : ?>
                                <div class="single-blog-wrap">
                                    <figure class="blog-thumb">
                                        <a href="bai-viet/<?php echo $Blogitem['id'] ?>/<?php echo $Blogitem['slug'] . '.html' ?>"><img src="<?php echo 'admin-page' . mb_substr($Blogitem['image'], 2); ?>" alt="blog" class="img-fluid" /></a>
                                        <figcaption class="blog-icon">
                                            <a href="bai-viet/<?php echo $Blogitem['id'] ?>/<?php echo $Blogitem['slug'] . '.html' ?>"><i class="fa fa-file-image-o"></i></a>
                                        </figcaption>
                                    </figure>

                                    <div class="blog-details" style="text-transform:capitalize;">
                                        <h3><a href="bai-viet/<?php echo $Blogitem['id'] ?>/<?php echo $Blogitem['slug'] . '.html' ?>" style="text-transform:uppercase;"><?php echo $Blogitem['title'];  ?></a></h3>
                                        <span class="post-date">
                                            <?php
                                            $date_showblog_1 = $Blogitem['date'] ?? null;

                                            if (is_string($date_showblog_1)) {
                                                $formatted_date = date("d/F/Y", strtotime($date_showblog_1));
                                                echo $formatted_date;
                                            } else {
                                                echo "Date is not a valid string";
                                            }
                                            ?>
                                        </span>
                                        <p><?php echo $Blogitem['description'];  ?></p>
                                        <a href="bai-viet/<?php echo $Blogitem['id'] ?>/<?php echo $Blogitem['slug'] . '.html' ?>" class="btn-long-arrow">Đọc thêm</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Không có bài viết</p>
                        <?php endif; ?>
                        <!-- Single Blog Item End -->
                    </div>

                    <!--  Pagination Area Start -->
                    <div class="page-pagination mt-5 pt-5">
                        <?php if (!empty($totalPages) && $totalPages > 1) : ?>
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1) : ?>
                                    <li><a href="<?php echo $current_file_PAGE; ?>?page=<?php echo ($page - 1); ?>" aria-label="Previous">«</a></li>
                                <?php endif; ?>

                                <?php
                                $numAdjacentPages = 2; // Số trang cố định xung quanh trang hiện tại
                                $startPage = max(1, $page - $numAdjacentPages);
                                $endPage = min($totalPages, $page + $numAdjacentPages);

                                if ($startPage > 1) {
                                    echo '<li><a href="'.$current_file_PAGE.'?page=1">1</a></li>';
                                    if ($startPage > 2) {
                                        echo '<li><span>...</span></li>';
                                    }
                                }

                                for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                    <?php if ($i == $page) : ?>
                                        <li><a class="current" href="#"><?php echo $i; ?></a></li>
                                    <?php else : ?>
                                        <li><a href="<?php echo $current_file_PAGE; ?>?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($endPage < $totalPages) : ?>
                                    <?php if ($endPage < ($totalPages - 1)) : ?>
                                        <li><span>...</span></li>
                                    <?php endif; ?>
                                    <li><a href="<?php echo $current_file_PAGE; ?>?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                                <?php endif; ?>

                                <?php if ($page < $totalPages) : ?>
                                    <li><a href="<?php echo $current_file_PAGE; ?>?page=<?php echo ($page + 1); ?>" aria-label="Next">»</a></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <!--  Pagination Area End -->
                </div>
                <!-- Blog Page Content End -->

                <!-- Sidebar Area Start -->
                <div class="col-lg-4 mt-5 mt-lg-0">
                    <div id="sidebar-area-wrap">
                        <!-- Single Sidebar Item Start -->
                        <div class="single-sidebar-wrap">
                            <h2 class="sidebar-title">Tìm kiếm Bài viết</h2>
                            <div class="sidebar-body">
                                <div class="sidebar-search">
                                    <form action="<?php echo $current_file_PAGE; ?>" method="post">
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
                                                    $date_showblog_2 = $showBlog_r['date'] ?? null;

                                                    if (is_string($date_showblog_2)) {
                                                        $formatted_date_2 = date("d/F/Y", strtotime($date_showblog_2));
                                                        echo $formatted_date_2;
                                                    } else {
                                                        echo "Date is not a valid string";
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
    <script src="assets/js/main.js"></script>
</body>

</html>