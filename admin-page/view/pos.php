<?php require_once('./main/role_manager.php'); ?>
<?php 
require '../model/product_model.php';
require '../model/cagegoryproduct_model.php';
require '../model/sale_model.php';
require '../model/usercustomer_model.php';
require '../model/discount_model.php';
$usercustomermodel = new UserCustomerModel($conn);
$listemailusercustomer = $usercustomermodel -> getEmailList();
$productModel = new ProductModel($conn);
$products= $productModel->showProduct();
$productData = $productModel ->showProductsalelist();
$categoryModelsale = new CategoryProductModel($conn);
$categorysale = $categoryModelsale->showCategoryProducts();
$saleModel = new SaleModel($conn);
$salescategoryressult = $saleModel->showCategoryProducts_subSale();
$discountModel = new DiscountModel($conn);
$discountsale = $discountModel->getAllDiscounts();

?>


<!DOCTYPE html>
<html lang="en">

<head>
<?php require_once('./main/head.php'); ?>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/animate.css">

    <link rel="stylesheet" href="assets/plugins/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/plugins/owlcarousel/owl.theme.default.min.css">

    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
<style>
        .overflow-y-product{
    overflow-y: auto !important;
    max-height: 100vh !important;
    }
    .productsetqtyminium{
        
    color: #ea5455;
    margin: 0 0 0 auto;
    font-weight: 600;
    font-size: 14px;

    }
    .suggestions {
    position: absolute;
    background-color: #fff;
    width: 91%;
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
    .card-view-responvive-total{
        max-height: 38vh !important;
        overflow-y: auto !important;
    }
    .product-table{
        display: none;
    }
    .setvaluecash ul li button {
    border: 1px solid #e9ecef;
    color: #000;
    font-size: 14px;
    font-weight: 600;
    min-height: 95px;
    border-radius: 5px;
    padding: 10px 20px;
    }
    .active-234 {
        background-color: #e0e0e0;
        /* Các thuộc tính CSS khác bạn muốn thêm vào đây */
    }
</style>
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <div class="main-wrappers">

    <?php require_once('./main/header.php'); ?>
        <div class="page-wrapper ms-0">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 col-sm-12 tabs_wrapper">
                        <div class="page-header ">
                            <div class="page-title">
                                <h4>Thêm đơn hàng</h4>
                                <h6>Manage your sale</h6>
                            </div>
                        </div>
                        <ul class=" tabs owl-carousel owl-theme owl-product  border-0 ">
                        <?php 
                            $firstItem = true;
                            foreach ($categorysale as $categorysale2): 
                                $activeClass = $firstItem ? "active" : "";
                                $firstItem = false;
                            ?>

                                <li class="<?php echo $activeClass; ?>" id="<?php echo $categorysale2['name']; ?>">
                                    <div class="product-details">
                                        <img src="<?php echo $categorysale2['image']; ?>" alt="img">
                                        <h6><?php echo $categorysale2['name']; ?></h6>
                                    </div>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                        <div class="tabs_container">
                            <?php 
                            $firstItem2product = true;
                            foreach ($productData as $tab => $products215): 
                                $activeClass2product = $firstItem2product ? "active" : "";
                                $firstItem2product = false;
                            ?>

                                <div class="tab_content <?php echo $activeClass2product; ?>" data-tab="<?php echo $tab; ?>">
                                    <div class="row overflow-y-product">
                                    <?php foreach ($products215 as $product315): ?>
                                        <div class="col-lg-3 col-sm-6 d-flex">
                                            <div class="productset flex-fill" data-sale-id="<?php echo $product315['id']; ?>">
                                                <div class="productsetimg">
                                                    <img src="<?php echo $product315['image']; ?>" alt="img">
                                                    <h6>Qty: <?php echo $product315['quantity']; ?></h6>
                                                    <div class="check-product">
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                </div>
                                                <div class="productsetcontent">
                                                    <h5><?php echo $tab; ?></h5>
                                                    <h4><?php echo $product315['name']; ?></h4>
                                                    <h6><?php echo $product315['price']; ?></h6>
                                                </div>
                                                <h6 class="productsetqtyminium">Qty:<?php echo $product315['minium_quantity']; ?></h6>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    </div>
                                </div>

                            <?php endforeach; ?>

                            <input type="hidden" id="categoryAndSubcategoryData" value="<?php echo htmlspecialchars($salescategoryressult) ?>">
                        </div>
                    </div>
                    <form class="col-lg-4 col-sm-12 " onsubmit="AddItem_sale(event)" method="post" enctype="multipart/form-data">
                            <div class="order-list">
                            <div class="orderid">
                                <h4>Order List</h4>
                                <h5>Transaction id : #65565</h5>
                            </div>
                            <div class="actionproducts">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);" class="deletebg confirm-text"><img
                                                src="assets/img/icons/delete-2.svg" alt="img"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"
                                            class="dropset">
                                            <img src="assets/img/icons/ellipise1.svg" alt="img">
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                            data-popper-placement="bottom-end">
                                            <li>
                                                <a href="#" class="dropdown-item">Hành động</a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item">Hàng động khác</a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item">Something Elses</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            </div>
                            <div class="card card-order">
                            <div class="card-body card-view-responvive-total">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="javascript:void(0);" class="btn btn-adds" data-bs-toggle="modal"
                                            data-bs-target="#create"><i class="fa fa-plus me-2"></i>Thêm khách hàng</a>
                                    </div>
                                    <div class="col-lg-12">
                                                <div class="select-split">
                                                    <div class="form-group w-100">
                                                    <label>Email khách hàng</label>
                                                    <input type="text" id="emailInput" placeholder="Enter customer email">
                                                    <div id="emailSuggestions" class="suggestions"></div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="select-split ">
                                            <div class="select-group w-100">
                                            <label>Trạng thái đơn hàng</label>
                                                <select class="select" id="statussaleselect">
                                                    <option value="">Choose Status</option>
                                                    <option value="Complete">Complete</option>
                                                    <option value="Pending">Pending</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                                <div class="select-split">
                                                    <div class="form-group w-100">
                                                    <label>Ngày</label>
                                                        <input type="date" placeholder="Choose Date" id="datesaleinput">
                                                    </div>
                                                </div>
                                            </div>
                                    <div class="col-lg-12">
                                                <div class="select-split ">
                                                    <div class="select-group w-100">
                                                        <label>Giảm giá</label>
                                                        <select class="select" id="discountsaleselect">
                                                            <option>Choose Discount</option>
                                                            <?php foreach ($discountsale as $discountsale2): ?>
                                                                <option value="<?php echo $discountsale2['discount_id']; ?>"><?php echo $discountsale2['reference']; ?>  <?php echo $discountsale2['content']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="select-split">
                                                    <div class="form-group w-100">
                                                    <label>Ship</label>
                                                        <input type="text" placeholder="Insert shipping" id="shippingsaleinput">
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="col-lg-12">
                                                <div class="select-split">
                                                    <div class="form-group w-100">
                                                    <label>Thuế đơn hàng</label>
                                                        <input type="text" placeholder="Insert Order tax" id="ordertaxinput">
                                                    </div>
                                                </div>
                                            </div>    
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Mô tả đơn hàng</label>
                                                    <textarea class="form-control" id="descriptioninput"></textarea>
                                                </div>
                                            </div>
                                    </div>
                            </div>
                            <input type="hidden" id="emailListData" value="<?php echo htmlspecialchars($listemailusercustomer) ?>">
                            <div class="split-card">
                            </div>
                            <div class="card-body pt-0">
                                <div class="totalitem">
                                    <h4>Total items : 0</h4>
                                    <a href="javascript:void(0);" onclick="deletelistcart_sale()">Clear all</a>
                                </div>

                                <div class="product-table">
                                    
                                </div>
                            </div>
                            <div class="split-card">
                            </div>
                            <div class="card-body pt-0 pb-2">
                                <div class="setvalue">
                                    <ul>
                                        <li>
                                            <h5>Subtotal </h5>
                                            <h6 class="subtotalproduct">0$</h6>
                                        </li>
                                        <li>
                                            <h5>Tax </h5>
                                            <h6 class="taxtotalproduct">0%</h6>
                                        </li>
                                        <li class="total-value">
                                            <h5>Total </h5>
                                            <h6 class="grand-totalproductsale">0$</h6>
                                        </li>
                                    </ul>
                                </div>
                                <div class="setvaluecash">
                                    <ul>
                                        <li>
                                            <a  class="paymentmethod">
                                                <img src="assets/img/icons/cash.svg" alt="img" class="me-2">
                                                Cash
                                            </a>
                                        </li>
                                        <li>
                                            <a  class="paymentmethod">
                                                <img src="assets/img/icons/debitcard.svg" alt="img" class="me-2">
                                                Debit
                                            </a>
                                        </li>
                                        <li>
                                            <a  class="paymentmethod">
                                                <img src="assets/img/icons/scan.svg" alt="img" class="me-2">
                                                MoMo
                                            </a>
                                        </li>
                                    </ul>
                                    <input type="hidden" id="selectedPaymentMethod" name="selectedPaymentMethod" value="">
                                </div>
                                <button type="submit" class="btn btn-totallabel  w-100" >
                                    <h5>Checkout</h5>
                                    <h6 class="grand-totalproductsale2">0$</h6>
                                </button>
                                <div class="btn-pos">
                                    <ul>
                                        <li>
                                            <a class="btn" data-bs-toggle="modal" data-bs-target="#holdsales"><img src="assets/img/icons/pause1.svg" alt="img"
                                                    class="me-1">Hold</a>
                                        </li>
                                        <li>
                                            <a class="btn" data-bs-toggle="modal" data-bs-target="#delete"><img src="assets/img/icons/edit-6.svg" alt="img"
                                                    class="me-1">Quotation</a>
                                        </li>
                                        <li>
                                            <a class="btn" data-bs-toggle="modal" data-bs-target="#edit"><img src="assets/img/icons/trash12.svg" alt="img"
                                                    class="me-1">Void</a>
                                        </li>
                                        <li>
                                            <a class="btn" data-bs-toggle="modal" data-bs-target="#recents"><img src="assets/img/icons/wallet1.svg" alt="img"
                                                    class="me-1">Payment</a>
                                        </li>
                                        <li>
                                            <a class="btn" data-bs-toggle="modal" data-bs-target="#recents"><img
                                                    src="assets/img/icons/transcation.svg" alt="img" class="me-1">
                                                Transaction</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="calculator" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Define Quantity</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="calculator-set">
                        <div class="calculatortotal">
                            <h4>0</h4>
                        </div>
                        <ul>
                            <li>
                                <a href="javascript:void(0);">1</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">2</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">3</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">4</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">5</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">6</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">7</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">8</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">9</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="btn btn-closes"><img
                                        src="assets/img/icons/close-circle.svg" alt="img"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">0</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="btn btn-reverse"><img
                                        src="assets/img/icons/reverse.svg" alt="img"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="holdsales" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hold order</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="hold-order">
                        <h2>4500.00</h2>
                    </div>
                    <div class="form-group">
                        <label>Order Reference</label>
                        <input type="text">
                    </div>
                    <div class="para-set">
                        <p>The current order will be set on hold. You can retreive this order from the pending order
                            button. Providing a reference to it might help you to identify the order more quickly.</p>
                    </div>
                    <div class="col-lg-12">
                        <a class="btn btn-submit me-2">Submit</a>
                        <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Order</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Product Price</label>
                                <input type="text" value="20">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Product Price</label>
                                <select class="select">
                                    <option>Exclusive</option>
                                    <option>Inclusive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label> Tax</label>
                                <div class="input-group">
                                    <input type="text">
                                    <a class="scanner-set input-group-text">
                                        %
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Discount Type</label>
                                <select class="select">
                                    <option>Fixed</option>
                                    <option>Percentage</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Discount</label>
                                <input type="text" value="20">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Sales Unit</label>
                                <select class="select">
                                    <option>Kilogram</option>
                                    <option>Grams</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <a class="btn btn-submit me-2">Submit</a>
                        <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="create" tabindex="-1" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-12">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <a class="btn btn-submit me-2">Submit</a>
                        <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Deletion</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="delete-order">
                        <img src="assets/img/icons/close-circle1.svg" alt="img">
                    </div>
                    <div class="para-set text-center">
                        <p>The current order will be deleted as no payment has been <br> made so far.</p>
                    </div>
                    <div class="col-lg-12 text-center">
                        <a class="btn btn-danger me-2">Yes</a>
                        <a class="btn btn-cancel" data-bs-dismiss="modal">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="recents" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recent Transactions</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tabs-sets">
                        <ul class="nav nav-tabs" id="myTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="purchase-tab" data-bs-toggle="tab"
                                    data-bs-target="#purchase" type="button" aria-controls="purchase"
                                    aria-selected="true" role="tab">Purchase</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment"
                                    type="button" aria-controls="payment" aria-selected="false"
                                    role="tab">Payment</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return"
                                    type="button" aria-controls="return" aria-selected="false"
                                    role="tab">Return</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="purchase" role="tabpanel"
                                aria-labelledby="purchase-tab">
                                <div class="table-top">
                                    <div class="search-set">
                                        <div class="search-input">
                                            <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg"
                                                    alt="img"></a>
                                        </div>
                                    </div>
                                    <div class="wordset">
                                        <ul>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                        src="assets/img/icons/pdf.svg" alt="img"></a>
                                            </li>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                        src="assets/img/icons/excel.svg" alt="img"></a>
                                            </li>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                        src="assets/img/icons/printer.svg" alt="img"></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table datanew">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Reference</th>
                                                <th>Customer</th>
                                                <th>Amount </th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>INV/SL0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>INV/SL0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>INV/SL0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>INV/SL0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>INV/SL0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>INV/SL0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>INV/SL0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="payment" role="tabpanel" >
                                <div class="table-top">
                                    <div class="search-set">
                                        <div class="search-input">
                                            <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg"
                                                    alt="img"></a>
                                        </div>
                                    </div>
                                    <div class="wordset">
                                        <ul>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                        src="assets/img/icons/pdf.svg" alt="img"></a>
                                            </li>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                        src="assets/img/icons/excel.svg" alt="img"></a>
                                            </li>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                        src="assets/img/icons/printer.svg" alt="img"></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table datanew">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Reference</th>
                                                <th>Customer</th>
                                                <th>Amount </th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0102</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0103</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0104</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0105</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0106</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0107</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="return" role="tabpanel">
                                <div class="table-top">
                                    <div class="search-set">
                                        <div class="search-input">
                                            <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg"
                                                    alt="img"></a>
                                        </div>
                                    </div>
                                    <div class="wordset">
                                        <ul>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                                        src="assets/img/icons/pdf.svg" alt="img"></a>
                                            </li>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                                        src="assets/img/icons/excel.svg" alt="img"></a>
                                            </li>
                                            <li>
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                                        src="assets/img/icons/printer.svg" alt="img"></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table datanew">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Reference</th>
                                                <th>Customer</th>
                                                <th>Amount </th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0101</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0102</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0103</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0104</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0105</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0106</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2022-03-07 </td>
                                                <td>0107</td>
                                                <td>Walk-in Customer</td>
                                                <td>$ 1500.00</td>
                                                <td>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/eye.svg" alt="img">
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);">
                                                        <img src="assets/img/icons/edit.svg" alt="img">
                                                    </a>
                                                    <a class="me-3 confirm-text" href="javascript:void(0);">
                                                        <img src="assets/img/icons/delete.svg" alt="img">
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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

    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>

    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <script src="assets/plugins/owlcarousel/owl.carousel.min.js"></script>

    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

    <script src="assets/js/script.js"></script>
    <script>
        var paymentOptions = document.querySelectorAll(".paymentmethod");
        var selectedPaymentMethodInput = document.getElementById("selectedPaymentMethod");

        paymentOptions.forEach(function(option) {
            option.addEventListener("click", function() {
                // Xóa class active từ tất cả các tùy chọn
                paymentOptions.forEach(function(otherOption) {
                    otherOption.classList.remove("active-234");
                });

                // Thêm class active cho tùy chọn hiện tại
                option.classList.add("active-234");

                // Lưu tùy chọn đã chọn vào input ẩn
                selectedPaymentMethodInput.value = option.textContent.trim();
            });
        });

</script>
<script> 

        var addedProducts = {};
        var totalItemElement = document.querySelector(".totalitem h4");
        var totalItemElementsaleplus = document.querySelector(".subtotalproduct");
        var totalItemElementtax = document.querySelector(".taxtotalproduct");
        var grandtotalproductSale = document.querySelector(".grand-totalproductsale");
        var grandtotalproductSale2 = document.querySelector(".grand-totalproductsale2");
        var selectedproducttale = document.querySelector(".product-table");
        var categoryAndSubcategory = JSON.parse($("#categoryAndSubcategoryData").val());
        function updatedisplaytableprodyc() {
                var totalItemsallseldct = parseInt(totalItemElement.textContent.split(":")[1].trim());
            if (totalItemsallseldct==0){
                selectedproducttale.style.display = "none";
            }
            else{
                selectedproducttale.style.display = "block";
            }
        }

                $(".productset").on("click", function() {
                    var saleId = $(this).data("sale-id");
                    var selectedProductInfo = null;
                
                    for (var i = 0; i < categoryAndSubcategory.length; i++) {
                        for (var j = 0; j < categoryAndSubcategory[i].products.length; j++) {
                            if (categoryAndSubcategory[i].products[j].product_id === saleId) {
                                selectedProductInfo = categoryAndSubcategory[i].products[j];
                                break;
                            }
                        }
                        if (selectedProductInfo) {
                            break;
                        }
                    }
                    if (addedProducts[saleId]) {
                        $(".product-table ul[data-sale-id='" + saleId + "']").remove();
                        addedProducts[saleId] = false;
                        updateTotalItems(-1);
                        updatetotalproductsale(-selectedProductInfo.price);
                        updatetotalproducttax(- parseFloat(selectedProductInfo.tax));
                        var flagproductgrand = parseFloat(selectedProductInfo.tax)* parseFloat(selectedProductInfo.price) + parseFloat(selectedProductInfo.price);

                        updateGrandtotalsale (-(flagproductgrand));
                        updatedisplaytableprodyc();

                    } else {
                        // selectedproducttale.style.display = "block";
                        var ulElement = $("<ul class='product-lists' data-sale-id='" + saleId + "'></ul>");

                        var liElement1 = $("<li></li>");
                        var productImgDiv = $("<div class='productimg'></div>");
                        var productImgContainer = $("<div class='productimgs'></div>");
                        productImgContainer.append("<img src='" + selectedProductInfo.image + "' alt='img'>");
                        productImgDiv.append(productImgContainer);
                        var productContent = $("<div class='productcontet'></div>");
                        productContent.append("<h4>" + selectedProductInfo.pname + "<a href='javascript:void(0);' class='ms-2' data-bs-toggle='modal' data-bs-target='#edit'><img src='assets/img/icons/edit-5.svg' alt='img'></a></h4>");
                        productContent.append("<div class='productlinkset'><h5>" + selectedProductInfo.sku + "</h5></div>");
                        var incrementDecrement = $("<div class='increment-decrement'></div>");
                        var inputGroups = $("<div class='input-groups'></div>");
                        inputGroups.append("<button type='button'  class='button-minus btn-light dec button'  data-sale-id='" + saleId + "' onclick='decreaseQuantity(" + saleId + ")'>-</button>");
                        inputGroups.append("<input type='text' name='child' value='1' class='quantity-field'  data-sale-id='" + saleId + "'>");
                        inputGroups.append("<button type='button'  class='button-plus btn-light inc button '  data-sale-id='" + saleId + "' onclick='increaseQuantity(" + saleId + ")'>+</button>");
                        incrementDecrement.append(inputGroups);
                        productContent.append(incrementDecrement);
                        productImgDiv.append(productContent);
                        liElement1.append(productImgDiv);
                        ulElement.append(liElement1);

                        var liElement2 = $("<li>" + selectedProductInfo.price + "</li>");
                        ulElement.append(liElement2);
                        // var liElement4 = $("<li>" + selectedProductInfo.tax + "</li>");
                        // ulElement.append(liElement4);
                        var liElement3 = $("<li><a class='confirm-text' href='javascript:void(0);'><img src='assets/img/icons/delete-2.svg' alt='img'></a></li>");
                        liElement3.find('a.confirm-text').on('click', function() {
                            $(".product-table ul[data-sale-id='" + saleId + "']").remove();
                            $(".productset[data-sale-id='" + saleId + "']").removeClass("active");
                            addedProducts[saleId] = false;
                            updateTotalItems(-1);
                            updatetotalproductsale(-selectedProductInfo.price);
                            updatetotalproducttax(-selectedProductInfo.tax);
                            var flagproductgrand = parseFloat(selectedProductInfo.tax)* parseFloat(selectedProductInfo.price) + parseFloat(selectedProductInfo.price);

                            updateGrandtotalsale (-(flagproductgrand));
                            updatedisplaytableprodyc();
                        });
                        ulElement.append(liElement3);

                        $(".product-table").append(ulElement);
                        addedProducts[saleId] = true;
                        updateTotalItems(1);
                        updatetotalproductsale(+selectedProductInfo.price);
                        updatetotalproducttax(+parseFloat(selectedProductInfo.tax));
                        var flagproductgrand = parseFloat(selectedProductInfo.tax)* parseFloat(selectedProductInfo.price) + parseFloat(selectedProductInfo.price);
                        updateGrandtotalsale (+(flagproductgrand));
                        updatedisplaytableprodyc();
                    }
                });


                function updateTotalItems(change) {
                    var totalItems = parseInt(totalItemElement.textContent.split(":")[1].trim()) + change;
                    if (totalItems < 0) {
                        totalItems = 0;
                    }
                    else {
                        totalItems = totalItems;
                    }
                    totalItemElement.textContent = "Total items: " + totalItems;
                }
                function updatetotalproductsale(change) {
                    var totalItemElementsaleplustext = totalItemElementsaleplus.textContent; 
                    var totalItemElementsaleplusvalue = parseFloat(totalItemElementsaleplustext.replace("$", "")) + change; 
                    totalItemElementsaleplus.textContent =  totalItemElementsaleplusvalue +'$';
                        if (totalItemElementsaleplusvalue<0){
                            totalItemElementsaleplus.textContent =  0 +'$';
                        }
                        else {
                            totalItemElementsaleplus.textContent =  totalItemElementsaleplusvalue +'$';
                        }
                }
                function updatetotalproducttax(change) {
                    var totalItemElementtaxtext = totalItemElementtax.textContent;
                    var totalItemElementtaxvalue = parseFloat(totalItemElementtaxtext.replace("%", "")) + change;

                    if(totalItemElementtaxvalue<0){
                        totalItemElementtax.textContent =  0 +'%';
                    }
                    else{
                        totalItemElementtax.textContent =  totalItemElementtaxvalue +'%';
                    }
                }
                function updateGrandtotalsale (change){
                    var grandtotalproductSaletext = grandtotalproductSale.textContent;
                    var grandtotalproductSalevalue = parseFloat(grandtotalproductSaletext.replace("$", "")) + change;
                    grandtotalproductSale.textContent =  grandtotalproductSalevalue +'$';

                    var grandtotalproductSalevalue2 = parseFloat(grandtotalproductSaletext.replace("$", "")) + change;
                    grandtotalproductSale2.textContent =  grandtotalproductSalevalue2 +'$';

                }
                // var buttonplusproductsale = document.querySelector(".button-plus[data-sale-id='" + saleId + "']")

                function increaseQuantity(saleId) {
                    updateQuantity(saleId, 1);
                }

                function decreaseQuantity(saleId) {
                    updateQuantity(saleId, -1);
                }

                function updateQuantity(saleId, change) {
                    var inputElement = document.querySelector(".quantity-field[data-sale-id='" + saleId + "']");
                    var currentValue = parseInt(inputElement.value);
                    var newValue = currentValue + change;
                    
                    if (newValue >= 1) {
                        inputElement.value = newValue;
                        // console.log(inputElement.value);
                    }
                    var selectedProductInfo = null;

                    for (var i = 0; i < categoryAndSubcategory.length; i++) {
                        for (var j = 0; j < categoryAndSubcategory[i].products.length; j++) {
                            if (categoryAndSubcategory[i].products[j].product_id === saleId) {
                                selectedProductInfo = categoryAndSubcategory[i].products[j];
                                break;
                            }
                        }
                        if (selectedProductInfo) {
                            break;
                        }
                    }
                    if(change==1){
                        updatetotalproductsale(+selectedProductInfo.price);
                        updatetotalproducttax(+selectedProductInfo.tax);
                                var flagproductgrand = parseFloat(selectedProductInfo.tax)* parseFloat(selectedProductInfo.price) + parseFloat(selectedProductInfo.price);

                        updateGrandtotalsale (+(flagproductgrand));
                    }else if (change!=1 && newValue >= 1){
                        updatetotalproductsale(-selectedProductInfo.price);
                        updatetotalproducttax(-selectedProductInfo.tax);
                                var flagproductgrand = parseFloat(selectedProductInfo.tax)* parseFloat(selectedProductInfo.price) + parseFloat(selectedProductInfo.price);

                        updateGrandtotalsale (-(flagproductgrand));
                    }
                }
                    function deletelistcart_sale(){
                        $(".product-table ul").remove();
                        addedProducts = {};
                        $(".productset").removeClass("active");
                        totalItemElement.textContent = "Total items: " + 0;
                        totalItemElementsaleplus.textContent =  0+'$';
                        totalItemElementtax.textContent =  0 +'%';
                        grandtotalproductSale.textContent =  0 +'$';
                        grandtotalproductSale2.textContent =  0 +'$';
                        updatedisplaytableprodyc();
                    }

                // select email :
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

        // add server 
        function AddItem_sale(event) {
                event.preventDefault();
                var emailInput = $('#emailInput').val();
                var statussaleselect = $('#statussaleselect').val();
                var datesaleinput = $('#datesaleinput').val();
                var discountsaleselect = $('#discountsaleselect').val(); // Chuyển đổi định dạng ngày
                var selectedPaymentMethod = $('#selectedPaymentMethod').val();
                var shippingsaleinput =  parseFloat($('#shippingsaleinput').val());
                var ordertaxinput =  parseFloat($('#ordertaxinput').val());
                var descriptioninput =  $('#descriptioninput').val();


                var productsData = {
                email: emailInput,
                status: statussaleselect,
                date: datesaleinput,
                shipping: shippingsaleinput,
                tax: ordertaxinput,
                discount: discountsaleselect,
                paymentMethod: selectedPaymentMethod,
                description: descriptioninput,


                items: []
                };

                $(".product-table .quantity-field").each(function() {
                    var saleId = $(this).data("sale-id");
                    var quantity = $(this).val();

                    // Thêm thông tin sản phẩm vào mảng items
                    productsData.items.push({
                        product_id: saleId,
                        quantity: quantity
                    });
                });


                console.log(productsData);

                $.ajax({
                type: "POST",
                url: "../controller/sale_controller.php",
                data: JSON.stringify(productsData), // Chuyển đối tượng thành chuỗi JSON
                contentType: "application/json",    // Đặt kiểu dữ liệu là JSON
                success: function(response) {
                    console.log(response);
                    var responseData = JSON.parse(response);
                    try {
            if (responseData.message === "success") {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Add sale success",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "saleslist.php";
                    }
                });
                } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Add sale error",
                });
                }
                } catch (error) {
                console.error("An error occurred:", error);
                }
                                                                        
                },
                error: function(error) {
                    // Xử lý lỗi (nếu có)
                    console.error("Lỗi khi gửi dữ liệu:", error);
                }
                });

    }
</script>
</body>

</html>