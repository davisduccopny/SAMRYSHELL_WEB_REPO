<?php
class reportModel {
    // Add your class properties and methods here
    private $conn;
    public function __construct($conn) {
        // Constructor code here
        $this->conn = $conn;
    }

    public function getReports() {
        // Code to retrieve reports from the database
    }

    public function getReportById($id) {
        // Code to retrieve a report by its ID from the database
    }

    public function getReportByCategory($category) {
        // Code to retrieve reports by category from the database
    }

    public function getReportByDate($startDate, $endDate) {
        // Code to retrieve reports within a specific date range from the database
    }
    public function showProduct_expire($start,$perpage) {
        // Tạo truy vấn SELECT với JOIN
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name= $categoryproduct_id= $short_description= $type_product =null;
    
        $query = "SELECT 
                    p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
                    p.status, p.description, sc.name , p.slug, 
                    p.updated_at, p.created_at, p.created_by,
                    c.name AS category_name, c.category_link, c.code,
                    ip.image ,c.image AS image_category, p.categoryproduct_id, p.short_description, p.type_product
                  FROM product AS p
                  JOIN categoryproduct AS c ON p.categoryproduct_id = c.categoryproduct_id
                  LEFT JOIN category_sub AS sc ON p.subcategoryproduct_id = sc.category_sub_id 
                  LEFT JOIN  (
                      SELECT product_id, MIN(image) AS image
                      FROM image_product
                      GROUP BY product_id
                  ) AS ip ON p.id = ip.product_id
                  WHERE created_at < DATE_SUB(NOW(), INTERVAL 3 MONTH) AND quantity > 0
                  ORDER BY p.created_at DESC LIMIT $start, $perpage";
    
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
        // Thực hiện truy vấn
        $stmt->execute();
    
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result(
            $id, $sku, $name, $price, $minium_quantity, $quantity, $unit, $tax, $discount,
            $status, $description, $categorysub_name, $slug,
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $image, $image_category,$categoryproduct_id, $short_description, $type_product
        );
    
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
    
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $results[] = [
                'id' => $id,
                'sku' => $sku,
                'name' => $name,
                'price' => $price,
                'minium_quantity' => $minium_quantity,
                'quantity' => $quantity,
                'unit' => $unit,
                'tax' => $tax,
                'discount' => $discount,
                'status' => $status,
                'description' => $description,
                'categorysub_name' => $categorysub_name,
                'slug' => $slug,
                'updated_at' => $updated_at,
                'created_at' => $created_at,
                'created_by' => $created_by,
                'category_name' => $category_name,
                'category_link' => $category_link,
                'code' => $code,
                'image' => $image,
                'image_category' => $image_category,
                'categoryproduct_id' => $categoryproduct_id,
                'short_description' => $short_description,
                'type_product' => $type_product
            ];
        }
    
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    public function TotalSale($type) {
        $total = null;
        $query = "SELECT SUM(grand_total) AS total FROM sale WHERE status = '$type'";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        return $total;
    }
    public function TotalExpense($type) {
        $total = null;
        $query = "SELECT SUM(amount) AS total FROM expense WHERE status = '$type'";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        return $total;
    }
    public function TotalSale_byMonth($type) {
        $month= $total= null;
        $totals = []; // Khởi tạo mảng rỗng để lưu tổng của grand_total cho 12 tháng
    
        // Khởi tạo mảng để lưu dữ liệu từ cơ sở dữ liệu
        $data = [];
        $query = "
            SELECT 
                MONTH(created_at) AS month, 
                SUM(grand_total) AS total
            FROM 
                sale
            WHERE 
                status = ?
                AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY 
                YEAR(created_at), MONTH(created_at)
            ORDER BY 
                YEAR(created_at) DESC, MONTH(created_at) DESC
        ";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
        $stmt->bind_param("s", $type); // Gán giá trị tham số cho truy vấn
        $stmt->execute();
        $stmt->bind_result($month, $total); // Ràng buộc các cột từ kết quả
        
        // Lặp qua kết quả và thêm vào mảng $data
        while ($stmt->fetch()) {
            $data[$month] = $total;
        }
        
        // Xây dựng mảng $totals từ dữ liệu thu được, điền vào các giá trị còn thiếu bằng 0
        for ($i = 1; $i <= 12; $i++) {
            if (isset($data[$i])) {
                $totals[] = $data[$i];
            } else {
                $totals[] = 0;
            }
        }
        
        return $totals;
    }

    
    public function TotalExpense_byMonth($type) {
        $month= $total= null;
        $totals = []; // Khởi tạo mảng rỗng để lưu tổng của grand_total cho 12 tháng
    
        // Khởi tạo mảng để lưu dữ liệu từ cơ sở dữ liệu
        $data = [];
        $query = "
            SELECT 
                MONTH(created_at) AS month, 
                SUM(amount) AS total
            FROM 
                expense
            WHERE 
                status = ?
                AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY 
                YEAR(created_at), MONTH(created_at)
            ORDER BY 
                YEAR(created_at) DESC, MONTH(created_at) DESC
        ";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
        $stmt->bind_param("s", $type); // Gán giá trị tham số cho truy vấn
        $stmt->execute();
        $stmt->bind_result($month, $total); // Ràng buộc các cột từ kết quả
        
        // Lặp qua kết quả và thêm vào mảng $data
        while ($stmt->fetch()) {
            $data[$month] = $total;
        }
        
        // Xây dựng mảng $totals từ dữ liệu thu được, điền vào các giá trị còn thiếu bằng 0
        for ($i = 1; $i <= 12; $i++) {
            if (isset($data[$i])) {
                $totals[] = $data[$i];
            } else {
                $totals[] = 0;
            }
        }
        
        return $totals;
    }
    
}