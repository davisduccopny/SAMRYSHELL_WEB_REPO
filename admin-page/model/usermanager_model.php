<?php
class UserManagerModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function unsetimageusermanager($p_id){
        $old_image = null;
        $stmt = $this->conn->prepare("SELECT image FROM user_manager WHERE id= ?");
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $stmt->bind_result($old_image);
        $stmt->fetch();
        $stmt->close();
        if ($old_image) {
            $source_path = $_SERVER['DOCUMENT_ROOT'].'/admin-page/'.str_replace("..", "", $old_image);
            if (unlink($source_path)) {
                return true;
            } else {
                return false;
            }   

        } else {
            return false;
        }

    }

    public function insertUsermanager($email, $password, $firstname, $lastname, $phone, $image,$imagetmp, $role, $status) {
        $location = "../upload/usermanager/";
        $imageinsert = $location . $image;
    
        $target_dir = "../upload/usermanager/";
         if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $finalImage = $target_dir . $image;
    
        move_uploaded_file($imagetmp, $finalImage);


        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO user_manager (email, password, firstname, lastname, phone, image, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this ->conn->prepare($sql);
        $stmt->bind_param("ssssssss", $email, $hashedPassword, $firstname, $lastname, $phone,  $imageinsert, $role, $status);
        return $stmt->execute();
    }

    public function updateUserManager($id, $email, $password, $firstname, $lastname, $phone, $image, $imagetmp, $role, $status) {
        $query = "UPDATE user_manager SET email=?, password=?, firstname=?, lastname=?, phone=?, image=?, role=?, status=? WHERE id=?";
        $params = [$email, $password, $firstname, $lastname, $phone, $image, $role, $status, $id];
    
        if ($image != 0) {
            $this->unsetimageusermanager($id);
            $location = "../upload/usermanager/";
            $imageFileName = uniqid() . "_" . $image;
            $finalImage = $location . $imageFileName;
    
            $target_dir = "../upload/usermanager/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
    
            move_uploaded_file($imagetmp, $finalImage);
    
            $params[5] = $finalImage; // Cập nhật đường dẫn hình ảnh mới vào mảng $params
        }
        else {
            $params[5] = $this->getUserManager($id)['image'];
        }
    
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            $checkpassword = '';
            $querycheckpass = "SELECT password FROM user_manager WHERE id = ?";
            $stmtcheckpass = $this->conn->prepare($querycheckpass);
            $stmtcheckpass->bind_param("i", $id);
            $stmtcheckpass->execute();
            $stmtcheckpass->bind_result($checkpassword);
            $stmtcheckpass->fetch();
            $stmtcheckpass->close();
    
            if ($checkpassword && $checkpassword == $password) {
                $params[1] = $checkpassword;
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $params[1] = $hashedPassword;
            }
    
            $paramTypes = str_repeat("s", count($params));
            $stmt->bind_param($paramTypes, ...$params);
    
            return $stmt->execute();
        } else {
            return false;
        }
    }
    
    
    
    public function deleteUserManager($id) {
        $query = "DELETE FROM user_manager WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
    
        return $stmt->execute();
    }

    public function getUserManager($user_id) {
        $first_name = $last_name = $password = $status = $email = $image = $phone = $role =null;
        $query = "SELECT id, firstname, lastname, password, status, email, image, phone,role FROM user_manager WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($user_id, $first_name, $last_name, $password, $status, $email, $image, $phone, $role);
        $stmt->fetch();
        $stmt->close();
    
        return [
            'id' => $user_id,
            'firstname' => $first_name,
            'lastname' => $last_name,
            'password' => $password,
            'status' => $status,
            'email' => $email,
            'image' => $image,
            'phone' => $phone,
            'role' => $role
        ];
    }
    

    public function listUserManagers() {
        $query = "SELECT * FROM user_manager";
        $result = $this->conn->query($query);
    
        $userManagers = [];
        while ($row = $result->fetch_assoc()) {
            $userManagers[] = $row;
        }
    
        return $userManagers;
    }
    public function update_password($email, $password){
        $query = "UPDATE user_manager SET password=? WHERE email=?";
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
