<?php
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['supplier_id'])) {
                $supplier_id = $_POST['supplier_id'];
                require '../config/database.php';
                require '../model/supplier_model.php';
                $supplierModel = new supplierModel($conn);
                $unsetimagesupplier = $supplierModel ->unsetimagesupplier($supplier_id);
                ob_clean(); // Xóa dữ liệu đầu ra hiện tại
                $deletesupplier = $supplierModel->deletesupplier($supplier_id);
                if ($deletesupplier) {
                    echo "success";
                    exit();
                } else {
                    echo "error";
                }
    }
}
?>