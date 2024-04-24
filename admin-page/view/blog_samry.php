<?php require_once('./main/role_manager.php'); ?>
<?php 
require '../model/category_expense_model.php';
$category_expense = new CategoryExpenseModel($conn);
$category_expense_list = $category_expense->showCategoryExpenses_forexpense();
ob_clean();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Dreams Pos admin template</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">

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
                        <h4>Expense Add</h4>
                        <h6>Add/Update Expenses</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="post" onsubmit="CreateItem_Expense(event)" enctype="multipart/form-data" class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Expense Category</label>
                                    <select class="select" id="expensecategory" required onchange="changecategoryexpense (this)">
                                        <option value="">Choose Category</option>
                                        <?php foreach ($category_expense_list as $Category): ?>
                                        <option value="<?php echo $Category['id']; ?>"><?php echo $Category['name']; ?> (<?php echo $Category['code']; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Expense status</label>
                                    <select class="select" id="expensestatus" required>
                                        <option value="">Choose Status</option>
                                        <option value="Active">Active</option>
                                        <option value="In Active">In Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Expense Date </label>
                                    <div class="input-groupicon">
                                        <input type="date" id="dateexpenseinput">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <div class="input-groupicon">
                                        <input type="text" id="amountinputcategory" required>
                                        <div class="addonset">
                                            <img src="assets/img/icons/dollar.svg" alt="img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Expense for</label>
                                    <input type="text" id="expenfortext">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" id="textdescriptioncategory"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" name="submit" href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
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
        function CreateItem_Expense(event) {
    event.preventDefault();
    var expensecategory = $("#expensecategory").val();
    var dateexpenseinput = $("#dateexpenseinput").val();
    var amountinputcategory = $("#amountinputcategory").val();
    var expenfortext = $("#expenfortext").val();
    var textdescriptioncategory = $("#textdescriptioncategory").val();
    var statusexpense = $("#expensestatus").val();
    var create = "create";



    var productsData = new FormData();
    productsData.append("categoryex_id", expensecategory);
    productsData.append("created_at", dateexpenseinput);
    productsData.append("amountexpense", amountinputcategory);
    productsData.append("expense_for", expenfortext);
    productsData.append("descriptionexpense", textdescriptioncategory);
    productsData.append("statusexpense", statusexpense);
    productsData.append("create", create);


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
                                text: "Edit return success",
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