<?php
    require '../config/database.php';
    require '../model/sale_return_model.php';

    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        if (isset($_POST['create'])) {
            $reference = $_POST['reference'];
            $status = $_POST['status'];
            $paymentstatus = $_POST['statuspayment'];
            $paymentname = $_POST['paymentname'];
            $reason = $_POST['reason'];
            $product_id = $_POST['product_id'];
            $returndate = $_POST['returndate'];
            $salereturnModel = new SaleReturnModel($conn);
            $addreturn = $salereturnModel->addSaleReturn($reference, $status, $paymentstatus, $paymentname, $reason, $product_id, $returndate);
            if ($addreturn) {
                $response['message'] = "success";
            } else {
                $response['message'] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
                error_log("Lỗi khi xử lý dữ liệu: " . $stmt->error);
            }
            // header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
        else if (isset($_POST['delete'])){
            $returnID = $_POST['return_id'];
            $salereturnModel = new SaleReturnModel($conn);
            $deletereturn = $salereturnModel->deleteSale_return($returnID);
            if ($deletereturn) {
                $response = "success";
            } else {
                $response= "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
                error_log("Lỗi khi xử lý dữ liệu: " . $stmt->error);
            }
            echo $response;
            exit();
        }
        else if (isset($_POST['update'])){

            $status = $_POST['status'];
            $paymentstatus = $_POST['statuspayment'];
            $paymentname = $_POST['paymentname'];
            $reason = $_POST['reason'];
            $return_id = $_POST['return_id'];
            $returndate = $_POST['returndate'];
            $salereturnModel = new SaleReturnModel($conn);
            $updatereturn = $salereturnModel->updateSale_return( $status, $paymentstatus, $paymentname, $reason,  $returndate , $return_id);
            if ($updatereturn) {
                $response['message'] = "success";
            } else {
                $response['message'] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
                error_log("Lỗi khi xử lý dữ liệu: " . $stmt->error);
            }
            // header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }

    }
?>