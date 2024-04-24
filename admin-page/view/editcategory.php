<?php require_once('./main/role_manager.php'); ?>
<?php
    require '../model/cagegoryproduct_model.php';
    if (isset($_GET['categoryproduct_id'])){
        $categoryproduct_id = $_GET['categoryproduct_id'];
        $categoryproductModel = new CategoryProductModel($conn);
        $categoryproduct = $categoryproductModel->getCategoryProduct($categoryproduct_id);
        if ($categoryproduct == null) {
            header("Location: categorylist.php");
        }
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if (isset($_POST['submit'])) {
                $name = $_POST['name_category'];
                // $category_link = $_POST['category_link'];
                $code = $_POST['code_category'];
                $description = $_POST['description_category'];
                $created_by = $_POST['created_by_category'];
                $image = $_FILES['image_category']['name'];  
                $imagetmp = $_FILES['image_category']['tmp_name'];
                if (isset($image) && $image != null) {
                    require '../controller/categoryProduct_controller.php';
                    $categoryproductController = new CategoryProductController($conn);
                    $categoryupdateimage = $categoryproductController->unsetimagecategoryProduct($categoryproduct_id);
                }
                $categoryupdate = $categoryproductModel->updateCategoryProduct($categoryproduct_id,$name, $code, $description, $created_by, $image, $imagetmp);
                if ($categoryupdate) {
                  
                    header("Location: categorylist.php");
                    exit();
                } else {
                    echo "error";
                } 
               
            }
        }
    } else {
        header("Location: categorylist.php");
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
                        <h4>Chỉnh sửa danh mục sản phẩm</h4>
                        <h6>Edit a product Category</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                    <form class="row" method="post" enctype="multipart/form-data">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên danh mục</label>
                                    <input type="text" name="name_category" value="<?php echo $categoryproduct['name']?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mã danh mục</label>
                                    <input type="text" name="code_category" value="<?php echo $categoryproduct['code']?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="description_category"><?php echo $categoryproduct['description']?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label> Ảnh danh mục</label>
                                    <div class="image-upload">
                                        <input type="file" name="image_category"  id="imageInput">
                                        <div class="image-uploads">
                                            <img src="assets/img/icons/upload.svg" alt="img">
                                            <h4>kéo thả hoặc nhấn để upload file</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="product-list">
                                    <ul class="row" id="imageList">
                                       <!-- image list -->
                                       <?php 

                                        if (!empty($categoryproduct['image'])) {
                                            function formatSizeUnits($size) {
                                                $units = array('B', 'KB', 'MB', 'GB', 'TB');
                                                $i = floor(log($size, 1024));
                                                return @round($size / pow(1024, $i), 2) . ' ' . $units[$i];
                                            }
                                            
                                                $formattedSize = 'Unknown';
                                                $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/admin-page/view/' . $categoryproduct['image'];
                                                $fileName = basename($categoryproduct['image']);
                                                                                                    
                                                // Lấy kích thước của tệp ảnh
                                                $fileSize = filesize($absolutePath);
                                                if ($fileSize !== false) {
                                                    $formattedSize = formatSizeUnits($fileSize);
                                                }
                                                
                                                echo ' <li>
                                                <div class="productviews">
                                                        <div class="productviewsimg">
                                                            <img src="'.$categoryproduct['image'].'" alt="img">
                                                        </div>
                                                        <div class="productviewscontent">
                                                            <div class="productviewsname">
                                                                <h2>'.$fileName.'</h2>
                                                                <h3>'.$formattedSize .'</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </li>';
                                            
                                        } else {
                                            echo 'No images available<br>';
                                        }

                                ?>
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