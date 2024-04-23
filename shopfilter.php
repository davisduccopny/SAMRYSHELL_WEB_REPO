<?php 
require './admin-page/config/database.php';
require './admin-page/model/product_model.php';
require './admin-page/model/cagegoryproduct_model.php';
$productModel = new ProductModel($conn);

// Kiểm tra xem có tham số category, minprice và maxprice được truyền hay không
$category = isset($_GET['category']) ? $_GET['category'] : null;
$minprice = isset($_GET['minprice']) ? $_GET['minprice'] : null;
$maxprice = isset($_GET['maxprice']) ? $_GET['maxprice'] : null;
$categoryproductModel = new CategoryProductModel($conn);
$categorylist = $categoryproductModel->showCategoryProducts();
// Gọi hàm showProductFilter với các tham số tương ứng
if ($category !== null || ($minprice !== null && $maxprice !== null)) {
    $products = $productModel->showProductFilter($category, $minprice, $maxprice);
}
 else {
    echo '<script>alert("Không tìm thấy sản phẩm");</script>';
    header('Location: shop.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="meta description">

    <title>Shop :: DNX - Jewelry Store e-Commerce Bootstrap 4 Template</title>

    <!--=== Favicon ===-->
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon"/>

    <!--== Google Fonts ==-->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Droid+Serif:400,400i,700,700i"/>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Montserrat:400,700"/>
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i"/>

    <!--=== Bootstrap CSS ===-->
    <link href="assets/css/vendor/bootstrap.min.css" rel="stylesheet">
    <!--=== Font-Awesome CSS ===-->
    <link href="assets/css/vendor/font-awesome.css" rel="stylesheet">
    <!--=== Plugins CSS ===-->
    <link href="assets/css/plugins.css" rel="stylesheet">
    <!--=== Main Style CSS ===-->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Modernizer JS -->
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!--== Header Area Start ==-->
<header id="header-area" class="header__3">
    <div class="ruby-container">
        <div class="row">
            <!-- Logo Area Start -->
            <div class="col-3 col-lg-1 col-xl-2 m-auto">
                <a href="index.php" class="logo-area">
                    <img src="assets/img/logo-black.png" alt="Logo" class="img-fluid"/>
                </a>
            </div>
            <!-- Logo Area End -->

            <!-- Navigation Area Start -->
            <div class="col-3 col-lg-9 col-xl-8 m-auto">
                <div class="main-menu-wrap">
                    <nav id="mainmenu">
                        <ul>
                            <li ><a href="index.php">Home</a>
                            </li>
                            <li class="dropdown-show"><a href="shop.php">Shop</a>
                            </li>
                            <li ><a href="about.php">About</a>
                            </li>
                            <li class="dropdown-show"><a href="#">Pages</a>
                                <ul class="dropdown-nav">
                                    <li><a href="about.php">About</a></li>
                                    <li><a href="my-account.php">My Account</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-show"><a href="#">Category</a>
                                <ul class="mega-menu-wrap dropdown-nav">
                                    <li class="mega-menu-item"><a href="shop-left-full-wide.php"
                                                                  class="mega-item-title">Shirt</a>
                                        <ul>
                                            <li><a href="shop.php">Tops & Tees</a></li>
                                            <li><a href="shop.php">Polo Short Sleeve</a></li>
                                            <li><a href="shop.php">Graphic T-Shirts</a></li>
                                            <li><a href="shop.php">Jackets & Coats</a></li>
                                            <li><a href="shop.php">Fashion Jackets</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li ><a href="single-blog.php">Blog</a>
                            </li>
                            <li><a href="contact.php">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Navigation Area End -->

            <!-- Header Right Meta Start -->
            <div class="col-6 col-lg-2 m-auto">
                <div class="header-right-meta text-right">
                    <ul>
                        <li><a href="#" class="modal-active"><i class="fa fa-search"></i></a></li>
                        <li class="settings"><a href="#"><i class="fa fa-cog"></i></a>
                            <div class="site-settings d-block d-sm-flex">
                                <dl class="currency">
                                    <dt>Currency</dt>
                                    <dd class="current"><a href="#">USD</a></dd>
                                    <dd><a href="#">AUD</a></dd>
                                    <dd><a href="#">CAD</a></dd>
                                    <dd><a href="#">BDT</a></dd>
                                </dl>

                                <dl class="my-account">
                                    <dt>My Account</dt>
                                    <dd><a href="#">Dashboard</a></dd>
                                    <dd><a href="#">Profile</a></dd>
                                    <dd><a href="#">Sign</a></dd>
                                </dl>

                                <dl class="language">
                                    <dt>Language</dt>
                                    <dd class="current"><a href="#">English (US)</a></dd>
                                    <dd><a href="#">English (UK)</a></dd>
                                    <dd><a href="#">Chinees</a></dd>
                                    <dd><a href="#">Bengali</a></dd>
                                    <dd><a href="#">Hindi</a></dd>
                                    <dd><a href="#">Japanees</a></dd>
                                </dl>
                            </div>
                        </li>
                        <li class="shop-cart"><a href="#"><i class="fa fa-shopping-bag"></i> <span
                                class="count">3</span></a>
                            <div class="mini-cart">
                                <div class="mini-cart-body">
                                    <div class="single-cart-item d-flex">
                                        <figure class="product-thumb">
                                            <a href="#"><img class="img-fluid" src="assets/img/product-1.jpg"
                                                             alt="Products"/></a>
                                        </figure>

                                        <div class="product-details">
                                            <h2><a href="#">Sprite Yoga Companion</a></h2>
                                            <div class="cal d-flex align-items-center">
                                                <span class="quantity">3</span>
                                                <span class="multiplication">X</span>
                                                <span class="price">$77.00</span>
                                            </div>
                                        </div>
                                        <a href="#" class="remove-icon"><i class="fa fa-trash-o"></i></a>
                                    </div>
                                    <div class="single-cart-item d-flex">
                                        <figure class="product-thumb">
                                            <a href="#"><img class="img-fluid" src="assets/img/product-2.jpg"
                                                             alt="Products"/></a>
                                        </figure>
                                        <div class="product-details">
                                            <h2><a href="#">Yoga Companion Kit</a></h2>
                                            <div class="cal d-flex align-items-center">
                                                <span class="quantity">2</span>
                                                <span class="multiplication">X</span>
                                                <span class="price">$6.00</span>
                                            </div>
                                        </div>
                                        <a href="#" class="remove-icon"><i class="fa fa-trash-o"></i></a>
                                    </div>
                                    <div class="single-cart-item d-flex">
                                        <figure class="product-thumb">
                                            <a href="#"><img class="img-fluid" src="assets/img/product-3.jpg"
                                                             alt="Products"/></a>
                                        </figure>
                                        <div class="product-details">
                                            <h2><a href="#">Sprite Yoga Companion Kit</a></h2>
                                            <div class="cal d-flex align-items-center">
                                                <span class="quantity">1</span>
                                                <span class="multiplication">X</span>
                                                <span class="price">$116.00</span>
                                            </div>
                                        </div>
                                        <a href="#" class="remove-icon"><i class="fa fa-trash-o"></i></a>
                                    </div>
                                </div>
                                <div class="mini-cart-footer">
                                    <a href="checkout.php" class="btn-add-to-cart">Checkout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Header Right Meta End -->
        </div>
    </div>
</header>
<!--== Header Area End ==-->

<!--== Search Box Area Start ==-->
<div class="body-popup-modal-area">
    <span class="modal-close"><img src="assets/img/cancel.png" alt="Close" class="img-fluid"/></span>
    <div class="modal-container d-flex">
        <div class="search-box-area">
            <div class="search-box-form">
                <form action="#" method="post">
                    <input type="search" placeholder="type keyword and hit enter"/>
                    <button class="btn" type="button"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--== Search Box Area End ==-->

<!--== Page Title Area Start ==-->
<div id="page-title-area">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="page-title-content">
                    <h1>Shop</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php" class="active">Shop</a></li>
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
                        <h2 class="sidebar-title">Shop By</h2>
                        <div class="sidebar-body">
                            <div class="shopping-option">
                                <h3>Shopping Options</h3>
                                <div class="shopping-option-item">
                                    <h4>Color</h4>
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

                                        <li class="color-item red">
                                            <div class="color-hvr">
                                                <span class="color-fill"></span>
                                                <span class="color-name">red</span>
                                            </div>
                                        </li>

                                        <li class="color-item yellow">
                                            <div class="color-hvr">
                                                <span class="color-fill"></span>
                                                <span class="color-name">yellow</span>
                                            </div>
                                        </li>

                                        <li class="color-item orange">
                                            <div class="color-hvr">
                                                <span class="color-fill"></span>
                                                <span class="color-name">Orange</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="shopping-option-item">
                                    <h4>MANUFACTURER</h4>
                                    <ul class="sidebar-list">
                                        <?php foreach ($categorylist as $category): ?>
                                            <li><a href="<?php echo "shopfilter.php?category=".$category['categoryproduct_id']; ?>"  data-category="<?php echo $category['categoryproduct_id'];  ?>" ><?php echo $category['name']; ?></a></li>
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
                                    <h4>Price</h4>
                                    <div class="price-filter">
                                        <input type="text" id="minPrice" placeholder="Min price" class="price-input" value="<?php echo $minprice; ?>">
                                        <span class="price-separator">-</span>
                                        <input type="text" id="maxPrice" placeholder="Max price" class="price-input" value="<?php echo $maxprice; ?>">
                                        <button id="filterBtn" class="filter-btn">Filter</button>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single Sidebar Item End -->

                    <!-- Single Sidebar Item Start -->
                    <div class="single-sidebar-wrap">
                        <h2 class="sidebar-title">My Wish List</h2>
                        <div class="sidebar-body">
                            <div class="small-product-list">
                                <div class="single-product-item">
                                    <figure class="product-thumb">
                                        <a href="#"><img class="mr-2 img-fluid" src="assets/img/product-2.jpg"
                                                         alt="Products"/></a>
                                    </figure>
                                    <div class="product-details">
                                        <h2><a href="single-product.php">Sprite Yoga Companion Kit</a></h2>
                                        <span class="price">$6.00</span>

                                    </div>
                                </div>

                                <div class="single-product-item">
                                    <figure class="product-thumb">
                                        <a href="single-product.php"><img class="mr-2 img-fluid"
                                                                           src="assets/img/product-3.jpg"
                                                                           alt="Products"/></a>
                                    </figure>
                                    <div class="product-details">
                                        <h2><a href="single-product.php">Set of Sprite Yoga Straps</a></h2>
                                        <span class="price">$88.00</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single Sidebar Item End -->

                    <!-- Single Sidebar Item Start -->
                    <div class="single-sidebar-wrap">
                        <h2 class="sidebar-title">MOSTVIEWED PRODUCTS</h2>
                        <div class="sidebar-body">
                            <div class="small-product-list">
                                <div class="single-product-item">
                                    <figure class="product-thumb">
                                        <a href="single-product.php"><img class="mr-2 img-fluid"
                                                                           src="assets/img/product-1.jpg"
                                                                           alt="Products"/></a>
                                    </figure>
                                    <div class="product-details">
                                        <h2><a href="single-product.php">Beginner's Yoga</a></h2>
                                        <span class="price">$50.00</span>
                                    </div>
                                </div>

                                <div class="single-product-item">
                                    <figure class="product-thumb">
                                        <a href="single-product.php"><img class="mr-2 img-fluid"
                                                                           src="assets/img/product-2.jpg"
                                                                           alt="Products"/></a>
                                    </figure>
                                    <div class="product-details">
                                        <h2><a href="single-product.php">Sprite Yoga Companion Kit</a></h2>
                                        <span class="price">$6.00</span>
                                    </div>
                                </div>

                                <div class="single-product-item">
                                    <figure class="product-thumb">
                                        <a href="single-product.php"><img class="mr-2 img-fluid"
                                                                           src="assets/img/product-3.jpg"
                                                                           alt="Products"/></a>
                                    </figure>
                                    <div class="product-details">
                                        <h2><a href="single-product.php">Set of Sprite Yoga Straps</a></h2>
                                        <span class="price">$88.00</span>
                                    </div>
                                </div>
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
                            <span class="show-items">Items 1 - 9 of 17</span>
                        </div>

                        <div class="product-sort_by d-flex align-items-center mt-3 mt-md-0">
                            <label for="sort">Sort By:</label>
                            <select name="sort" id="sort">
                                <option value="Position">Relevance</option>
                                <option value="Name Ascen">Name, A to Z</option>
                                <option value="Name Decen">Name, Z to A</option>
                                <option value="Price Ascen">Price low to heigh</option>
                                <option value="Price Decen">Price heigh to low</option>
                            </select>
                        </div>
                    </div>
        
                    <div class="shop-page-products-wrap">
                        <div class="products-wrapper">
                            <div class="row">
                            <?php foreach ($products as $product): ?>
                                <div class="col-lg-4 col-sm-6" id="<?php echo $product['id']  ?>">
                                    <!-- Single Product Item -->
                                    <div class="single-product-item text-center">
                                        <figure class="product-thumb">
                                            <a href="<?php echo "single-product.php?productid=".$product['id']  ?>"><img src="<?php
                                    $strfirt = './admin-page';  
                                    echo  $strfirt.substr($product['image'], 2); ?>"
                                                                               alt="<?php echo $product['name']; ?>" class="img-fluid"></a>
                                        </figure>

                                        <div class="product-details product-filter-category" data-category = "<?php echo $product['categoryproduct_id']; ?>">
                                            <h2><a href="<?php echo "single-product.php?productid=".$product['id']  ?>"><?php echo $product['name']; ?></a></h2>
                                            <div class="rating">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-half"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>
                                            <span class="price">$<?php echo $product['price']; ?></span>
                                            <p class="products-desc">Ideal for cold-weathered training worked lorem
                                                outdoors, the Chaz Hoodie promises superior warmth with every wear.
                                                Thick material blocks out the wind as ribbed cuffs and bottom band seal
                                                in body heat.</p>
                                            <a href="<?php echo "single-product.php?productid=".$product['id']  ?>" class="btn btn-add-to-cart">+ Add to Cart</a>
                                            <a href="<?php echo "single-product.php?productid=".$product['id']  ?>" class="btn btn-add-to-cart btn-whislist">+
                                                Wishlist</a>
                                            <a href="<?php echo "single-product.php?productid=".$product['id']  ?>" class="btn btn-add-to-cart btn-compare">+
                                                Compare</a>
                                        </div>

                                        <div class="product-meta">
                                            <button type="button" data-toggle="modal" data-target="#quickView">
                                    <span data-toggle="tooltip" data-placement="left" title="Quick View"><i
                                            class="fa fa-compress"></i></span>
                                            </button>
                                            <a href="#" data-toggle="tooltip" data-placement="left"
                                               title="Add to Wishlist"><i
                                                    class="fa fa-heart-o"></i></a>
                                            <a href="#" data-toggle="tooltip" data-placement="left" title="Compare"><i
                                                    class="fa fa-tags"></i></a>
                                        </div>
                                        <span class="product-bedge">New</span>
                                    </div>
                                    <!-- Single Product Item -->
                                </div>
                            <?php endforeach; ?>


                                
                            </div>
                        </div>
                    </div>


                    <div class="products-settings-option d-block d-md-flex">
                        <nav class="page-pagination">
                            <div id="pagination-container">
                            </div>
                        </nav>
                                    <script>
                                // Số sản phẩm trên mỗi trang
                            function setupPagination() {
                                var productsPerPage = 9;
                                var totalProducts = document.querySelectorAll('.col-lg-4.col-sm-6').length;
                                var totalPages = Math.ceil(totalProducts / productsPerPage);
                                var currentPage = 1;

                                function createPaginationButtons() {
                                var paginationContainer = document.getElementById('pagination-container');
                                var paginationList = document.createElement('ul');
                                paginationList.classList.add('pagination');

                                var prevButton = document.createElement('li');
                                var prevLink = document.createElement('a');
                                prevLink.href = '#';
                                prevLink.id = 'prevPage';
                                prevLink.setAttribute('aria-label', 'Previous');
                                prevLink.innerHTML = '&laquo;';
                                prevButton.appendChild(prevLink);
                                paginationList.appendChild(prevButton);

                                for (var i = 1; i <= totalPages; i++) {
                                    var pageButton = document.createElement('li');
                                    var pageLink = document.createElement('a');
                                    pageLink.href = '#';
                                    pageLink.id = 'page' + i;
                                    pageLink.textContent = i;
                                    pageButton.appendChild(pageLink);
                                    paginationList.appendChild(pageButton);
                                }

                                var nextButton = document.createElement('li');
                                var nextLink = document.createElement('a');
                                nextLink.href = '#';
                                nextLink.id = 'nextPage';
                                nextLink.setAttribute('aria-label', 'Next');
                                nextLink.innerHTML = '&raquo;';
                                nextButton.appendChild(nextLink);
                                paginationList.appendChild(nextButton);

                                paginationContainer.appendChild(paginationList);

                                var pageLinks = document.querySelectorAll('.pagination li a');
                                pageLinks.forEach(function(pageLink) {
                                    pageLink.addEventListener('click', function(event) {
                                        event.preventDefault();
                                        var targetPage = event.target.id;
                                        if (targetPage === 'prevPage') {
                                            goToPage(currentPage - 1);
                                        } else if (targetPage === 'nextPage') {
                                            goToPage(currentPage + 1);
                                        } else {
                                            var pageNumber = parseInt(event.target.textContent);
                                            goToPage(pageNumber);
                                        }
                                    });
                                });
                                }
                                function updateItemsInfo(startIndex, endIndex, totalProducts) {
                                    var startItem = startIndex + 1;
                                    var endItem = Math.min(endIndex, totalProducts);
                                    var itemsInfo = document.querySelector('.show-items');
                                    itemsInfo.textContent = "Items " + startItem + " - " + endItem + " of " + totalProducts;
                                }

                                function goToPage(pageNumber) {
                                currentPage = pageNumber;
                                hideAllProducts();
                                var startIndex = (pageNumber - 1) * productsPerPage;
                                var endIndex = Math.min(startIndex + productsPerPage, totalProducts);
                                showProducts(startIndex, endIndex);
                                updateItemsInfo(startIndex, endIndex, totalProducts);   
                                }

                                function hideAllProducts() {
                                var products = document.querySelectorAll('.col-lg-4.col-sm-6');
                                products.forEach(function(product) {
                                    product.style.display = 'none';
                                });
                                }

                                function showProducts(startIndex, endIndex) {
                                var products = document.querySelectorAll('.col-lg-4.col-sm-6');
                                for (var i = startIndex; i < endIndex; i++) {
                                    products[i].style.display = 'block';
                                }
                                }

                                createPaginationButtons();
                                goToPage(1);
                            };

                            setupPagination();

                           
                            // THẺ FILTER
                            document.addEventListener("DOMContentLoaded", function() {
                            var productCategories = document.querySelectorAll(".product-filter-category");
                            var liItems = document.querySelectorAll(".sidebar-list li a");

                            productCategories.forEach(function(productCategory) {
                                var category = productCategory.getAttribute("data-category");

                                liItems.forEach(function(li) {
                                    var liCategory = li.getAttribute("data-category");

                                    if (category === liCategory) {
                                        li.classList.add("selected-category");
                                    }
                                });
                            });

                            document.getElementById('filterBtn').addEventListener('click', function() {
                                var minPrice = parseFloat(document.getElementById('minPrice').value);
                                var maxPrice = parseFloat(document.getElementById('maxPrice').value);
                                
                                // Kiểm tra nếu minPrice hoặc maxPrice không hợp lệ thì không điều hướng
                                if (isNaN(minPrice) || isNaN(maxPrice) || minPrice < 0 || maxPrice < 0 || minPrice > maxPrice) {
                                    alert("Vui lòng nhập giá trị minprice và maxprice hợp lệ.");
                                    return;
                                }

                                // Lấy category từ phần tử đầu tiên có class product-filter-category
                                var category = document.querySelector(".product-filter-category").getAttribute("data-category");

                                // Đường dẫn tới trang shopfilter với các tham số minprice, maxprice và category
                                var url = "shopfilter.php?minprice=" + minPrice + "&maxprice=" + maxPrice + "&category=" + category;

                                // Điều hướng đến trang mới với các tham số đã lọc
                                window.location.href = url;
                            });
                        });



                            // END FILTER



                                </script>

                        <div class="product-per-page d-flex align-items-center mt-3 mt-md-0">
                            <label for="show-per-page">Show Per Page</label>
                            <select name="sort" id="show-per-page">
                                <option value="9">9</option>
                                <option value="15">15</option>
                                <option value="21">21</option>
                                <option value="6">27</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shop Page Content End -->
        </div>
    </div>
</div>
<!--== Page Content Wrapper End ==-->

<!-- Footer Area Start -->
<footer id="footer-area">
    <!-- Footer Call to Action Start -->
    <div class="footer-callto-action">
        <div class="ruby-container">
            <div class="callto-action-wrapper">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <!-- Single Call-to Action Start -->
                        <div class="single-callto-action d-flex">
                            <figure class="callto-thumb">
                                <img src="assets/img/air-plane.png" alt="WorldWide Shipping"/>
                            </figure>
                            <div class="callto-info">
                                <h2>Free Shipping Worldwide</h2>
                                <p>On order over $150 - 7 days a week</p>
                            </div>
                        </div>
                        <!-- Single Call-to Action End -->
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <!-- Single Call-to Action Start -->
                        <div class="single-callto-action d-flex">
                            <figure class="callto-thumb">
                                <img src="assets/img/support.png" alt="Support"/>
                            </figure>
                            <div class="callto-info">
                                <h2>24/7 CUSTOMER SERVICE</h2>
                                <p>Call us 24/7 at 000 - 123 - 456k</p>
                            </div>
                        </div>
                        <!-- Single Call-to Action End -->
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <!-- Single Call-to Action Start -->
                        <div class="single-callto-action d-flex">
                            <figure class="callto-thumb">
                                <img src="assets/img/money-back.png" alt="Money Back"/>
                            </figure>
                            <div class="callto-info">
                                <h2>MONEY BACK Guarantee!</h2>
                                <p>Send within 30 days</p>
                            </div>
                        </div>
                        <!-- Single Call-to Action End -->
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <!-- Single Call-to Action Start -->
                        <div class="single-callto-action d-flex">
                            <figure class="callto-thumb">
                                <img src="assets/img/cog.png" alt="Guide"/>
                            </figure>
                            <div class="callto-info">
                                <h2>SHOPPING GUIDE</h2>
                                <p>Quis Eum Iure Reprehenderit</p>
                            </div>
                        </div>
                        <!-- Single Call-to Action End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Call to Action End -->

    <!-- Footer Follow Up Area Start -->
    <div class="footer-followup-area">
        <div class="ruby-container">
            <div class="followup-wrapper">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="follow-content-wrap">
                            <a href="index.php" class="logo"><img src="assets/img/logo.png" alt="logo"/></a>
                            <p>Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum</p>

                            <div class="footer-social-icons">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-flickr"></i></a>
                            </div>

                            <a href="#"><img src="assets/img/payment.png" alt="Payment Method"/></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Follow Up Area End -->

    <!-- Footer Image Gallery Area Start -->
    <div class="footer-image-gallery">
        <div class="ruby-container">
            <div class="image-gallery-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="imgage-gallery-carousel owl-carousel">
                            <div class="gallery-item">
                                <a href="#"><img src="assets/img/gallery-img-1.jpg" alt="Gallery"/></a>
                            </div>
                            <div class="gallery-item">
                                <a href="#"><img src="assets/img/gallery-img-2.jpg" alt="Gallery"/></a>
                            </div>
                            <div class="gallery-item">
                                <a href="#"><img src="assets/img/gallery-img-3.jpg" alt="Gallery"/></a>
                            </div>
                            <div class="gallery-item">
                                <a href="#"><img src="assets/img/gallery-img-4.jpg" alt="Gallery"/></a>
                            </div>
                            <div class="gallery-item">
                                <a href="#"><img src="assets/img/gallery-img-3.jpg" alt="Gallery"/></a>
                            </div>
                            <div class="gallery-item">
                                <a href="#"><img src="assets/img/gallery-img-2.jpg" alt="Gallery"/></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Image Gallery Area End -->

    <!-- Copyright Area Start -->
    <div class="copyright-area">
        <div class="ruby-container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="copyright-text">
                        <p><a target="_blank" href="https://www.templateshub.net">Templates Hub</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright Area End -->

</footer>
<!-- Footer Area End -->

<!-- Start All Modal Content -->
<!--== Product Quick View Modal Area Wrap ==-->
<div class="modal fade" id="quickView" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img src="assets/img/cancel.png" alt="Close" class="img-fluid"/></span>
            </button>
            <div class="modal-body">
                <div class="quick-view-content single-product-page-content">
                    <div class="row">
                        <!-- Product Thumbnail Start -->
                        <div class="col-lg-5 col-md-6">
                            <div class="product-thumbnail-wrap">
                                <div class="product-thumb-carousel owl-carousel">
                                    <div class="single-thumb-item">
                                        <a href="single-product.php"><img class="img-fluid"
                                                                           src="assets/img/single-pro-thumb.jpg"
                                                                           alt="Product"/></a>
                                    </div>

                                    <div class="single-thumb-item">
                                        <a href="single-product.php"><img class="img-fluid"
                                                                           src="assets/img/single-pro-thumb-2.jpg"
                                                                           alt="Product"/></a>
                                    </div>

                                    <div class="single-thumb-item">
                                        <a href="single-product.php"><img class="img-fluid"
                                                                           src="assets/img/single-pro-thumb-3.jpg"
                                                                           alt="Product"/></a>
                                    </div>

                                    <div class="single-thumb-item">
                                        <a href="single-product.php"><img class="img-fluid"
                                                                           src="assets/img/single-pro-thumb-4.jpg"
                                                                           alt="Product"/></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product Thumbnail End -->

                        <!-- Product Details Start -->
                        <div class="col-lg-7 col-md-6 mt-5 mt-md-0">
                            <div class="product-details">
                                <h2><a href="single-product.php">Crown Summit Backpack</a></h2>

                                <div class="rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-half"></i>
                                    <i class="fa fa-star-o"></i>
                                </div>

                                <span class="price">$52.00</span>

                                <div class="product-info-stock-sku">
                                    <span class="product-stock-status">In Stock</span>
                                    <span class="product-sku-status ml-5"><strong>SKU</strong> MH03</span>
                                </div>

                                <p class="products-desc">Ideal for cold-weathered training worked lorem ipsum outdoors,
                                    the Chaz Hoodie promises superior warmth with every wear. Thick material blocks out
                                    the wind as ribbed cuffs and bottom band seal in body heat Lorem ipsum dolor sit
                                    amet, consectetur adipisicing elit. Enim, reprehenderit.</p>
                                <div class="shopping-option-item">
                                    <h4>Color</h4>
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
                                </div>

                                <div class="product-quantity d-flex align-items-center">
                                    <div class="quantity-field">
                                        <label for="qty">Qty</label>
                                        <input type="number" id="qty" min="1" max="100" value="1"/>
                                    </div>

                                    <a href="cart.php" class="btn btn-add-to-cart">Add to Cart</a>
                                </div>
                            </div>
                        </div>
                        <!-- Product Details End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== Product Quick View Modal Area End ==-->
<!-- End All Modal Content -->

<!-- Scroll to Top Start -->
<a href="#" class="scrolltotop"><i class="fa fa-angle-up"></i></a>
<!-- Scroll to Top End -->


<!--=======================Javascript============================-->
<!--=== Jquery Min Js ===-->
<script src="admin-page/view/assets/js/jquery-3.6.0.min.js"></script>
<!--=== Jquery Migrate Min Js ===-->
<script src="assets/js/vendor/jquery-migrate-1.4.1.min.js"></script>
<!--=== Popper Min Js ===-->
<script src="assets/js/vendor/popper.min.js"></script>
<!--=== Bootstrap Min Js ===-->
<script src="assets/js/vendor/bootstrap.min.js"></script>
<!--=== Plugins Min Js ===-->
<script src="assets/js/plugins.js"></script>

<!--=== Active Js ===-->
<script src="assets/js/active.js"></script>
</body>

</html>