<?php require_once('./main/role_manager.php'); ?>
<?php 
// session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Kiểm tra xem các giá trị từ biểu mẫu đã được gửi hay chưa
        if (
            isset($_POST['sku']) && isset($_POST['product_name']) && isset($_POST['price']) &&
            isset($_POST['minium_quantity']) && isset($_POST['quantity']) && isset($_POST['unit']) &&
            isset($_POST['tax']) && isset($_POST['discount']) && isset($_POST['status']) &&
            isset($_FILES['image_upload']['name']) && isset($_POST['description']) && isset($_POST['categoryproduct_id']) &&
            isset($_POST['subcategoryID']) && isset($_POST['created_by'])
        ) {
            // Lấy các giá trị từ biểu mẫu
            $sku = $_POST['sku'];
            $name = $_POST['product_name'];
            $price = $_POST['price'];
            $minQuantity = $_POST['minium_quantity'];
            $quantity = $_POST['quantity'];
            $unit = $_POST['unit'];
            $tax = $_POST['tax'];
            $discount = $_POST['discount'];
            $status = $_POST['status'];
            $description = $_POST['description'];
            $categoryID = $_POST['categoryproduct_id'];
            $subcategoryID = $_POST['subcategoryID'];
            $created_by = $_POST['created_by'];
            $short_description = $_POST['short_description'];
            $type_product = $_POST['type_product'];
            $fileNames = $_FILES['image_upload']['name'];
            $fileTmpNames = $_FILES['image_upload']['tmp_name'];


            require '../model/product_model.php';
            
            // Xử lý tệp ảnh tải lên


            // Thêm thông tin sản phẩm vào cơ sở dữ liệu
            $productModel = new ProductModel($conn);
            $insertResult = $productModel->insertProduct($sku, $name, $price, $minQuantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryID, $subcategoryID, $created_by, $short_description, $type_product);

            if ($insertResult) {
                $lastInsertedId = mysqli_insert_id($conn);
               // Xử lý tệp ảnh tải lên
                        if (!empty($_FILES['image_upload']['name'][0])) { // Kiểm tra xem có tệp nào được tải lên hay không
                            require '../controller/Product_controller.php';
                            $productController= new ProductController($conn);
                          $inserimageproduct=  $productController->addImageproductcontroller($fileTmpNames,$fileNames,$lastInsertedId);
                        
                        }

                echo "<script>alert('Thêm sản phẩm thành công.');</script>";
                header("location: productlist.php");
            } else {
                echo "<script>alert('Thêm sản phẩm thất bại.');</script>";
            }
        } else {
            echo "Please fill out all required fields.";
        }
    }
}

require '../model/product_model.php';
require '../model/cagegoryproduct_model.php';
$productModel2 = new ProductModel($conn);
$categoryResult = $productModel2->showCategoryProducts_sub();
$categoryModal = new CategoryProductModel($conn);
$category_resultfinale = $categoryModal->showCategoryProducts();
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
                        <h4>Thêm sản phẩm</h4>
                        <h6>Tạo một sản phẩm mới</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form class="row" method="post" enctype="multipart/form-data">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên sản phẩm</label>
                                    <input type="text" name="product_name" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Danh mục</label>
                                    <select class="select" name="categoryproduct_id" id="categoryproduct_123" required onchange="handleCategoryChange(this)" >
                                        <option value="">Choose Category</option>
                                        <?php foreach ($category_resultfinale as $category_product) {
                                            echo '<option value="'.$category_product['categoryproduct_id'].'">'.$category_product['name'].'</option>';

                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Danh mục phụ</label>
                                    <select class="select" name="subcategoryID" id="category_sub_123">
                                    <!-- <option value="">Choose Sub Category</option> -->
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="categoryAndSubcategoryData" value="<?php echo htmlspecialchars($categoryResult) ?>">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tạo lập bởi</label>
                                    <select type="text" class="select" name="created_by" id="created_by" required>
                                        <option value="admin">admin</option>
                                        <option value="manager">manager</option>
                                        <option value="salesman">salesman</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Loại sản phẩm</label>
                                    <select type="text" class="select" name="type_product" id="type_product" required>
                                        <option value="Other">Choose Type Product</option>
                                        <option value="New">New</option>
                                        <option value="Sale">Sale</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select class="select" name="unit" required>
                                        <option value="">Chọn đơn vị</option>
                                        <option value="USD">USD</option>
                                        <option value="INR">INR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="CAD">CAD</option>
                                        <option value="AUD">AUD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="JPY">JPY</option>
                                        <option value="CHF">CHF</option>
                                        <option value="VND">VND</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mã sản phẩm</label>
                                    <input type="text" name="sku" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Số lượng tối thiểu (để bán)</label>
                                    <input type="number" name="minium_quantity" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Số lượng tồn kho</label>
                                    <input type="number" name="quantity" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả đầy đủ</label>
                                    <textarea class="form-control" name="description" id="description" ></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả ngắn (bắt buộc)</label>
                                    <textarea class="form-control" name="short_description" id="short_description" required ></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Thuế</label>
                                    <select class="select" name="tax">
                                        <option value="">Choose Tax</option>
                                        <option value="0.2">2%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giảm giá</label>
                                    <select class="select" name="discount">
                                        <option value="">Percentage</option>
                                        <option value="0.1">10%</option>
                                        <option value="0.2">20%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giá bán</label>
                                    <input type="text" name="price" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label> Trạng thái</label>
                                    <select class="select" name="status">
                                        <option value="Closed">Close</option>
                                        <option value="Open">Open</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Ảnh sản phẩm (có thể chọn nhiều)</label>
                                    <div class="image-upload">
                                        <input type="file" name="image_upload[]"  id="imageInput" multiple>
                                        <div class="image-uploads">
                                            <img src="assets/img/icons/upload.svg" alt="img">
                                            <h4>Kéo hoặc thả để upload file</h4>
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
                                <a href="productlist.php" class="btn btn-cancel">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
    CKEDITOR.replace('description', {
    filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
});

function handleCategoryChange(selectElement) {
    var selectedCategoryId = parseInt(selectElement.value);

    var categoryAndSubcategoryDataInput = document.getElementById('categoryAndSubcategoryData');
    var categoryAndSubcategory = JSON.parse(categoryAndSubcategoryDataInput.value);

    var subcategorySelect = document.getElementById('category_sub_123');

    // Xóa tất cả các option cũ trong subcategorySelect
    subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
    
    // Lặp qua dữ liệu categoryAndSubcategory và thêm option cho subcategorySelect nếu id category phù hợp
    categoryAndSubcategory.forEach(function(item) {
        if (item.categoryproduct_id === selectedCategoryId) {
            item.subcategories.forEach(function(subcategory) {
                var option = document.createElement('option');
                option.value = subcategory.category_sub_id;
                option.textContent = subcategory.category_sub_name;
                subcategorySelect.appendChild(option);
            });
        }
    });
}



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