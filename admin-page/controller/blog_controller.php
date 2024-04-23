<?php
class blog_controller {
    private $conn;
   

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function unsetimageBlog($p_id){
        $old_image = null;
        $stmt = $this->conn->prepare("SELECT image FROM blog WHERE id= ?");
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
                if (isset($_POST['blog_id'])) {
                    $blog_id = $_POST['blog_id'];
                    require '../config/database.php';
                    require '../model/blog_model.php';
                    $Blog = new BlogModel($conn);
                   $BlogDelete_image = new blog_controller($conn);
                   $BlogDelete_image ->unsetimageBlog($blog_id);
                    ob_clean(); // Xóa dữ liệu đầu ra hiện tại
                    $deleteBlog = $Blog->deleteBlog($blog_id);
                    if ($deleteBlog) {
                        echo "success";
                        exit();
                    } else {
                        echo "error";
                    }
        }
    }
    
    
?>
