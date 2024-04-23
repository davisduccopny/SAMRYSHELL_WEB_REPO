<?php require_once('./main/role_manager.php'); ?>
<?php

    require '../model/blog_model.php';

    $Blog_model = new BlogModel($conn);
    $showcategory = $Blog_model->showCategory_blog();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {

            $name_blog = $_POST['name_blog'];
            $content_blog = $_POST['content_blog'];
            $description_blog = $_POST['description_blog'];
            $image_blog = $_FILES['image_blog']['name'];
            $image_blog_tmp = $_FILES['image_blog']['tmp_name'];
            $created_by_blog = $_SESSION['email_manager'];
            $category_id = $_POST['category_id'];
           
            $addcategoryproduct = $Blog_model->insertBlog($name_blog,$description_blog,$content_blog,$created_by_blog,$category_id,$image_blog,$image_blog_tmp);
            if ($addcategoryproduct) {
                echo "<script>alert('Thêm thành công.');</script>";
                header("Location: bloglist.php");
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../ckeditor/ckeditor.js"></script>
    <script src="../ckfinder/ckfinder.js"></script>
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
                        <h4>Thêm một bài viết mới</h4>
                        <h6>Tạo ra bài viết của bạn</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form class="row" method="post" enctype="multipart/form-data">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tiêu đề</label>
                                    <input type="text" name="name_blog">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Danh mục</label>
                                    <select class="select" name="category_id" required >
                                        <?php foreach ($showcategory as $category_product) {
                                                echo '<option value="'.$category_product['id'].'">'.$category_product['name'].'</option>';

                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="description_blog" ></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Nội dung</label>
                                    <textarea type="text" name="content_blog" id="content_blog"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Ảnh bìa</label>
                                    <div class="image-upload">
                                        <input type="file" name="image_blog"  id="imageInput">
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
    <script>
            CKEDITOR.replace('content_blog', {
    filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
});
    </script>

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