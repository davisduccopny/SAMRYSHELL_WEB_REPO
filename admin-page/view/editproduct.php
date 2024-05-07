<?php require_once('./main/role_manager.php'); ?>
<?php 
require '../model/product_model.php';
require '../model/cagegoryproduct_model.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

$productModel2 = new ProductModel($conn);
$productInfo= $productModel2->getProduct($id);
$categoryResult = $productModel2->showCategoryProducts_sub();
$categoryModal = new CategoryProductModel($conn);
$category_resultfinale = $categoryModal->showCategoryProducts();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['submit'])) {
            
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
                $type_product= $_POST['type_product'];
                $fileNames = $_FILES['image_upload']['name'];
                $fileTmpNames = $_FILES['image_upload']['tmp_name'];
                $productUpdate = $productModel2 -> updateProduct($id, $sku, $name, $price, $minQuantity, $quantity, $unit, $tax, $discount, $status, $description, $categoryID, $subcategoryID, $created_by, $short_description, $type_product);
                if ($productUpdate){

                if(!empty($_FILES['image_upload']['name'][0])){
                    require '../controller/Product_controller.php';
                    $productController = new ProductController($conn);
                    $addTrashimagecontroller = $productController->addImageproductTrashcontroller($id);
                    $updateimageproductcontroller = $productController->updateImageproductcontroller($fileTmpNames, $fileNames, $id );
                    
                }
                 }
                
                echo "<script>alert('Thêm sản phẩm thành công.');</script>";
                header("location: productlist.php");
          
        }
    }
}
else {
    echo '<script>alert("Không tìm thấy sản phẩm");</script>';
    header('Location: productlist.php');
    exit();
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
                        <h4>Chỉnh sửa thông tin sản phẩm</h4>
                        <h6>Edit old product</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form class="row" method="post" enctype="multipart/form-data">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên sản phẩm</label>
                                    <input type="text" name="product_name" required value="<?php echo $productInfo['name']?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Danh mục sản phẩm</label>
                                    <select class="select" name="categoryproduct_id" id="categoryproduct_123" required onchange="handleCategoryChange(this)">
                                    <option value="">Choose Category</option>
                                    <?php foreach ($category_resultfinale as $category_product) {
                                        $selected = ($category_product['categoryproduct_id'] == $productInfo['categoryproduct_id']) ? 'selected' : '';
                                        echo '<option value="'.$category_product['categoryproduct_id'].'" '.$selected.'>'.$category_product['name'].'</option>';
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
                                    <option value="<?php echo $productInfo['subcategoryproduct_id']?>"><?php echo $productInfo['categorysub_name']?></option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="categoryAndSubcategoryData" value="<?php echo htmlspecialchars($categoryResult) ?>">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tạo bởi</label>
                                    <select type="text" class="select" name="created_by" id="created_by" required>
                                        <option value="admin" <?php echo ($productInfo['created_by'] === 'admin') ? 'selected' : ''; ?>>admin</option>
                                        <option value="manager" <?php echo ($productInfo['created_by'] === 'manager') ? 'selected' : ''; ?>>manager</option>
                                        <option value="salesman" <?php echo ($productInfo['created_by'] === 'salesman') ? 'selected' : ''; ?>>salesman</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Loại sản phẩm</label>
                                    <select type="text" class="select" name="type_product" id="type_product">
                                        <option value="Other" <?php echo ($productInfo['type_product'] === 'Other') ? 'selected' : ''; ?>>Choose Type Product</option>
                                        <option value="New" <?php echo ($productInfo['type_product'] === 'New') ? 'selected' : ''; ?>>New</option>
                                        <option value="Sale" <?php echo ($productInfo['type_product'] === 'Sale') ? 'selected' : ''; ?>>Sale</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Đơn vị</label>
                                    <select class="select" name="unit" required>
                                        <option value="">Choose Unit</option>
                                        <option value="USD" <?php echo ($productInfo['unit'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                                        <option value="INR" <?php echo ($productInfo['unit'] == 'INR') ? 'selected' : ''; ?>>INR</option>
                                        <option value="GBP" <?php echo ($productInfo['unit'] == 'GBP') ? 'selected' : ''; ?>>GBP</option>
                                        <option value="CAD" <?php echo ($productInfo['unit'] == 'CAD') ? 'selected' : ''; ?>>CAD</option>
                                        <option value="AUD" <?php echo ($productInfo['unit'] == 'AUD') ? 'selected' : ''; ?>>AUD</option>
                                        <option value="EUR" <?php echo ($productInfo['unit'] == 'EUR') ? 'selected' : ''; ?>>EUR</option>
                                        <option value="JPY" <?php echo ($productInfo['unit'] == 'JPY') ? 'selected' : ''; ?>>JPY</option>
                                        <option value="CHF" <?php echo ($productInfo['unit'] == 'CHF') ? 'selected' : ''; ?>>CHF</option>
                                        <option value="VND" <?php echo ($productInfo['unit'] == 'VND') ? 'selected' : ''; ?>>VND</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mã sản phẩm</label>
                                    <input type="text" name="sku" required value="<?php echo $productInfo['sku']?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Số lượng tối thiểu (để bán)</label>
                                    <input type="number" name="minium_quantity" required value="<?php echo $productInfo['minium_quantity']?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tồn kho</label>
                                    <input type="number" name="quantity" required value="<?php echo $productInfo['quantity']?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả ngắn (bắt buộc)</label>
                                    <textarea class="form-control" name="short_description" id="short_description" ><?php echo $productInfo['short_description']?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả đầy đủ</label>
                                    <textarea class="form-control" name="description" id="description" ><?php echo $productInfo['description']?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Thuế</label>
                                    <select class="select" name="tax">
                                        <option value="">Choose Tax</option>
                                        <option value="0.2" <?php echo ($productInfo['tax'] == '0.2') ? 'selected' : ''; ?>>2%</option>
                                        <option value="0.5" <?php echo ($productInfo['tax'] == '0.5') ? 'selected' : ''; ?>>50%</option>
                                        <option value="0.1" <?php echo ($productInfo['tax'] == '0.1') ? 'selected' : ''; ?>>10%</option>
                                        <option value="0.15" <?php echo ($productInfo['tax'] == '0.15') ? 'selected' : ''; ?>>15%</option>
                                        <option value="0.25" <?php echo ($productInfo['tax'] == '0.25') ? 'selected' : ''; ?>>25%</option>
                                        <option value="0.3" <?php echo ($productInfo['tax'] == '0.3') ? 'selected' : ''; ?>>30%</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Loại giảm giá</label>
                                    <select class="select" name="discount">
                                        <option value="">Percentage</option>
                                        <option value="0.1" <?php echo ($productInfo['discount'] == '0.1') ? 'selected' : ''; ?>>10%</option>
                                        <option value="0.2" <?php echo ($productInfo['discount'] == '0.2') ? 'selected' : ''; ?>>20%</option>
                                        <option value="0.3" <?php echo ($productInfo['discount'] == '0.3') ? 'selected' : ''; ?>>30%</option>
                                        <option value="0.4" <?php echo ($productInfo['discount'] == '0.4') ? 'selected' : ''; ?>>40%</option>
                                        <option value="0.5" <?php echo ($productInfo['discount'] == '0.5') ? 'selected' : ''; ?>>50%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giá sản phẩm</label>
                                    <input type="number" name="price" value="<?php echo $productInfo['price']?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label> TRạng thái</label>
                                    <select class="select" name="status">
                                        <option value="Closed" <?php echo ($productInfo['status'] == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                                        <option value="Open" <?php echo ($productInfo['status'] == 'Open') ? 'selected' : ''; ?>>Open</option>
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
                                            <h4>Kéo thả hoặc nhấn để upload file</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="product-list">
                                    <ul class="row" id="imageList">
                                       <!-- image list -->
                                       <?php 

                                                if (!empty($productInfo['images'])) {
                                                    function formatSizeUnits($size) {
                                                        $units = array('B', 'KB', 'MB', 'GB', 'TB');
                                                        $i = floor(log($size, 1024));
                                                        return @round($size / pow(1024, $i), 2) . ' ' . $units[$i];
                                                    }
                                                    foreach ($productInfo['images'] as $image) {
                                                        $formattedSize = 'Unknown'; 
                                                        $absolutePath =$image;
                                                        $fileName = basename($image);
                                                                                                              
                                                        // Lấy kích thước của tệp ảnh
                                                        $fileSize = filesize($absolutePath);
                                                        if ($fileSize !== false) {
                                                            $formattedSize = formatSizeUnits($fileSize);
                                                        }
                                                        
                                                        echo ' <li>
                                                        <div class="productviews">
                                                                <div class="productviewsimg">
                                                                    <img src="'.$image.'" alt="img">
                                                                </div>
                                                                <div class="productviewscontent">
                                                                    <div class="productviewsname">
                                                                        <h2>'.$fileName.'</h2>
                                                                        <h3>'.$formattedSize .'</h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </li>';
                                                    }
                                                } else {
                                                    echo 'No images available<br>';
                                                }

                                                ?>
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