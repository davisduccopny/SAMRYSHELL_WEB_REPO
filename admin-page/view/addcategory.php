<?php require_once('./main/role_manager.php'); ?>
<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {

            require '../model/cagegoryproduct_model.php';
            $name_category = $_POST['name_category'];
            $code_category = $_POST['code_category'];
            $description_category = $_POST['description_category'];
            $image_category = $_FILES['image_category']['name'];
            $image_Category_tmp = $_FILES['image_category']['tmp_name'];
            $created_by_category = $_POST['created_by_category'];
            $categoryproduct_model = new CategoryProductModel($conn);
            $addcategoryproduct = $categoryproduct_model->insertCategoryProduct($name_category, $code_category, $description_category,$created_by_category,$image_category,$image_Category_tmp);
            if ($addcategoryproduct) {
                echo "<script>alert('Thêm thành công.');</script>";
                header("Location: categorylist.php");
            } else {
                echo "<script>alert('Thêm thất bại.');</script>";
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
                        <h4>Thêm danh mục cho sản phẩm</h4>
                        <h6>Tạo danh mục sản phẩm mới</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form class="row" method="post" enctype="multipart/form-data">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên danh mục</label>
                                    <input type="text" name="name_category">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mã danh mục</label>
                                    <input type="text" name="code_category">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="description_category"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label> Ảnh bìa danh mục</label>
                                    <div class="image-upload">
                                        <input type="file" name="image_category"  id="imageInput">
                                        <div class="image-uploads">
                                            <img src="assets/img/icons/upload.svg" alt="img">
                                            <h4>Kéo thả hoặc nhấn để upload file</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="product-list">
                                    <ul class="row" id="imageList">
                                       <!-- image list -->
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="created_by_category" id="created_by_category" value="admin">
                            <div class="col-lg-12">
                                <button href="javascript:void(0);" type="submit" name="submit" class="btn btn-submit me-2">Submit</button>
                                <a href="categorylist.php" class="btn btn-cancel">Cancel</a>
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