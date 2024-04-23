<?php require_once('./main/role_manager.php'); ?>
<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {
            require '../model/customer_model.php';
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
            $customerproduct_model = new CustomerModel($conn);
            $checkcustomer = $customerproduct_model->listCustomers();
            $checkarraymail = [];

            foreach ($checkcustomer as $check) {
                $checkarraymail[] = $check['email'];
            }
            
            // Check trùng email
            function checkEmail($email, $checkemail) {
                $found = false;
            
                for ($i = 0; $i < count($email); $i++) {
                    if ($email[$i] === $checkemail) {

                        $found = true;
                        break;
                    }
                }
        
                return $found;
            }
              // Check trùng email
            $result = checkEmail($checkarraymail, $customeremail);
            if ($result){
                echo "<script>alert('Email đã tồn tại! Vui lòng chọn email khác!');</script>";
                // header("Location: addcustomer.php");
                // exit();
            }
            else {
            $addcustomerproduct = $customerproduct_model->insertCustomer($customername, $customerphone, $customerimage,$customerimage_tmp, $customeremail, $customertype, $customercountries, $customercity, $customerdistrict, $customeraddress, $customerzipcode, $customerdescription);
            if ($addcustomerproduct) {
                echo "<script>alert('Thêm thành công.');</script>";
                header("Location: customerlist.php");
            } else {
                echo "<script>alert('Thêm thất bại.');</script>";
            }
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
                        <h4>Thêm một khách hàng mới</h4>
                        <h6>Thêm/Cập nhật khách hàng</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên khách hàng</label>
                                    <input type="text" name="customername">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="customeremail" >
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Điện thoại</label>
                                    <input type="tel" pattern="[0-9]{10,}" name="customerphone" id="phone">
                                    <small id="phoneError" style="color: red;"></small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Chọn quốc gia</label>
                                    <select class="select countries" id="countryId" name="customercountries">
                                        <option>Choose Country</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Chọn khu vực/Thành phố</label>
                                    <select class="select states" id="stateId" name="customercity">
                                        <option>Choose state/city</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Chọn phường/xã</label>
                                    <select class="select cities" id="cityId" name="customerdistrict">
                                        <option>Choose District</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Loại khách hàng</label>
                                    <select class="select" name="customertype">
                                        <option>Choose Type</option>
                                        <option value="Customer normal">Customer normal</option>
                                        <option value="Customer Pro">Customer Pro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Zipcode</label>
                                    <input type="number" name="customerzipcode">
                                </div>
                            </div>
                            <div class="col-lg-9 col-12">
                                <div class="form-group">
                                    <label>Địa chỉ</label>
                                    <input type="text" name="customeraddress">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="customerdescription"></textarea>
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