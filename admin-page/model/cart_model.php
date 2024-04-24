<?php
class CartModel {
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

    public function insertCart($product_id, $quantity, $email) {

        // Tạo truy vấn INSERT
        $query = "INSERT INTO cart (product_id, quantity, email) VALUES (?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iis", $product_id, $quantity, $email);

        // Thực hiện truy vấn
        return $stmt->execute();
        
    }
    public function updateCart($product_id, $quantityToAdd, $email) {
        // Lấy quantity hiện tại từ cơ sở dữ liệu
        $currentQuantity = $this->getCurrentQuantity($product_id, $email);
    
        if ($currentQuantity === false) {
            // Không tìm thấy sản phẩm trong giỏ hàng
            return false;
        }
    
        // Cộng dồn quantity mới vào quantity hiện tại
        $newQuantity = $currentQuantity + $quantityToAdd;
    
        // Cập nhật quantity mới vào cơ sở dữ liệu
        $query = "UPDATE cart SET quantity=? WHERE email=? AND product_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isi", $newQuantity, $email, $product_id);
    
        // Thực hiện truy vấn
        return $stmt->execute();
    }
    
    private function getCurrentQuantity($product_id, $email) {
        $quantity = null;
        // Tạo truy vấn SELECT để lấy quantity hiện tại từ cơ sở dữ liệu
        $query = "SELECT quantity FROM cart WHERE email=? AND product_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $email, $product_id);
    
        // Thực hiện truy vấn
        $stmt->execute();
    
        // Ràng buộc kết quả trả về cho biến $quantity
        $stmt->bind_result($quantity);
    
        // Lấy quantity hiện tại
        $stmt->fetch();
    
        // Đóng câu truy vấn
        $stmt->close();
    
        return $quantity;
    }
    


    public function deleteCart($product_id, $email) {
        // Thực hiện truy vấn DELETE
        $query = "DELETE FROM cart WHERE product_id=? AND email= ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $product_id, $email);
    
        // Thực hiện truy vấn
        $stmt_execute_result = $stmt->execute();
        $stmt->close();
    
        return $stmt_execute_result;
    }
    

    public function getCart($email) {
        $id=$name = $price = $quantity= $image = $product_id = null;
        // Khai báo biến
        $products = []; // Mảng để lưu danh sách sản phẩm trong giỏ hàng
        
        // Tạo truy vấn SELECT cho thông tin sản phẩm và kết hợp với categoryproduct
        $query = "SELECT p.id,c.quantity, p.name, p.price,  ip.image, c.product_id
                  FROM cart AS c
                  JOIN product AS p ON p.id = c.product_id
                  LEFT JOIN  (
                      SELECT product_id, MIN(image) AS image
                      FROM image_product
                      GROUP BY product_id
                  ) AS ip ON p.id = ip.product_id
                  WHERE c.email=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
    
        // Thực hiện truy vấn
        $stmt->execute();
    
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($id,$quantity, $name, $price, $image,$product_id );
    
        // Lặp qua các dòng kết quả và lưu vào mảng products
        while ($stmt->fetch()) {
            $products[] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $image,
                'product_id' => $product_id 
            ];
        }
    
        // Đóng kết nối và trả về danh sách sản phẩm trong giỏ hàng
        $stmt->close();
        return $products;
    }
    
    
    
    
    public function showProduct() {
        // Tạo truy vấn SELECT với JOIN
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name= $categoryproduct_id =null;
    
        $query = "SELECT 
                    p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
                    p.status, p.description, sc.name , p.slug, 
                    p.updated_at, p.created_at, p.created_by,
                    c.name AS category_name, c.category_link, c.code,
                    ip.image ,c.image AS image_category, p.categoryproduct_id
                  FROM product AS p
                  JOIN categoryproduct AS c ON p.categoryproduct_id = c.categoryproduct_id
                  LEFT JOIN category_sub AS sc ON p.subcategoryproduct_id = sc.category_sub_id 
                  LEFT JOIN  (
                      SELECT product_id, MIN(image) AS image
                      FROM image_product
                      GROUP BY product_id
                  ) AS ip ON p.id = ip.product_id";
    
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
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $image, $image_category,$categoryproduct_id
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
                'categoryproduct_id' => $categoryproduct_id
            ];
        }
    
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    public function showProductFilter($categoryproduct_id = null, $minprice = null, $maxprice = null) {
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name= $categoryid = null;
        // Tạo câu truy vấn SQL cơ bản
        $query = "SELECT 
                    p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
                    p.status, p.description, sc.name , p.slug, 
                    p.updated_at, p.created_at, p.created_by,
                    c.name AS category_name, c.category_link, c.code,
                    ip.image ,c.image AS image_category, p.categoryproduct_id
                  FROM product AS p
                  JOIN categoryproduct AS c ON p.categoryproduct_id = c.categoryproduct_id
                  LEFT JOIN category_sub AS sc ON p.subcategoryproduct_id = sc.category_sub_id 
                  LEFT JOIN  (
                      SELECT product_id, MIN(image) AS image
                      FROM image_product
                      GROUP BY product_id
                  ) AS ip ON p.id = ip.product_id";
    
        // Nếu chỉ có đối số categoryproduct_id được truyền vào, thêm điều kiện vào truy vấn SQL
        if ($categoryproduct_id ===  null && $minprice !== null && $maxprice !== null) {
            $query .= " WHERE p.price <= ? AND p.price >= ?";
        }
        elseif ($categoryproduct_id !== null && $minprice === null && $maxprice === null) {
            $query .= " WHERE p.categoryproduct_id = ?";
        }
        
        // Nếu cả ba đối số đều được truyền vào, thêm điều kiện vào truy vấn SQL
        elseif ($categoryproduct_id !== null && $minprice !== null && $maxprice !== null) {
            $query .= " WHERE p.categoryproduct_id = ? AND p.price <= ? AND p.price >= ?";
        }
        
        // Chuẩn bị truy vấn
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
        
        // Ràng buộc giá trị vào truy vấn

        if ($categoryproduct_id === null && $minprice !== null && $maxprice !== null) {
            $stmt->bind_param("ii",  $maxprice, $minprice);
        }
        else if ($categoryproduct_id !== null && $minprice === null && $maxprice === null) {
            $stmt->bind_param("i", $categoryproduct_id);
        }
        else if ($categoryproduct_id !== null && $minprice !== null && $maxprice !== null) {
            $stmt->bind_param("iii", $categoryproduct_id, $maxprice, $minprice);
        }
        
        // Thực thi truy vấn
        $stmt->execute();
        
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result(
            $id, $sku, $name, $price, $minium_quantity, $quantity, $unit, $tax, $discount,
            $status, $description, $categorysub_name, $slug,
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $image, $image_category, $categoryproduct_id
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
                'categoryproduct_id' => $categoryproduct_id
            ];
        }
        
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    
    public function getProductByIds($ids) {
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name=null;
        // Chuyển đổi mảng các ID thành chuỗi để sử dụng trong truy vấn SQL
        $idList = implode(',', $ids);
    
        // Tạo truy vấn SELECT với JOIN và điều kiện WHERE để chỉ lấy các sản phẩm có ID nằm trong danh sách đã cung cấp
        $query = "SELECT 
                    p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
                    p.status, p.description, sc.name , p.slug, 
                    p.updated_at, p.created_at, p.created_by,
                    c.name AS category_name, c.category_link, c.code,
                    ip.image ,c.image AS image_category
                  FROM product AS p
                  JOIN categoryproduct AS c ON p.categoryproduct_id = c.categoryproduct_id
                  LEFT JOIN category_sub AS sc ON p.subcategoryproduct_id = sc.category_sub_id 
                  LEFT JOIN  (
                      SELECT product_id, MIN(image) AS image
                      FROM image_product
                      GROUP BY product_id
                  ) AS ip ON p.id = ip.product_id
                  WHERE p.id IN ($idList)";
    
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
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $image, $image_category
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
                'image_category' => $image_category
            ];
        }
    
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    
    public function showCategoryProducts_sub() {
        $categoryproduct_id=$name=$category_link=$category_sub_id=$category_sub_name=null;
        $query = "SELECT a.categoryproduct_id, a.name, a.category_link, c.category_sub_id, c.name AS category_sub_name  
                  FROM categoryproduct AS a
                  LEFT JOIN category_sub AS c ON a.categoryproduct_id = c.categoryproduct_id";
    
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
    
        // Thực hiện truy vấn
        $stmt->execute();
    
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($categoryproduct_id, $name, $category_link, $category_sub_id, $category_sub_name);
    
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
    
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $categoryIndex = array_search($categoryproduct_id, array_column($results, 'categoryproduct_id'));
    
            if ($categoryIndex !== false) {
                $results[$categoryIndex]["subcategories"][] = [
                    "category_sub_id" => $category_sub_id,
                    "category_sub_name" => $category_sub_name
                ];
            } else {
                $results[] = [
                    "categoryproduct_id" => $categoryproduct_id,
                    "name" => $name,
                    "category_link" => $category_link,
                    "subcategories" => [
                        [
                            "category_sub_id" => $category_sub_id,
                            "category_sub_name" => $category_sub_name
                        ]
                    ]
                ];
            }
        }
    
        // Đóng kết nối
        $stmt->close();
    
        // Chuyển đổi mảng thành chuỗi JSON
        return json_encode($results);
    }
    
    public function showProductsalelist() {
        // Tạo truy vấn SELECT với JOIN
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount =
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code = $image =$categorysub_name =null;
    
        $query = "SELECT 
                    p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
                    p.status, p.description, sc.name AS categorysub_name, p.slug, 
                    p.updated_at, p.created_at, p.created_by,
                    c.name AS category_name, c.category_link, c.code,
                    ip.image
                  FROM product AS p
                  JOIN categoryproduct AS c ON p.categoryproduct_id = c.categoryproduct_id
                  LEFT JOIN category_sub AS sc ON p.subcategoryproduct_id = sc.category_sub_id 
                  LEFT JOIN  (
                      SELECT product_id, MIN(image) AS image
                      FROM image_product
                      GROUP BY product_id
                  ) AS ip ON p.id = ip.product_id";
    
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
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $image
        );
    
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
    
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $category_tab = $category_name; // Gán giá trị của category_name vào biến $category_tab
            $results[$category_tab][] = [
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
                'image' => $image
            ];
        }
    
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    
    
    
    
    
}
?>
