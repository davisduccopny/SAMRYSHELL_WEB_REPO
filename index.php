<?php
require './admin-page/config/database.php';
require 'admin-page/model/product_model.php';
require './controller/cart_controller.php';
require 'admin-page/model/blog_model.php';
require 'admin-page/model/cagegoryproduct_model.php';
require 'admin-page/model/general_model.php';
$GeneralModel = new GeneralModel($conn);
$generalListshow = $GeneralModel->getGeneral(1);
$blogModel = new BlogModel($conn);
$ListBlog = $blogModel->showBlog_publicinfo();
$showBlog = $blogModel->showBlog_foruser(1, 4);
$CategoryProductModelList = new CategoryProductModel($conn);
$listcategoryMenu = $CategoryProductModelList->showCategoryProducts();

$productModel = new ProductModel($conn);
$products = $productModel->showProduct();
?>
<!DOCTYPE html>
<html class="no-js" lang="zxx" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Trang chủ || Được thành lập từ năm 2010, Công ty TNHH Samry là một nhà sản xuất hàng đầu về nút ảo, sử dụng vỏ ốc biển làm nguyên liệu chính. Với sứ mệnh mang lại sự tiện lợi và độ tin cậy cho khách hàng, chúng tôi cam kết cung cấp sản phẩm chất lượng và dịch vụ tận tâm. Ghé thăm website samryvn.com để biết thêm thông tin chi tiết và các sản phẩm của chúng tôi.">
    <!-- Thẻ meta cho Facebook Open Graph -->
    <meta property="og:title" content="Trang chủ Công ty TNHH sản xuất thương mại Samry|| sản xuất nút áo, phôi nút và các loại trang sức thì vỏ ốc biển">
    <meta property="og:description" content="Liên hệ hotline công ty 0936.095.515, email: support@samryvn.com, Cập nhật những thông tin mới nhất liên quan đến doanh nghiệp">
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
    <meta name="twitter:title" content="Trang chủ Công ty TNHH sản xuất thương mại Samry|| sản xuất nút áo, phôi nút và các loại trang sức thì vỏ ốc biển">
    <meta name="twitter:description" content="Liên hệ hotline công ty 0936.095.515, email: support@samryvn.com, Cập nhật những thông tin mới nhất liên quan đến doanh nghiệp">
    <meta name="twitter:image" content="./assets/img/samryshell-logo.jpg">
    <link rel="canonical" href="<?php
                                $schema = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
                                $host = $_SERVER['HTTP_HOST'];
                                $path = $_SERVER['REQUEST_URI'];
                                $current_url = $schema . $host . $path;
                                echo $current_url;
                                ?>">
    <title>Trang chủ || Công ty TNHH sản xuất thương mại Samry|| sản xuất nút áo, phôi nút và các loại trang sức thì vỏ ốc biển </title>

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

    <!--== Banner // Slider Area Start ==-->
    <section id="banner-area">
        <div class="ruby-container">
            <div class="row">
                <div class="col-lg-12">
                    <div id="banner-carousel" class="owl-carousel text_transform_edit">
                        <!-- Banner Single Carousel Start -->
                        <div class="single-carousel-wrap slide-item-1">
                            <div class="banner-caption text-center text-lg-center">
                                <h4>SeaShells Store</h4>
                                <h2>Khám phá vẻ đẹp tự nhiên</h2>
                                <p>Khám phá thế giới đầy màu sắc của vỏ sò tự nhiên và khám phá vẻ đẹp độc đáo mà mỗi chiếc vỏ mang lại.</p>
                                <a href="shop.php" class="btn-long-arrow">Shop Now</a>
                            </div>
                        </div>
                        <!-- Banner Single Carousel End -->

                        <!-- Banner Single Carousel Start -->
                        <div class="single-carousel-wrap slide-item-2">
                            <div class="banner-caption text-center text-lg-left">
                                <h4>Samry Shell <?php echo date('Y'); ?></h4>
                                <h2>Sáng tạo từ biển cả</h2>
                                <p>Chúng tôi mang đến cho bạn những sản phẩm độc đáo được làm từ vỏ sò, tượng trưng cho sự sáng tạo và tinh thần của đại dương.</p>
                                <a href="shop.php" class="btn-long-arrow">Shop Now</a>
                            </div>
                        </div>
                        <div class="single-carousel-wrap slide-item-3">
                            <div class="banner-caption text-center text-lg-left">
                                <h4>Samry Shell <?php echo date('Y'); ?></h4>
                                <h2>Xây dựng cuộc sống bền vững</h2>
                                <p>Bằng cách tái chế vỏ sò tự nhiên, chúng tôi không chỉ tôn vinh thiên nhiên mà còn hướng tới một lối sống bền vững hơn.</p>
                                <a href="shop.php" class="btn-long-arrow">Shop Now</a>
                            </div>
                        </div>
                        <div class="single-carousel-wrap slide-item-4">
                            <div class="banner-caption text-center text-lg-left">
                                <h4>SeaShells Store <?php echo date('Y'); ?></h4>
                                <h2>Đắm chìm trong sự bao la của biển</h2>
                                <p>Sản phẩm từ vỏ sò mang đến không gian sống lãng mạn, thân mật gần gũi hơn với đại dương, tạo nên môi trường sống sang trọng.</p>
                                <a href="shop.php" class="btn-long-arrow">Shop Now</a>
                            </div>
                        </div>
                        <div class="single-carousel-wrap slide-item-5">
                            <div class="banner-caption text-center text-lg-left">
                                <h4>Samry Shell <?php echo date('Y'); ?></h4>
                                <h2>Sang trọng trong từng chi tiết</h2>
                                <p>Trải nghiệm sự tinh tế và độc đáo của từng chiếc cúc áo và món trang sức được làm hoàn toàn từ vỏ sò tự nhiên.</p>
                                <a href="shop.php" class="btn-long-arrow">Shop Now</a>
                            </div>
                        </div>
                        <!-- Banner Single Carousel End -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== Banner Slider End ==-->

    <!--== About Us Area Start ==-->
    <section id="aboutUs-area" class="pt-9">
        <div class="ruby-container">
            <div class="row">
                <div class="col-lg-6">
                    <!-- About Image Area Start -->
                    <div class="about-image-wrap">
                        <a href="about.php?blog_id=12"><img src="../../admin-page/upload/661c0b954dae1.jpg" style="border-radius: 5%;" alt="About Us" class="img-fluid" /></a>
                    </div>
                    <!-- About Image Area End -->
                </div>

                <div class="col-lg-6 m-auto">
                    <!-- About Text Area Start -->
                    <div class="about-content-wrap ml-0 ml-lg-5 mt-5 mt-lg-0">
                        <h2>Về chúng tôi</h2>
                        <h3>WE ARE VISIONARY</h3>
                        <div class="about-text text_transform_edit" style="font-size: 1.3rem;">
                            <p>SamryShell là công ty tiên phong trong việc sáng tạo và sản xuất các sản phẩm làm từ vỏ sò tự nhiên. Với sứ mệnh khám phá và tôn vinh vẻ đẹp của đại dương, SamryShell không chỉ mang đến những sản phẩm độc đáo mà còn góp phần bảo vệ môi trường bằng cách tái chế và sử dụng tài nguyên sinh học bền vững. Nổi tiếng về chất lượng và sự đổi mới, SamryShell đã tạo dựng được vị thế của mình trong ngành và thu hút được sự chú ý trên toàn thế giới.</p>

                            <a href="about.php?blog_id=12" class="btn btn-long-arrow">Learn More</a>


                            <h4 class="vertical-text">WHO WE ARE?</h4>
                        </div>
                    </div>
                    <!-- About Text Area End -->
                </div>
            </div>
        </div>
    </section>
    <!--== About Us Area End ==-->

    <!--== New Collection Area Start ==-->
    <section id="new-collection-area" class="p-9">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h2>BỘ SƯU TẬP SẢN PHẨM MỚI</h2>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="new-collection-tabs">

                        <!-- Tab Menu Area Start -->
                        <ul class="nav tab-menu-wrap" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="active" id="feature-products-tab" data-toggle="tab" href="#feature-products" role="tab" aria-controls="feature-products-tab" aria-selected="true">TẤT CẢ SẢN PHẨM</a>
                            </li>
                            <li class="nav-item">
                                <a id="new-products-tab" data-toggle="tab" href="#new-products" role="tab" aria-controls="new-products" aria-selected="false">SẢN PHẨM MỚI</a>
                            </li>
                            <li class="nav-item">
                                <a id="onsale-tab" data-toggle="tab" href="#onsale" role="tab" aria-controls="onsale" aria-selected="false">ĐANG SALE</a>
                            </li>
                        </ul>
                        <!-- Tab Menu Area End -->

                        <!-- Tab Content Area Start -->
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="feature-products" role="tabpanel" aria-labelledby="feature-products-tab">
                                <div class="products-wrapper">
                                    <div class="products-carousel owl-carousel">
                                        <!-- Single Product Item -->
                                        <?php $type_product = 'Other';
                                        $showProduct_type = $productModel->showProduct_forTypeProduct($type_product, 1, 4); ?>
                                        <?php foreach ($showProduct_type as $showProduct_slide) : ?>

                                            <div class="single-product-item text-center">
                                                <figure class="product-thumb">
                                                    <a href="single-product.php?productid=<?php echo $showProduct_slide['id'] ?>"><img src="<?php echo 'admin-page' . mb_substr($showProduct_slide['image'], 2); ?>" alt="Products" class="img-fluid"></a>
                                                </figure>

                                                <div class="product-details">
                                                    <h2><a href="single-product.php?productid=<?php echo $showProduct_slide['id'] ?>"><?php echo $showProduct_slide['name'] ?></a></h2>
                                                    <span class="price">$<?php echo $showProduct_slide['price'] ?></span>
                                                    <a href="single-product.php?productid=<?php echo $showProduct_slide['id']; ?>" class="btn btn-add-to-cart">Thêm vào giỏ</a>
                                                </div>

                                                <div class="product-meta">
                                                    <button type="button" data-toggle="modal" data-target="#quickView" class="button_showdetail" data-product-id="<?php echo $showProduct_slide['id']; ?>">
                                                        <span data-toggle="tooltip" data-placement="left" title="Quick View"><i class="fa fa-compress"></i></span>
                                                    </button>
                                                    <a href="#" data-toggle="tooltip" data-placement="left" title="Add to Wishlist"><i class="fa fa-heart-o"></i></a>
                                                    <a href="#" data-toggle="tooltip" data-placement="left" title="Compare"><i class="fa fa-tags"></i></a>
                                                </div>
                                            </div>
                                            <!-- Single Product Item -->
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="new-products" role="tabpanel" aria-labelledby="new-products-tab">
                                <div class="products-wrapper">
                                    <div class="products-carousel owl-carousel">
                                        <!-- Single Product Item -->
                                        <?php $type_product_2 = 'New';
                                        $showProduct_type_2 = $productModel->showProduct_forTypeProduct($type_product_2, 1, 4); ?>
                                        <?php foreach ($showProduct_type_2 as $showProduct_slide_2) : ?>

                                            <div class="single-product-item text-center">
                                                <figure class="product-thumb">
                                                    <a href="single-product.php?productid=<?php echo $showProduct_slide_2['id'] ?>"><img src="<?php echo 'admin-page' . mb_substr($showProduct_slide_2['image'], 2); ?>" alt="Products" class="img-fluid"></a>
                                                </figure>

                                                <div class="product-details">
                                                    <h2><a href="single-product.php?productid=<?php echo $showProduct_slide_2['id'] ?>"><?php echo $showProduct_slide_2['name'] ?></a></h2>
                                                    <div class="rating">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-half"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <span class="price">$<?php echo $showProduct_slide_2['price'] ?></span>
                                                    <a href="single-product.php?productid=<?php echo $showProduct_slide_2['id']; ?>" class="btn btn-add-to-cart">+ Add to Cart</a>
                                                    <span class="product-bedge">New</span>
                                                </div>

                                                <div class="product-meta">
                                                    <button type="button" data-toggle="modal" data-target="#quickView" class="button_showdetail" data-product-id="<?php echo $showProduct_slide_2['id']; ?>">
                                                        <span data-toggle="tooltip" data-placement="left" title="Quick View"><i class="fa fa-compress"></i></span>
                                                    </button>
                                                    <a href="#" data-toggle="tooltip" data-placement="left" title="Add to Wishlist"><i class="fa fa-heart-o"></i></a>
                                                    <a href="#" data-toggle="tooltip" data-placement="left" title="Compare"><i class="fa fa-tags"></i></a>
                                                </div>
                                            </div>
                                            <!-- Single Product Item -->
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="onsale" role="tabpanel" aria-labelledby="onsale-tab">
                                <div class="products-wrapper">
                                    <div class="products-carousel owl-carousel">
                                        <?php $type_product_3 = 'Sale';
                                        $showProduct_type_3 = $productModel->showProduct_forTypeProduct($type_product_3, 1, 4); ?>
                                        <?php foreach ($showProduct_type_3 as $showProduct_slide_3) : ?>

                                            <div class="single-product-item text-center">
                                                <figure class="product-thumb">
                                                    <a href="single-product.php?productid=<?php echo $showProduct_slide_3['id'] ?>"><img src="<?php echo 'admin-page' . mb_substr($showProduct_slide_3['image'], 2); ?>" alt="Products" class="img-fluid"></a>
                                                </figure>

                                                <div class="product-details">
                                                    <h2><a href="single-product.php?productid=<?php echo $showProduct_slide_3['id'] ?>"><?php echo $showProduct_slide_3['name'] ?></a></h2>
                                                    <div class="rating">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-half"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <span class="price">$<?php echo $showProduct_slide_3['price'] ?></span>
                                                    <a href="single-product.php?productid=<?php echo $showProduct_slide_3['id']; ?>" class="btn btn-add-to-cart">+ Add to Cart</a>
                                                    <span class="product-bedge sale">Sale</span>
                                                </div>

                                                <div class="product-meta">
                                                    <button type="button" data-toggle="modal" data-target="#quickView" class="button_showdetail" data-product-id="<?php echo $showProduct_slide_3['id']; ?>">
                                                        <span data-toggle="tooltip" data-placement="left" title="Quick View"><i class="fa fa-compress"></i></span>
                                                    </button>
                                                    <a href="#" data-toggle="tooltip" data-placement="left" title="Add to Wishlist"><i class="fa fa-heart-o"></i></a>
                                                    <a href="#" data-toggle="tooltip" data-placement="left" title="Compare"><i class="fa fa-tags"></i></a>
                                                </div>
                                            </div>
                                            <!-- Single Product Item -->
                                        <?php endforeach; ?>
                                        <!-- Single Product Item -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tab Content Area End -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== New Collection Area End ==-->

    <!--== Products by Category Area Start ==-->
    <!-- <div id="product-categories-area">
        <div class="ruby-container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="large-size-cate">
                        <div class="row">
                            <div class="col-sm-6 ">
                                <div class="single-cat-item">
                                    <figure class="category-thumb">
                                        <a href="#"><img src="assets/img/author-1.jpg" alt="Women Category" class="img-fluid" /></a>

                                        <figcaption class="category-name">
                                            <a href="#">Shop For Women</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="single-cat-item">
                                    <figure class="category-thumb">
                                        <a href="#"><img src="assets/img/author-2.jpg" alt="Men Category" class="img-fluid " /></a>

                                        <figcaption class="category-name">
                                            <a href="#">Shop For Men</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="single-cat-item">
                                    <figure class="category-thumb">
                                        <a href="#"><img src="assets/img/author-3.jpg" alt="Men Category" class="img-fluid " /></a>

                                        <figcaption class="category-name">
                                            <a href="#">Tyle</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="single-cat-item">
                                    <figure class="category-thumb">
                                        <a href="#"><img src="assets/img/about-img-2.jpg" alt="Men Category" class="img-fluid " /></a>

                                        <figcaption class="category-name">
                                            <a href="#">Style</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="small-size-cate">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="single-cat-item">
                                    <figure class="category-thumb">
                                        <a href="#"><img src="./assets/img/aboutus.jpg" alt="Men Category" class="img-fluid" /></a>

                                        <figcaption class="category-name">
                                            <a href="#">Jewellery</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="single-cat-item">
                                    <figure class="category-thumb">
                                        <a href="#"><img src="assets/img/image_category_1.jpg" alt="Men Category" class="img-fluid" /></a>

                                        <figcaption class="category-name">
                                            <a href="#">Shell home décor</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="single-cat-item">
                                    <figure class="category-thumb">
                                        <a href="#"><img src="assets/img/image_category_2.jpg" alt="Men Category" class="img-fluid" /></a>

                                        <figcaption class="category-name">
                                            <a href="#">Shell crafts</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="single-cat-item">
                                    <figure class="category-thumb">
                                        <a href="#"><img src="assets/img/image_category_3.jpg" alt="Men Category" class="img-fluid" /></a>

                                        <figcaption class="category-name">
                                            <a href="#">Coastal living</a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!--== Products by Category Area End ==-->

    <!--== New Products Area Start ==-->
    <section id="new-products-area" class="p-9">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h2>SẢN PHẨM MỚI</h2>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="products-wrapper">
                        <div class="new-products-carousel owl-carousel">
                            <!-- Single Product Item -->
                            <?php foreach ($products as $product) : ?>
                                <div class="single-product-item text-center">
                                    <figure class="product-thumb">
                                        <a href="single-product.php?productid=<?php echo $product['id'] ?>"><img src="<?php
                                                                                                                        $strfirt = './admin-page';
                                                                                                                        echo  $strfirt . mb_substr($product['image'], 2); ?>" alt="Products" class="img-fluid" style="height: 195px;"></a>
                                    </figure>

                                    <div class="product-details">
                                        <h2><a href="single-product.php?productid=<?php echo $product['id'] ?>"><?php echo $product['name']; ?></a></h2>
                                        <span class="price"><?php echo $product['price']; ?></span>
                                        <a href="single-product.php?productid=<?php echo $product['id'] ?>" class="btn btn-add-to-cart">+ Add to Cart</a>
                                        <span class="<?php
                                                        if ($product['type_product'] == 'Other') {
                                                            echo '';
                                                        } elseif ($product['type_product'] == 'New') {
                                                            echo 'product-bedge';
                                                        } else {
                                                            echo 'product-bedge sale';
                                                        }

                                                        ?>
                                    
                                    ">
                                            <?php
                                            if ($product['type_product'] == 'Other') {
                                                echo '';
                                            } elseif ($product['type_product'] == 'New') {
                                                echo 'New';
                                            } else {
                                                echo 'Sale';
                                            }

                                            ?>

                                        </span>
                                    </div>

                                    <div class="product-meta">
                                        <button type="button" data-toggle="modal" data-target="#quickView" class="button_showdetail" data-product-id="<?php echo $product['id']; ?>">
                                            <span data-toggle="tooltip" data-placement="left" title="Quick View"><i class="fa fa-compress"></i></span>
                                        </button>
                                        <a href="#" data-toggle="tooltip" data-placement="left" title="Add to Wishlist"><i class="fa fa-heart-o"></i></a>
                                        <a href="#" data-toggle="tooltip" data-placement="left" title="Compare"><i class="fa fa-tags"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- Single Product Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--== New Products Area End ==-->

    <!--== Testimonial Area Start ==-->
    <!--== Testimonial Area End ==-->

    <!--== Blog Area Start ==-->
    <!--== Blog Area End ==-->

    <!--== Newsletter Area Start ==-->
    <!--== Newsletter Area End ==-->
    <!-- Footer Area Start -->
    <?php require_once('main/footer.php'); ?>
    <!-- Footer Area End -->


    <!-- Start All Modal Content -->
    <!--== Product Quick View Modal Area Wrap ==-->
    <?php require_once('main/model_view.php'); ?>
    <!--== Product Quick View Modal Area End ==-->
    <!-- End All Modal Content -->

    <!-- Scroll to Top Start -->
    <a href="#" class="scrolltotop"><i class="fa fa-angle-up"></i></a>
    <!-- Scroll to Top End -->


    <!--=======================Javascript============================-->
    <script src="assets/js/main.js"></script>
    <?php require_once('main/src_js.php'); ?>
</body>

</html>