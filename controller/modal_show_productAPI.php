<?php 
if ($_GET['action'] === 'getProductDetailsbyid') {
require $_SERVER['DOCUMENT_ROOT'].'/admin-page/config/database.php';
require $_SERVER['DOCUMENT_ROOT'].'/admin-page/model/product_model.php';
$productModel = new ProductModel($conn);
$product_id = $_GET['product_id'];
$getdetail = $productModel->getProduct($product_id);

if ($getdetail) {
    // Nếu có dữ liệu, xuất ra dữ liệu JSON
    
    header('Content-Type: application/json');
    echo json_encode($getdetail);
} else {
    // Nếu không có dữ liệu, xuất ra thông báo lỗi
    header("HTTP/1.1 404 Not Found");
    echo json_encode(array("error" => "Product not found"));
}
}
else {
echo 'api not found';
}
mysqli_close($conn);    

    

?>