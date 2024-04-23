<?php 
require '../config/database.php';
require '../model/expense_model.php';
    if (isset($_POST['create'])){
        $categoryex_id = $_POST['categoryex_id'];
        $statusexpense = $_POST['statusexpense'];
        $descriptionexpense = $_POST['descriptionexpense'];
        $amountexpense = $_POST['amountexpense'];
        $expense_for = $_POST['expense_for'];
        $created_at = $_POST['created_at'];
        $expenseModel = new ExpenseModel($conn);
        $addexpense = $expenseModel->insertExpense($categoryex_id, $expense_for ,$descriptionexpense, $statusexpense, $amountexpense,$created_at);
        if ($addexpense) {
            $response['message'] = "success";
        } else {
            $response['message'] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
            error_log("Lỗi khi xử lý dữ liệu: " . $stmt->error);
        }
        echo json_encode($response);
    }
    if (isset($_POST['update'])){
        $id = $_POST['id'];
        $categoryex_id = $_POST['categoryex_id'];
        $statusexpense = $_POST['statusexpense'];
        $descriptionexpense = $_POST['descriptionexpense'];
        $amountexpense = $_POST['amountexpense'];
        $expense_for = $_POST['expense_for'];
        $created_at = $_POST['created_at'];
        $expenseModel = new ExpenseModel($conn);
        $editexpense = $expenseModel->updateExpense($id, $categoryex_id,$expense_for ,$descriptionexpense, $statusexpense, $amountexpense, $created_at);
        if ($editexpense) {
            $response['message'] = "success";
        } else {
            $response['message'] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
            error_log("Lỗi khi xử lý dữ liệu: " . $stmt->error);
        }
        echo json_encode($response);
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['expense_id'];
        $expenseModel = new ExpenseModel($conn);
        $deleteexpense = $expenseModel->deleteExpense($id);
        if ($deleteexpense) {
            $response="success";
        } else {
            $response['message'] = "Đã xảy ra lỗi khi xử lý dữ liệu: " . $stmt->error;
            error_log("Lỗi khi xử lý dữ liệu: " . $stmt->error);
        }
        echo $response;
    }
?>