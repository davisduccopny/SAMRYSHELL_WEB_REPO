<?php require_once('./main/role_manager.php'); ?>
<?php
    require '../model/categorysub_model.php';
    require '../model/cagegoryproduct_model.php';
    $categoryModal = new CategoryProductModel($conn);
    $category_resultfinale = $categoryModal->showCategoryProducts();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {

        if ($categorysub == null) {
            header("Location: subcategorylist.php");
        }
                $name = $_POST['categorysub_name'];
                $categoryproduct_id = $_POST['categorysub_parencategory'];
                $code = $_POST['categorysub_code'];
                $description = $_POST['categorysub_description'];
                $created_by = $_POST['categorysub_code_createdby'];
                $categorysubModel = new CategorySubModel($conn);
                $categorysub = $categorysubModel->insertCategorySub($categoryproduct_id,$name, $code, $description, $created_by);
                if ($categorysubupdate) {
                    header("Location: subcategorylist.php");
                    exit();
                } else {
                    echo "error";
                }     
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php require_once('./main/head.php'); ?>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

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
                        <h4>Thêm danh mục phụ</h4>
                        <h6>Create new product Category</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                    <form class="row" method="post" enctype="multipart/form-data">
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Danh mục cha</label>
                                    <select class="select" name="categorysub_parencategory" required>
                                    <option value="">Choose Category</option>
                                    <?php foreach ($category_resultfinale as $category_product) {
                                            echo '<option value="'.$category_product['categoryproduct_id'].'">'.$category_product['name'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên danh mục</label>
                                    <input type="text" name="categorysub_name">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mã danh mục</label>
                                    <input type="text"  name="categorysub_code">
                                </div>
                            </div>
                            <input type="hidden" name="categorysub_code_createdby" id="categorysub_code_createdby" value="admin">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="categorysub_description"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" name="submit" href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
                                <a href="subcategorylist.php" class="btn btn-cancel">Cancel</a>
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

    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>