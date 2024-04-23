<?php require_once('./main/role_manager.php'); ?>
<?php
require '../model/sale_return_model.php';

if (isset($_GET['return_id']) && $_GET['return_id']) {
    $salereturn_id = $_GET['return_id'];
    $salereturnModel = new SaleReturnModel($conn);
    $resultsalereturnlist = $salereturnModel->getAllSales_returnbyID($salereturn_id);
    // var_dump($resultsalereturnlist);
    if (empty($resultsalereturnlist)) {
        // Xử lý thông báo lỗi ở đây, ví dụ:
        header('Location: salesreturnlist.php');
        exit();
    }
} else {
    header('Location: salesreturnlist.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php require_once('./main/head.php'); ?>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

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
                        <h4>Chỉnh sửa thông tin trả hàng</h4>
                        <h6>Add/Update Sales Return</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                    <label>Email khách hàng</label>
                                    <div class="row">
                                        <div class="col-lg-10 col-sm-10 col-10">
                                            <select class="select " id="customeremail" disabled>
                                                <option value=""><?php echo $resultsalereturnlist[0]['email']; ?></option>
                                                
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                            <div class="add-icon">
                                                <a href="./addcustomer.php"><img src="assets/img/icons/plus1.svg"
                                                        alt="img"></a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Ngày trả hàng</label>
                                    <div class="input-groupicon form-group">
                                        <input type="date" placeholder="DD-MM-YYYY" id="datereturninput" value="<?php echo $resultsalereturnlist[0]['created_at']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mã tham chiếu.</label>
                                    <input type="text" id="searchInputreference" placeholder="Input reference no" value="<?php echo $resultsalereturnlist[0]['referencesale']; ?>">
                                    <div id="detailsaleSuggestions" class="suggestions"></div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Thông tin sản phẩm</label>
                                    <select class="select" name="detailProductsale" id="detailProductsale" onchange=" changeproductSaleDetail (this)">
                                        <option value="" >Choose detail</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Sản phẩm</label>
                                    <div class="input-groupicon">
                                        <input type="text" placeholder="Scan/Search Product by code and select...">
                                        <div class="addonset">
                                            <img src="assets/img/icons/scanners.svg" alt="img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="hiddenhtmlsalereference" id="hiddenhtmlsalereference" value="<?php echo htmlspecialchars( $saleinfo); ?>">
                        <input type="hidden" name="saledetailresponse" id="saledetailresponse" value="">
                        <input type="hidden" name="saledetailresponse234" id="saledetailresponse234">
                        <input type="hidden" name="idproductreturn" id="idproductreturn" value="">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th>Đơn giá($) </th>
                                            <th>Tồn kho</th>
                                            <th>Số lượng </th>
                                            <th>Giảm giá($) </th>
                                            <th>Thuế % </th>
                                            <th>Tạm tính ($) </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="productimgname">
                                                <a class="product-img">
                                                    <img src="<?php echo $resultsalereturnlist[0]['image']; ?>" alt="product">
                                                </a>
                                                <a href="javascript:void(0);"><?php echo $resultsalereturnlist[0]['nameproduct']; ?></a>
                                            </td>
                                            <td><?php echo $resultsalereturnlist[0]['pricesale']; ?></td>
                                            <td><?php echo $resultsalereturnlist[0]['quantityproduct']; ?></td>
                                            <td><?php echo $resultsalereturnlist[0]['quantity']; ?></td>
                                            <td><?php echo $resultsalereturnlist[0]['discount_amount']; ?></td>
                                            <td><?php echo $resultsalereturnlist[0]['taxsale']; ?></td>
                                            <td><?php echo $resultsalereturnlist[0]['total']; ?></td>
                                            
                                        </tr>
                                        <!-- <tr>
                                            <td class="productimgname">
                                                <a class="product-img">
                                                    <img src="assets/img/product/product6.jpg" alt="product">
                                                </a>
                                                <a href="javascript:void(0);">Macbook Pro</a>
                                            </td>
                                            <td>150</td>
                                            <td>500</td>
                                            <td>500</td>
                                            <td>100</td>
                                            <td>50</td>
                                            <td>250</td>
                                            <td>
                                                <a class="delete-set"><img src="assets/img/icons/delete.svg"
                                                        alt="svg"></a>
                                            </td>
                                        </tr> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 float-md-right">
                                <div class="total-order">
                                    <ul>
                                        <li>
                                            <h4>Thuế đơn hàng</h4>
                                            <h5>$ 0.00 (0.00%)</h5>
                                        </li>
                                        <li>
                                            <h4>Giảm giá </h4>
                                            <h5>$ 0.00</h5>
                                        </li>
                                        <li>
                                            <h4>Ship</h4>
                                            <h5>$ 0.00</h5>
                                        </li>
                                        <li class="total">
                                            <h4>Tổng đơn hàng</h4>
                                            <h5>$ 20000.00</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="select" id="SelectStatusreturn">
                                        <option>Choose Status</option>
                                        <option value="Complete" <?php echo ($resultsalereturnlist[0]['status'] == 'Complete') ? 'selected' : ''; ?>>Complete</option>
                                        <option value="Pending" <?php echo ($resultsalereturnlist[0]['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Odered" <?php echo ($resultsalereturnlist[0]['status'] == 'Odered') ? 'selected' : ''; ?>>Odered</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái thanh toán</label>
                                    <select class="select" id="SelectPaymentStatusreturn">
                                        <option>Choose Status</option>
                                        <option value="Paid" <?php echo ($resultsalereturnlist[0]['payment'] == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                        <option value="Due" <?php echo ($resultsalereturnlist[0]['payment'] == 'Due') ? 'selected' : ''; ?>>Due</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Loại thanh toán</label>
                                    <select class="select" id="SelectPaymentnamereturn">
                                        <option>Choose payment</option>
                                        <option value="Cash" <?php echo ($resultsalereturnlist[0]['payment_name'] == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                        <option value="Debit" <?php echo ($resultsalereturnlist[0]['payment_name'] == 'Debit') ? 'selected' : ''; ?>>Debit</option>
                                        <option value="MoMo" <?php echo ($resultsalereturnlist[0]['payment_name'] == 'MoMo') ? 'selected' : ''; ?>>MoMo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Lý do</label>
                                    <textarea class="form-control" id="reasontexreturn"><?php echo $resultsalereturnlist[0]['reason']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <a class="btn btn-submit me-2" onclick="UpdateItem_saleReturn()">Update</a>
                                <a href="salesreturnlist.php" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
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

    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

    <script src="assets/js/script.js"></script>
    <script>
function UpdateItem_saleReturn() {
    var statussaleselect = $("#SelectStatusreturn").val();
    var statuspayment = $("#SelectPaymentStatusreturn").val();
    var returndate = $("#datereturninput").val();
    var paymentname = $("#SelectPaymentnamereturn").val();
    var reason = $("#reasontexreturn").val();
    var return_id = <?php echo $resultsalereturnlist[0]['id']; ?>;
    var update = "update";
    console.log(return_id);

    var productsData = new FormData();
    productsData.append("status", statussaleselect);
    productsData.append("statuspayment", statuspayment);
    productsData.append("returndate", returndate);
    productsData.append("paymentname", paymentname);
    productsData.append("reason", reason);
    productsData.append("return_id", return_id);
    productsData.append("update", update);
    // console.log(productsData);

    $.ajax({
        type: "POST",
        url: "../controller/salereturn_controller.php",
        data: productsData,
        contentType: false,
        processData: false, // Thêm dòng này để ngăn jQuery xử lý dữ liệu
        success: function(response) {
            console.log(response);
            if (response) {
                try {
                    var responseData = JSON.parse(response);
                    console.log(response.message);

                    try {
                        if (responseData.message === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: "Edit return success",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "./salesreturnlist.php";
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Edit sale error",
                            });
                        }
                    } catch (error) {
                        console.error("An error occurred:", error);
                        console.log(error);
                    }
                } catch (error) {
                    console.error("Lỗi phân tích JSON: " + error.message);
                    console.log(error);
                }
            } else {
                console.error("Không có dữ liệu JSON được trả về từ máy chủ.");
            }
        },
        error: function(error) {
            // Xử lý lỗi (nếu có)
            console.log(error);
            console.error("Lỗi khi gửi dữ liệu:", error);
        },
    });
}
    </script>
</body>

</html>