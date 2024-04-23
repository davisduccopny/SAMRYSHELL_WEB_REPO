<?php
class BlogModel {
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

    public function insertBlog($name, $description, $content, $created_by,$category_id, $image, $imagetmp) {
        // slug
        $slug = $this->createSlug($name);
        // slug
        $location = "../upload/blog" ;
        $imageinsert = $location . $image;
    
        $target_dir = "../upload/blog" ;
         if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $finalImage = $target_dir . $image;
    
        move_uploaded_file($imagetmp, $finalImage);
        // Tạo truy vấn INSERT
        
        $query = "INSERT INTO blog (title, description,content, created_by, category_id,image, slug) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssiss", $name, $description, $content,$created_by,$category_id, $imageinsert,$slug);

        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function updateBlog($blog_id,$name, $description, $content, $created_by,$category_id, $image, $imagetmp) {
        $category_link = $this->createSlug($name);
        if (isset($image) && $image != null) {
        $location = "../upload/blog";
        $imageinsert = $location . $image;
        $target_dir = "../upload/blog";
         if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $finalImage = $target_dir . $image;
        move_uploaded_file($imagetmp, $finalImage);
        $query = "UPDATE blog SET title=?, description=?, content=?,created_by=?,category_id=?,image=?, slug=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssissi", $name, $description,$content,$created_by,$category_id,$imageinsert, $category_link,$blog_id);
        return $stmt->execute();
        }else{
            $query = "UPDATE blog SET title=?, description=?, content=?,created_by=?,category_id=?, slug=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssssi", $name, $description,$content,$created_by,$category_id, $category_link,$blog_id);
            return $stmt->execute();
        }
            
        }
        public function updateBlog_info($blog_id,$name, $description, $content, $created_by, $image, $imagetmp) {
            $category_link = $this->createSlug($name);
            if (isset($image) && $image != null) {
            $location = "../upload/blog";
            $imageinsert = $location . $image;
            $target_dir = "../upload/blog";
             if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $finalImage = $target_dir . $image;
            move_uploaded_file($imagetmp, $finalImage);
            $query = "UPDATE blog SET title=?, description=?, content=?,created_by=?,image=?, slug=? WHERE id=?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssssi", $name, $description,$content,$created_by,$imageinsert,$category_link,$blog_id);
            return $stmt->execute();
            }else{
                $query = "UPDATE blog SET title=?, description=?, content=?,created_by=?, slug=? WHERE id=?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sssssi", $name, $description,$content,$created_by,$category_link,$blog_id);
                return $stmt->execute();
            }
                
            }
    
    public function deleteBlog($blog_id) {
        // Tạo truy vấn DELETE
        $query = "DELETE FROM blog WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $blog_id);
        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function getBlog($blog_id) {
        // Khai báo biến
        $name = $category_id = $content = $description = $created_by = $image =$date = $slug=null;

        // Tạo truy vấn SELECT
        $query = "SELECT id, title, description, content,image, created_by,category_id, date, slug FROM blog WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $blog_id);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($blog_id, $name, $description, $content, $image, $created_by,$category_id, $date, $slug);
        
        // Lấy dòng kết quả
        $stmt->fetch();
        
        // Trả về kết quả dưới dạng mảng
        return [
            'blog_id' => $blog_id,
            'title' => $name,
            'description' => $description,
            'content' => $content,
            'image' => $image,
            'created_by' => $created_by,
            'category_id' => $category_id,
            'date' => $date,
            'slug' => $slug
        ];
    }
    
    public function showBlog() {
        $id= $name = $content = $category_id =$description= $created_by= $image = $category_name = $date=$slug = null;
        // Tạo truy vấn SELECT
        $query = "SELECT b.id, b.title, b.description, b.content,b.image,b.created_by,b.category_id,c.name, b.date, b.slug FROM blog b
        JOIN category_blog c ON c.id = b.category_id WHERE b.type != 1;";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id, $name, $description, $content,$image,$created_by,$category_id,$category_name, $date, $slug);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'content' => $content,
                'image' => $image,
                'created_by' => $created_by,
                'category_id' => $category_id,
                'category_name'=> $category_name,
                'date'=> $date,
                'slug' => $slug
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    public function showBlog_forsearch($content_seach) {
        $id= $name = $content = $category_id =$description= $created_by= $image = $category_name = $date= $slug= null;
        // Tạo truy vấn SELECT
        $query = "SELECT b.id, b.title, b.description, b.content,b.image,b.created_by,b.category_id,c.name, b.date, b.slug FROM blog b
        JOIN category_blog c ON c.id = b.category_id WHERE b.type != 1 AND (b.title LIKE '%$content_seach%' OR b.content LIKE '%$content_seach%' OR b.description LIKE '%$content_seach%');";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id, $name, $description, $content,$image,$created_by,$category_id,$category_name, $date, $slug);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $id,
                'title' => $name,
                'description' => $description,
                'content' => $content,
                'image' => $image,
                'created_by' => $created_by,
                'category_id' => $category_id,
                'category_name'=> $category_name,
                'date'=> $date,
                'slug' => $slug
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    public function showBlog_publicinfo() {
        $id= $name = $content =$description= $created_by= $image  = $date=$slug = null;
        // Tạo truy vấn SELECT
        $query = "SELECT b.id, b.title, b.description, b.content,b.image,b.created_by, b.date, b.slug FROM blog b WHERE b.type = 1";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id, $name, $description, $content,$image,$created_by, $date, $slug);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'content' => $content,
                'image' => $image,
                'created_by' => $created_by,
                'date'=> $date,
                'slug' => $slug
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    public function showBlog_foruser($start, $perpage) {
        $id= $name = $content = $category_id =$description= $created_by= $image = $category_name= $date= $slug = null;
        // Tạo truy vấn SELECT
        $query = "SELECT b.id, b.title, b.description, b.content,b.image,b.created_by,b.category_id,c.name, b.date,b.slug  FROM blog b
        JOIN category_blog c ON c.id = b.category_id WHERE b.type != 1
        ORDER BY b.date DESC 
        LIMIT $start,$perpage";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id, $name, $description, $content,$image,$created_by,$category_id,$category_name, $date, $slug);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $id,
                'title' => $name,
                'description' => $description,
                'content' => $content,
                'image' => $image,
                'created_by' => $created_by,
                'category_id' => $category_id,
                'category_name'=> $category_name,
                'date'=> $date,
                'slug' => $slug
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    
    public function showCategory_blog() {
        $id= $name = null;
        // Tạo truy vấn SELECT
        $query = "SELECT id, name FROM category_blog";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id, $name);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $id,
                'name' => $name
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }

    public function showBlog_filter($category_blog,$start, $perpage) {
        $id= $name = $content = $category_id =$description= $created_by= $image = $category_name= $date= $slug = null;
        // Tạo truy vấn SELECT
        $query = "SELECT b.id, b.title, b.description, b.content,b.image,b.created_by,b.category_id,c.name, b.date,b.slug FROM blog b
        JOIN category_blog c ON c.id = b.category_id WHERE b.type != 1 AND category_id = $category_blog
        ORDER BY b.date DESC 
        LIMIT $start,$perpage";
        $stmt = $this->conn->prepare($query);
        
        // Thực hiện truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id, $name, $description, $content,$image,$created_by,$category_id,$category_name, $date, $slug);
        
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
        
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $id,
                'title' => $name,
                'description' => $description,
                'content' => $content,
                'image' => $image,
                'created_by' => $created_by,
                'category_id' => $category_id,
                'category_name'=> $category_name,
                'date'=> $date,
                'slug' => $slug
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
}
?>
