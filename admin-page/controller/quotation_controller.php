<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);

    if (isset($data['update']) && $data['update']) {
        $quotation_id= $data['quotation_id'];
        $email = $data["email"];
        $status = $data["status"];
        $tax = isset($data["tax"]) ? floatval($data["tax"]) : null;
        $discount = isset($data["discount"]) ? floatval($data["discount"]) : null;;
        $description = $data["description"];
        $date = $data["date"];
        $items = $data["items"];
        // $saleID = $data['sale_id'];
        require "../model/quotation_model.php";
        $quotationModel = new QuotationModel($conn);
        $updatequotation= $quotationModel->updateQuotation($quotation_id, $email, $status, $date, $tax, $discount,$description, $items);
        if ($updatequotation) {
            $response["message"] = "success";
        } else {
            $response["message"] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
        }
        echo json_encode($response);
    }
    else if (isset($_POST['delete']) && $_POST['delete']){
        $quotation_id = $_POST['quotation_id'];
       require '../model/quotation_model.php';
        $quotationModel = new QuotationModel($conn);
        $deletesquotation = $quotationModel->deleteQuotation($quotation_id);
        if ($deletesquotation) {
            $response = "success";
        } else {
            $responses = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
        }
        echo $response;
        exit();
    }
    
    else{
        // Xử lý khi "submit" được gửi từ trình duyệt
        $email = $data["email"];
        $status = $data["status"];
        $tax = isset($data["tax"]) ? floatval($data["tax"]) : null;
        $discount = isset($data["discount"]) ? floatval($data["discount"]) : null;;
        $description = $data["description"];
        $date = $data["date"];
        $items = $data["items"];
        // $saleID = $data['sale_id'];
        require "../model/quotation_model.php";
        $quotationModel = new QuotationModel($conn);
        $updatquotation= $quotationModel->addQuotation($email, $status, $date, $tax, $discount,$description, $items);
        if ($updatquotation) {
            $response["message"] = "success";
        } else {
            $response["message"] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
        }
        echo json_encode($response);
    }
    $conn->close();
    
}

?>