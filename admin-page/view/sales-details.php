<?php require_once('./main/role_manager.php'); ?>
<?php 
require '../model/product_model.php';
require '../model/sale_model.php';
if (isset($_GET['sale_id'])) {
    $sale_id = $_GET['sale_id'];
    $saleModel = new SaleModel($conn);
    $sale = $saleModel->getSaleById($sale_id);
    $saleDetails = $saleModel->showsaledetailbyId($sale_id);
    

}
else {
    echo '<script>alert("Không tìm thấy sản phẩm");</script>';
    header('Location: saleslist.php');
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
                        <h4>Thông tin đơn hàng</h4>
                        <h6>View sale details</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="card-sales-split">
                            <h2>Sale Detail : <?php echo $sale['reference']?></h2>
                            <ul>
                                <li>
                                    <a href="edit-sales.php?sale_id=<?php echo $sale['sale_id'];?>"><img src="assets/img/icons/edit.svg" alt="img"></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><img src="assets/img/icons/pdf.svg" alt="img"></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><img src="assets/img/icons/excel.svg" alt="img"></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"><img src="assets/img/icons/printer.svg" alt="img"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="invoice-box table-height"
                            style="max-width: 1600px;width:100%;overflow: auto;margin:15px auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                            <table cellpadding="0" cellspacing="0"
                                style="width: 100%;line-height: inherit;text-align: left;">
                                <tbody>
                                    <tr class="top">
                                        <td colspan="6" style="padding: 5px;vertical-align: top;">
                                            <table style="width: 100%;line-height: inherit;text-align: left;">
                                                <tbody>
                                                    <tr>
                                                        <td
                                                            style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                                            <font style="vertical-align: inherit;margin-bottom:25px;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                                                    Customer Info</font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    <?php echo $sale['reference']?></font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    <a href=""
                                                                        class="__cf_email__"
                                                                        ><?php echo $sale['email']?></a>
                                                                </font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    <?php echo $sale['phone']?></font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    <?php echo $sale['country']?>,<?php echo $sale['city']?>,<?php echo $sale['district']?></font>
                                                            </font><br>
                                                        </td>
                                                        <td
                                                            style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                                            <font style="vertical-align: inherit;margin-bottom:25px;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                                                    Company Info</font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    DGT </font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    <a href="/cdn-cgi/l/email-protection"
                                                                        class="__cf_email__"
                                                                        data-cfemail="9ffefbf2f6f1dffae7fef2eff3fab1fcf0f2">[email&#160;protected]</a>
                                                                </font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    default</font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    default</font>
                                                            </font><br>
                                                        </td>
                                                        <td
                                                            style="padding:5px;vertical-align:top;text-align:left;padding-bottom:20px">
                                                            <font style="vertical-align: inherit;margin-bottom:25px;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                                                    Invoice Info</font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    Reference </font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    Payment Status</font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    Status</font>
                                                            </font><br>
                                                        </td>
                                                        <td
                                                            style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px">
                                                            <font style="vertical-align: inherit;margin-bottom:25px;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size:14px;color:#7367F0;font-weight:600;line-height: 35px; ">
                                                                    &nbsp;</font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;">
                                                                    <?php echo $sale['reference']?> </font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">
                                                                    <?php echo $sale['payment']?></font>
                                                            </font><br>
                                                            <font style="vertical-align: inherit;">
                                                                <font
                                                                    style="vertical-align: inherit;font-size: 14px;color:#2E7D32;font-weight: 400;">
                                                                    <?php echo $sale['status']?></font>
                                                            </font><br>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr class="heading " style="background: #F3F2F7;">
                                        <td
                                            style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                            Tên sản phẩm
                                        </td>
                                        <td
                                            style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                            Số lượng
                                        </td>
                                        <td
                                            style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                            Giá
                                        </td>
                                        <td
                                            style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                            Giảm giá
                                        </td>
                                        <td
                                            style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                            Thuế
                                        </td>
                                        <td
                                            style="padding: 5px;vertical-align: middle;font-weight: 600;color: #5E5873;font-size: 14px;padding: 10px; ">
                                            Tổng
                                        </td>
                                    </tr>
                                    <?php foreach ($saleDetails as $saleDetail): ?>
                                    <tr class="details" style="border-bottom:1px solid #E9ECEF ;">
                                        <td
                                            style="padding: 10px;vertical-align: top; display: flex;align-items: center;">
                                            <img src="<?php echo $saleDetail['image']; ?>" alt="img" class="me-2" style="width:40px;height:40px;">
                                                <?php echo $saleDetail['name']; ?>
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                        <?php echo $saleDetail['quantity']; ?>
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                        <?php echo $saleDetail['price']; ?>
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                        <?php echo $saleDetail['discount']; ?>
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                        <?php echo $saleDetail['tax']; ?>
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                        <?php echo $saleDetail['subtotal']; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <!-- <tr class="details" style="border-bottom:1px solid #E9ECEF ;">
                                        <td
                                            style="padding: 10px;vertical-align: top; display: flex;align-items: center;">
                                            <img src="assets/img/product/product7.jpg" alt="img" class="me-2"
                                                style="width:40px;height:40px;">
                                            Apple Earpods
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            1.00
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            2000.00
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            0.00
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            0.00
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            1500.00
                                        </td>
                                    </tr>
                                    <tr class="details" style="border-bottom:1px solid #E9ECEF ;">
                                        <td
                                            style="padding: 10px;vertical-align: top; display: flex;align-items: center;">
                                            <img src="assets/img/product/product8.jpg" alt="img" class="me-2"
                                                style="width:40px;height:40px;">
                                            samsung
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            1.00
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            8000.00
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            0.00
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            0.00
                                        </td>
                                        <td style="padding: 10px;vertical-align: top; ">
                                            1500.00
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Thuế</label>
                                    <input type="text" value="<?php echo $sale['tax']?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giảm giá</label>
                                    <input type="text" value="<?php echo $sale['discount']?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Ship</label>
                                    <input type="text" value="<?php echo $sale['ship']?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="select">
                                    <option value="Complete" <?php echo ($sale['status'] == 'Complete') ? 'selected' : ''; ?>>Complete</option>
                                    <option value="Pending" <?php echo ($sale['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Thuể</h4>
                                                <h5>$<?php echo $sale['tax']/100*$sale['total'];?> (<?php echo $sale['tax'];?>%)</h5>
                                            </li>
                                            <li>
                                                <h4>Giảm giá </h4>
                                                <h5>$<?php echo $sale['discount']/100*$sale['total'];?> </h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Ship</h4>
                                                <h5>$ <?php echo $sale['ship'];?> </h5>
                                            </li>
                                            <li class="total">
                                                <h4>Tổng đơn hàng</h4>
                                                <h5>$ <?php echo $sale['grand_total'];?> </h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <a href="saleslist.php" class="btn btn-submit me-2">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
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