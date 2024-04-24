<?php 
    require_once('../config/database.php');
 class SigninModal {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function signin($email, $password) {
        $Oldpassword = $role_manager =null;
        $sql = "SELECT password,role FROM user_manager WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($Oldpassword,$role_manager); 
        $stmt->fetch(); 
        $stmt->close();
    
        if ($Oldpassword === null) {
            return false; 
        } else {
            if (password_verify($password, $Oldpassword)) {
                return ['role' => $role_manager]; 
            } else {
                return false; 
            }
        }
    }
    public function signin_customer($email, $password) {
        $Oldpassword = null;
        $sql = "SELECT password FROM user_customer WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($Oldpassword); 
        $stmt->fetch(); 
        $stmt->close();
    
        if ($Oldpassword === null) {
            return false; 
        } else {
            if (password_verify($password, $Oldpassword)) {
                return true;
            } else {
                return false; 
            }
        }
    }
    
    public function ForgotPassword (){
        $email = $_POST['email'];
        $sql = "SELECT * FROM user_manager WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $user = $result->fetch_assoc(); 
        $stmt->close();
        if ($user) { 
            $token = bin2hex(random_bytes(50));
            $sql = "INSERT INTO password_reset(email, token) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $email, $token);
            $stmt->execute();
            $stmt->close();
            $to = $email;
            $subject = "Reset your password on example.com";
            $msg = "Hi there, click on this <a href=\"http://localhost:8080/ProjectWeb/controller/reset_password.php?token=" . $token . "\">link</a> to reset your password on our site";
            $msg = wordwrap($msg,70);
            $headers = "From: ";
            
    }
    
 }
}
?>