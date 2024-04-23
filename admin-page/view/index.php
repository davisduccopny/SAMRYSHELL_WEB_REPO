<?php require_once('./main/role_manager.php'); ?>
<?php
      require_once('../model/product_model.php');
      require_once('../model/report_model.php');
        $product_model = new ProductModel($conn);
        $report_model = new ReportModel($conn);
        $product = $product_model->showProduct_foruser(1,4);
        $product_expire = $report_model->showProduct_expire(1,4);
        $countSale = num_rowsCount_item("SELECT * FROM sale WHERE status='Complete'",$conn); 
        $coutnExpense = num_rowsCount_item("SELECT * FROM expense WHERE status='Active'",$conn);   
        $countSuplier = num_rowsCount_item("SELECT * FROM supplier",$conn);
        $countCustomer = num_rowsCount_item("SELECT * FROM customer",$conn);
        $totalSale_complete = $report_model->TotalSale('Complete');
        $totalSale_pending = $report_model->TotalSale('Pending');
        $totalExpense_active = $report_model->TotalExpense('Active');
        $totalExpense_inactive = $report_model->TotalExpense('In Active');
        $totalSale_bymonth = $report_model->TotalSale_byMonth('Complete');
        $totalExpense_bymonth = $report_model->TotalExpense_byMonth('Active');
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
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget">
                            <div class="dash-widgetimg">
                                <span><img src="assets/img/icons/dash1.svg" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="<?php if($totalExpense_inactive){echo $totalExpense_inactive;}; ?>">$<?php if($totalExpense_inactive){echo $totalExpense_inactive;}; ?></span></h5>
                                <h6>Chi phí không hoạt động</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash1">
                            <div class="dash-widgetimg">
                                <span><img src="assets/img/icons/dash2.svg" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="<?php if($totalExpense_active){echo $totalExpense_active;}; ?>">$<?php if($totalExpense_active){echo $totalExpense_active;}; ?></span></h5>
                                <h6>Chi phí hoạt động</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash2">
                            <div class="dash-widgetimg">
                                <span><img src="assets/img/icons/dash3.svg" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="<?php if($totalSale_complete){echo $totalSale_complete;}; ?>"><?php if($totalSale_complete){echo $totalSale_complete;}; ?></span></h5>
                                <h6>Giá trị đơn hoàn thành</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash3">
                            <div class="dash-widgetimg">
                                <span><img src="assets/img/icons/dash4.svg" alt="img"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5>$<span class="counters" data-count="<?php if($totalSale_pending){echo $totalSale_pending;}; ?>"><?php if($totalSale_pending){echo $totalSale_pending;}; ?></span></h5>
                                <h6>Giá trị đơn hàng chờ</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count">
                            <div class="dash-counts">
                                <h4><?php if($countCustomer){echo $countCustomer;}; ?></h4>
                                <h5>Khách hàng</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4><?php if($countSuplier){echo $countSuplier;}; ?></h4>
                                <h5>Nhà cung cấp</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4><?php if($coutnExpense){echo $coutnExpense;}; ?></h4>
                                <h5>Chi phí hoạt động</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das3">
                            <div class="dash-counts">
                                <h4><?php if($countSale){echo $countSale;}; ?></h4>
                                <h5>Đơn hàng hoàn thành</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-7 col-sm-12 col-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                <!-- section chart input -->
                            <input type="hidden" id="Total_sale_by_month" value="<?php 
                                $total_sale_tempt = json_encode($totalSale_bymonth)??null;
                                if ($total_sale_tempt != null)
                                    echo htmlspecialchars($total_sale_tempt); ?>">
                            <input type="hidden" id="Total_expense_by_month" value="<?php 
                                $total_expense_tempt = json_encode($totalExpense_bymonth)??null;
                                if ($total_expense_tempt != null)
                                    echo htmlspecialchars($total_expense_tempt); ?>">
                                <!-- end section chart input -->
                                <h5 class="card-title mb-0">Đơn hàng & Chi phí</h5>
                                <div class="graph-sets">
                                    <ul>
                                        <li>
                                            <span>Đơn hàng</span>
                                        </li>
                                        <li>
                                            <span>Chi phí</span>
                                        </li>
                                    </ul>
                                    <div class="dropdown">
                                        <button class="btn btn-white btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <?php $currentYear = date("Y");
                                                echo $currentYear;
                                                ?> 
                                            <img src="assets/img/icons/dropdown.svg" alt="img" class="ms-2">
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                <?php $currentYear = date("Y");
                                                echo $currentYear;
                                                ?></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                <?php $currentYear = date("Y")-1;
                                                echo $currentYear;
                                                ?>
                                                </a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="sales_charts"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-12 col-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Sản phẩm vửa thêm</h4>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"
                                        class="dropset">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <a href="productlist.php" class="dropdown-item">Product List</a>
                                        </li>
                                        <li>
                                            <a href="addproduct.php" class="dropdown-item">Product Add</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive dataview">
                                    <table class="table datatable ">
                                        <thead>
                                            <tr>
                                                <th>Sno</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Giá</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            $tempcount = 1;
                                            foreach($product as $value): ?>
                                            <tr>
                                                <td><?php echo $tempcount; $tempcount++; ?></td>
                                                <td class="productimgname">
                                                    <a href="productlist.php" class="product-img">
                                                        <img src="<?php echo $value['image']; ?>" alt="product">
                                                    </a>
                                                    <a href="productlist.php"><?php echo $value['name']; ?></a>
                                                </td>
                                                <td>$<?php echo $value['price']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-body">
                        <h4 class="card-title">Sản phẩm hết hạn (hơn 3 tháng)</h4>
                        <div class="table-responsive dataview">
                            <table class="table datatable ">
                                <thead>
                                    <tr>
                                        <th>SNo</th>
                                        <th>Mã sản phẩm</th>
                                        <th>Tên</th>
                                        <th>Thương hiệu</th>
                                        <th>Danh mục</th>
                                        <th>Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $tempcountexpire=1;
                                    foreach($product_expire as $valueexpire): ?>
                                    <tr>
                                        <td><?php echo $tempcountexpire; $tempcountexpire++; ?></td>
                                        <td><a href="javascript:void(0);"><?php echo $valueexpire['sku']; ?></a></td>
                                        <td class="productimgname">
                                            <a class="product-img" href="productlist.php">
                                                <img src="<?php echo $valueexpire['image']; ?>" alt="product">
                                            </a>
                                            <a href="productlist.php"><?php echo $valueexpire['name']; ?></a>
                                        </td>
                                        <td>N/D</td>
                                        <td><?php echo $valueexpire['category_name']; ?></td>
                                        <td>
                                            <?php 
                                                $datetamp =$valueexpire['created_at']??null ;
                                                if ($datetamp != null) {
                                                    echo date('d-m-Y', strtotime($datetamp));
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
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
    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>