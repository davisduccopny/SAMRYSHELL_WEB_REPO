<?php
require_once('admin-page/config/database.php');
require_once('admin-page/model/cagegoryproduct_model.php');

$getProduct = new CategoryProductModel($conn);
$listProduct = $getProduct->getCategoryProduct($_GET['product_id']);

// Lấy thông tin định dạng ảnh
$rootimage = $_SERVER['DOCUMENT_ROOT'].'admin-page'.mb_substr($listProduct['image'],2);

if ($rootimage) {
    // Lấy kiểu định dạng (MIME type) của ảnh
    $imageType = $imageInfo['mime'];
    
    // Thiết lập kiểu định dạng cho header
    header("Content-Type: image/png");
    
    // Trả về dữ liệu ảnh
    echo $listProduct['image'];
} else {
    echo "Unable to get image information.";
}
?>
