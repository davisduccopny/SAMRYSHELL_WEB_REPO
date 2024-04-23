<?php
class GeneralController {
    private $conn;
   

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function unsetimagecustomer($p_id){
        $old_image = null;
        $stmt = $this->conn->prepare("SELECT image FROM general_setting WHERE id= ?");
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $stmt->bind_result($old_image);
        $stmt->fetch();
        $stmt->close();
        if ($old_image) {
            $source_path = $_SERVER['DOCUMENT_ROOT'].'/admin-page/'.str_replace("..", "", $old_image);
            if (unlink($source_path)) {
                echo 'File đã được di chuyển thành công.';
            } else {
                echo 'Đã xảy ra lỗi trong quá trình di chuyển file.';
            }   

        } else {
            echo 'error' . $p_id;
        }

    }
            
            
                    
}
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['customer_id'])) {
                    $customer_id = $_POST['customer_id'];
                    require '../config/database.php';
                    require '../model/customer_model.php';
                    $customerModel = new CustomerModel($conn);
                   $customerDelete_image = new customerController($conn);
                   $customerDelete_image ->unsetimagecustomer($customer_id);
                    ob_clean(); // Xóa dữ liệu đầu ra hiện tại
                    $deletecustomer = $customerModel->deleteCustomer($customer_id);
                    if ($deletecustomer) {
                        echo "success";
                        exit();
                    } else {
                        echo "error";
                    }
        }
    }
    
    
?>
