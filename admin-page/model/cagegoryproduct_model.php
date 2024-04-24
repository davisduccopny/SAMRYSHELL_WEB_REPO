<?php
class CategoryProductModel {
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

    public function insertCategoryProduct($name, $code, $description, $created_by, $image, $imagetmp) {
        $category_link = $this->createSlug($name);
        $location = "../upload/category/" . $category_link . "/";
        $imageinsert = $location . $image;
    
        $target_dir = "../upload/category/" . $category_link . "/";
         if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $finalImage = $target_dir . $image;
    
        move_uploaded_file($imagetmp, $finalImage);
        // Tạo truy vấn INSERT
        
        $query = "INSERT INTO categoryproduct (name, category_link, code,description, created_by, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssss", $name, $category_link, $code,$description,$created_by, $imageinsert);

        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function updateCategoryProduct($categoryproduct_id,$name, $code, $description, $created_by, $image, $imagetmp) {
        $category_link = $this->createSlug($name);
        if (isset($image) && $image != null) {
        $location = "../upload/category/" . $category_link . "/";
        $imageinsert = $location . $image;
        $target_dir = "../upload/category/" . $category_link . "/";
         if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $finalImage = $target_dir . $image;
        move_uploaded_file($imagetmp, $finalImage);
        $query = "UPDATE categoryproduct SET name=?, category_link=?, code=?,description=?,created_by=?,image=? WHERE categoryproduct_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", $name, $category_link,$code,$description,$created_by,$imageinsert,$categoryproduct_id);
        return $stmt->execute();
        }else{
            $query = "UPDATE categoryproduct SET name=?, category_link=?, code=?,description=?,created_by=? WHERE categoryproduct_id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssssi", $name, $category_link,$code,$description,$created_by,$categoryproduct_id);
            return $stmt->execute();
        }
            
        }

    public function deleteCategoryProduct($categoryproduct_id) {
        // Tạo truy vấn DELETE
        $query = "DELETE FROM categoryproduct WHERE categoryproduct_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $categoryproduct_id);
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function getCategoryProduct($categoryproduct_id) {
        // Khai báo biến
        $name = $category_link = $code = $description = $created_by = $image =null;

        // Tạo truy vấn SELECT
        $query = "SELECT categoryproduct_id, name, category_link, code,	image, created_by,description FROM categoryproduct WHERE categoryproduct_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $categoryproduct_id);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($categoryproduct_id, $name, $category_link, $code, $image, $created_by,$description);
        
        // Lấy dòng kết quả
        $stmt->fetch();
        
        // Trả về kết quả dưới dạng mảng
        return [
            'categoryproduct_id' => $categoryproduct_id,
            'name' => $name,
            'category_link' => $category_link,
            'code' => $code,
            'image' => $image,
            'created_by' => $created_by,
            'description' => $description
        ];
    }
    
    public function showCategoryProducts() {
        $categoryproduct_id= $name = $category_link = $code =$description= $created_by= $image = null;
        // Tạo truy vấn SELECT
        $query = "SELECT categoryproduct_id, name, category_link, code,description,created_by,image FROM categoryproduct";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($categoryproduct_id, $name, $category_link, $code,$description,$created_by,$image);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'categoryproduct_id' => $categoryproduct_id,
                'name' => $name,
                'category_link' => $category_link,
                'code' => $code,
                'description' => $description,
                'created_by' => $created_by,
                'image' => $image
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
}
?>
