<?php
class CategorySubModel  {
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

    public function insertCategorySub($categoryproduct_id,$name, $code, $description, $created_by) {
        $category_link = $this->createSlug($name);
        
        $query = "INSERT INTO category_sub (categoryproduct_id,name, category_link, code,description, created_by) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssss",$categoryproduct_id, $name, $category_link, $code,$description,$created_by);

        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function updateCategorySub($category_sub_id, $categoryproduct_id, $name, $code, $description, $created_by) {
        $category_link = $this->createSlug($name);
            $query = "UPDATE  category_sub SET categoryproduct_id=?,name=?, category_link=?, code=?,description=?,created_by=? WHERE category_sub_id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("isssssi",$categoryproduct_id, $name, $category_link,$code,$description,$created_by,$category_sub_id);
            return $stmt->execute();
        }

    public function deleteCategorySub($category_sub_id) {
        // Tạo truy vấn DELETE
        $query = "DELETE FROM category_sub WHERE category_sub_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_sub_id);
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function getCategorySub($category_sub_id) {
        $categoryproductname=$name = $category_link = $code = $description = $created_by = $image=$categoryproduct_id =null;
        $query = "SELECT ct.name AS categoryproductname ,cts.category_sub_id,cts.categoryproduct_id, cts.name, cts.category_link, cts.code,	ct.image, cts.created_by,cts.description FROM category_sub cts
        LEFT JOIN categoryproduct ct ON cts.categoryproduct_id = ct.categoryproduct_id WHERE category_sub_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $category_sub_id);
        $stmt->execute();
        $stmt->bind_result($categoryproductname,$category_sub_id, $categoryproduct_id, $name, $category_link, $code, $image, $created_by,$description);
        $stmt->fetch();
        
        // Trả về kết quả dưới dạng mảng
        return [
            'categoryproductname' => $categoryproductname,
            'category_sub_id' => $category_sub_id,
            'categoryproduct_id' => $categoryproduct_id,
            'name' => $name,
            'category_link' => $category_link,
            'code' => $code,
            'image' => $image,
            'created_by' => $created_by,
            'description' => $description
        ];
    }
    
    public function showCategorySubs() {
        $categoryproductname=$category_sub_id=$categoryproduct_id= $name = $category_link = $code =$description= $created_by= $image = null;
        // Tạo truy vấn SELECT
        $query = "SELECT ct.name AS categoryproduct_name,cts.category_sub_id,cts.categoryproduct_id, cts.name, cts.category_link, cts.code,cts.description,cts.created_by,ct.image FROM category_sub cts
        LEFT JOIN categoryproduct ct ON cts.categoryproduct_id = ct.categoryproduct_id ";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($categoryproductname ,$category_sub_id,$categoryproduct_id, $name, $category_link, $code,$description,$created_by,$image);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'categoryproductname' => $categoryproductname,
                'category_sub_id' => $category_sub_id,
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
