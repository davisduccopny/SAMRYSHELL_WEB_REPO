<?php require_once('./main/role_manager.php'); ?>
<?php
    if (isset($_GET['categoryexpense_id'])) {
        require '../model/category_expense_model.php';
        $categoryexpense_model = new CategoryExpenseModel($conn);
        $categoryexpense_id = $_GET['categoryexpense_id'];
        $categoryexpense = $categoryexpense_model->getCategoryExpense($categoryexpense_id);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {
            $name_category = $_POST['categoryexpensename'];
            $code_category = $_POST['codecategoryexpenseinput'];
            $description_category = $_POST['textdescriptioncategory'];
            $status_category = $_POST['selectcategoryexpensestatus'];
            $amount_category = $_POST['amountinputcategory'];

            
            $editcatrgory_expense = $categoryexpense_model->updateCategoryExpense($categoryexpense_id, $name_category, $code_category, $description_category, $status_category, $amount_category);
            if ($editcatrgory_expense) {
                header("Location: expensecategory.php?action_alert_expense=edit-success");
            } else {
                echo "<script>alert('Thêm thất bại.');</script>";
            }
    }
    }
}
else {
    header("Location: expensecategory.php");
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
                        <h4>Chỉnh sửa danh mục chi phí</h4>
                        <h6>Add/Update Expenses category</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên danh mục.</label>
                                    <input type="text" name="categoryexpensename" value="<?php echo $categoryexpense['name'] ?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="select" name="selectcategoryexpensestatus">
                                        <option value="">Choose Category</option>
                                        <option value="Active" <?php echo ($categoryexpense['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="In Active" <?php echo ($categoryexpense['status'] == 'In Active') ? 'selected' : ''; ?>>In Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giá trị</label>
                                    <div class="input-groupicon">
                                        <input type="text" name="amountinputcategory" value="<?php echo $categoryexpense['amount'] ?>">
                                        <div class="addonset">
                                            <img src="assets/img/icons/dollar.svg" alt="img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mã tham chiếu.</label>
                                    <input type="text" name="codecategoryexpenseinput" value="<?php echo $categoryexpense['code'] ?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="textdescriptioncategory"><?php echo $categoryexpense['description'] ?></textarea>
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
</body>

</html>