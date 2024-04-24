<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    if (isset($data['paymentMethod']) && $data['paymentMethod'] && !isset($data['submit'])) {
        // Xử lý khi "paymentMethod" được gửi từ trình duyệt

        $email = $data["email"];
        $status = $data["status"];
        $date = $data["date"];

        // Chuyển đổi các trường shipping và tax sang kiểu DECIMAL(10, 2)
        $shipping = isset($data["shipping"]) ? floatval($data["shipping"]) : null;
        $tax = isset($data["tax"]) ? floatval($data["tax"]) : null;

        $discount = $data["discount"];
        $paymentMethod = $data["paymentMethod"];
        $description = $data["description"];
        $items = $data["items"]; // Mảng chứa thông tin sản phẩm và số lượng

        require "../model/sale_model.php";
        $saleModel = new SaleModel($conn);
        $addsale = $saleModel->addSale($email, $status, $paymentMethod, $items, $discount, $tax, $date, $shipping, $description);
        if ($addsale) {
            $response["message"] = "success";
        } else {
            $response["message"] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
        }
        echo json_encode($response);
    }
    else if (isset($_POST['delete'])){
        $saleID = $_POST['sale_id'];
        require "../model/sale_model.php";
        $saleModel = new SaleModel($conn);
        $deletesale = $saleModel->deleteSale($saleID);
        if ($deletesale) {
            $response = "success";
        } else {
            $responses = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
        }
        echo $response;
        exit();
    }
    
    else {
        // Xử lý khi "submit" được gửi từ trình duyệt
        $email = $data["email"];
        $status = $data["status"];
        $shipping = isset($data["shipping"]) ? floatval($data["shipping"]) : null;
        $tax = isset($data["tax"]) ? floatval($data["tax"]) : null;
        $discount = $data["discount"];
        $description = $data["description"];
        $items = $data["items"];
        $saleID = $data['sale_id'];
        require "../model/sale_model.php";
        $saleModel = new SaleModel($conn);
        $updatesale = $saleModel->updateSale($email, $status, $items, $discount, $tax, $shipping, $description, $saleID);
        if ($updatesale) {
            $response["message"] = "success";
        } else {
            $response["message"] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
        }
        echo json_encode($response);
    }
    $conn->close();
    
}

?>