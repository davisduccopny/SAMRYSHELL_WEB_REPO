<?php
class DiscountModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function addDiscount($discount_amount, $start_date, $end_date, $content, $status, $type, $quantity, $minimum_value, $max_discount) {
        $query = "INSERT INTO discount (discount_amount, start_date, end_date, content, status, type, quantity, minium_value, max_discount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("dsssdiiid", $discount_amount, $start_date, $end_date, $content, $status, $type, $quantity, $minimum_value, $max_discount);
        
        return $stmt->execute();
    }
    
    public function deleteDiscount($discount_id) {
        $query = "DELETE FROM discount WHERE discount_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $discount_id);
        
        return $stmt->execute();
    }
    
    public function updateDiscount($discount_id, $discount_amount, $start_date, $end_date, $content, $status, $type, $quantity, $minimum_value, $max_discount) {
        $query = "UPDATE discount SET discount_amount = ?, start_date = ?, end_date = ?, content = ?, status = ?, type = ?, quantity = ?, minium_value = ?, max_discount = ? WHERE discount_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("dsssdiiidi", $discount_amount, $start_date, $end_date, $content, $status, $type, $quantity, $minimum_value, $max_discount, $discount_id);
        
        return $stmt->execute();
    }
    
    public function getAllDiscounts() {
        $query = "SELECT * FROM discount";
        $result = $this->conn->query($query);
        
        $discounts = [];
        while ($row = $result->fetch_assoc()) {
            $discounts[] = $row;
        }
        
        $result->free_result();
        
        return $discounts;
    }
    public function getAlldiscountJson(){
        try {
            $query = "SELECT * FROM discount";
            $result = $this->conn->query($query);
            
            $discounts = [];
            while ($row = $result->fetch_assoc()) {
                $discounts[] = $row;
            }
            
            $result->free_result();
            
            return json_encode($discounts);
        } catch (Exception $e) {
            // Xử lý lỗi ở đây, ví dụ:
            return json_encode(['error' => $e->getMessage()]);
        }
    }
    
    
    public function getDiscountById($discount_id) {
        $query = "SELECT * FROM discount WHERE discount_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $discount_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $discount = $result->fetch_assoc();
        
        $stmt->close();
        
        return $discount;
    }
    
}
?>
