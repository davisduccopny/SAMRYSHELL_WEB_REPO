<?php require_once('./main/role_manager.php'); ?>
<?php 
    if (isset($_GET['quotation_id'])) {
require '../model/product_model.php';
require '../model/cagegoryproduct_model.php';
require '../model/usercustomer_model.php';
require '../model/sale_model.php';
require '../model/quotation_model.php';
$usercustomermodel = new UserCustomerModel($conn);
$listemailusercustomer = $usercustomermodel -> getEmailList();
$productModel = new ProductModel($conn);
$products= $productModel->showProduct();
$categoryModelsale = new CategoryProductModel($conn);
$categorysale = $categoryModelsale->showCategoryProducts();
$saleModel = new SaleModel($conn);
$salescategoryressult = $saleModel->showCategoryProducts_subSale();
$category_resultfinale = $categoryModelsale->showCategoryProducts();
 $quotation_idlist = $_GET['quotation_id'];
$quotationModel = new QuotationModel($conn);
$quotationList = $quotationModel-> getQuotationListbyID($quotation_idlist);
$quotationDetail = $quotationModel-> getQuotationDetailbyID($quotation_idlist);
}
else {
    header("location: quotationlist.php");
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
        .suggestions {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    width: 19%;
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
    .product-info {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 5px;
    }
    .product-info img {
        max-width: 50px;
        max-height: 50px;
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
                        <h4>Chỉnh sửa báo giá</h4>
                        <h6>Add/Update Quotation</h6>
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
                                                <input type="text" id="emailInput" placeholder="Enter customer email" name="emailcustomeruser" value="<?php echo $quotationList[0]['email']; ?>" autocomplete="off">
                                                <div id="emailSuggestions" class="suggestions"></div>
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
                                    <label>Ngày báo giá </label>
                                    <div class="input-groupicon">
                                        <input type="date" id="dateinputquotation" value="<?php echo $quotationList[0]['created_at']; ?>">
                                    </div>
                                </div>
                            </div>
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
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th>Đơn giá($) </th>
                                            <th>Tồn kho</th>
                                            <th style="padding-right: 8%;" class="text-center">Số lượng</th>
                                            <th>Giảm giá($) </th>
                                            <th>Thuế % </th>
                                            <th class="text-end">Tạm tính ($)</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tableeditdetail">
                                    <?php foreach ($quotationDetail as $results): ?>
                                            <tr class="tableeditdetail">
                                                <td class="productimgname">
                                                    <a class="product-img">
                                                        <img src="<?php echo $results['image']; ?>" alt="Product Image">
                                                    </a>
                                                    <a href="javascript:void(0);"><?php echo $results['name']; ?></a>
                                                </td>
                                                <td class="classpricechange" data-product-id="<?php echo $results['product_id']; ?>"><?php echo $results['price']; ?></td>
                                                <td data-product-id="<?php echo $results['product_id']; ?>"><?php echo $results['quantity_product']; ?></td>
                                                <td data-product-id="<?php echo $results['product_id']; ?>" class="text-end">
                                                    <div class="input-group form-group mb-0">
                                                            <a class="scanner-set input-group-text" onclick="updateQuantity(<?php echo $results['product_id']; ?>, 1)">
                                                                <img src="assets/img/icons/plus1.svg" alt="img">
                                                            </a>
                                                            <input type="text" value="<?php echo $results['quantity']; ?>" class="calc-no quantity_field" data-product-id="<?php echo $results['product_id']; ?>">
                                                            <a class="scanner-set input-group-text" onclick="updateQuantity(<?php echo $results['product_id']; ?>, -1)">
                                                                <img src="assets/img/icons/minus.svg" alt="img">
                                                            </a>
                                                    </div>
                                                </td>
                                                <td class="classdiscountchange" data-product-id="<?php echo $results['product_id']; ?>"><?php echo $results['discount']; ?></td>
                                                <td class="classtaxchange" data-product-id="<?php echo $results['product_id']; ?>"><?php echo $results['tax']; ?></td>
                                                <td class="subtotalflag text-end" data-product-id="<?php echo $results['product_id']; ?>"><?php echo $results['total']; ?></td>
                                                <td data-detail-id="<?php echo $results['product_id']; ?>">
                                                    <a href="javascript:void(0);" class="delete_set_saledetail" data-detail-id="<?php echo $results['product_id']; ?>"><img src="assets/img/icons/delete.svg" alt="svg"></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-lg-12 float-md-right">
                                <div class="total-order">
                                    <ul>
                                        <li>
                                            <h4>Thuế đơn hàng</h4>
                                            <h5  id="totalItemtax">$ <?php echo $quotationList[0]['tax']*$quotationList[0]['total']; ?></h5>
                                        </li>
                                        <li>
                                            <h4>Giảm giá đơn hàng </h4>
                                            <h5 id="totalItemdiscount">$ <?php echo $quotationList[0]['discount']*$quotationList[0]['total']; ?> </h5>
                                        </li>
                                        <li class="total">
                                            <h4>Tổng đơn hàng</h4>
                                            <h5 id="grandtotalallsale">$ <?php echo $quotationList[0]['grand_total']; ?></h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Thuế đơn hàng</label>
                                    <input type="text"  onchange="updatetotalproducttax(this.value)" id="inputtaxsale" value="<?php echo $quotationList[0]['tax'];?>">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giảm giá đơn hàng</label>
                                    <input type="text"id="selectdiscountsale" onchange="updatetotalproductdiscount(this.value)" value="<?php echo $quotationList[0]['discount']; ?>">
                                </div>
                            </div>
                        <input type="hidden" id="categoryAndSubcategoryData" value="<?php echo htmlspecialchars($salescategoryressult) ?>">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="select" id="statusselectquotation">
                                        <option value="">Choose Status</option>
                                        <option value="Send" <?php echo ($quotationList[0]['status'] == 'Send') ? 'selected' : ''; ?>>Send</option>
                                        <option value="Ordered" <?php echo ($quotationList[0]['status'] == 'Ordered') ? 'selected' : ''; ?>>Ordered</option>
                                        <option value="Pending" <?php echo ($quotationList[0]['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control" id="descriptionquotation"><?php echo $quotationList[0]['description']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button name="submit" type="submit" onclick=" Update_quotation(event)" href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
                                <a href="quotationList.php" class="btn btn-cancel">Cancel</a>
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
         $(document).ready(function() {
                    // var emailList = JSON.parse($("#emailListData").val());
                    var emailList = JSON.parse(<?php echo json_encode($listemailusercustomer); ?>);
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
    <script>
        var categoryAndSubcategoryDataInput = document.getElementById('categoryAndSubcategoryData');
        var categoryAndSubcategory = JSON.parse(categoryAndSubcategoryDataInput.value);
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
                                <td class="productimgname">
                                    <a class="product-img">
                                        <img src="${product.image}" alt="Product Image">
                                    </a>
                                    <a href="javascript:void(0);">${product.pname}</a>
                                </td>
                                <td class="classpricechange" data-product-id="${product.product_id}">${product.price}</td>
                                <td data-product-id="${product.product_id}">${product.quantity}</td>
                                <td  data-product-id="${product.product_id}" class="text-end">
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
                                <td class="classdiscountchange" data-product-id="${product.product_id}">${product.discount*product.price}</td>
                                <td class="classtaxchange" data-product-id="${product.product_id}">${product.tax*product.price}</td>
                                <td class="subtotalflag text-end" data-product-id="${product.product_id}">${product.price *1.00 - product.price*product.discount + product.tax*product.price }</td>
                                <td data-detail-id="${product.product_id}">
                                    <a href="javascript:void(0);" class="delete_set_saledetail" data-detail-id="${product.product_id}"><img
                                        src="assets/img/icons/delete.svg" alt="svg"></a>
                                </td>
                            `;
                            tableBody.appendChild(newRow);
                           var changetax = document.getElementById('inputtaxsale').value;
                            updatetotalproducttax(changetax);
                            var changediscount = document.getElementById('selectdiscountsale').value;
                            updatetotalproductdiscount(changediscount);
                            var totalItemtax = document.getElementById('totalItemtax');
                            var totalItemdiscount = document.getElementById('totalItemdiscount');
                            var totalItemtaxvalue = parseFloat(totalItemtax.textContent.replace("$", ""));
                            var totalItemdiscountvalue = parseFloat(totalItemdiscount.textContent.replace("$", ""));
                            var changegrandtotalAll = totalItemtaxvalue - totalItemdiscountvalue;
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
                                        var changediscount = document.getElementById('selectdiscountsale').value;
                                        updatetotalproductdiscount(changediscount);
                                        var totalItemtax = document.getElementById('totalItemtax');
                                        var totalItemdiscount = document.getElementById('totalItemdiscount');
                                        var totalItemtaxvalue = parseFloat(totalItemtax.textContent.replace("$", ""));
                                        var totalItemdiscountvalue = parseFloat(totalItemdiscount.textContent.replace("$", ""));
                                        var changegrandtotalAll = totalItemtaxvalue - totalItemdiscountvalue;
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
                    var changetax = document.getElementById('inputtaxsale').value;
                    updatetotalproducttax(changetax);
                    var changediscount = document.getElementById('selectdiscountsale').value;
                    updatetotalproductdiscount(changediscount);
                    var totalItemtax = document.getElementById('totalItemtax');
                    var totalItemdiscount = document.getElementById('totalItemdiscount');
                    var totalItemtaxvalue = parseFloat(totalItemtax.textContent.replace("$", ""));
                    var totalItemdiscountvalue = parseFloat(totalItemdiscount.textContent.replace("$", ""));
                    var changegrandtotalAll = totalItemtaxvalue - totalItemdiscountvalue;
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
                        var changegrandtotalAll = totalItemElementtaxvalue - totalItemdiscountvalue;
                        updateGrandtotalallSale(changegrandtotalAll);
                    }
                }
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
                            var changegrandtotalAll = totalItemtaxvalue - totalItemElementdiscountvalue;
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
            function Update_quotation(event) {
                event.preventDefault();
                var quotation_id = <?php echo $quotation_idlist;?>;
                var emailInput = $('#emailInput').val();
                var statussaleselect = $('#statusselectquotation').val();
                var discountsaleselect = $('#selectdiscountsale').val(); // Chuyển đổi định dạng ngày
                var ordertaxinput =  parseFloat($('#inputtaxsale').val());
                var descriptioninput =  $('#descriptionquotation').val();
                var dateinputquotation = $('#dateinputquotation').val();
                // var idsale = $('#inputhiddensaleid').val();
                var submitbutton = $('#submitbutton').val();


                var productsData = {
                quotation_id: quotation_id,
                email: emailInput,
                status: statussaleselect,
                tax: ordertaxinput,
                discount: discountsaleselect,
                description: descriptioninput,
                date: dateinputquotation,
                // sale_id: idsale,
                update: 'update',


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
                url: "../controller/quotation_controller.php",
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
                            window.location.href = "quotationList.php";
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