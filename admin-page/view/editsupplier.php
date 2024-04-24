<?php require_once('./main/role_manager.php'); ?>
<?php
    require '../model/supplier_model.php';
    if (isset($_GET['supplier_id'])){
        $supplier_id = $_GET['supplier_id'];
        $supplierproduct_model = new supplierModel($conn);
        $supplier = $supplierproduct_model->getsupplier($supplier_id);
        if ($supplier == null) {
            header("Location: supplierlist.php");
        }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {
            $suppliername = $_POST['suppliername'];
            $supplieremail = $_POST['supplieremail'];
            $supplierphone = $_POST['supplierphone'];
            $suppliercountries = $_POST['suppliercountries'];
            $suppliercity = $_POST['suppliercity'];
            $supplierdistrict = $_POST['supplierdistrict'];
            $supplierzipcode = $_POST['supplierzipcode'];
            $supplieraddress = $_POST['supplieraddress'];
            $supplierdescription = $_POST['supplierdescription'];
            $suppliertype = $_POST['suppliertype'];
            $supplierimage = $_FILES['supplierimage']['name'];
            $supplierimage_tmp = $_FILES['supplierimage']['tmp_name'];
            if (isset($supplierimage) && $supplierimage != null) {
                
                $supplierupdateimage = $supplierproduct_model->unsetimagesupplier($supplier_id);
                
            }
            $supplierupdate = $supplierproduct_model->updatesupplier($supplier_id,$suppliername, $supplierphone, $supplierimage,$supplierimage_tmp, $supplieremail, $suppliertype, $suppliercountries, $suppliercity, $supplierdistrict,
             $supplieraddress, $supplierzipcode, $supplierdescription);
            if ($supplierupdate) {
                
                header("Location: supplierlist.php");
                exit();
            } else {
                // echo "error";
            } 
            
    }
    }
}
else {
    header("Location: supplierlist.php");
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
                        <h4>Chỉnh sửa thông tin nhà cung cấp</h4>
                        <h6>Edit/Update supplier</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                    <form method="post" enctype="multipart/form-data" class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên nhà cung cấp</label>
                                    <input type="text" name="suppliername" value="<?php echo $supplier['name'];?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="supplieremail" value="<?php echo $supplier['email'];?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Số điện thoại</label>
                                    <input type="tel" pattern="[0-9]{10,}" required name="supplierphone" id="phone" value="<?php echo $supplier['phone'];?>">
                                    <small id="phoneError" style="color: red;"></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Chọn quốc gia</label>
                                    <select class="select countries" id="countryId" name="suppliercountries">
                                        <option>Choose Country</option>
                                        
                                    </select>
                                    <small>Old: <?php echo $supplier['country'];?></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Vùng/Thành phố</label>
                                    <select class="select states" id="stateId" name="suppliercity">
                                        <option>Choose state/city</option>

                                    </select>
                                    <small>Old: <?php echo $supplier['city'];?></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Quận/Huyện</label>
                                    <select class="select cities" id="cityId" name="supplierdistrict">
                                        <option>Choose District</option>
                                    </select>
                                    <small>Old: <?php echo $supplier['district'];?></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Loại nhà cung cấp</label>
                                    <select class="select" name="suppliertype">
                                        <option>Choose Type</option>
                                        <option value="supplier normal" <?php echo ($supplier['type'] == 'supplier normal') ? 'selected' : ''; ?>>supplier normal</option>
                                        <option value="supplier Pro" <?php echo ($supplier['type'] == 'supplier Pro') ? 'selected' : ''; ?>>supplier Pro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Zipcode</label>
                                    <input type="number" name="supplierzipcode" value="<?php echo $supplier['zipcode'];?>">
                                </div>
                            </div>
                            <div class="col-lg-9 col-12">
                                <div class="form-group">
                                    <label>Địa chỉ</label>
                                    <input type="text" name="supplieraddress" value="<?php echo $supplier['address'];?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="supplierdescription"><?php echo $supplier['description'];?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label> Avatar</label>
                                    <div class="image-upload">
                                        <input type="file"  id="imageInput" name="supplierimage">
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
                                        if (!empty($supplier['image'])) {
                                            function formatSizeUnits($size) {
                                                $units = array('B', 'KB', 'MB', 'GB', 'TB');
                                                $i = floor(log($size, 1024));
                                                return @round($size / pow(1024, $i), 2) . ' ' . $units[$i];
                                            }
                                            
                                                $formattedSize = 'Unknown';
                                                $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/admin-page/view/' . $supplier['image'];
                                                $fileName = basename($supplier['image']);
                                                                                                    
                                                // Lấy kích thước của tệp ảnh
                                                $fileSize = filesize($absolutePath);
                                                if ($fileSize !== false) {
                                                    $formattedSize = formatSizeUnits($fileSize);
                                                }
                                                
                                                echo ' <li>
                                                <div class="productviews">
                                                        <div class="productviewsimg">
                                                            <img src="'.$supplier['image'].'" alt="img">
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
                            <div class="col-lg-12">
                                <button type="submit" name="submit" href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
                                <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
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
    <script>
   
   function ajaxCall() {
       this.send = function(data, url, method, success, type) {
           type = 'json';
           var successRes = function(data) {
               success(data);
           }
   
           var errorRes = function(xhr, ajaxOptions, thrownError) {
               
               console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
   
           }
           jQuery.ajax({
               url: url,
               type: method,
               data: data,
               success: successRes,
               error: errorRes,
               dataType: type,
               timeout: 60000
           });
   
       }
   
   }
   
   function locationInfo() {
       var rootUrl = "https://geodata.phplift.net/api/index.php";
       var call = new ajaxCall();
   
   
       this.getCities = function(id) {
           jQuery(".cities option:gt(0)").remove();
           //get additional fields
           
           var url = rootUrl+'?type=getCities&countryId='+ '&stateId=' + id;
           var method = "post";
           var data = {};
           jQuery('.cities').find("option:eq(0)").html("Please wait..");
           call.send(data, url, method, function(data) {
               jQuery('.cities').find("option:eq(0)").html("Select City");
                   var listlen = Object.keys(data['result']).length;
   
                   if(listlen > 0)
                   {
                       jQuery.each(data['result'], function(key, val) {
   
                           var option = jQuery('<option />');
                           option.attr('value', val.name).text(val.name);
                           jQuery('.cities').append(option);
                       });
                   }
                   
   
                   jQuery(".cities").prop("disabled",false);
               
           });
       };
   
       this.getStates = function(id) {
           jQuery(".states option:gt(0)").remove();
           jQuery(".cities option:gt(0)").remove();
           //get additional fields
           var stateClasses = jQuery('#stateId').attr('class');
   
           
           var url = rootUrl+'?type=getStates&countryId=' + id;
           var method = "post";
           var data = {};
           jQuery('.states').find("option:eq(0)").html("Please wait..");
           call.send(data, url, method, function(data) {
               jQuery('.states').find("option:eq(0)").html("Select State");
               
                   jQuery.each(data['result'], function(key, val) {
                       var option = jQuery('<option />');
                       option.attr('value', val.name).text(val.name);
                       option.attr('stateid', val.id);
                       jQuery('.states').append(option);
                   });
                   jQuery(".states").prop("disabled",false);
               
           });
       };
   
       this.getCountries = function() {
           var url = rootUrl+'?type=getCountries';
           var method = "post";
           var data = {};
           jQuery('.countries').find("option:eq(0)").html("Please wait..");
           call.send(data, url, method, function(data) {
               jQuery('.countries').find("option:eq(0)").html("Select Country");
               
               jQuery.each(data['result'], function(key, val) {
                   var option = jQuery('<option />');
                   
                   option.attr('value', val.name).text(val.name);
                   option.attr('countryid', val.id);
                   
                   jQuery('.countries').append(option);
               });
                   
               
           });
       };
   
   }
   
   jQuery(function() {
       var loc = new locationInfo();
       loc.getCountries();
       jQuery(".countries").on("change", function(ev) {
           var countryId = jQuery("option:selected", this).attr('countryid');
           if(countryId != ''){
               loc.getStates(countryId);
           }
           else{
               jQuery(".states option:gt(0)").remove();
           }
       });
       jQuery(".states").on("change", function(ev) {
           var stateId = jQuery("option:selected", this).attr('stateid');
           if(stateId != ''){
               loc.getCities(stateId);
           }
           else{
               jQuery(".cities option:gt(0)").remove();
           }
       });
   });
 
 
 
   
   </script>
</body>

</html>