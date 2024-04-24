<?php require_once('./main/role_manager.php'); ?>
<?php 
require '../model/category_expense_model.php';
require '../model/expense_model.php';
if (isset($_GET['expense_id'])){
$id = $_GET['expense_id'];
$category_expense = new CategoryExpenseModel($conn);
$category_expense_list = $category_expense->showCategoryExpenses_forexpense();
$expense = new ExpenseModel($conn);
$expense_list = $expense->getCategoryExpense($id);
ob_clean();
}
else
{
    header('Location: expenselist.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php require_once('./main/head.php'); ?>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

    <link rel="stylesheet" href="assets/css/animate.css">

    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">


    <?php require_once('./main/header.php'); ?>


    <?php require_once('./main/sidebar.php'); ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Chỉnh sửa chi phí</h4>
                        <h6>Add/Update Expenses</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="post" onsubmit="UpdateItem_Expense(event)" enctype="multipart/form-data" class="row">
                             <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Danh mục chi phí</label>
                                    <select class="select" id="expensecategory" required onchange="changecategoryexpense(this)">
                                        <option value="">Choose Category</option>
                                        <?php foreach ($category_expense_list as $Category){
                                             $selected = ($Category['id'] == $expense_list['categoryex_id']) ? 'selected' : '';
                                            echo '<option value="'.$Category['id'].'" '.$selected.'>'.$Category['name'].'</option>';
                                        }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái chi phí</label>
                                    <select class="select" id="expensestatus" required>
                                        <option value="">Choose Status</option>
                                        <option value="Active" <?php echo ($expense_list['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="In Active" <?php echo ($expense_list['status'] == 'In Active') ? 'selected' : ''; ?>>In Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label> Ngày </label>
                                    <div class="input-groupicon">
                                        <input type="date" id="dateexpenseinput" value="<?php echo $expense_list['created_at'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giá trị</label>
                                    <div class="input-groupicon">
                                        <input type="text" id="amountinputcategory" required value="<?php echo $expense_list['amount'];?>">
                                        <div class="addonset">
                                            <img src="assets/img/icons/dollar.svg" alt="img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Chi phí cho</label>
                                    <input type="text" id="expenfortext" value="<?php echo $expense_list['expense_for'];?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" id="textdescriptioncategory"><?php echo $expense_list['description'];?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" name="submit" class="btn btn-submit me-2">Update</button>
                                <a href="expenselist.php" class="btn btn-cancel">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

    <script src="assets/js/script.js"></script>
    <script>
        function changecategoryexpense (selectElement){
            var selectedId = parseInt(selectElement.value);
            var categoryexpense = <?php echo json_encode($category_expense_list); ?>;
            categoryexpense.forEach(element => {
                if(element.id === selectedId){
                    $("#amountinputcategory").val(element.amount);
                }
            });
        }
    </script>
    <script>
        function UpdateItem_Expense(event) {
    event.preventDefault();
    var id = <?php echo $expense_list['id']; ?>;
    var expensecategory = $("#expensecategory").val();
    var dateexpenseinput = $("#dateexpenseinput").val();
    var amountinputcategory = $("#amountinputcategory").val();
    var expenfortext = $("#expenfortext").val();
    var textdescriptioncategory = $("#textdescriptioncategory").val();
    var statusexpense = $("#expensestatus").val();
    var update = "update";



    var productsData = new FormData();
    productsData.append("id", id);
    productsData.append("categoryex_id", expensecategory);
    productsData.append("created_at", dateexpenseinput);
    productsData.append("amountexpense", amountinputcategory);
    productsData.append("expense_for", expenfortext);
    productsData.append("descriptionexpense", textdescriptioncategory);
    productsData.append("statusexpense", statusexpense);
    productsData.append("update", update);


    $.ajax({
        type: "POST",
        url: "../controller/expense_controller.php",
        data: productsData,
        contentType: false,
        processData: false, // Thêm dòng này để ngăn jQuery xử lý dữ liệu
        success: function(response) {
            console.log(response);
            if (response) {
                try {
                    var responseData = JSON.parse(response);
                    console.log(response.message);

                    try {
                        if (responseData.message === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: "Edit expense success",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "./expenselist.php";
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Edit sale error",
                            });
                        }
                    } catch (error) {
                        console.error("An error occurred:", error);
                        console.log(error);
                    }
                } catch (error) {
                    console.error("Lỗi phân tích JSON: " + error.message);
                    console.log(error);
                }
            } else {
                console.error("Không có dữ liệu JSON được trả về từ máy chủ.");
            }
        },
        error: function(error) {
            // Xử lý lỗi (nếu có)
            console.log(error);
            console.error("Lỗi khi gửi dữ liệu:", error);
        },
    });
}
    </script>
</body>

</html>