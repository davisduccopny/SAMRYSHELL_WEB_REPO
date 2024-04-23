<?php require_once('./main/role_manager.php'); ?>
<?php
    require '../model/usercustomer_model.php';
    $usercustomermodel = new UserCustomerModel($conn);
    $listusercustomer = $usercustomermodel -> listUserCustomers();
    $listemailusercustomer = $usercustomermodel -> getEmailList();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['submit'])) {
            $firstnameuser = $_POST['firstnameuser'];
            $lastnameuser = $_POST['lastnameuser'];
            $emailcustomeruser = $_POST['emailcustomeruser'];
            $passworduser = $_POST['passworduser'];
            $statususer = $_POST['statususer'];
            if ($statususer == "ON") {
                $statususerstatus = 1;
            } else {
                $statususerstatus = 0;
            }
            $userinsert = $usercustomermodel-> insertUserCustomer($firstnameuser, $emailcustomeruser, $lastnameuser, $passworduser, $statususerstatus) ;
            if ($userinsert) {
                echo "<script>alert('Thêm thành công.');</script>";
                header("Location: userlist.php");
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
    <style>
        .suggestions {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    width: 23%;
    max-height: 150px;
    overflow-y: auto;
    }

    .suggestion {
        padding: 5px 10px;
        cursor: pointer;
    }

    .suggestion:hover {
        background-color: #f0f0f0;
    }
    </style>
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
                        <h4>Thêm một User cho khách hàng</h4>
                        <h6>Add/Update User</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Họ</label>
                                    <input type="text" name="firstnameuser">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên</label>
                                    <input type="text" name="lastnameuser">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Chọn Email (từ khách hàng)</label>
                                    <input type="text" id="emailInput" placeholder="Enter customer email" name="emailcustomeruser">
                                    <div id="emailSuggestions" class="suggestions"></div>
                                </div>
                            </div>
                            <input type="hidden" id="emailListData" value="<?php echo htmlspecialchars($listemailusercustomer) ?>">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mật khẩu</label>
                                    <div class="pass-group">
                                        <input type="password" class=" pass-input" name="passworduser">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="select" name="statususer">
                                        <option value="ON">ON</option>
                                        <option value="OFF">OFF</option>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-lg-12">
                                <button type="submit" name="submit" href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
                                <a href="userlist.php" class="btn btn-cancel">Cancel</a>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script src="assets/js/script.js"></script>
    <script>
$(document).ready(function() {
    var emailList = JSON.parse($("#emailListData").val());
    var maxSuggestions = 5; // Số lượng kết quả gợi ý tối đa hiển thị

    $("#emailInput").on("input", function() {
        var query = $(this).val();
        var suggestions = [];
        for (var i = 0; i < emailList.length; i++) {
            if (emailList[i].toLowerCase().includes(query.toLowerCase())) {
                suggestions.push(emailList[i]);
            }
        }

        var suggestionsHtml = "";
        for (var i = 0; i < Math.min(suggestions.length, maxSuggestions); i++) {
            suggestionsHtml += "<div class='suggestion'>" + suggestions[i] + "</div>";
        }
        if (suggestions.length > maxSuggestions) {
            suggestionsHtml += "<div id='moreSuggestions' class='suggestion'>See more suggestions...</div>";
        }
        $("#emailSuggestions").html(suggestionsHtml);
    });

    $("#emailSuggestions").on("click", ".suggestion", function() {
        var selectedEmail = $(this).text();
        $("#emailInput").val(selectedEmail);
        $("#emailSuggestions").html("");
    });

    $("#emailSuggestions").on("click", "#moreSuggestions", function() {
        var allSuggestionsHtml = "";
        for (var i = maxSuggestions; i < emailList.length; i++) {
            allSuggestionsHtml += "<div class='suggestion'>" + emailList[i] + "</div>";
        }
        allSuggestionsHtml += "<div id='lessSuggestions' class='suggestion'>See fewer suggestions...</div>";
        $("#emailSuggestions").html(allSuggestionsHtml);
    });

    $("#emailSuggestions").on("click", "#lessSuggestions", function() {
        var suggestionsHtml = "";
        for (var i = 0; i < Math.min(emailList.length, maxSuggestions); i++) {
            suggestionsHtml += "<div class='suggestion'>" + emailList[i] + "</div>";
        }
        if (emailList.length > maxSuggestions) {
            suggestionsHtml += "<div id='moreSuggestions' class='suggestion'>See more suggestions...</div>";
        }
        $("#emailSuggestions").html(suggestionsHtml);
    });
});


    </script>
</body>

</html>