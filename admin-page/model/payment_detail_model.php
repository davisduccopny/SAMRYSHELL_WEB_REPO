<?php
    class paymentDetailmodel  {
        private $conn;
   

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function addpaymentdetail ( $payment_name, $sale_id, $valueplus, $status, $note, $paymentconst){
        $query = "INSERT INTO payment_detail (payment_name, sale_id, valueplus, status, note,paymentconst) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sidsss", $payment_name, $sale_id, $valueplus, $status, $note, $paymentconst);

        return $stmt->execute();
    }
    public function updatepaymentdetail ($payment_name, $valueplus, $status, $note,$paymentconst, $id){
        $query = "UPDATE payment_detail SET payment_name = ?, valueplus = ?, status = ?, note = ?,paymentconst=? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sdsssi", $payment_name, $valueplus, $status, $note,$paymentconst, $id);

        return $stmt->execute();
    }
    public function getpaymentdetailbyId ($saleId){
        $query = "SELECT * FROM payment_detail WHERE sale_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $saleId);
        $stmt->execute();
        $result = $stmt->free_result();
        $paymentdetail = $result->fetch_assoc();
        $stmt->close();
        return $paymentdetail;

    }
    public function deletepaymentdetail($id){
        $query = "DELETE FROM payment_detail WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "../config/database.php";
        if (isset($_POST['sale_id'])) {
        $payment_name = $_POST["payment_name"];
        $sale_id = $_POST["sale_id"];
        $valueplus = $_POST["valueplus"];
        $status = $_POST["status"];
        $created_at = $_POST["created_at"];
        $note = $_POST["note"];
        $paymentconst = $_POST["paymentconstvalue"];
        $paymentDetailmodel = new paymentDetailmodel($conn);
        $result = $paymentDetailmodel->addpaymentdetail($payment_name, $sale_id, $valueplus, $status, $note, $paymentconst);
        if ($result) {
            $response["status"] = "success";
            $response["message"] = "payment detail added successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "failed to add payment detail";
        }
        echo json_encode($response);
    }
    if (isset($_POST['paymentdetail_id'])) {
        $payment_name = $_POST["payment_name"];
        $valueplus = $_POST["valueplus"];
        $status = $_POST["status"];
        $note = $_POST["note"];
        $id = $_POST["paymentdetail_id"];
        $paymentconst = $_POST["paymentconstvalue"];
        $paymentDetailmodel = new paymentDetailmodel($conn);
        $result = $paymentDetailmodel->updatepaymentdetail($payment_name, $valueplus, $status, $note,$paymentconst , $id);
        if ($result) {
            $response["status"] = "success";
            $response["message"] = "payment detail updated successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "failed to update payment detail";
        }
        echo json_encode($response);
    }
    if ( isset($_POST['paymentdetail_id_delete'])){
        $id = $_POST["paymentdetail_id_delete"];
        $paymentDetailmodel = new paymentDetailmodel($conn);
        $result = $paymentDetailmodel->deletepaymentdetail($id);
        // Xóa dữ liệu đầu ra hiện tại
        if ($result) {
            // ob_clean(); 
            echo "success";
            exit();
        } else {
            echo "error";
        }
    }
}
    
?>