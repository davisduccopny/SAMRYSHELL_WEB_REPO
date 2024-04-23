<?php
    class saleReturnAPI {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }
        public function getALLsaleforreturnAPI(){
            $sale = array();
            $query = "SELECT  sale.reference FROM sale WHERE status = 'Complete';";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $sale[] = $row;
                }
                mysqli_free_result($result);
            }
            return json_encode($sale);
            
        }
        public function getProductDetailBySaleReference($reference) {
            $product = array();
        
            // Thực hiện kiểm tra dữ liệu đầu vào và xử lý an toàn (ví dụ: sử dụng mysqli_real_escape_string)
            $reference = mysqli_real_escape_string($this->conn, $reference);
        
            $query = "SELECT sd.sale_id, sd.tax, sd.discount, sd.minium_quantity, sd.total, sd.quantity, sd.price, p.name, sd.product_id,
            i.image, p.quantity as stock,p.sku,s.email,IFNULL(s.tax,0),IFNULL(d.discount_amount,0) as discountvalue,IFNULL(s.ship,0) as shipsale,s.total as totalsale
            FROM sale_detail sd 
            LEFT JOIN sale s ON s.sale_id = sd.sale_id
            LEFT JOIN discount d ON d.discount_id = s.discount_id
            LEFT JOIN product p ON p.id = sd.product_id
            LEFT JOIN (SELECT product_id, MIN(image) as image
                        FROM image_product
                        GROUP BY product_id) as i ON p.id = i.product_id 
            WHERE s.reference = '$reference';";
        
            $result = mysqli_query($this->conn, $query);
            
            if (!$result) {
                // Xử lý lỗi truy vấn
                return json_encode(array('error' => 'Error executing query.'));
            }
        
            while ($row = mysqli_fetch_assoc($result)) {
                $product[] = $row;
            }
        
            mysqli_free_result($result);
        
            return $product;
        }
        public function getdetailreturn ($sale_id){
            $product = array();
        
            // Thực hiện kiểm tra dữ liệu đầu vào và xử lý an toàn (ví dụ: sử dụng mysqli_real_escape_string)
            $sale_id = mysqli_real_escape_string($this->conn, $sale_id);
        
            $query = "SELECT 	
            id,	
            sale_id	,
            reference,	
            email,
            product_id	,
            quantity,	
            status,	
            payment,	
            total,	
            grand_total	,
            paid,	
            due	,
            reason,	
            created_at,
            updated_at
            FROM sale_return
            WHERE sale_id = '$sale_id';
            ";
        
            $result = mysqli_query($this->conn, $query);
            
            if (!$result) {
                // Xử lý lỗi truy vấn
                return json_encode(array('error' => 'Error executing query.'));
            }
        
            while ($row = mysqli_fetch_assoc($result)) {
                $product[] = $row;
            }
        
            mysqli_free_result($result);
        
            return $product;
        }
        


        
    }

    if (isset($_POST['reference'])) {
        require '../../config/database.php';
        $reference = $_POST['reference'];
        $saleReturnAPI = new saleReturnAPI($conn);
        echo json_encode($saledetail345 = $saleReturnAPI->getProductDetailBySaleReference($reference));
    }
    if (isset($_POST['sale_id'])) {
        require '../../config/database.php';
        $sale_id = $_POST['sale_id'];
        $saleReturnAPI = new saleReturnAPI($conn);
        echo json_encode($saledetail345345 = $saleReturnAPI->getdetailreturn($sale_id));
    }
?>