<?php
class SupplierModel {
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
    public function insertSupplier($name, $phone, $image,$imagetmp, $email, $type, $country, $city, $district, $address, $zipcode, $description) {
        $location = "../upload/supplier/";
        $imageinsert = $location . $image;
    
        $target_dir = "../upload/supplier/";
         if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $finalImage = $target_dir . $image;
    
        move_uploaded_file($imagetmp, $finalImage);

        $query = "INSERT INTO supplier (name, phone, image, email, type, country, city, district, address, zipcode, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssssss", $name, $phone, $imageinsert, $email, $type, $country, $city, $district, $address, $zipcode, $description);

        return $stmt->execute();
    }

    public function updateSupplier($id, $name, $phone, $image, $imagetmp, $email, $type, $country, $city, $district, $address, $zipcode, $description) {
        $query = "UPDATE supplier SET name=?, phone=?, email=?, type=?, country=?, city=?, district=?, address=?, zipcode=?, description=?";
    
        if (isset($image) && $image != null) {
            $location = "../upload/supplier/";
            $imageFileName = uniqid() . "_" . $image; // Generate a unique filename to avoid conflicts
            $finalImage = $location . $imageFileName;
    
            $target_dir = "../upload/supplier/";
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
            $stmt->bind_param("sssssssssssi", $name, $phone, $email, $type, $country, $city, $district, $address, $zipcode, $description, $imagePathForDb, $id);
        } else {
            $stmt->bind_param("ssssssssssi", $name, $phone, $email, $type, $country, $city, $district, $address, $zipcode, $description, $id);
        }
    
        return $stmt->execute();
    }
    

    public function deleteSupplier($id) {
        $query = "DELETE FROM supplier WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function getsupplier($id) {
        $query = "SELECT id, name, phone, image, email, type, country, city, district, address, zipcode, description, code FROM supplier WHERE id='$id'";
        $result = $this->conn->query($query);
        
        if ($result) {
            if ($result->num_rows === 1) {
                $supplier = $result->fetch_assoc();
                $result->free_result();
                return $supplier;
            } else {
                return ['error' => 'supplier not found'];
            }
        } else {
            return ['error' => $this->conn->error];
        }
    }
    

    public function listSupplier() {
        $query = "SELECT id, name, phone, image, email, type, country, city, district, address, zipcode, description,code FROM supplier";
        $result = $this->conn->query($query);
        
        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = $row;
        }
        
        return $suppliers;
    }  
    public function unsetimagesupplier($p_id){
        $old_image = null;
        $stmt = $this->conn->prepare("SELECT image FROM supplier WHERE id= ?");
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
?>
