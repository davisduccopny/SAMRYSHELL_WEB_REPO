<?php require_once('./main/role_manager.php'); ?>
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
                        <h4>User Management</h4>
                        <h6>Add/Update User</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" onsubmit="AddUserManager (event)"  class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>First name</label>
                                    <input type="text" id="firtnameusermanager">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" id="emailusermanager">
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select" id="statususermanager">
                                        <option value="">Select Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Restricted">Restricted</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="pass-group">
                                        <input type="password" class=" pass-input" id="passusermanager" oninput="checkPassword(this)">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                        <span id="password-error" style="color: red;"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                 <div class="form-group">
                                    <label>Last name</label>
                                    <input type="text" id="lastnameusermanger">
                                </div>
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <input type="text" id="phoneusermanager">
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="select" id="roleusermanager">
                                        <option value="">Select Role</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Salesman">Salesman</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <div class="pass-group">
                                        <input type="password" class=" pass-inputs" id="confirmpassusermanager">
                                        <span class="fas toggle-passworda fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label> Profile Picture</label>
                                    <div class="image-upload image-upload-new">
                                        <input type="file"  id="imageInput" name="customerimage">
                                        <div class="image-uploads">
                                            <img src="assets/img/icons/upload.svg" alt="img">
                                            <h4>Drag and drop a file to upload</h4>
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
                                <button type="submit" name="submit"  href="javascript:void(0);"  class="btn btn-submit me-2">Submit</button>
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

    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <script src="assets/plugins/toastr/toastr.js"></script>

    <script src="assets/js/script.js"></script>
    <script>
        
function AddUserManager(event) {
    // Ngăn chặn hành vi gửi form mặc định
    event.preventDefault();

    // Lấy giá trị từ các trường input
    var firtnameusermanager = $('#firtnameusermanager').val();
    var lastnameusermanger = $('#lastnameusermanger').val();
    var emailusermanager = $('#emailusermanager').val();
    var phoneusermanager = $('#phoneusermanager').val();
    var statususermanager = $('#statususermanager').val();
    var roleusermanager = $('#roleusermanager').val();
    var passusermanager = $('#passusermanager').val();
    var confirmpassusermanager = $('#confirmpassusermanager').val();
    var imageInput = $('#imageInput')[0].files[0];
    if (!firtnameusermanager || !lastnameusermanger || !emailusermanager || !phoneusermanager || !statususermanager || !roleusermanager || !passusermanager || !confirmpassusermanager || !imageInput) {
        // Hiển thị thông báo lỗi bằng Swal
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Vui lòng nhập đầy đủ thông tin!',
        }).then((result) => {
            if (result.isConfirmed) {
                return;
            }
        });
    } else {
    // Kiểm tra mật khẩu xác nhận
    if (passusermanager != confirmpassusermanager) {
        // Hiển thị thông báo lỗi bằng Swal
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Mật khẩu không khớp!',
        }).then((result) => {
            if (result.isConfirmed) {
                return;
            }
        });
    } else {
        // Tạo đối tượng FormData để gửi dữ liệu
        var formData = new FormData();
        formData.append('firstname', firtnameusermanager);
        formData.append('lastname', lastnameusermanger);
        formData.append('email', emailusermanager);
        formData.append('phone', phoneusermanager);
        formData.append('status', statususermanager);
        formData.append('role', roleusermanager);
        formData.append('pass', passusermanager);
        formData.append('confirm', confirmpassusermanager);
        formData.append('image', imageInput);
        formData.append('create', 'create');

        // Kiểm tra sự tồn tại của email trên máy chủ
        $.ajax({
            url: '../controller/user_manager_controller.php',
            method: 'POST',
            data: { email: emailusermanager, check_email: 1 },
            success: function (response) {
                console.log(response);
                var exists = JSON.parse(response).exists;

                if (exists) {
                    // Hiển thị thông báo lỗi bằng Swal
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Email đã tồn tại!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            return;
                        }
                    });
                } else {
                    // Nếu email không tồn tại, thực hiện thêm người quản lý
                    $.ajax({
                        url: '../controller/user_manager_controller.php',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            console.log(response);
                            var responsesuccess = JSON.parse(response).success;
                            if (responsesuccess) {
                                // Hiển thị thông báo thành công bằng Swal và chuyển hướng trang
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thêm thành công!',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                setTimeout(function () {
                                    window.location.href = 'userlists.php';
                                }, 2000);
                            } else {
                                // Hiển thị thông báo lỗi bằng Swal
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Thêm thất bại!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        return;
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    }
}
}
                
                    
    
    </script>
     <script>
                    function checkPassword(input) {
                    var passwordError = document.getElementById('password-error');
                    var password = input.value;
                    
                    // Kiểm tra độ dài mật khẩu
                    if (password.length < 8) {
                        passwordError.textContent = "Mật khẩu phải có ít nhất 8 ký tự.";
                        return;
                    }
                    
                    // Kiểm tra sự hiện diện của các loại ký tự
                    var regex = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W)/;
                    if (!regex.test(password)) {
                        passwordError.textContent = "Mật khẩu phải chứa ít nhất một chữ hoa, một chữ thường, một số và một ký tự đặc biệt.";
                        return;
                    }
                    
                    // Nếu mật khẩu đáp ứng yêu cầu, xóa thông báo lỗi
                    passwordError.textContent = "";
                    }
    </script>
</body>

</html>