<?php 
class SaleModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function addSale($email, $status, $paymentMethod, $items, $discount, $tax, $date, $shipping, $description) {
         // Chuẩn bị và thực thi stored procedure
        $query = "CALL create_sale(?, ?, ?, STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s'), ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        // Tạo các biến tạm thời để truyền vào bind_param
        $bindingEmail = $email;
        $bindingStatus = $status;
        $bindingPaymentMethod = $paymentMethod;
        $bindingDate = $date;
        $bindingShipping = $shipping;
        $bindingTax = $tax;
        $bindingDiscount = $discount;
        $bindingDescription = $description;
        $bindingItems = json_encode($items);

        $stmt->bind_param("ssssddiss", $bindingEmail, $bindingStatus, $bindingPaymentMethod, $bindingDate, $bindingShipping, $bindingTax, $bindingDiscount, $bindingDescription, $bindingItems);
            return $stmt->execute();
    }
    
    public function deleteSale($sale_id) {
        // Truy vấn xóa từ bảng sale
        $query = "DELETE FROM sale WHERE sale_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $sale_id);
        
        // Thực thi truy vấn xóa từ bảng sale
        if (!$stmt->execute()) {
            return false; // Xóa không thành công
        }
    
        // Tiếp theo, truy vấn xóa từ bảng payment_detail
        $query = "DELETE FROM payment_detail WHERE sale_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $sale_id);
    
        // Thực thi truy vấn xóa từ bảng payment_detail
        if (!$stmt->execute()) {
            return false; // Xóa không thành công
        }
    
        // Cuối cùng, truy vấn xóa từ bảng sale_detail
        $query = "DELETE FROM sale_detail WHERE sale_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $sale_id);
    
        // Thực thi truy vấn xóa từ bảng sale_detail
        if (!$stmt->execute()) {
            return false; // Xóa không thành công
        }
    
        return true; // Xóa thành công từ cả ba bảng
    }
    
    
    public function updateSale($email, $status, $items, $discount, $tax, $shipping, $description, $saleID) {
        // Chuẩn bị và thực thi stored procedure
        $query = "CALL update_sale(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        // Tạo các biến tạm thời để truyền vào bind_param
        $bindingEmail = $email;
        $bindingStatus = $status;
        $bindingShipping = $shipping;
        $bindingTax = $tax;
        $bindingDiscount = $discount;
        $bindingDescription = $description;
        $bindingItems = json_encode($items);
        $bindingsaleID = $saleID;

        $stmt->bind_param("ssddissi", $bindingEmail, $bindingStatus,  $bindingShipping, $bindingTax, $bindingDiscount, $bindingDescription, $bindingItems, $bindingsaleID);
            return $stmt->execute();
    }
    
    public function getAllSales() {
        $query = "SELECT sale.*, customer.name
                  FROM sale
                  INNER JOIN customer ON sale.email = customer.email";
    
        $result = $this->conn->query($query);
    
        $sales = [];
        while ($row = $result->fetch_assoc()) {
            // Định dạng lại trường created_at
            $formatted_created_at = date("d M Y", strtotime($row['created_at']));
    
            // Thêm dữ liệu đã định dạng vào mảng
            $row['created_at'] = $formatted_created_at;
    
            $sales[] = $row;
        }
    
        $result->free_result();
    
        return $sales;
    }
    public function getAllSales_byemail($email) {
        $query = "SELECT sale.*, customer.name
                  FROM sale
                  INNER JOIN customer ON sale.email = customer.email
                  WHERE sale.email = '$email'";
    
        $result = $this->conn->query($query);
    
        $sales = [];
        while ($row = $result->fetch_assoc()) {
            // Định dạng lại trường created_at
            $formatted_created_at = date("d M Y", strtotime($row['created_at']));
    
            // Thêm dữ liệu đã định dạng vào mảng
            $row['created_at'] = $formatted_created_at;
    
            $sales[] = $row;
        }
    
        $result->free_result();
    
        return $sales;
    }
    
    
    public function getSaleById($sale_id) {
        $reference = $email = $status = $payment = $total = $ship = $grand_total = $paid = $due = $description = $biller = $updated_at = $customer_name = $country = $city = $district = $phone=$discount =$tax =$discount_id= $sale_discount_id =$max_discount=null;
        $created_at = '';
        $tax_percentage = $discount_percentage = 0; // Khởi tạo giá trị ban đầu của phần trăm tax và discount
    
        $query = "SELECT sale.*, customer.name, customer.country, customer.city, customer.district, customer.phone, discount.discount_amount, discount.discount_id as havediscount, discount.max_discount
                  FROM sale
                  INNER JOIN customer ON sale.email = customer.email
                  LEFT JOIN discount ON sale.discount_id = discount.discount_id AND discount.status=1 AND sale.total >= discount.minium_value 
                  WHERE sale.sale_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $sale_id);
        
        $stmt->execute();
        $stmt->bind_result(
            $sale_id, $reference, $email, $status, $payment, $total, $tax, $ship, $grand_total, $paid, $due,
            $description, $biller, $created_at, $updated_at,$sale_discount_id, $customer_name, $country, $city, $district, $phone, $discount, $discount_id,$max_discount
        );
        $stmt->fetch();
        
        // Chuyển đổi tax và discount thành dạng phần trăm
        // $tax_percentage = $tax * 100;
        // $discount_percentage = $discount * 100;
        $stmt->close();
        
        $formatted_created_at = date("d M Y", strtotime($created_at));
        $discountvaluetotal = $total * $discount;
        if ($discountvaluetotal > $max_discount) {
            $discountvaluetotal = $max_discount;
        }

        
        
        return [
            "sale_id" => $sale_id,
            "reference" => $reference,
            "email" => $email,
            "status" => $status,
            "payment" => $payment,
            "total" => $total,
            "tax" => $tax,
            "ship" => $ship,
            "grand_total" => $grand_total,
            "paid" => $paid,
            "due" => $due,
            "description" => $description,
            "biller" => $biller,
            "created_at" => $formatted_created_at,
            "updated_at" => $updated_at,
            "sale_discount_id" => $sale_discount_id,
            "customer_name" => $customer_name,
            "country" => $country,
            "city" => $city,
            "district" => $district,
            "phone" => $phone,
            "discount" => $discount,
            "discount_id" => $discount_id,
            "discountvaluetotal" => $discountvaluetotal
            
        ];
    }
    // public function getSaleByEmail($email_check) {
    //     $reference = $email = $status = $payment = $total = $ship = $grand_total = $paid = $due = $description = $biller = $updated_at = $customer_name = $country = $city = $district = $phone=$discount =$tax =$discount_id= $sale_discount_id =$max_discount= $sale_id=null;
    //     $created_at = '';
    //     $tax_percentage = $discount_percentage = 0; // Khởi tạo giá trị ban đầu của phần trăm tax và discount
    
    //     $query = "SELECT sale.*, customer.name, customer.country, customer.city, customer.district, customer.phone, discount.discount_amount, discount.discount_id as havediscount, discount.max_discount
    //               FROM sale
    //               INNER JOIN customer ON sale.email = customer.email
    //               LEFT JOIN discount ON sale.discount_id = discount.discount_id AND discount.status=1 AND sale.total >= discount.minium_value 
    //               WHERE sale.email = ?";
        
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param("s", $email_check);
        
    //     $stmt->execute();
    //     $stmt->bind_result(
    //         $sale_id, $reference, $email, $status, $payment, $total, $tax, $ship, $grand_total, $paid, $due,
    //         $description, $biller, $created_at, $updated_at,$sale_discount_id, $customer_name, $country, $city, $district, $phone, $discount, $discount_id,$max_discount
    //     );
    //     $stmt->fetch();
        
    //     // Chuyển đổi tax và discount thành dạng phần trăm
    //     // $tax_percentage = $tax * 100;
    //     // $discount_percentage = $discount * 100;
    //     $stmt->close();
        
    //     $formatted_created_at = date("d M Y", strtotime($created_at));
    //     $discountvaluetotal = $total * $discount;
    //     if ($discountvaluetotal > $max_discount) {
    //         $discountvaluetotal = $max_discount;
    //     }

        
        
    //     return [
    //         "sale_id" => $sale_id,
    //         "reference" => $reference,
    //         "email" => $email,
    //         "status" => $status,
    //         "payment" => $payment,
    //         "total" => $total,
    //         "tax" => $tax,
    //         "ship" => $ship,
    //         "grand_total" => $grand_total,
    //         "paid" => $paid,
    //         "due" => $due,
    //         "description" => $description,
    //         "biller" => $biller,
    //         "created_at" => $formatted_created_at,
    //         "updated_at" => $updated_at,
    //         "sale_discount_id" => $sale_discount_id,
    //         "customer_name" => $customer_name,
    //         "country" => $country,
    //         "city" => $city,
    //         "district" => $district,
    //         "phone" => $phone,
    //         "discount" => $discount,
    //         "discount_id" => $discount_id,
    //         "discountvaluetotal" => $discountvaluetotal
            
    //     ];
    // }
    public function showCategoryProducts_subSale() {
        $categoryproduct_id = $name = $category_link = $product_id = $sku = $price = $minimum_quantity = $quantity = $discount = $tax = $status = $unit = $image =$pname= null;
    
        $query = "SELECT cp.categoryproduct_id, cp.name,p.name AS pname, cp.category_link, p.id, p.sku, p.price, p.minium_quantity, p.quantity, p.discount, p.tax, p.status, p.unit, MIN(ip.image) AS image 
                  FROM categoryproduct AS cp
                  LEFT JOIN product AS p ON cp.categoryproduct_id = p.categoryproduct_id
                  LEFT JOIN image_product AS ip ON p.id = ip.product_id
                  GROUP BY p.id"; // Group by product_id to get only one image per product
    
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            die("Query error: " . $this->conn->error);
        }
    
        // Thực hiện truy vấn
        $stmt->execute();
    
        // Ràng buộc kết quả trả về cho các cột
        $stmt->bind_result($categoryproduct_id, $name,$pname, $category_link, $product_id, $sku, $price, $minimum_quantity, $quantity, $discount, $tax, $status, $unit, $image);
    
        // Tạo mảng để lưu trữ tất cả các dòng kết quả
        $results = [];
    
        // Lặp qua các dòng và lấy dữ liệu
        while ($stmt->fetch()) {
            $categoryIndex = array_search($categoryproduct_id, array_column($results, 'categoryproduct_id'));
    
            if ($categoryIndex !== false) {
                $results[$categoryIndex]["products"][] = [
                    "product_id" => $product_id,
                    "pname" => $pname,
                    "sku" => $sku,
                    "price" => $price,
                    "minimum_quantity" => $minimum_quantity,
                    "quantity" => $quantity,
                    "discount" => $discount,
                    "tax" => $tax,
                    "status" => $status,
                    "unit" => $unit,
                    "image" => $image
                ];
            } else {
                $results[] = [
                    "categoryproduct_id" => $categoryproduct_id,
                    "name" => $name,
                    "category_link" => $category_link,
                    "products" => [
                        [
                            "product_id" => $product_id,
                            "pname" => $pname,
                            "sku" => $sku,
                            "price" => $price,
                            "minimum_quantity" => $minimum_quantity,
                            "quantity" => $quantity,
                            "discount" => $discount,
                            "tax" => $tax,
                            "status" => $status,
                            "unit" => $unit,
                            "image" => $image
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
    public function showsaledetailbyId($sale_id){
        $product_id=$quantity=$total=$tax=$discount=$price=$name=$image=$sku=null;
        $query = 'SELECT s.sale_id, s.product_id, s.quantity, s.total, s.tax,s.discount, s.price,p.name, i.image,p.sku  FROM sale_detail s
        LEFT JOIN product p ON s.product_id = p.id 
        LEFT JOIN(SELECT product_id, MIN(image) as image
                    FROM image_product
                    GROUP BY product_id ) as i ON p.id = i.product_id
        WHERE sale_id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $sale_id);
        $stmt->execute();
        $stmt->bind_result($sale_id, $product_id, $quantity, $total, $tax, $discount, $price, $name, $image,$sku);
        $sale_detail = [];
        while ($stmt->fetch()) {
            $subtotal = $price * $quantity + $tax*$price*$quantity - $discount * $quantity*$price;
            $subdiscount = $discount * $quantity*$price;
            $subtax = $tax*$price*$quantity;
            $row = array(
                "sale_id" => $sale_id,
                "product_id" => $product_id,
                "quantity" => $quantity,
                "total" => $total,
                "tax" => $subtax,
                "discount" => $subdiscount,
                "price" => $price,
                "name" => $name,
                "image" => $image,
                "subtotal" => $subtotal,
                "sku" => $sku
            );
            $sale_detail[] = $row;
        }
        $stmt->close();
        return $sale_detail;
    }
    public function createSaleAndSaleDetail_cart($email, $status, $payment) {
        // Chuẩn bị và thực thi stored procedure
        $stmt = $this->conn->prepare("CALL createSaleAndSaleDetail_cart(?, ?, ?)");
        $stmt->bind_param("sss", $email, $status, $payment);
        $stmt->execute();
        $stmt->close();
        return True;
    }
    
    
    
    
    
    
}
    
?>