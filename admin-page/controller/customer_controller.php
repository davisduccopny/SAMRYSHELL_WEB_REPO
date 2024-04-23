<?php
class customerController {
    private $conn;
   

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function unsetimagecustomer($p_id){
        $old_image = null;
        $stmt = $this->conn->prepare("SELECT image FROM customer WHERE id= ?");
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


    
    
?>
