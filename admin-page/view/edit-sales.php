<?php require_once('./main/role_manager.php'); ?>
<?php
    if (isset($_GET['sale_id']))  {
    require '../model/usercustomer_model.php';
    require '../model/customer_model.php';
    require '../model/cagegoryproduct_model.php';
    require '../model/sale_model.php';
    require '../model/discount_model.php';
    $modelsale = new SaleModel($conn);
    $usercustomermodel = new UserCustomerModel($conn);
    $customermodel = new CustomerModel($conn);
    $listemailusercustomer = $usercustomermodel -> getEmailList();
    $listusercustomer = $customermodel -> listCustomers();
    $categoryModal = new CategoryProductModel($conn);
    $category_resultfinale = $categoryModal->showCategoryProducts();
    $ca_pro_finale_sub = $modelsale->showCategoryProducts_subSale();
    
    $detailsales = $modelsale -> showsaledetailbyId($_GET['sale_id']);
    $salesinfo = $modelsale -> getSaleById($_GET['sale_id']);
    $discountmodel = new DiscountModel($conn);
    $discounts = $discountmodel -> getAllDiscounts();
    $discountsjson = $discountmodel -> getAlldiscountJson();
}
else {
    header("Location: saleslist.php");
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
    width: 22%;
    max-height: 150px;
    overflow-y: auto;
    z-index: 9999;
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

    <div class="main-wrapper">


    <?php require_once('./main/header.php'); ?>


    <?php require_once('./main/sidebar.php'); ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Sửa thông tin đơn hàng</h4>
                        <h6>Edit your sale</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <div class="row">
                                        <div class="col-lg-10 col-sm-10 col-10">
                                            <select class="select" id="customernameselectadd" onchange="toggleInputADDcustomer()">
                                                <option value="">Choose</option>
                                                <?php foreach ($listusercustomer as $listusercustomers) {
                                                echo '<option value="'.$listusercustomers['email'].'">'.$listusercustomers['name'].'</option>';
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                            <div class="add-icon">
                                                <span><img src="assets/img/icons/plus1.svg" alt="img"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tìm email khách hàng</label>
                                    <input type="text" id="emailInput" placeholder="Enter customer email" name="emailcustomeruser" value="<?php echo $salesinfo['email']?>">
                                    <div id="emailSuggestions" class="suggestions"></div>
                                </div>
                            </div>
                            <input type="hidden" id="emailListData" value="<?php echo htmlspecialchars($listemailusercustomer) ?>">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Ngày</label>
                                    <div class="input-groupicon">
                                        <input type="text" placeholder="Choose Date"  value="<?php echo $salesinfo['created_at']?>" disabled>
                                        <a class="addonset">
                                            <img src="assets/img/icons/calendars.svg" alt="img">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Biller</label>
                                    <select class="select">
                                        <option value="">Choose biller</option>
                                        <option value="admin" <?php echo ($salesinfo['biller'] == 'admin') ? 'selected' : ''; ?>>admin</option>
                                        <option value="manager" <?php echo ($salesinfo['biller'] == 'manager') ? 'selected' : ''; ?>>manager</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Danh mục</label>
                                    <select class="select" name="categoryproduct_id" id="categorySelect" required onchange="handleSelectionChangecategory(this, 'productSelect')" >
                                        <option value="">Choose Category</option>
                                        <?php foreach ($category_resultfinale as $category_product) {
                                            echo '<option value="'.$category_product['categoryproduct_id'].'">'.$category_product['name'].'</option>';

                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Sản phẩm</label>
                                    <select class="select" name="productSelect" id="productSelect" required onchange=" handleSelectionChangeProduct(this)" >
                                        <option value="">Choose Product</option>
                                      
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="categoryAndSubcategoryData" value="<?php echo htmlspecialchars($ca_pro_finale_sub) ?>">
                        <input type="hidden" id="discountjsonendcode" value="<?php echo htmlspecialchars($discountsjson)?>">
                        <div class="row">
                            <div class="table-responsive mb-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên sản phẩm</th>
                                            <th >Số lượng(QTY)</th>
                                            <th>Giá</th>
                                            <th>Giảm giá</th>
                                            <th>Thuế</th>
                                            <th>Tạm tính</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tableeditdetail">
                                    <?php foreach ($detailsales as $detailsaleinfo): ?>
                                         <tr>
                                            <td><?php echo $detailsaleinfo['sku']; ?></td>
                                            <td class="productimgname">
                                                <a class="product-img">
                                                    <img src="<?php echo $detailsaleinfo['image']; ?>" alt="product">
                                                </a>
                                                <a href="javascript:void(0);"><?php echo $detailsaleinfo['name']; ?></a>
                                            </td>
                                            <td data-product-id="<?php echo $detailsaleinfo['product_id']; ?>">
                                                <div class="input-group form-group mb-0">
                                                        <a class="scanner-set input-group-text" onclick="updateQuantity(<?php echo $detailsaleinfo['product_id']; ?>, 1)">
                                                            <img src="assets/img/icons/plus1.svg" alt="img">
                                                        </a>
                                                        <input type="text" value="<?php echo $detailsaleinfo['quantity']; ?>" class="calc-no quantity_field" data-product-id="<?php echo $detailsaleinfo['product_id']; ?>">
                                                        <a class="scanner-set input-group-text" onclick="updateQuantity(<?php echo $detailsaleinfo['product_id']; ?>, -1)">
                                                            <img src="assets/img/icons/minus.svg" alt="img">
                                                        </a>
                                                </div>
                                            </td>
                                            <td class="classpricechange" data-product-id="<?php echo $detailsaleinfo['product_id']; ?>"><?php echo $detailsaleinfo['price']; ?></td>
                                            <td class="classdiscountchange" data-product-id="<?php echo $detailsaleinfo['product_id']; ?>"><?php echo $detailsaleinfo['discount']; ?></td>
                                            <td class="classtaxchange" data-product-id="<?php echo $detailsaleinfo['product_id']; ?>"><?php echo $detailsaleinfo['tax']; ?></td>
                                            <td class="subtotalflag" data-product-id="<?php echo $detailsaleinfo['product_id']; ?>"><?php echo $detailsaleinfo['subtotal']; ?></td>
                                            <td data-product-id="<?php echo $detailsaleinfo['product_id']; ?>">
                                                <a href="javascript:void(0);" class="delete_set_saledetail" data-detail-id="<?php echo $detailsaleinfo['product_id']; ?>"><img
                                                        src="assets/img/icons/delete.svg" alt="svg"></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Thuế đơn hàng</label>
                                    <input type="text" value="<?php echo $salesinfo['tax']?>" onchange="updatetotalproducttax(this.value)" id="inputtaxsale">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giảm giá</label>
                                    <select class="select" id="selectdiscountsale" onchange="changeselectdiscountsale(this)" >
                                        <option value="0" data-discount-value="0">Choose discount</option>
                                        <?php foreach ($discounts as $discountsinfo) {
                                            $selected = ($discountsinfo['discount_id'] == $salesinfo['discount_id']) ? 'selected' : '';
                                            echo '<option value="'.$discountsinfo['discount_id'].'" '.$selected.' data-discount-value="'.$discountsinfo['discount_amount'].'">'.$discountsinfo['reference'].' '.$discountsinfo['discount_amount'].'</option>';
                                        }?>
                                    </select>

 
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Ship</label>
                                    <input type="text" value="<?php echo $salesinfo['ship']?>" id="inputshippingsale" onchange="updateshipsalechange(this.value)">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="select" name="statuseditsale" id="statuseditsale">
                                        <option value="" >Choose Status</option>
                                        <option value="Complete" <?php echo ($salesinfo['status'] == 'Complete') ? 'selected' : ''; ?>>Complete</option>
                                        <option value="Pending" <?php echo ($salesinfo['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" name="description" id="descriptionsale" ><?php echo $salesinfo['description']?></textarea>
                                </div>
                            </div>
                            <input type="hidden" id="inputhiddensaleid" value="<?php echo $salesinfo['sale_id']?>">
                            <div class="row">
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Thuế đơn hàng</h4>
                                                <h5 id="totalItemtax"><?php echo  ($salesinfo['tax']*$salesinfo['total']);?> $</h5>
                                            </li>
                                            <li>
                                                <h4>Giảm giá </h4>
                                                <h5 id="totalItemdiscount">$ <?php echo $salesinfo['discountvaluetotal']; ?></h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Ship</h4>
                                                <h5 id="shippingallsale">$ <?php echo $salesinfo['ship']; ?></h5>
                                            </li>
                                            <li class="total">
                                                <h4>Tổng đơn hàng</h4>
                                                <h5 id="grandtotalallsale">$<?php echo $salesinfo['grand_total']; ?></h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button name="submit" id="submitbutton" onclick="EditItem_sale(event)" href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
                                <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
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

        // function toggleInputADDcustomer() {
        //     var selectField = document.getElementById("customernameselectadd");
        //     var inputField = document.getElementById("emailInput");
            
        //     if (selectField.value !== "") {
        //         inputField.disabled = true;
        //         inputField.value = ""; // Reset the value
        //     } else {
        //         inputField.disabled = false;
        //     }
            
        //     if (inputField.value !== "") {
        //         selectField.disabled = true;
        //         selectField.selectedIndex = 0; // Reset the selected option
        //     } else {
        //         selectField.disabled = false;
        //     }
        // }

                // $(document).ready(function() {
                //     var emailList = JSON.parse($("#emailListData").val());
                //     var maxSuggestions = 5; // Số lượng kết quả gợi ý tối đa hiển thị

                //     $("#emailInput").on("input", function() {
                //         toggleInputADDcustomer();
                //         var query = $(this).val();
                //         var suggestions = [];
                //         for (var i = 0; i < emailList.length; i++) {
                //             if (emailList[i].toLowerCase().includes(query.toLowerCase())) {
                //                 suggestions.push(emailList[i]);
                //             }
                //         }

                //         var suggestionsHtml = "";
                //         for (var i = 0; i < Math.min(suggestions.length, maxSuggestions); i++) {
                //             suggestionsHtml += "<div class='suggestion'>" + suggestions[i] + "</div>";
                //         }
                //         if (suggestions.length > maxSuggestions) {
                //             suggestionsHtml += "<div id='moreSuggestions' class='suggestion'>See more suggestions...</div>";
                //         }
                //         $("#emailSuggestions").html(suggestionsHtml);
                //     });

                //     $("#emailSuggestions").on("click", ".suggestion", function() {
                //         var selectedEmail = $(this).text();
                //         $("#emailInput").val(selectedEmail);
                //         $("#emailSuggestions").html("");
                //     });

                //     $("#emailSuggestions").on("click", "#moreSuggestions", function() {
                //         var allSuggestionsHtml = "";
                //         for (var i = maxSuggestions; i < emailList.length; i++) {
                //             allSuggestionsHtml += "<div class='suggestion'>" + emailList[i] + "</div>";
                //         }
                //         allSuggestionsHtml += "<div id='lessSuggestions' class='suggestion'>See fewer suggestions...</div>";
                //         $("#emailSuggestions").html(allSuggestionsHtml);
                //     });

                //     $("#emailSuggestions").on("click", "#lessSuggestions", function() {
                //         var suggestionsHtml = "";
                //         for (var i = 0; i < Math.min(emailList.length, maxSuggestions); i++) {
                //             suggestionsHtml += "<div class='suggestion'>" + emailList[i] + "</div>";
                //         }
                //         if (emailList.length > maxSuggestions) {
                //             suggestionsHtml += "<div id='moreSuggestions' class='suggestion'>See more suggestions...</div>";
                //         }
                //         $("#emailSuggestions").html(suggestionsHtml);
                //     });
        // });
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
            var categoryAndSubcategoryDataInput = document.getElementById('categoryAndSubcategoryData');
            var categoryAndSubcategory = JSON.parse(categoryAndSubcategoryDataInput.value);
            var discountjsonendcode = document.getElementById('discountjsonendcode');
            var discountjsonend = JSON.parse(discountjsonendcode.value);
            function handleSelectionChangecategory(selectElement, productSelectId) {
                var selectedId = parseInt(selectElement.value);

                var categoryAndSubcategoryDataInput = document.getElementById('categoryAndSubcategoryData');
                var categoryAndSubcategory = JSON.parse(categoryAndSubcategoryDataInput.value);

                var productSelect = document.getElementById(productSelectId);
                productSelect.innerHTML = '<option value="">Select Product</option>';
                // Loop through categoryAndSubcategory data and find the selected item (category or product)
                categoryAndSubcategory.forEach(function (item) {
                    if (item.categoryproduct_id === selectedId) {
                        // Handle category change: update product list
                        item.products.forEach(function (product) {
                            var option = document.createElement('option');
                            option.value = product.product_id;
                            option.textContent = product.sku + " - " + product.pname;
                            productSelect.appendChild(option);
                        });
                    }
                    
                });
            }
            function handleSelectionChangeProduct(selectElement) {
                var selectedId = parseInt(selectElement.value);

                
                
                var tableBody = document.querySelector('.tableeditdetail');
                var existingProductIds = Array.from(tableBody.querySelectorAll('tr td[data-product-id]')).map(td => parseInt(td.dataset.productId));
                categoryAndSubcategory.forEach(function (item) {
                    item.products.forEach(function (product) {
                        if (product.product_id === selectedId) {
                            if (existingProductIds.includes(product.product_id)) {
                                Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                text: "product has been selected",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                     return;
                                }
                            });
                                // Reset the select option
                                selectElement.selectedIndex = 0;
                                return;
                            }
                            var newRow = document.createElement('tr');
                            newRow.className = "tableeditdetail"; // Add the class for the row
                            newRow.innerHTML = `
                                <td data-product-id="${product.product_id}">${product.sku}</td>
                                <td class="productimgname">
                                    <a class="product-img">
                                        <img src="${product.image}" alt="Product Image">
                                    </a>
                                    <a href="javascript:void(0);">${product.pname}</a>
                                </td>
                                <td  data-product-id="${product.product_id}">
                                                <div class="input-group form-group mb-0">
                                                        <a class="scanner-set input-group-text" onclick="updateQuantity(${product.product_id}, 1)">
                                                            <img src="assets/img/icons/plus1.svg" alt="img">
                                                        </a>
                                                        <input type="text"  value="1" class="calc-no quantity_field" data-product-id="${product.product_id}">
                                                        <a class="scanner-set input-group-text" onclick="updateQuantity(${product.product_id}, -1)">
                                                            <img src="assets/img/icons/minus.svg" alt="img">
                                                        </a>
                                                </div>
                                </td>
                                <td class="classpricechange" data-product-id="${product.product_id}">${product.price}</td>
                                <td class="classdiscountchange" data-product-id="${product.product_id}">${product.discount*product.price}</td>
                                <td class="classtaxchange" data-product-id="${product.product_id}">${product.tax*product.price}</td>
                                <td class="subtotalflag" data-product-id="${product.product_id}">${product.price *1.00 - product.price*product.discount + product.tax*product.price }</td>
                                <td data-detail-id="${product.product_id}">
                                    <a href="javascript:void(0);" class="delete_set_saledetail" data-detail-id="${product.product_id}"><img
                                        src="assets/img/icons/delete.svg" alt="svg"></a>
                                </td>
                            `;
                            tableBody.appendChild(newRow);
                           var changetax = document.getElementById('inputtaxsale').value;
                            updatetotalproducttax(changetax);
                            var selectElement = document.getElementById('selectdiscountsale');
                            var selectedIndex = selectElement.selectedIndex;
                            var option = selectElement.options[selectedIndex];
                            var changediscount = option.dataset.discountValue;
                            updatetotalproductdiscount(changediscount);
                            var totalItemtax = document.getElementById('totalItemtax');
                            var totalItemdiscount = document.getElementById('totalItemdiscount');
                            var changeshippingsale = document.getElementById('inputshippingsale').value;
                            var totalItemtaxvalue = parseFloat(totalItemtax.textContent.replace("$", ""));
                            var totalItemdiscountvalue = parseFloat(totalItemdiscount.textContent.replace("$", ""));
                            var changegrandtotalAll = totalItemtaxvalue - totalItemdiscountvalue + parseFloat(changeshippingsale);
                            updateGrandtotalallSale(changegrandtotalAll);
                        }
                    });
                });
            }
            var tableBody = document.querySelector('.tableeditdetail');
                tableBody.addEventListener('click', function(event) {
                    var clickedElement = event.target.closest('a');
                    if (clickedElement.classList.contains('delete_set_saledetail')) {
                        var tableRow = clickedElement.closest('tr');
                        if (tableRow) {
                            var productId = tableRow.querySelector('td[data-product-id]').dataset.productId;
                            if (productId) {
                                tableBody.removeChild(tableRow);
                                categoryAndSubcategory.forEach(function (item) {
                                item.products.forEach(function (product) {
                                    if (product.product_id === parseInt(productId)) {
                                        var changetax = document.getElementById('inputtaxsale').value;
                                        updatetotalproducttax(changetax);
                                        var selectElement = document.getElementById('selectdiscountsale');
                                        var selectedIndex = selectElement.selectedIndex;
                                        var option = selectElement.options[selectedIndex];
                                        var changediscount = option.dataset.discountValue;
                                        updatetotalproductdiscount(changediscount);
                                        var totalItemtax = document.getElementById('totalItemtax');
                                        var totalItemdiscount = document.getElementById('totalItemdiscount');
                                        var changeshippingsale = document.getElementById('inputshippingsale').value;
                                        var totalItemtaxvalue = parseFloat(totalItemtax.textContent.replace("$", ""));
                                        var totalItemdiscountvalue = parseFloat(totalItemdiscount.textContent.replace("$", ""));
                                        var changegrandtotalAll = totalItemtaxvalue - totalItemdiscountvalue + parseFloat(changeshippingsale);
                                        updateGrandtotalallSale(changegrandtotalAll);
                                    }
                                    });
                                     });
                            }
                        }
                    }
            });
            function updateQuantity(product_id, change) {
                    var inputElement = document.querySelector(".quantity_field[data-product-id='" + product_id + "']");
                    var currentValue = parseInt(inputElement.value);
                    var newValue = currentValue + change;
                    
                    if (newValue >= 1) {
                        inputElement.value = newValue;
                        // console.log(inputElement.value);
                    }
                    categoryAndSubcategory.forEach(function (item) {
                    item.products.forEach(function (product) {
                        if (product_id === product.product_id) {
                    var subtotalflag = document.querySelector(".subtotalflag[data-product-id='" + product_id + "']");
                    var subtotalflagvalue = parseFloat(subtotalflag.textContent);
                    if (change==1){
                        var subtotalflagvalue = subtotalflagvalue+ parseFloat(product.price) - parseFloat(product.discount*product.price) + parseFloat(product.tax*product.price);
                    }
                    else if (change!=1 && newValue >= 1) {
                        var subtotalflagvalue = subtotalflagvalue - parseFloat(product.price) + parseFloat(product.discount*product.price) - parseFloat(product.tax*product.price);
                    }
                    subtotalflag.textContent = subtotalflagvalue;
                }
                     });
                    });
                    var totalItemtax = document.getElementById('totalItemtax');
                    var totalItemdiscount = document.getElementById('totalItemdiscount');
                    var changeshippingsale = document.getElementById('inputshippingsale').value;
                    var totalItemtaxvalue = parseFloat(totalItemtax.textContent.replace("$", ""));
                    var totalItemdiscountvalue = parseFloat(totalItemdiscount.textContent.replace("$", ""));
                    var changegrandtotalAll = totalItemtaxvalue - totalItemdiscountvalue + parseFloat(changeshippingsale);
                    updateGrandtotalallSale(changegrandtotalAll);
                    
                }
            function updatetotalproducttax(change) {
                var totalItemElementtax = document.getElementById('totalItemtax');
                var subtotalflagElements = document.querySelectorAll('.subtotalflag');
                var flagsubtotal = 0;
                if (change=='') {change=0;}
                subtotalflagElements.forEach(function (subtotalflag) {
                    var subtotalflagvalue = parseFloat(subtotalflag.textContent);
                    if (!isNaN(subtotalflagvalue)) {
                        flagsubtotal += subtotalflagvalue;
                    }
                });

                if (!isNaN(change)) { // Kiểm tra xem change có phải là số hợp lệ không
                    change = change * flagsubtotal;
                    var totalItemElementtaxvalue = change;

                    if (totalItemElementtaxvalue < 0) {
                        totalItemElementtax.textContent = '0 $';
                    } else {
                        totalItemElementtax.textContent = totalItemElementtaxvalue + ' $';
                        var totalItemdiscount = document.getElementById('totalItemdiscount');
                        var totalItemdiscountvalue = parseFloat(totalItemdiscount.textContent.replace("$", ""));
                        var changeshippingsale = document.getElementById('inputshippingsale').value;
                        var changegrandtotalAll = totalItemElementtaxvalue - totalItemdiscountvalue + parseFloat(changeshippingsale);
                        updateGrandtotalallSale(changegrandtotalAll);
                    }
                }
            }
            // var selectElementdiscount = document.getElementById('selectdiscountsale');
            // function changeselectdiscountsale (selectElementdiscount) {
            //     var selectedId2 = parseInt(selectElementdiscount.value);
            //     console.log(selectedId2);
            //     discountjsonend.forEach(function (item) {
            //         console.log(item.minium_value);
            //         if (selectedId2 === item.discount_id) {
            //             console.log(item.discount_id);
            //             var discountamountminimum = item.minium_value;
            //             var totalItemdelelementdiscount = document.getElementById('totalItemdiscount');
            //             var subtotalflagElements = document.querySelectorAll('.subtotalflag');
            //             var flagsubtotal = 0;
            //             subtotalflagElements.forEach(function(subtotalflag) {
            //                 var subtotalflagvalue = parseFloat(subtotalflag.textContent);
            //                 flagsubtotal += subtotalflagvalue;
            //             });
            //             if (discountamountminimum > flagsubtotal) {
            //                 Swal.fire({
            //                     icon: "error",
            //                     title: "Error",
            //                     text: "Discount amount minimum is "+discountamountminimum,
            //                 }).then((result) => {
            //                     if (result.isConfirmed) {
            //                         selectelement.selectedIndex = 0;
            //                         return;
            //                     }
            //                 });
            //             }
            //         }
            //     });
            //     var selectedIndex = selectElementdiscount.selectedIndex;
            //     var option = selectElementdiscount.options[selectedIndex];
            //     var changediscount = option.dataset.discountValue;
            //     updatetotalproductdiscount(changediscount);
            // }
            // document.addEventListener("DOMContentLoaded", function () {
            //     // Kiểm tra xem phần tử select có tồn tại
            //     var selectElementdiscount = document.getElementById("selectdiscountsale");
            //     if (selectElementdiscount) {
            //         selectElementdiscount.addEventListener("change", function () {
            //             var selectedId2 = parseInt(selectElementdiscount.value);
            //             console.log(selectedId2);
            //             discountjsonend.forEach(function (item) {
            //                 if (item.discount_id === selectedId2) {
            //                     var discountamountminimum = item.minium_value;
            //                     var totalItemdelelementdiscount = document.getElementById(
            //                         "totalItemdiscount"
            //                     );
            //                     var subtotalflagElements = document.querySelectorAll(
            //                         ".subtotalflag"
            //                     );
            //                     var flagsubtotal = 0;
            //                     subtotalflagElements.forEach(function (subtotalflag) {
            //                         var subtotalflagvalue = parseFloat(
            //                             subtotalflag.textContent
            //                         );
            //                         flagsubtotal += subtotalflagvalue;
            //                     });
            //                     if (discountamountminimum >= flagsubtotal) {
            //                         Swal.fire({
            //                             icon: "error",
            //                             title: "Error",
            //                             text: "Discount amount minimum is " + discountamountminimum,
            //                         }).then((result) => {
            //                             if (result.isConfirmed) {
            //                                 // Đặt giá trị về mặc định
            //                                 selectElementdiscount.selectedIndex = 0;
            //                             }
            //                         });
            //                     }
            //                 }
            //             });
            //             // Làm cái khác với giá trị đã chọn
            //             var selectedIndex = selectElementdiscount.selectedIndex;
            //             var option = selectElementdiscount.options[selectedIndex];
            //             var changediscount = option.dataset.discountValue;
            //             updatetotalproductdiscount(changediscount);
            //         });
            //     }
            // });
            function changeselectdiscountsale(selectElementdiscount) {
                var selectedId2 = parseInt(selectElementdiscount.value);

                discountjsonend.forEach(function (item) {
                    // console.log(item.minium_value);
                    if (selectedId2 === parseInt(item.discount_id)) {
                        console.log(item.discount_id);
                        var discountamountminimum = item.minium_value;
                        var totalItemdelelementdiscount = document.getElementById('totalItemdiscount');
                        var subtotalflagElements = document.querySelectorAll('.subtotalflag');
                        var flagsubtotal = 0;
                        subtotalflagElements.forEach(function(subtotalflag) {
                            var subtotalflagvalue = parseFloat(subtotalflag.textContent);
                            flagsubtotal += subtotalflagvalue;
                        });
                        if (discountamountminimum > flagsubtotal && parseInt(item.status) === 1) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Discount amount minimum is " + discountamountminimum,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Corrected variable name
                                    selectElementdiscount.selectedIndex = 0;
                                    return;
                                }
                            });
                        }
                        else if (parseInt(item.status)!==1){
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Discount is not active",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Corrected variable name
                                    selectElementdiscount.selectedIndex = 0;
                                    return;
                                }
                            });
                        }
                        else {
                            var selectedIndex = selectElementdiscount.selectedIndex;
                            var option = selectElementdiscount.options[selectedIndex];
                            var changediscount = option.dataset.discountValue;
                            updatetotalproductdiscount(changediscount);
                        }
                    }
                    else if (selectedId2 === 0) {
                        var selectedIndex = selectElementdiscount.selectedIndex;
                        var option = selectElementdiscount.options[selectedIndex];
                        var changediscount = option.dataset.discountValue;
                        updatetotalproductdiscount(changediscount);
                    }
                });
                
                
            }


        function updatetotalproductdiscount(change) {
            

            var totalItemdelelementdiscount = document.getElementById('totalItemdiscount');
            var subtotalflagElements = document.querySelectorAll('.subtotalflag');
            var flagsubtotal = 0;
            if (change=='') {change=0;}
            subtotalflagElements.forEach(function(subtotalflag) {
                var subtotalflagvalue = parseFloat(subtotalflag.textContent);
                flagsubtotal += subtotalflagvalue;
            });

            change = change * flagsubtotal;
                    var totalItemElementdiscountvalue = change;
                    if (totalItemElementdiscountvalue < 0) {
                        totalItemdelelementdiscount.textContent = '0 $';
                    } else {
                        totalItemdelelementdiscount.textContent = totalItemElementdiscountvalue + ' $';
                        var totalItemtax = document.getElementById('totalItemtax');
                        var totalItemtaxvalue = parseFloat(totalItemtax.textContent.replace("$", ""));
                        var changeshippingsale = document.getElementById('inputshippingsale').value;
                        var changegrandtotalAll = totalItemtaxvalue - totalItemElementdiscountvalue + parseFloat(changeshippingsale);
                        updateGrandtotalallSale(changegrandtotalAll);
                        
                    }
           
        }
        function updateshipsalechange(change){
            var shippingallsale = document.getElementById('shippingallsale');
            if (change=='') {change=0;}
            var shippingallsalevalue = parseFloat(change);
            if (shippingallsalevalue < 0) {
                shippingallsale.textContent = '0 $';
            } else {
                shippingallsale.textContent = shippingallsalevalue + ' $';
                var totalItemtax = document.getElementById('totalItemtax');
                var totalItemdiscount = document.getElementById('totalItemdiscount');
                var totalItemtaxvalue = parseFloat(totalItemtax.textContent.replace("$", ""));
                var totalItemdiscountvalue = parseFloat(totalItemdiscount.textContent.replace("$", ""));
                var changegrandtotalAll = totalItemtaxvalue - totalItemdiscountvalue + shippingallsalevalue;
                updateGrandtotalallSale(changegrandtotalAll);
            }
        }
        function updateGrandtotalallSale(change){
            var grandtotalallsale = document.getElementById('grandtotalallsale');

            var subtotalflagElements = document.querySelectorAll('.subtotalflag');
            var flagsubtotal = 0;
            subtotalflagElements.forEach(function(subtotalflag) {
                var subtotalflagvalue = parseFloat(subtotalflag.textContent);
                flagsubtotal += subtotalflagvalue;
            });

            var grandtotalallsalevalue =flagsubtotal+ change;
            if (grandtotalallsalevalue < 0) {
                grandtotalallsale.textContent = '0 $';
            } else {
                grandtotalallsale.textContent = grandtotalallsalevalue + ' $';
            }
        }



</script>
<script>
            function EditItem_sale(event) {
                event.preventDefault();
                var emailInput = $('#emailInput').val();
                var statussaleselect = $('#statuseditsale').val();
                var discountsaleselect = $('#selectdiscountsale').val(); // Chuyển đổi định dạng ngày
                var shippingsaleinput =  parseFloat($('#inputshippingsale').val());
                var ordertaxinput =  parseFloat($('#inputtaxsale').val());
                var descriptioninput =  $('#descriptionsale').val();
                var idsale = $('#inputhiddensaleid').val();
                var submitbutton = $('#submitbutton').val();


                var productsData = {
                email: emailInput,
                status: statussaleselect,
                shipping: shippingsaleinput,
                tax: ordertaxinput,
                discount: discountsaleselect,
                description: descriptioninput,
                sale_id: idsale,
                submit: 'submit',


                items: []
                };

                $(".tableeditdetail .quantity_field").each(function() {
                    var productId = $(this).data("product-id");
                    var quantity = $(this).val();

                    // Thêm thông tin sản phẩm vào mảng items
                    productsData.items.push({
                        product_id: productId,
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
                        text: "Edit sale success",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "saleslist.php";
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