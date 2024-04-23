<?php require_once('./main/role_manager.php'); ?>
<?php
    require '../model/customer_model.php';
    if (isset($_GET['customer_id'])){
        $customer_id = $_GET['customer_id'];
        $customerproduct_model = new CustomerModel($conn);
        $customer = $customerproduct_model->getCustomer($customer_id);
        if ($customer == null) {
            header("Location: customerlist.php");
        }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {
            $customername = $_POST['customername'];
            $customeremail = $_POST['customeremail'];
            $customerphone = $_POST['customerphone'];
            $customercountries = $_POST['customercountries'];
            $customercity = $_POST['customercity'];
            $customerdistrict = $_POST['customerdistrict'];
            $customerzipcode = $_POST['customerzipcode'];
            $customeraddress = $_POST['customeraddress'];
            $customerdescription = $_POST['customerdescription'];
            $customertype = $_POST['customertype'];
            $customerimage = $_FILES['customerimage']['name'];
            $customerimage_tmp = $_FILES['customerimage']['tmp_name'];
            if (isset($customerimage) && $customerimage != null) {
                require '../controller/customer_controller.php';
                $customerController = new customerController($conn);
                $customerupdateimage = $customerController->unsetimagecustomer($customer_id);
                
            }
            $customerupdate = $customerproduct_model->updateCustomer($customer_id,$customername, $customerphone, $customerimage,$customerimage_tmp, $customeremail, $customertype, $customercountries, $customercity, $customerdistrict,
             $customeraddress, $customerzipcode, $customerdescription);
            if ($customerupdate) {
                
                header("Location: customerlist.php");
                exit();
            } else {
                // echo "error";
            } 
            
    }
    }
}
else {
    header("Location: customerlist.php");
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
                        <h4>Chỉnh sửa thông tin khách hàng</h4>
                        <h6>Edit/Update Customer</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                    <form method="post" enctype="multipart/form-data" class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên khách hàng</label>
                                    <input type="text" name="customername" value="<?php echo $customer['name'];?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="customeremail" value="<?php echo $customer['email'];?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Số điện thoại</label>
                                    <input type="tel" pattern="[0-9]{10,}" required name="customerphone" id="phone" value="<?php echo $customer['phone'];?>">
                                    <small id="phoneError" style="color: red;"></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Chọn quốc gia</label>
                                    <select class="select countries" id="countryId" name="customercountries">   
                                        <option>Choose Country</option>
                                        
                                    </select>
                                    <small>Old: <?php echo $customer['country'];?></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Vùng/Thành phố</label>
                                    <select class="select states" id="stateId" name="customercity">
                                        <option>Choose state/city</option>

                                    </select>
                                    <small>Old: <?php echo $customer['city'];?></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Quận/huyện</label>
                                    <select class="select cities" id="cityId" name="customerdistrict">
                                        <option>Choose District</option>
                                    </select>
                                    <small>Old: <?php echo $customer['district'];?></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Loại khách hàng</label>
                                    <select class="select" name="customertype">
                                        <option>Choose Type</option>
                                        <option value="Customer normal" <?php echo ($customer['type'] == 'Customer normal') ? 'selected' : ''; ?>>Customer normal</option>
                                        <option value="Customer Pro" <?php echo ($customer['type'] == 'Customer Pro') ? 'selected' : ''; ?>>Customer Pro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Zipcode</label>
                                    <input type="number" name="customerzipcode" value="<?php echo $customer['zipcode'];?>">
                                </div>
                            </div>
                            <div class="col-lg-9 col-12">
                                <div class="form-group">
                                    <label>Địa chỉ</label>
                                    <input type="text" name="customeraddress" value="<?php echo $customer['address'];?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="customerdescription"><?php echo $customer['description'];?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label> Avatar</label>
                                    <div class="image-upload">
                                        <input type="file"  id="imageInput" name="customerimage">
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
                                        if (!empty($customer['image'])) {
                                            function formatSizeUnits($size) {
                                                $units = array('B', 'KB', 'MB', 'GB', 'TB');
                                                $i = floor(log($size, 1024));
                                                return @round($size / pow(1024, $i), 2) . ' ' . $units[$i];
                                            }
                                            
                                                $formattedSize = 'Unknown';
                                                $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/admin-page/view/' . $customer['image'];
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 
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