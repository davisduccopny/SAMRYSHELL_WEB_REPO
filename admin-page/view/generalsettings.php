<?php require_once('./main/role_manager.php'); ?>
<?php
    require '../model/general_model.php';
        $general_id = 1;
        $generalModel = new GeneralModel($conn);
        $customer = $generalModel->getGeneral($general_id);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $time_zone = $_POST['time_zone'];
            $address = $_POST['address'];
            $fax = $_POST['fax'];
            $date_format = $_POST['date_format'];
            $currency = $_POST['currency'];
            $image = $_FILES['image']['name'];
            $imagetmp = $_FILES['image']['tmp_name'];
            if (isset($customerimage) && $customerimage != null) {
                require '../controller/general_controller.php';
                $customerController = new GeneralController($conn);
                $customerupdateimage = $customerController->unsetimagecustomer($general_id);
                
            }
            $customerupdate = $generalModel-> updateGeneral($general_id, $name, $phone, $image, $imagetmp, $email, $time_zone, $address, $fax, $date_format, $currency);
            header("Location: generalsettings.php");
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
                        <h4>Cài đặt thông tin hiển thị</h4>
                        <h6>Manage General Setting</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tiêu đề trang <span class="manitory">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="Enter Title" value="<?php echo $customer['title'] ?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Time Zone </label>
                                    <select class="select" name="time_zone" id="time_zone">
                                        <option>Choose Time Zone </option>
                                        <option value="GMT+7" <?php echo ($customer['time_zone'] == 'GMT+7') ? 'selected' : ''; ?>>GMT+7</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tiền tệ <span class="manitory">*</span></label>
                                    <select class="select" name="currency" id="currency">
                                        <option value="" >Choose Currency </option>
                                        <option value="VND" <?php echo ($customer['currency'] == 'VND') ? 'selected' : ''; ?>>VND</option>
                                        <option value="USD" <?php echo ($customer['currency'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Định dạng ngày<span class="manitory">*</span></label>
                                    <select class="select" name="date_format" id="date_format">
                                        <option value="">Choose Date Format </option>
                                        <option value="DD/MM/YYYY" <?php echo ($customer['date_format'] == 'DD/MM/YYYY') ? 'selected' : ''; ?>>DD/MM/YYYY</option>
                                        <option value="MM/DD/YYYY" <?php echo ($customer['date_format'] == 'MM/DD/YYYY') ? 'selected' : ''; ?>>MM/DD/YYYY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Email<span class="manitory">*</span></label>
                                    <textarea name="email" id="email" cols="30" rows="10" placeholder="Enter email"><?php echo $customer['email']?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Điện thoại<span class="manitory">*</span></label>
                                    <textarea name="phone" id="phone" cols="30" rows="10" placeholder="Enter phone"><?php echo $customer['phone']?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Địa chỉ<span class="manitory">*</span> </label>
                                    <textarea name="address" id="address" cols="30" rows="10" placeholder="Enter address"><?php echo $customer['address']?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Fax<span class="manitory">*</span> </label>
                                    <textarea name="fax" id="fax" cols="30" rows="10" placeholder="Enter fax"><?php echo $customer['fax']?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Ảnh logo</label>
                                    <div class="image-upload">
                                        <input type="file" name="image"  id="imageInput" multiple>
                                        <div class="image-uploads">
                                            <img src="assets/img/icons/upload.svg" alt="img">
                                            <h4>Kéo thả hoặc nhấn để tải lên logo</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="product-list">
                                    <ul class="row" id="imageList">
                                       <!-- image list -->
                                       <?php 
                                       if (!empty($customer['image'])) {
                                            function formatSizeUnits($size) {
                                                $units = array('B', 'KB', 'MB', 'GB', 'TB');
                                                $i = floor(log($size, 1024));
                                                return @round($size / pow(1024, $i), 2) . ' ' . $units[$i];
                                            }
                                            
                                                $formattedSize = 'Unknown';
                                                $absolutePath =$customer['image'];
                                                $fileName = basename($customer['image']);
                                                                                                    
                                                // Lấy kích thước của tệp ảnh
                                                $fileSize = filesize($absolutePath);
                                                if ($fileSize !== false) {
                                                    $formattedSize = formatSizeUnits($fileSize);
                                                }
                                                
                                                echo ' <li>
                                                <div class="productviews">
                                                        <div class="productviewsimg">
                                                            <img src="'.$customer['image'].'" alt="img">
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
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" name="submit" href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
                                    <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                                </div>
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