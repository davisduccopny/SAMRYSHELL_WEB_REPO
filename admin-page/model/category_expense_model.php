<?php
class CategoryExpenseModel {
    private $conn;
   
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertCategoryExpense($name, $code, $description, $status, $amount) {
        // Tạo truy vấn INSERT
        $query = "INSERT INTO category_expense (name, code, description, status, amount) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssd", $name, $code, $description, $status, $amount);
        
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function updateCategoryExpense($category_id, $name, $code, $description, $status, $amount) {
        // Tạo truy vấn UPDATE
        $query = "UPDATE category_expense SET name=?, code=?, description=?, status=?, amount=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssdi", $name, $code, $description, $status, $amount, $category_id);
        
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function deleteCategoryExpense($category_id) {
        // Tạo truy vấn DELETE
        $query = "DELETE FROM category_expense WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_id);
        
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function getCategoryExpense($category_id) {
        // Khai báo biến
        $name = $code = $description = $status = $amount =$created_at= null;


        // Tạo truy vấn SELECT
        $query = "SELECT id, name, code, description, status, amount,created_at FROM category_expense WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_id);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($category_id, $name, $code, $description, $status, $amount,$created_at);
        
        // Lấy dòng kết quả
        $stmt->fetch();
        
        // Trả về kết quả dưới dạng mảng
        return [
            'id' => $category_id,
            'name' => $name,
            'code' => $code,
            'description' => $description,
            'status' => $status,
            'amount' => $amount,
            'created_at' => date('Y-m-d')
        ];
    }
    
    public function showCategoryExpenses() {
        $category_id = $name = $code = $description = $status = $amount = null;
        $created_at ='';
        
        // Tạo truy vấn SELECT
        $query = "SELECT id, name, code, description, status, amount,created_at FROM category_expense";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($category_id, $name, $code, $description, $status, $amount, $created_at);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $category_id,
                'name' => $name,
                'code' => $code,
                'description' => $description,
                'status' => $status,
                'amount' => $amount,
                'created_at' => date('d/m/Y', strtotime($created_at))
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    public function showCategoryExpenses_forexpense() {
        $category_id = $name = $code = $description = $status = $amount = null;
        $created_at ='';
        
        // Tạo truy vấn SELECT
        $query = "SELECT id, name, code, description, status, amount,created_at FROM category_expense WHERE status='Active'";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($category_id, $name, $code, $description, $status, $amount, $created_at);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $category_id,
                'name' => $name,
                'code' => $code,
                'description' => $description,
                'status' => $status,
                'amount' => $amount,
                'created_at' => date('d/m/Y', strtotime($created_at))
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
}
if (isset($_POST['delete'])) {
    ob_clean();
    require '../config/database.php';
    $category_expense = new CategoryExpenseModel($conn);
    $category_expensedelete =  $category_expense->deleteCategoryExpense($_POST['cateroryexpen_id']);
    if ($category_expensedelete) {
        echo 'success';
    }
    else {
        echo 'error';
    }
}
?>
