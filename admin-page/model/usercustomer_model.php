<?php
class UserCustomerModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertUserCustomer($firstname, $customeremail, $lastname, $password, $status) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO user_customer (first_name, email, last_name, password, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $firstname, $customeremail, $lastname, $hashedPassword, $status);

        return $stmt->execute();
    }

    public function updateUserCustomer($user_id, $firstname, $lastname,$email) {
        
        $query = "UPDATE user_customer SET first_name=?, last_name=?,email=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $firstname, $lastname,$email, $user_id);

        return $stmt->execute();
    }
    public function updateUserstatusCustomer($user_id,$status) {
        $query = "UPDATE user_customer SET status=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii",$status, $user_id);

        return $stmt->execute();
    }
    public function deleteUserCustomer($user_id) {
        $query = "DELETE FROM user_customer WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);

        return $stmt->execute();
    }

    public function getUserCustomer($user_id) {
        $first_name=$customer_id=$last_name=$password=$status=$email=$image=$phone=null;
        $query = "SELECT uc.id, uc.first_name, uc.customer_id, uc.last_name, uc.password, uc.status,ct.email,ct.image,ct.phone FROM 
        user_customer uc JOIN customer ct ON uc.email = ct.email WHERE uc.id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($user_id, $first_name, $customer_id, $last_name, $password, $status,$email,$image, $phone);
        $stmt->fetch();
        $stmt->close();

        return [
            'id' => $user_id,
            'first_name' => $first_name,
            'customer_id' => $customer_id,
            'last_name' => $last_name,
            'password' => $password,
            'status' => $status,
            'email' => $email,
            'image' => $image,
            'phone' => $phone
        ];
    }

    public function listUserCustomers() {
        $query = "SELECT uc.id, uc.first_name, uc.customer_id, uc.last_name, uc.password, uc.status,ct.email,ct.image,ct.phone FROM 
         user_customer uc JOIN customer ct ON uc.email = ct.email";
        $result = $this->conn->query($query);

        $userCustomers = [];
        while ($row = $result->fetch_assoc()) {
            $userCustomers[] = $row;
        }

        return $userCustomers;
    }
    public function getEmailList() {
        $query = "SELECT email FROM customer";
        $result = $this->conn->query($query);

        $emailList = [];

        while ($row = $result->fetch_assoc()) {
            $emailList[] = $row['email'];
        }

        return json_encode($emailList);
    }

    public function update_password($email, $password){
        $query = "UPDATE user_customer SET password=? WHERE email=?";
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            // Xử lý lỗi khi chuẩn bị câu lệnh SQL
            return false;
        }
        $stmt->bind_param('ss', $hashedPassword, $email);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    
    
}
?>
