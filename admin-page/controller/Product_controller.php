<?php
class ProductController {
    private $conn;
   

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addImageproductcontroller($fileTmpNames,$fileNames,$lastInsertedId) {
        $uploadDir = '../upload/';
        $filePaths = [];
        $fileName =null;
        if (is_array($fileTmpNames)) {
        for ($i = 0; $i < count($fileTmpNames); $i++) {
            $tmpName = $fileTmpNames[$i];
            if (is_uploaded_file($tmpName)) {
                $fileName = $fileNames[$i];
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = uniqid() . '.' . $fileExtension; // Tạo tên file mới bằng cách thêm prefix duy nhất
                $filePath = $uploadDir . $newFileName; // Đường dẫn mới đến thư mục upload
                $filePaths[] = $filePath;
                move_uploaded_file($tmpName, $filePath);
            } else {
                echo "Upload failed for file: $fileName"; // Thay bằng thông báo hoặc xử lý tương ứng
            }
                }
            } else {
                echo "<script>alert('Vui lòng chọn ảnh.');</script>";
                exit();
            }
            // Insert file information into the database
            $sql_image = "INSERT INTO image_product (image, product_id) VALUES (?, ?)";
            $stmt_image =$this->conn->prepare($sql_image);

            foreach ($filePaths as $filePath) {
                $stmt_image->bind_param("si", $filePath, $lastInsertedId);
                $stmt_image->execute();
            }
            $stmt_image->close();
    }  
    public function addImageproductTrashcontroller($lastInsertedId) {
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $uploadDir = '/admin-page/upload/';
        $trashDir = '/admin-page/trash/';
    
        // Truy vấn CSDL để lấy danh sách các ảnh cần di chuyển
        $sql = "SELECT image FROM image_product WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $lastInsertedId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = $row['image'];
                $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadFilePath = $documentRoot . $uploadDir . $imagePath;
                $trashFilePath = $documentRoot . $trashDir . $newFileName;
    
                if (rename($uploadFilePath, $trashFilePath)) {
                    $sql_image = "INSERT INTO trash_image_product (image, product_trash_id) VALUES (?, ?)";
                    $stmt_image = $this->conn->prepare($sql_image);
                    $stmt_image->bind_param("si", $trashFilePath, $lastInsertedId);
                    $stmt_image->execute();
                    $stmt_image->close();
                } else {
                    echo "Failed to move file: $imagePath";
                }
            }
        } else {
            echo "No images to move.";
        }
    
        $stmt->close();
    }
    
    
    public function updateImageproductcontroller($fileTmpNames, $fileNames, $productId) {
        $uploadDir = '../upload/';
        $filePaths = [];
        $fileName = null;
        
        // Xóa các bản ghi cũ liên quan đến sản phẩm
        $sql_delete = "DELETE FROM image_product WHERE product_id = ?";
        $stmt_delete = $this->conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $productId);
        $stmt_delete->execute();
        $stmt_delete->close();
        
        if (is_array($fileTmpNames)) {
            for ($i = 0; $i < count($fileTmpNames); $i++) {
                $tmpName = $fileTmpNames[$i];
    
                if (is_uploaded_file($tmpName)) {
                    $fileName = $fileNames[$i];
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $filePath = $uploadDir . $newFileName;
                    move_uploaded_file($tmpName, $filePath);
                    $filePaths[$i] = $filePath;
                } else {
                    echo "Upload failed for file: $fileName";
                }
            }
        } else {
            echo "<script>alert('Vui lòng chọn ảnh.');</script>";
            exit();
        }
    
        // Thêm các bản ghi mới vào CSDL
        $sql_image = "INSERT INTO image_product (image, product_id) VALUES (?, ?)";
        $stmt_image = $this->conn->prepare($sql_image);
    
        for ($i = 0; $i < count($filePaths); $i++) {
            $filePath = $filePaths[$i];
            $stmt_image->bind_param("si", $filePath, $productId);
            $stmt_image->execute();
        }
    
        $stmt_image->close();
    }
    
    
    
       
            
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            require '../config/database.php';
            require '../model/product_model.php';
    
            $productModel = new ProductModel($conn);
            $productController = new ProductController($conn);
            $productController->addImageproductTrashcontroller($id);
            
            // Xóa dữ liệu đầu ra không cần thiết tại đây
            ob_clean(); // Xóa dữ liệu đầu ra hiện tại
    
            $deleteproduct = $productModel->deleteProduct($id);
            if ($deleteproduct) {
                echo "success";
                exit();
            } else {
                echo "error";
            }
        }
    }
    
    
?>
