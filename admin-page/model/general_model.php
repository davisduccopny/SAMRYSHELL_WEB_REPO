<?php
class GeneralModel {
    private $conn;
   

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function createSlug($str, $delimiter = '-') {
        $chars = array(
            'a' => 'áàảãạăắằẳẵặâấầẩẫậ',
            'd' => 'đ',
            'e' => 'éèẻẽẹêếềểễệ',
            'i' => 'íìỉĩị',
            'o' => 'óòỏõọôốồổỗộơớờởỡợ',
            'u' => 'úùủũụưứừửữự',
            'y' => 'ýỳỷỹỵ',
        );
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^a-z0-9' . implode('', $chars) . ']+/u', ' ', $str);
        foreach ($chars as $replacement => $pattern) {
            $str = preg_replace("/[$pattern]/u", $replacement, $str);
        }
        $str = preg_replace('/\s+/', ' ', $str);
        $str = str_replace(' ', $delimiter, $str);
        $str = trim($str, '-');
        return $str;
    }
    public function insertCustomer($name, $phone, $image,$imagetmp, $email, $type, $country, $city, $district, $address, $zipcode, $description) {
        $location = "../upload/customer/";
        $imageinsert = $location . $image;
    
        $target_dir = "../upload/customer/";
         if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $finalImage = $target_dir . $image;
    
        move_uploaded_file($imagetmp, $finalImage);

        $query = "INSERT INTO customer (name, phone, image, email, type, country, city, district, address, zipcode, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssssss", $name, $phone, $imageinsert, $email, $type, $country, $city, $district, $address, $zipcode, $description);

        return $stmt->execute();
    }

    public function updateGeneral($id, $name, $phone, $image, $imagetmp, $email, $time_zone, $address, $fax, $date_format, $currency) {
        $query = "UPDATE general_setting SET title=?, phone=?, email=?, time_zone=?, address=?, fax=?, date_format=?, currency=?";
    
        if (isset($image) && $image != null) {
            $location = "../upload/general/";
            $imageFileName = uniqid() . "_" . $image; // Generate a unique filename to avoid conflicts
            $finalImage = $location . $imageFileName;
    
            $target_dir = "../upload/general/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
    
            move_uploaded_file($imagetmp, $finalImage);
    
            $query .= ", image=?";
            $imagePathForDb = $finalImage;
        } else {
            $imagePathForDb = null;
        }
    
        $query .= " WHERE id=?";
        $stmt = $this->conn->prepare($query);
    
        if ($imagePathForDb) {
            $stmt->bind_param("sssssssssi", $name, $phone, $email, $time_zone, $address, $fax, $date_format,$currency, $imagePathForDb, $id);
        } else {
            $stmt->bind_param("ssssssssi", $name, $phone, $email, $time_zone, $address, $fax, $date_format,$currency, $id);
        }
    
        return $stmt->execute();
    }
    

    public function deleteCustomer($id) {
        $query = "DELETE FROM customer WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function getGeneral($id) {
        $query = "SELECT id, title, phone, image, email, currency, address, fax, date_format, time_zone, youtube, instagram,linkedin,facebook FROM general_setting WHERE id='$id'";
        $result = $this->conn->query($query);
        
        if ($result) {
            if ($result->num_rows === 1) {
                $customer = $result->fetch_assoc();
                $result->free_result();
                return $customer;
            } else {
                return ['error' => 'Customer not found'];
            }
        } else {
            return ['error' => $this->conn->error];
        }
    }
    

    public function listCustomers() {
        $query = "SELECT id, name, phone, image, email, type, country, city, district, address, zipcode, description,code FROM customer";
        $result = $this->conn->query($query);
        
        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
        
        return $customers;
    }  
    
}
?>
