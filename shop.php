<?php
require './admin-page/config/database.php';
require './admin-page/model/product_model.php';
require './admin-page/model/cagegoryproduct_model.php';
require 'admin-page/model/blog_model.php';
require 'controller/cart_controller.php';
require 'admin-page/model/general_model.php';
$GeneralModel = new GeneralModel($conn);
$generalListshow = $GeneralModel->getGeneral(1);
$showlistcart_wl = $cartmodel->getCart($emaillist);
$categoryproductModel = new CategoryProductModel($conn);
$categorylist = $categoryproductModel->showCategoryProducts();
$listcategoryMenu = $categoryproductModel->showCategoryProducts();
$blogModel = new BlogModel($conn);
$ListBlog = $blogModel->showBlog_publicinfo();

// XÁC ĐỊNH SỐ TRANG
$perPage = 9;
$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
}
$countItem = num_rowsCount_item("SELECT * FROM product", $conn);
$totalPages = ceil($countItem / $perPage);

$page = max(1, min($page, $totalPages));
$start = ($page - 1) * $perPage;
$productModel = new ProductModel($conn);
$products = $productModel->showProduct_foruser($start, $perPage);
$products_most = $productModel->showProduct_foruser(1, 3);
// END XÁC ĐỊNH SỐ TRANG





// START FILTER
$category = isset($_GET['category']) ? $_GET['category'] : null;
$minprice = isset($_GET['minprice']) ? $_GET['minprice'] : null;
$maxprice = isset($_GET['maxprice']) ? $_GET['maxprice'] : null;
if ($category !== null || ($minprice !== null && $maxprice !== null)) {
    $products = $productModel->showProductFilter($category, $minprice, $maxprice);
}
// END FILTER

// SEARCH PRODUCT
if (isset($_POST['search_product'])) {
    $showProductsearch = $_POST['content_search_product'];
    $products = $productModel->showProduct_search($showProductsearch);
}

// END SEARCH

// LAY URL TRANG HIEN TAI

$schema_URL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$host_URL = $_SERVER['HTTP_HOST'];
$path_URL = $_SERVER['REQUEST_URI'];
$current_url_PAGE = $schema_URL . $host_URL . $path_URL;
$current_file_PAGE = 'cua-hang.html';

// END LAY URL TRANG HIEN TAI
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trang bán hàng || Công ty TNHH Sản xuất Thương mại Samry</title>
    <meta name="description" content="Trang bán hàng || Công ty TNHH Sản xuất Thương mại Samry sản xuất nút áo, phôi nút và các loại trang sức thì vỏ ốc biển. Ghé thăm để lựa chọn cho mình những bộ nút áo ưng ý nhất.">
    <!-- Thẻ meta cho Facebook Open Graph -->
    <meta property="og:title" content="Trang bán hàng || Công ty TNHH Sản xuất Thương mại Samry">
    <meta property="og:description" content="Công ty TNHH Sản xuất Thương mại Samry sản xuất nút áo, phôi nút và các loại trang sức thì vỏ ốc biển. Ghé thăm để lựa chọn cho mình những bộ nút áo ưng ý nhất.">
    <meta property="og:image" content="./assets/img/samryshell-logo.jpg">
    <meta property="og:url" content="<?php echo $current_url_PAGE; ?>">
    <meta property="og:type" content="website">
    <!-- Thẻ meta cho Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Trang bán hàng || Công ty TNHH Sản xuất Thương mại Samry">
    <meta name="twitter:description" content="Công ty TNHH Sản xuất Thương mại Samry sản xuất nút áo, phôi nút và các loại trang sức thì vỏ ốc biển. Ghé thăm để lựa chọn cho mình những bộ nút áo ưng ý nhất.">
    <meta name="twitter:image" content="./assets/img/samryshell-logo.jpg">
    <link rel="canonical" href="<?php echo $current_url_PAGE; ?>">
   
    <!-- START SEO JSON -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Công ty TNHH sản xuất thương mại samry",
            "url": "https://samryvn.com/cua-hang.html",
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
                        <h1>Cửa hàng</h1>
                        <ul class="breadcrumb">
                            <li><a href="trang-chu.html">Trang chủ</a></li>
                            <li><a href="#" class="active">Cửa hàng</a></li>
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
                <!-- Sidebar Area Start -->
                <div class="col-lg-3 mt-5 mt-lg-0 order-last order-lg-first">
                    <div id="sidebar-area-wrap">
                        <!-- Single Sidebar Item Start -->
                        <div class="single-sidebar-wrap">
                            <h2 class="sidebar-title">MUA SẮM BỞI</h2>
                            <div class="sidebar-body">
                                <div class="shopping-option">

                                    <div class="shopping-option-item">
                                        <h4>DANH MỤC</h4>
                                        <ul class="sidebar-list">
                                            <?php foreach ($categorylist as $category) : ?>
                                                <li><a href="<?php echo $current_file_PAGE . "?category=" . $category['categoryproduct_id']; ?>" data-category="<?php echo $category['categoryproduct_id'];  ?>"><?php echo $category['name']; ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <style>
                                        .price-filter {
                                            display: flex;
                                            align-items: center;
                                        }

                                        .price-input {
                                            width: 50%;
                                            padding: 8px;
                                            margin-right: 10px;
                                            border: 1px solid #ccc;
                                            border-radius: 4px;
                                        }

                                        .price-separator {
                                            margin-right: 10px;
                                        }

                                        .filter-btn {
                                            padding: 8px 16px;
                                            background-color: #f5740a;
                                            color: #fff;
                                            border: none;
                                            border-radius: 4px;
                                            cursor: pointer;
                                        }

                                        .filter-btn:hover {
                                            background-color: #0056b3;
                                        }

                                        .selected-category {
                                            color: red !important;
                                        }
                                    </style>
                                    <div class="shopping-option-item">
                                        <h4>GIÁ</h4>
                                        <div class="price-filter">
                                            <input type="text" id="minPrice" placeholder="Min price" class="price-input">
                                            <span class="price-separator">-</span>
                                            <input type="text" id="maxPrice" placeholder="Max price" class="price-input">
                                            <button id="filterBtn" class="filter-btn">Filter</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Sidebar Item End -->

                        <!-- Single Sidebar Item Start -->
                        <div class="single-sidebar-wrap">
                            <h2 class="sidebar-title">Danh Mục của tôi</h2>
                            <div class="sidebar-body">
                                <div class="small-product-list">
                                    <?php foreach ($showlistcart_wl as $showlistcat) : ?>
                                        <div class="single-product-item">
                                            <figure class="product-thumb">
                                                <a href="san-pham/<?php echo $showlistcat['id'] ?>/<?php echo $showlistcat['slug'] . '.html' ?>"><img class="mr-2 img-fluid" src="<?php echo '/admin-page' . mb_substr($showlistcat['image'], 2); ?>" alt="Products" /></a>
                                            </figure>
                                            <div class="product-details">
                                                <h2><a href="san-pham/<?php echo $showlistcat['id'] ?>/<?php echo $showlistcat['slug'] . '.html' ?>"><?php echo $showlistcat['name'] ?></a></h2>
                                                <span class="price">$<?php echo $showlistcat['price'] ?></span>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Single Sidebar Item End -->

                        <!-- Single Sidebar Item Start -->
                        <div class="single-sidebar-wrap">
                            <h2 class="sidebar-title">SẢN PHẨM NỔI BẬT</h2>
                            <div class="sidebar-body">
                                <div class="small-product-list">
                                    <?php foreach ($products_most as $productshowyk) : ?>
                                        <div class="single-product-item">
                                            <figure class="product-thumb">
                                                <a href="san-pham/<?php echo $productshowyk['id'] ?>/<?php echo $productshowyk['slug'] . '.html' ?>"><img class="mr-2 img-fluid" src="<?php echo '/admin-page' . mb_substr($productshowyk['image'], 2); ?>" alt="Products" /></a>
                                            </figure>
                                            <div class="product-details">
                                                <h2><a href="san-pham/<?php echo $productshowyk['id'] ?>/<?php echo $productshowyk['slug'] . '.html' ?>"><?php echo $productshowyk['name'] ?></a></h2>
                                                <span class="price">$<?php echo $productshowyk['price'] ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Single Sidebar Item End -->
                    </div>
                </div>
                <!-- Sidebar Area End -->

                <!-- Shop Page Content Start -->
                <div class="col-lg-9">
                    <div class="shop-page-content-wrap">
                        <div class="products-settings-option d-block d-md-flex">
                            <div class="product-cong-left d-flex align-items-center">
                                <ul class="product-view d-flex align-items-center">
                                    <li class="current column-gird"><i class="fa fa-bars fa-rotate-90"></i></li>
                                    <li class="box-gird"><i class="fa fa-th"></i></li>
                                    <li class="list"><i class="fa fa-list-ul"></i></li>
                                </ul>
                                <span class="show-items">BỐ CỤC</span>
                            </div>

                            <div class="product-sort_by d-flex align-items-center mt-3 mt-md-0">
                                <label for="sort">SẮP XẾP BỞI:</label>
                                <select name="sort" id="sort_by_product" onchange="sortProducts()">
                                    <option value="Position">Liên quan</option>
                                    <option value="Name Ascen">Tên, A tới Z</option>
                                    <option value="Name Decen">Tên, Z tới A</option>
                                    <option value="Price Ascen">Giá từ thấp => cao</option>
                                    <option value="Price Decen">Giá từ cao => thấp</option>
                                </select>
                            </div>
                        </div>

                        <div class="shop-page-products-wrap">
                            <div class="products-wrapper">
                                <div class="row product_sort_insert_filter">
                                    <?php foreach ($products as $product) : ?>
                                        <div class="col-lg-4 col-sm-6 sort_product_by_select" id="<?php echo $product['id']  ?>">
                                            <!-- Single Product Item -->
                                            <div class="single-product-item text-center">
                                                <figure class="product-thumb">
                                                    <a href="<?php echo "san-pham/" . $product['id'] . '/' . $product['slug'] . '.html'  ?>"><img src="<?php
                                                                                                                                                        $strfirt = './admin-page';
                                                                                                                                                        echo  $strfirt . mb_substr($product['image'], 2); ?>" alt="<?php echo $product['name']; ?>" class="img-fluid" style="height: 195px;"></a>
                                                </figure>

                                                <div class="product-details product-filter-category" data-category="<?php if (isset($_GET['category'])) {
                                                                                                                        echo $product['categoryproduct_id'];
                                                                                                                    } ?>">
                                                    <h2><a href="<?php echo "san-pham/" . $product['id'] . '/' . $product['slug'] . '.html'  ?>"><?php echo $product['name']; ?></a></h2>
                                                    <div class="rating">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-half"></i>
                                                        <i class="fa fa-star-o"></i>
                                                    </div>
                                                    <span class="price">$<?php echo $product['price']; ?></span>
                                                    <p class="products-desc"><?php echo $product['short_description'] . '.html'; ?></p>
                                                    <a href="<?php echo "san-pham/" . $product['id'] . '/' . $product['slug'] . '.html';  ?>" class="btn btn-add-to-cart">Xem chi tiết</a>
                                                    <a href="<?php echo "san-pham/" . $product['id'] . '/' . $product['slug'] . '.html'; ?>" class="btn btn-add-to-cart btn-whislist">+
                                                        Wishlist</a>
                                                    <a href="<?php echo "san-pham/" . $product['id'] . '/' . $product['slug'] . '.html';  ?>" class="btn btn-add-to-cart btn-compare">+
                                                        Compare</a>
                                                </div>

                                                <div class="product-meta">
                                                    <button type="button" class="button_showdetail" data-toggle="modal" data-target="#quickView" data-product-id="<?php echo $product['id']; ?>">
                                                        <span data-toggle="tooltip" data-placement="left" title="Quick View"><i class="fa fa-compress"></i></span>
                                                    </button>
                                                    <a href="#" data-toggle="tooltip" data-placement="left" title="Add to Wishlist"><i class="fa fa-heart-o"></i></a>
                                                    <a href="#" data-toggle="tooltip" data-placement="left" title="Compare"><i class="fa fa-tags"></i></a>
                                                </div>
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
                                            <!-- Single Product Item -->
                                        </div>
                                    <?php endforeach; ?>



                                </div>
                            </div>
                        </div>


                        <div class="products-settings-option d-block d-md-flex">
                            <nav class="page-pagination">
                                <?php if (!empty($totalPages) && $totalPages > 1) : ?>
                                    <ul class="pagination">
                                        <?php if ($page > 1) : ?>
                                            <li><a href="<?php echo $current_file_PAGE; ?>?page=<?php echo ($page - 1); ?>" aria-label="Previous">«</a></li>
                                        <?php endif; ?>

                                        <?php
                                        $numAdjacentPages = 2; // Số trang cố định xung quanh trang hiện tại
                                        $startPage = max(1, $page - $numAdjacentPages);
                                        $endPage = min($totalPages, $page + $numAdjacentPages);

                                        if ($startPage > 1) {
                                            echo '<li><a href="' . $current_file_PAGE . '?page=1">1</a></li>';
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
                            </nav>
                            <script>
                                // START FILTER

                                document.getElementById('filterBtn').addEventListener('click', function() {
                                    var minPrice = parseFloat(document.getElementById('minPrice').value);
                                    var maxPrice = parseFloat(document.getElementById('maxPrice').value);

                                    // Kiểm tra nếu minPrice hoặc maxPrice không hợp lệ thì không điều hướng
                                    if (isNaN(minPrice) || isNaN(maxPrice) || minPrice < 0 || maxPrice < 0 || minPrice > maxPrice) {
                                        alert("Vui lòng nhập giá trị minprice và maxprice hợp lệ.");
                                        return;
                                    }

                                    // Lấy category từ phần tử đầu tiên có class product-filter-category
                                    var categoryElement = document.querySelector(".product-filter-category");
                                    var category = categoryElement ? categoryElement.getAttribute("data-category") : null;

                                    // Kiểm tra xem category có tồn tại không
                                    if (category) {
                                        // Đường dẫn tới trang shopfilter với các tham số minprice, maxprice và category
                                        var url = "<?php echo $current_file_PAGE; ?>?minprice=" + minPrice + "&maxprice=" + maxPrice + "&category=" + category;
                                    } else {
                                        // Đường dẫn tới trang shopfilter chỉ với các tham số minprice và maxprice
                                        var url = "<?php echo $current_file_PAGE; ?>?minprice=" + minPrice + "&maxprice=" + maxPrice;
                                    }

                                    // Điều hướng đến trang mới với các tham số đã lọc
                                    window.location.href = url;
                                });

                                document.addEventListener("DOMContentLoaded", function() {
                                    var productCategories = document.querySelectorAll(".product-filter-category");
                                    var liItems = document.querySelectorAll(".sidebar-list li a");
                                    // check param
                                    var urlParams = new URLSearchParams(window.location.search);
                                    var category_param = urlParams.get('category');
                                    // end check param
                                    productCategories.forEach(function(productCategory) {
                                        var category = productCategory.getAttribute("data-category");

                                        liItems.forEach(function(li) {
                                            var liCategory = li.getAttribute("data-category");

                                            if (category === liCategory && category_param) {
                                                li.classList.add("selected-category");
                                            }
                                        });
                                    });



                                });
                                // END FILTER

                                // START SORT
                                function sortProducts() {
                                    var sortSelect = document.getElementById("sort_by_product");
                                    var selectedOption = sortSelect.value;
                                    var productsWrapper = document.querySelector(".product_sort_insert_filter");
                                    var products = Array.from(productsWrapper.querySelectorAll(".sort_product_by_select"));

                                    products.sort(function(a, b) {
                                        var aValue, bValue;
                                        switch (selectedOption) {
                                            case "Name Ascen":
                                                aValue = a.querySelector("h2 a").innerText;
                                                bValue = b.querySelector("h2 a").innerText;
                                                return aValue.localeCompare(bValue);
                                            case "Name Decen":
                                                aValue = a.querySelector("h2 a").innerText;
                                                bValue = b.querySelector("h2 a").innerText;
                                                return bValue.localeCompare(aValue);
                                            case "Price Ascen":
                                                aValue = parseFloat(a.querySelector(".price").innerText.slice(1));
                                                bValue = parseFloat(b.querySelector(".price").innerText.slice(1));
                                                return aValue - bValue;
                                            case "Price Decen":
                                                aValue = parseFloat(a.querySelector(".price").innerText.slice(1));
                                                bValue = parseFloat(b.querySelector(".price").innerText.slice(1));
                                                return bValue - aValue;
                                            default:
                                                return 0;
                                        }
                                    });

                                    // Xóa tất cả các sản phẩm trong productsWrapper
                                    productsWrapper.innerHTML = "";

                                    // Thêm lại các sản phẩm đã được sắp xếp
                                    products.forEach(function(product) {
                                        productsWrapper.appendChild(product);
                                    });
                                }



                                // END SORT
                            </script>

                            <!-- <div class="product-per-page d-flex align-items-center mt-3 mt-md-0">
                                <label for="show-per-page">Show Per Page</label>
                                <select name="sort" id="show-per-page">
                                    <option value="9">9</option>
                                    <option value="15">15</option>
                                    <option value="21">21</option>
                                    <option value="6">27</option>
                                </select>
                            </div> -->
                        </div>
                    </div>
                </div>
                <!-- Shop Page Content End -->
            </div>
        </div>
    </div>
    <!--== Page Content Wrapper End ==-->

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
    <?php require_once('main/src_js.php'); ?>

</body>

</html>