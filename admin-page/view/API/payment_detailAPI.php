<?php
class Payment_detailAPI {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getPaymentDetails() {
        $paymentDetails = array();

        $query = "SELECT id, payment_name, sale_id, valueplus, status, created_at, updated_at FROM payment_detail";
        $result = mysqli_query($this->conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $paymentDetails[] = $row;
            }
            mysqli_free_result($result);
        }

        return $paymentDetails;
    }
    public function getPaymentDetailsBySaleID($sale_id, $paymentconst) {
        $paymentDetails = array();
    
        $query = "SELECT pd.id, pd.payment_name, pd.sale_id, pd.valueplus, pd.status, pd.created_at, pd.updated_at,
                  s.reference, s.biller,s.due, s.grand_total,s.paid
                  FROM payment_detail pd
                  LEFT JOIN sale s ON s.sale_id = pd.sale_id
                  WHERE pd.sale_id = ? AND pd.paymentconst = ?
                   ;";
        $stmt = mysqli_prepare($this->conn, $query);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "is", $sale_id, $paymentconst);
            mysqli_stmt_execute($stmt);
    
            mysqli_stmt_bind_result($stmt, $id, $payment_name, $sale_id, $valueplus, $status, $created_at, $updated_at, $reference, $biller, $due, $grand_total, $paid);
    
            while (mysqli_stmt_fetch($stmt)) {
                $row = array(
                    "id" => $id,
                    "payment_name" => $payment_name,
                    "sale_id" => $sale_id,
                    "valueplus" => $valueplus,
                    "status" => $status,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at,
                    "reference" => $reference,
                    "biller" => $biller,
                    "due" => $due,
                    "grand_total" => $grand_total,
                    "paid" => $paid
                );
    
                $paymentDetails[] = $row;
            }
    
            mysqli_stmt_close($stmt);
        }
    
        return $paymentDetails;
    }
    public function getPaymentDetailsBySaleID_create($sale_id, $paymentconst) {
        $paymentDetails = array();
    
        $query = "SELECT pd.id, pd.payment_name, pd.sale_id, sum(pd.valueplus) as valueplus, pd.status, pd.created_at, pd.updated_at,
                  s.reference, s.biller,s.due, s.grand_total,s.paid
                  FROM payment_detail pd
                  LEFT JOIN sale s ON s.sale_id = pd.sale_id
                  WHERE pd.sale_id = ? AND pd.paymentconst = ?
                  GROUP BY pd.sale_id ;";
        $stmt = mysqli_prepare($this->conn, $query);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "is", $sale_id, $paymentconst);
            mysqli_stmt_execute($stmt);
    
            mysqli_stmt_bind_result($stmt, $id, $payment_name, $sale_id, $valueplus, $status, $created_at, $updated_at, $reference, $biller, $due, $grand_total, $paid);
    
            while (mysqli_stmt_fetch($stmt)) {
                $row = array(
                    "id" => $id,
                    "payment_name" => $payment_name,
                    "sale_id" => $sale_id,
                    "valueplus" => $valueplus,
                    "status" => $status,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at,
                    "reference" => $reference,
                    "biller" => $biller,
                    "due" => $due,
                    "grand_total" => $grand_total,
                    "paid" => $paid
                );
    
                $paymentDetails[] = $row;
            }
    
            mysqli_stmt_close($stmt);
        }
    
        return $paymentDetails;
    }
    public function getPaymentDetailBypaymentID ($payment_detailID, $paymentconst){
        $paymentDetails = array();
    
        $query = "SELECT pd.id, pd.payment_name, pd.sale_id, pd.valueplus, pd.status, pd.created_at, pd.updated_at, s.paid, s.reference, s.grand_total,pd.note FROM payment_detail pd
        LEFT JOIN sale s ON s.sale_id = pd.sale_id WHERE id = ? AND paymentconst=?;";
        $stmt = mysqli_prepare($this->conn, $query);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "is", $payment_detailID, $paymentconst);
            mysqli_stmt_execute($stmt);
    
            mysqli_stmt_bind_result($stmt, $id, $payment_name, $sale_id, $valueplus, $status, $created_at, $updated_at, $paid, $reference, $grand_total, $note);
    
            while (mysqli_stmt_fetch($stmt)) {
                $created_at_formatted = date('Y-m-d', strtotime($created_at));
                $row = array(
                    "id" => $id,
                    "payment_name" => $payment_name,
                    "sale_id" => $sale_id,
                    "valueplus" => $valueplus,
                    "status" => $status,
                    "created_at" =>$created_at_formatted,
                    "updated_at" => $updated_at,
                    "paid" => $paid,
                    "reference" => $reference,
                    "grand_total" => $grand_total,
                    "note" => $note
                );
    
                $paymentDetails[] = $row;
            }
    
            mysqli_stmt_close($stmt);
        }
    
        return $paymentDetails;
    }
    public function getsaleIDdetailbysaleid ($sale_id){
        $saledetail = array();
        $query = "SELECT sale_id,reference,email,status,grand_total,paid,due FROM sale WHERE sale_id = ?;";
        $stmt = mysqli_prepare($this->conn, $query);
        if ($stmt){
            mysqli_stmt_bind_param($stmt, "i", $sale_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $sale_id, $reference, $email, $status, $grand_total, $paid, $due);
            while (mysqli_stmt_fetch($stmt)) {
                $row = array(
                    "sale_id" => $sale_id,
                    "reference" => $reference,
                    "email" => $email,
                    "grand_total" => $grand_total,
                    "paid" => $paid,
                    "due" => $due
                );
                $saledetail[] = $row;
            }
            mysqli_stmt_close($stmt);
        }
        
        return $saledetail; // Thêm dòng này để trả về kết quả của hàm
    }
    
    
}
require '../../config/database.php';


$productAPI = new Payment_detailAPI($conn);

if ($_GET['action'] === 'getPaymentDetails') {
    echo $productAPI->getPaymentDetails();
} else if ($_GET['action'] === 'getProductDetailsbyid') {
    
    $sale_id = $_GET['sale_id'];
    header('Content-Type: application/json');
    echo json_encode($listpaymentid =$productAPI->getPaymentDetailsBySaleID($sale_id,'salepayment')) ;
}
else if ($_GET['action'] === 'getProductDetailsbyid_create') {
    
    $sale_id = $_GET['sale_id'];
    header('Content-Type: application/json');
    echo json_encode($listpaymentid =$productAPI->getPaymentDetailsBySaleID_create($sale_id,'salepayment')) ;
}
else if ($_GET['action']==='getpaymentdetailByid'){
    $payment_detailID = $_GET['payment_detailID'];
    header('Content-Type: application/json');
    echo json_encode($listpaymentid =$productAPI->getPaymentDetailBypaymentID($payment_detailID,'salepayment')) ;
}
else if ($_GET['action']==='getsaleIDdetailbysaleid'){
    $sale_id = $_GET['sale_id'];
    header('Content-Type: application/json');
    echo json_encode($listpaymentid =$productAPI->getsaleIDdetailbysaleid($sale_id)) ;
}
else {
    echo "API is not found";
}

mysqli_close($conn);

?>