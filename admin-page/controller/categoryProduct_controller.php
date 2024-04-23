<?php
class CategoryProductController {
    private $conn;
   

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function unsetimagecategoryProduct($p_id){
        $old_image = null;
        $stmt = $this->conn->prepare("SELECT image FROM categoryproduct WHERE categoryproduct_id= ?");
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
                if (isset($_POST['categoryproduct_id'])) {
                    $categoryproduct_id = $_POST['categoryproduct_id'];
                    require '../config/database.php';
                    require '../model/cagegoryproduct_model.php';
                    $CategoryModel = new CategoryProductModel($conn);
                   $categoryproductDelete_image = new CategoryProductController($conn);
                   $categoryproductDelete_image ->unsetimagecategoryProduct($categoryproduct_id);
                    ob_clean(); // Xóa dữ liệu đầu ra hiện tại
                    $deletecategory = $CategoryModel->deleteCategoryProduct($categoryproduct_id);
                    if ($deletecategory) {
                        echo "success";
                        exit();
                    } else {
                        echo "error";
                    }
        }
    }
    
    
?>
