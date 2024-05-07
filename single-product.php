<?php
require 'admin-page/config/database.php';
require 'admin-page/model/product_model.php';
require './admin-page/model/blog_model.php';
require 'admin-page/model/cagegoryproduct_model.php';
require 'admin-page/model/general_model.php';
$GeneralModel = new GeneralModel($conn);
$generalListshow = $GeneralModel->getGeneral(1);
$blogModel = new BlogModel($conn);
$ListBlog = $blogModel->showBlog_publicinfo();
$CategoryProductModelList = new CategoryProductModel($conn);
$listcategoryMenu = $CategoryProductModelList->showCategoryProducts();
if (isset($_GET['productid'])) {
    $id = $_GET['productid'];

    $productModel2 = new ProductModel($conn);
    $productInfo = $productModel2->getProduct($id);
} else {
    echo '<script>alert("Không tìm thấy sản phẩm");</script>';
    header('Location: cua-hang.html');
    exit();
}
require './controller/cart_controller.php';
// LAY URL TRANG HIEN TAI

$schema_URL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$host_URL = $_SERVER['HTTP_HOST'];
$path_URL = $_SERVER['REQUEST_URI'];
$current_url_PAGE = $schema_URL . $host_URL . $path_URL;
$current_file_PAGE = 'cua-hang.html';

// END LAY URL TRANG HIEN TAI
?>
<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> <?php echo $productInfo['name']; ?> || Công ty TNHH SX-TM Samry</title>
    <meta name="description" content="<?php echo $productInfo['short_description']; ?>">
    <!-- Thẻ meta cho Facebook Open Graph -->
    <meta property="og:title" content="<?php echo $productInfo['name']; ?> || Công ty TNHH SX-TM Samry">
    <meta property="og:description" content="<?php echo $productInfo['short_description']; ?>">
    <meta property="og:image" content="<?php echo _WEB_HOST . '/admin-page' . mb_substr($productInfo['images'][0], 2); ?>">
    <meta property="og:url" content="<?php echo $current_url_PAGE; ?>">
    <meta property="og:type" content="website">
    <!-- Thẻ meta cho Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $productInfo['name']; ?> || Công ty TNHH SX-TM Samry">
    <meta name="twitter:description" content="<?php echo $productInfo['short_description']; ?>">
    <meta name="twitter:image" content="<?php echo _WEB_HOST . '/admin-page' . mb_substr($productInfo['images'][0], 2); ?>">
    <link rel="canonical" href="<?php echo $current_url_PAGE; ?>">

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Product",
            "name": "<?php echo $productInfo['name']; ?>",
            "description": "<?php echo $productInfo['short_description']; ?>",
            "brand": {
                "@type": "Brand",
                "name": "samryvn"
            },
            "image": "<?php echo _WEB_HOST . '/admin-page' . mb_substr($productInfo['images'][0], 2); ?>",
            "offers": {
                "@type": "Offer",
                "priceCurrency": "VND",
                "price": "<?php echo $productInfo['price']; ?>",
                "availability": "còn hàng",
                "seller": {
                    "@type": "Organization",
                    "name": "công ty TNHH sản xuất thương mại samry"
                }
            }
        }
    </script>
    <?php require_once('main/head.php') ?>




</head>

<body>
    <!--== Header Area Start ==-->
    <?php require_once('main/header.php'); ?>
    <!--== Header Area End ==-->

    <!--== Search Box Area Start ==-->
    <?php require_once('main/search_box.php') ?>
    <!--== Search Box Area End ==-->

    <!--== Page Title Area Start ==-->
    <div id="page-title-area">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="page-title-content">
                        <ul class="breadcrumb">
                            <li><a href="trang-chu.html">TRANG CHỦ</a></li>
                            <li><a href="<?php echo $current_file_PAGE; ?>">CỬA HÀNG</a></li>
                            <li><a href="<?php echo $current_url_PAGE; ?>" class="active"><?php echo $productInfo['name'] ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--== Page Title Area End ==-->

    <!--== Page Content Wrapper Start ==-->
    <div id="page-content-wrapper" class="p-9">
        <div class="ruby-container">
            <div class="row">
                <!-- Single Product Page Content Start -->
                <div class="col-lg-12">
                    <div class="single-product-page-content">
                        <div class="row">
                            <!-- Product Thumbnail Start -->
                            <div class="col-lg-5">
                                <div class="product-thumbnail-wrap">
                                    <div class="product-thumb-carousel owl-carousel">
                                        <?php
                                        if (!empty($productInfo['images'])) {
                                            foreach ($productInfo['images'] as $image) {
                                                $image = './admin-page' . substr($image, 2);
                                                echo '<div class="single-thumb-item">
                                                        <a href="' . $current_file_PAGE . '"><img class="img-fluid"
                                                                                           src="' . $image . '"
                                                                                           alt="' . 'nút ' . $productInfo['name'] . '"/></a>
                                                    </div>';
                                            }
                                        } else {
                                            echo 'No images available<br>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Product Thumbnail End -->

                            <!-- Product Details Start -->
                            <div class="col-lg-7 mt-5 mt-lg-0">
                                <div class="product-details">
                                    <h2><a href="<?php echo $current_url_PAGE; ?>"><?php echo $productInfo['name'] ?></a></h2>

                                    <div class="rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half"></i>
                                        <i class="fa fa-star-o"></i>
                                    </div>

                                    <span class="price"><?php
                                                        if ($productInfo['price'] == 0) {
                                                            echo 'Liên hệ';
                                                        } else {
                                                            echo $productInfo['price'];
                                                        }
                                                        ?>
                                    </span>

                                    <div class="product-info-stock-sku">
                                        <span class="product-stock-status"><?php
                                                                            if ($productInfo['quantity'] >= 1) {
                                                                                echo "CÒN HÀNG";
                                                                            } else {
                                                                                echo "HẾT HÀNG";
                                                                            };
                                                                            ?></span>
                                        <span class="product-sku-status ml-5"><strong>SKU</strong><?php echo $productInfo['sku'] ?> </span>
                                    </div>
                                    <style>
                                        #description p span,
                                        #description ul li span,
                                        #description h1 span,
                                        #description h2 span,
                                        #description h3 span,
                                        #description h4 span,
                                        #description h5 span,
                                        #description h6 span,
                                        #description blockquote {
                                            font-family: "TimesNewRoman" !important;
                                            font-size: 18px !important;
                                        }

                                        #description,
                                        #description ul li,
                                        #description p,
                                        #description h1,
                                        #description h2,
                                        #description h3,
                                        #description h4,
                                        #description h5,
                                        #description h6,
                                        #description blockquote {
                                            font-family: "TimesNewRoman" !important;
                                            font-size: 18px !important;
                                        }

                                        @media (max-width: 767px) {

                                            #description img {
                                                width: 100% !important;
                                                /* Sử dụng 100% chiều rộng cho thiết bị di động */
                                            }

                                        }

                                        /* Quy tắc cho các thiết bị có độ rộng màn hình từ 768px đến 1024px (máy tính bảng) */
                                        @media (min-width: 768px) and (max-width: 1024px) {

                                            #description img {
                                                width: 100% !important;
                                                /* Sử dụng 100% chiều rộng cho máy tính bảng */
                                            }
                                        }
                                    </style>

                                    <p class="products-desc"></p>
                                    <!-- <div class="shopping-option-item">
                                        <h4>Size</h4>
                                        <ul class="color-option-select d-flex">
                                            <li class="color-item black">
                                                <div class="color-hvr">
                                                    <span class="color-fill"></span>
                                                    <span class="color-name">Black</span>
                                                </div>
                                            </li>

                                            <li class="color-item green">
                                                <div class="color-hvr">
                                                    <span class="color-fill"></span>
                                                    <span class="color-name">green</span>
                                                </div>
                                            </li>

                                            <li class="color-item orange">
                                                <div class="color-hvr">
                                                    <span class="color-fill"></span>
                                                    <span class="color-name">Orange</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div> -->

                                    <form method="post" enctype="multipart/form-data" class="product-quantity d-flex align-items-center">
                                        <div class="quantity-field">
                                            <label for="qty">Số lượng</label>
                                            <input type="number" id="qty" name="quantity_addcart" min="1" max="100" value="1" />
                                            <input type="hidden" name="product_id_addcart_new" value="<?php echo $id; ?>">
                                        </div>

                                        <button name="login_addcart" type="submit" class="btn btn-add-to-cart">THÊM VÀO GIỎ</button>
                                    </form>

                                    <!-- <div class="product-btn-group">
                                        <a href="<?php echo $current_url_PAGE; ?>" class="btn btn-add-to-cart btn-whislist">+ Add to
                                            Wishlist</a>
                                        <a href="<?php echo $current_url_PAGE; ?>" class="btn btn-add-to-cart btn-whislist">+ Add to
                                            Compare</a>
                                    </div> -->
                                </div>
                            </div>
                            <!-- Product Details End -->
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Product Full Description Start -->
                                <div class="product-full-info-reviews">
                                    <!-- Single Product tab Menu -->
                                    <nav class="nav" id="nav-tab">
                                        <a class="active" id="description-tab" data-toggle="tab" href="#description">Mô tả</a>
                                        <!-- <a id="reviews-tab" data-toggle="tab" href="#reviews">Đánh giá</a> -->
                                    </nav>
                                    <!-- Single Product tab Menu -->

                                    <!-- Single Product tab Content -->
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="description">
                                            <?php echo $productInfo['description'] ?>
                                        </div>

                                        <div class="tab-pane fade" id="reviews">
                                            <!-- <div class="row">
                                            <div class="col-lg-7">
                                                <div class="product-ratting-wrap">
                                                    <div class="pro-avg-ratting">
                                                        <h4>4.5 <span>(Overall)</span></h4>
                                                        <span>Based on 9 Comments</span>
                                                    </div>
                                                    <div class="ratting-list">
                                                        <div class="sin-list float-left">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <span>(5)</span>
                                                        </div>
                                                        <div class="sin-list float-left">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <span>(3)</span>
                                                        </div>
                                                        <div class="sin-list float-left">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <span>(1)</span>
                                                        </div>
                                                        <div class="sin-list float-left">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <span>(0)</span>
                                                        </div>
                                                        <div class="sin-list float-left">
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <i class="fa fa-star-o"></i>
                                                            <span>(0)</span>
                                                        </div>
                                                    </div>
                                                    <div class="rattings-wrapper">

                                                        <div class="sin-rattings">
                                                            <div class="ratting-author">
                                                                <h3>Cristopher Lee</h3>
                                                                <div class="ratting-star">
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <span>(5)</span>
                                                                </div>
                                                            </div>
                                                            <p>enim ipsam voluptatem quia voluptas sit aspernatur aut
                                                                odit aut fugit, sed quia res eos qui ratione voluptatem
                                                                sequi Neque porro quisquam est, qui dolorem ipsum quia
                                                                dolor sit amet, consectetur, adipisci veli</p>
                                                        </div>

                                                        <div class="sin-rattings">
                                                            <div class="ratting-author">
                                                                <h3>Nirob Khan</h3>
                                                                <div class="ratting-star">
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <span>(5)</span>
                                                                </div>
                                                            </div>
                                                            <p>enim ipsam voluptatem quia voluptas sit aspernatur aut
                                                                odit aut fugit, sed quia res eos qui ratione voluptatem
                                                                sequi Neque porro quisquam est, qui dolorem ipsum quia
                                                                dolor sit amet, consectetur, adipisci veli</p>
                                                        </div>

                                                        <div class="sin-rattings">
                                                            <div class="ratting-author">
                                                                <h3>MD.ZENAUL ISLAM</h3>
                                                                <div class="ratting-star">
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <span>(5)</span>
                                                                </div>
                                                            </div>
                                                            <p>enim ipsam voluptatem quia voluptas sit aspernatur aut
                                                                odit aut fugit, sed quia res eos qui ratione voluptatem
                                                                sequi Neque porro quisquam est, qui dolorem ipsum quia
                                                                dolor sit amet, consectetur, adipisci veli</p>
                                                        </div>

                                                    </div>
                                                    <div class="ratting-form-wrapper fix">
                                                        <h3>Add your Comments</h3>
                                                        <form action="#" method="post">
                                                            <div class="ratting-form row">
                                                                <div class="col-12 mb-4">
                                                                    <h5>Rating:</h5>
                                                                    <div class="ratting-star fix">
                                                                        <i class="fa fa-star-o"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                        <i class="fa fa-star-o"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-12 mb-4">
                                                                    <label for="name">Name:</label>
                                                                    <input id="name" placeholder="Name" type="text">
                                                                </div>
                                                                <div class="col-md-6 col-12 mb-4">
                                                                    <label for="email">Email:</label>
                                                                    <input id="email" placeholder="Email" type="text">
                                                                </div>
                                                                <div class="col-12 mb-4">
                                                                    <label for="your-review">Your Review:</label>
                                                                    <textarea name="review" id="your-review"
                                                                              placeholder="Write a review"></textarea>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input value="add review" type="submit">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        </div>
                                    </div>
                                    <!-- Single Product tab Content -->
                                </div>
                                <!-- Product Full Description End -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Product Page Content End -->
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