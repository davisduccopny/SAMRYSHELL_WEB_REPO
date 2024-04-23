<?php
class ExpenseModel {
    private $conn;
   
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertExpense($categoryex_id, $exfor ,$description, $status, $amount, $created_at) {
        // Tạo truy vấn INSERT
        $query = "INSERT INTO expense (categoryex_id,expense_for ,description, status, amount,created_at) VALUES ( ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssds", $categoryex_id, $exfor ,$description, $status, $amount, $created_at);
        
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function updateExpense($id, $categoryex_id,$exfor ,$description, $status, $amount, $created_at) {
        // Tạo truy vấn UPDATE
        $query = "UPDATE expense SET categoryex_id=?, description=?, status=?, amount=?, expense_for=?,created_at=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssdssi", $categoryex_id, $description, $status, $amount,$exfor,$created_at , $id);
        
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function deleteExpense($id) {
        // Tạo truy vấn DELETE
        $query = "DELETE FROM expense WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function getCategoryExpense($id) {
        // Khai báo biến
        $categoryex_id = $code = $description = $status = $amount =$created_at= $expense_for = null;


        // Tạo truy vấn SELECT
        $query = "SELECT id, categoryex_id, code, description, status, amount,created_at,expense_for FROM expense WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id, $categoryex_id, $code, $description, $status, $amount,$created_at,$expense_for);
        
        // Lấy dòng kết quả
        $stmt->fetch();
        
        // Trả về kết quả dưới dạng mảng
        return [
            'id' => $id,
            'categoryex_id' => $categoryex_id,
            'code' => $code,
            'description' => $description,
            'status' => $status,
            'amount' => $amount,
            'created_at' => date('Y-m-d'),
            'expense_for' => $expense_for
        ];
    }
    
    public function showExpenses() {
        $id = $name = $code = $description = $status = $amount = $expense_for = $categoryex_id = null;
        $created_at ='';
        
        // Tạo truy vấn SELECT
        $query = "SELECT expense.id, expense.categoryex_id,category_expense.name,expense.code,expense.description,expense.status,expense.amount,
        expense.created_at,expense.expense_for  FROM expense
         JOIN category_expense ON expense.categoryex_id = category_expense.id";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id, $categoryex_id, $name, $code, $description, $status, $amount, $created_at,$expense_for);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $id,
                'categoryex_id' => $categoryex_id,
                'name' => $name,
                'code' => $code,
                'description' => $description,
                'status' => $status,
                'amount' => $amount,
                'created_at' => date('d M Y', strtotime($created_at)),
                'expense_for' => $expense_for
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
}
?>
