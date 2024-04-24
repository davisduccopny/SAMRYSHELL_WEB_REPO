<?php
class ProductModel {
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

    public function insertProduct($sku, $name, $price, $minQuantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryID, $subcategoryID, $created_by, $short_description, $type_product) {
      
        $slug = $this->createSlug($name);

        // Tạo truy vấn INSERT
        $query = "INSERT INTO product (sku, name, price, minium_quantity, quantity, unit, tax, discount, status, description, categoryproduct_id, subcategoryproduct_id, slug, created_by, short_description, type_product ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdiisddssiissss", $sku, $name, $price, $minQuantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryID, $subcategoryID, $slug, $created_by, $short_description,$type_product );

        // Thực hiện truy vấn
        return $stmt->execute();
        
    }
    public function insertTrashProduct($product_id,$sku, $name, $price, $minQuantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryID, $subcategoryID,$slug, $created_by, $short_description, $type_product) {

        // Tạo truy vấn INSERT
        $query = "INSERT INTO trash_product (product_id,sku, name, price, minium_quantity, quantity, unit, tax, discount, status, description, categoryproduct_id, subcategoryproduct_id, slug, created_by, short_description, type_product) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issdiisddssiissss",$product_id, $sku, $name, $price, $minQuantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryID, $subcategoryID, $slug, $created_by, $short_description, $type_product);

        // Thực hiện truy vấn
        return $stmt->execute();
        
    }

    public function updateProduct($id, $sku, $name, $price, $minQuantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryID, $subcategoryID, $created_by, $short_description, $type_product) {

        $slug = $this->createSlug($name);

        // Tạo truy vấn UPDATE
        $query = "UPDATE product SET sku=?, name=?, price=?, minium_quantity=?, quantity=?, unit=?, tax=?, discount=?, status=?, description=?, categoryproduct_id=?, subcategoryproduct_id=?, slug=?,created_by=?, short_description=?, type_product=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdiisddssiissssi", $sku, $name, $price, $minQuantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryID, $subcategoryID, $slug, $created_by, $short_description ,$type_product , $id);

        // Thực hiện truy vấn
        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $status = $description = $categoryproduct_id = $categorysub_name = $slug = $created_by= $short_description= $type_product = null;
    
        // Thực hiện truy vấn SELECT cho thông tin sản phẩm
        $queryselect = "SELECT 
            p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
            p.status, p.description, p.categoryproduct_id, sc.name AS categorysub_name, p.slug, 
            p.created_by, p.short_description, p.type_product
        FROM product AS p
        LEFT JOIN category_sub AS sc ON p.subcategoryproduct_id = sc.category_sub_id 
        WHERE p.id=?";
        
        $stmtselect = $this->conn->prepare($queryselect);
        $stmtselect->bind_param("i", $id);
        $stmtselect->execute();
        $stmtselect->bind_result(
            $id, $sku, $name, $price, $minium_quantity, $quantity, $unit, $tax, $discount, 
            $status, $description, $categoryproduct_id, $categorysub_name, $slug, $created_by,$short_description, $type_product
        );
    
        // Lấy dòng kết quả
        $stmtselect->fetch();
        $stmtselect->close();
    
        // Gọi phương thức insertTrashProduct
        $this->insertTrashProduct($id, $sku, $name, $price, $minium_quantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryproduct_id, $categorysub_name, $slug, $created_by, $short_description, $type_product);
    
        // Thực hiện truy vấn DELETE
        $query = "DELETE FROM product WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
    
        // Thực hiện truy vấn
        $stmt_execute_result = $stmt->execute();
        $stmt->close();
    
        return $stmt_execute_result;
    }
    

    public function getProduct($id) {
        // Khai báo biến
        $subcategoryproduct_id= $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $status = $description = $categoryproduct_id = $categorysub_name = $slug = $updated_at = $created_at = $created_by = $category_name = $category_link = $code =$image= $short_description= $type_product =null;
        $images = []; // Mảng để lưu danh sách ảnh
    
        // Tạo truy vấn SELECT cho thông tin sản phẩm và kết hợp với categoryproduct
        $query = "SELECT 
                    p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
                    p.status, p.description, p.categoryproduct_id, sc.name AS categorysub_name, p.slug, 
                    p.updated_at, p.created_at, p.created_by,
                    c.name AS category_name, c.category_link, c.code, p.subcategoryproduct_id, p.short_description, p.type_product
                  FROM product AS p
                  JOIN categoryproduct AS c ON p.categoryproduct_id = c.categoryproduct_id
                  LEFT JOIN category_sub AS sc ON p.subcategoryproduct_id = sc.category_sub_id 
                  WHERE p.id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
    
        // Thực hiện truy vấn
        $stmt->execute();
    
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result(
            $id, $sku, $name, $price, $minium_quantity, $quantity, $unit, $tax, $discount, 
            $status, $description, $categoryproduct_id, $categorysub_name, $slug, 
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $subcategoryproduct_id, $short_description, $type_product
        );
    
        // Lấy dòng kết quả
        $stmt->fetch();
        $stmt->close();
        // Tạo truy vấn SELECT cho danh sách ảnh
        $imageQuery = "SELECT image FROM image_product WHERE product_id=?";
        $imageStmt = $this->conn->prepare($imageQuery);
        $imageStmt->bind_param("i", $id);
    
        // Thực hiện truy vấn ảnh
        $imageStmt->execute();
    
        // Ràng buộc kết quả trả về cho cột image
        $imageStmt->bind_result($image);
      
        // Lặp qua các dòng và lấy dữ liệu ảnh
        while ($imageStmt->fetch()) {
            $images[] = $image;
        }
        // $imageStmt->close();
        // Trả về kết quả dưới dạng mảng
        return [
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
            'categoryproduct_id' => $categoryproduct_id,
            'categorysub_name' => $categorysub_name,
            'slug' => $slug,
            'updated_at' => $updated_at,
            'created_at' => $created_at,
            'created_by' => $created_by,
            'category_name' => $category_name,
            'category_link' => $category_link,
            'code' => $code,
            'subcategoryproduct_id' => $subcategoryproduct_id,
            'short_description' => $short_description,
            'type_product' => $type_product,
            'images' => $images // Thêm danh sách ảnh vào mảng kết quả
        ];
    }
    
    
    
    public function showProduct() {
        // Tạo truy vấn SELECT với JOIN
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name= $categoryproduct_id= $short_description = $type_product =null;
    
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
    public function showProduct_search($contentseacrch) {
        // Tạo truy vấn SELECT với JOIN
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name= $categoryproduct_id= $short_description = $type_product =null;
    
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
                  WHERE p.name LIKE '%$contentseacrch%' OR p.description LIKE '%$contentseacrch%' OR p.sku LIKE '%$contentseacrch%'";
    
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
    public function showProduct_foruser($start,$perpage) {
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
    public function showProduct_forTypeProduct($type_product,$start,$perpage) {
        // Tạo truy vấn SELECT với JOIN
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name= $categoryproduct_id= $short_description =null;
    
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
                  WHERE p.type_product = '$type_product'
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
    
    public function showProductFilter($categoryproduct_id = null, $minprice = null, $maxprice = null) {
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name= $categoryid =$short_description=$type_product= null;
        // Tạo câu truy vấn SQL cơ bản
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
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $image, $image_category, $categoryproduct_id, $short_description, $type_product
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
    
    public function getProductByIds($ids) {
        $id = $sku = $name = $price = $minium_quantity = $quantity = $unit = $tax = $discount = $image_category=
        $status = $description = $slug =
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code =  $image= $categorysub_name=$short_description= $type_product =null;
        // Chuyển đổi mảng các ID thành chuỗi để sử dụng trong truy vấn SQL
        $idList = implode(',', $ids);
    
        // Tạo truy vấn SELECT với JOIN và điều kiện WHERE để chỉ lấy các sản phẩm có ID nằm trong danh sách đã cung cấp
        $query = "SELECT 
                    p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
                    p.status, p.description, sc.name , p.slug, 
                    p.updated_at, p.created_at, p.created_by,
                    c.name AS category_name, c.category_link, c.code,
                    ip.image ,c.image AS image_category, p.short_description, p.type_product
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
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $image, $image_category, $short_description, $type_product
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
                'short_description' => $short_description,
                'type_product' => $type_product
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
        $updated_at = $created_at = $created_by = $category_name = $category_link = $code = $image =$categorysub_name= $short_description= $type_product =null;
    
        $query = "SELECT 
                    p.id, p.sku, p.name, p.price, p.minium_quantity, p.quantity, p.unit, p.tax, p.discount, 
                    p.status, p.description, sc.name AS categorysub_name, p.slug, 
                    p.updated_at, p.created_at, p.created_by,
                    c.name AS category_name, c.category_link, c.code,
                    ip.image, p.short_description, p.type_product
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
            $updated_at, $created_at, $created_by, $category_name, $category_link, $code, $image, $short_description, $type_product
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
                'image' => $image,
                'short_description' => $short_description,
                'type_product' => $type_product
            ];
        }
    
        // Trả về mảng chứa tất cả các dòng kết quả
        return $results;
    }
    
    
    
    
    
}
?>
