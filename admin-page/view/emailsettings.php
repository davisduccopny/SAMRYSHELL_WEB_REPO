<?php require_once('./main/role_manager.php'); ?>
<?php

    require '../model/email_model.php';
    $emailmodel = new EmailModal($conn);
    $showemail = $emailmodel->get_email_settings();

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['submiteditmail'])){
            $host = $_POST['host_setting'];
            $port = $_POST['port_setting'];
            $address = $_POST['address_setting'];
            $password = $_POST['password_setting'];
            $business_name = $_POST['business_setting'];
            $subject_forgot = $_POST['subject_forgot'];
            $subject_advertse = $_POST['subject_advertse'];
            $contentforgot = $_POST['content_forgot'];
            $contentadvertse = $_POST['content_advertse'];
            $editmail = $emailmodel->editemail($host,$port,$address,$password,$business_name, $subject_forgot,$subject_advertse,$contentforgot, $contentadvertse);
            if ($editmail){
                echo "<script> alert('edit thành công!')</script>";
                header('location: emailsettings.php');
            }
            else {
                echo "<script> alert('Đã xảy ra lỗi!')</script>";
            }
        }

    }
?><!DOCTYPE html>
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
                        <h4>Cài đặt gửi email</h4>
                        <h6>Manage Email Setting</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Mail Host <span class="manitory">*</span></label>
                                    <input type="text" name="host_setting" required value="<?php echo $showemail['host']; ?>" <?php echo $role_show_element; ?>>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Mail Port<span class="manitory">*</span></label>
                                    <input type="text" name="port_setting" required value="<?php echo $showemail['port']; ?>" <?php echo $role_show_element; ?>>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Mail Address <span class="manitory">*</span></label>
                                    <input type="email" name="address_setting"  required value="<?php echo $showemail['addressserver']; ?>" <?php echo $role_show_element; ?>>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Password <span class="manitory">*</span></label>
                                    <div class="pass-group">
                                        <input type="password" class=" pass-input" name="password_setting" required value="<?php echo $showemail['password']; ?>" <?php echo $role_show_element; ?>>
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Tên doanh nghiệp<span class="manitory">*</span></label>
                                    <input type="text" name="business_setting" required value="<?php echo $showemail['business_name']; ?>" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Tiêu đề email quên mật khẩu<span class="manitory">*</span></label>
                                    <input type="text" name="subject_forgot" required value="<?php echo $showemail['subject_forgot']; ?>" >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label name="content_forgot">Nội dung email quên mật khẩu</label>
                                    <textarea class="form-control" name="content_forgot" id="content_forgot" ><?php echo $showemail['content_forgot']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Tiêu đề email quảng cáo <span class="manitory">*</span></label>
                                    <input type="text" name="subject_advertse" required value="<?php echo $showemail['subject_advertse']; ?>" >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label name="content_advertse">Nội dung email quảng cáo</label>
                                    <textarea class="form-control" name="content_advertse" id="content_advertse" ><?php echo $showemail['content_advertse']; ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" name="submiteditmail" class="btn btn-submit me-2">Submit</button>
                                    <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
    CKEDITOR.replace('content_forgot', {
    filebrowserBrowseUrl: '../ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: '../ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: '../ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
});
CKEDITOR.replace('content_advertse', {
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