<?php require_once('./main/role_manager.php'); ?>
<?php 
require '../model/product_model.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

$productModel2 = new ProductModel($conn);
$productInfo= $productModel2->getProduct($id);
}
else {
    echo '<script>alert("Không tìm thấy sản phẩm");</script>';
    header('Location: productlist.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php require_once('./main/head.php'); ?>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/animate.css">

    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="assets/plugins/owlcarousel/owl.carousel.min.css">

    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../ckeditor/ckeditor.js"></script>
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">


    <?php require_once('./main/header.php'); ?>



    <?php require_once('./main/sidebar.php'); ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Thông tin chi tiết</h4>
                        <h6>Full details of a product</h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="bar-code-view">
                                    <img src="assets/img/barcode1.png" alt="barcode">
                                    <a class="printimg">
                                        <img src="assets/img/icons/printer.svg" alt="print">
                                    </a>
                                </div>
                                <div class="productdetails">
                                    <ul class="product-bar">
                                        <li>
                                            <h4>Tên</h4>
                                            <h6><?php echo $productInfo['name']?></h6>
                                        </li>
                                        <li>
                                            <h4>Danh mục</h4>
                                            <h6><?php echo $productInfo['category_name']?></h6>
                                        </li>
                                        <li>
                                            <h4>Danh mục phụ</h4>
                                            <h6><?php echo $productInfo['categorysub_name']?></h6>
                                        </li>
                                        <li>
                                            <h4>Ngày</h4>
                                            <h6><?php echo $productInfo['created_at']?></h6>
                                        </li>
                                        <li>
                                            <h4>Unit</h4>
                                            <h6><?php echo $productInfo['unit']?></h6>
                                        </li>
                                        <li>
                                            <h4>Mã sản phẩm</h4>
                                            <h6><?php echo $productInfo['sku']?></h6>
                                        </li>
                                        <li>
                                            <h4>Số lượng tối thiểu</h4>
                                            <h6><?php echo $productInfo['minium_quantity']?></h6>
                                        </li>
                                        <li>
                                            <h4>Tồn kho</h4>
                                            <h6><?php echo $productInfo['quantity']?></h6>
                                        </li>
                                        <li>
                                            <h4>Thuế</h4>
                                            <h6><?php echo $productInfo['tax']?></h6>
                                        </li>
                                        <li>
                                            <h4>Giảm giá</h4>
                                            <h6><?php echo $productInfo['discount']?></h6>
                                        </li>
                                        <li>
                                            <h4>Giá sản phẩm</h4>
                                            <h6><?php echo $productInfo['price']?></h6>
                                        </li>
                                        <li>
                                            <h4>Trạng thái</h4>
                                            <h6><?php echo $productInfo['status']?></h6>
                                        </li>
                                        <li>
                                            <h4>Loại sản phẩm</h4>
                                            <h6><?php echo $productInfo['type_product']?></h6>
                                        </li>
                                        <li>
                                            <h4>Mô tả ngắn</h4>
                                            
                                        </li>
                                        <li>
                                        <textarea id="short_description" disabled><?php echo $productInfo['short_description']?></textarea>
                                        </li>
                                        <li>
                                            <h4>Mô tả đầy đủ</h4>
                                            
                                        </li>
                                        <li>
                                        <textarea id="description" disabled><?php echo $productInfo['description']?></textarea>
                                        </li>
                                      
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="slider-product-details">
                                    <div class="owl-carousel owl-theme product-slide">
                                            <?php 
                                                if (!empty($productInfo['images'])) {
                                                    foreach ($productInfo['images'] as $image) {
                                                        $formattedSize = 'null';
                                                        $fileName = basename($image);
                                                        echo '<div class="slider-product">
                                                        <img src="'.$image.'" alt="img">
                                                        <h4>'. $fileName.'</h4>
                                                        <h6>'.$formattedSize .'</h6>
                                                    </div>';
                                                    }
                                                } else {
                                                    echo 'No images available<br>';
                                                }
                                                ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    CKEDITOR.replace('description', {
    filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
});
CKEDITOR.replace('short_description', {
    filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
});
        </script>                                           
    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/plugins/owlcarousel/owl.carousel.min.js"></script>

    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>