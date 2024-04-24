<?php require_once('./main/role_manager.php'); ?>
<?php

    require '../model/usercustomer_model.php';
    require '../model/customer_model.php';
    require '../model/cagegoryproduct_model.php';
    require '../model/sale_model.php';
    $modelsale = new SaleModel($conn);
    $usercustomermodel = new UserCustomerModel($conn);
    $customermodel = new CustomerModel($conn);
    $listemailusercustomer = $usercustomermodel -> getEmailList();
    $listusercustomer = $customermodel -> listCustomers();
    $categoryModal = new CategoryProductModel($conn);
    $category_resultfinale = $categoryModal->showCategoryProducts();
    $ca_pro_finale_sub = $modelsale->showCategoryProducts_subSale();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Dreams Pos admin template</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">

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
    width: 23%;
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
                        <h4>Add Sale</h4>
                        <h6>Add your new sale</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
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
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Email Customer</label>
                                    <input type="text" id="emailInput" placeholder="Enter customer email" name="emailcustomeruser">
                                    <div id="emailSuggestions" class="suggestions"></div>
                                </div>
                            </div>
                            <input type="hidden" id="emailListData" value="<?php echo htmlspecialchars($listemailusercustomer) ?>">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer</label>
                                    <div class="input-groupicon">
                                        <input type="text" placeholder="Choose Date" class="datetimepicker">
                                        <a class="addonset">
                                            <img src="assets/img/icons/calendars.svg" alt="img">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select class="select">
                                        <option>Choose</option>
                                        <option>Supplier Name</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <div class="input-groupicon">
                                        <input type="text" placeholder="Please type product code and select...">
                                        <div class="addonset">
                                            <img src="assets/img/icons/scanners.svg" alt="img">
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="select" name="categoryproduct_id" id="categorySelect" required onchange="handleSelectionChange(this, 'productSelect', 'selectedProductImage', 'selectedProductInfo')" >
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
                                    <label>Product</label>
                                    <select class="select" name="productSelect" id="productSelect" required  >
                                        <option value="">Choose Product</option>
                                      
                                    </select>
                                </div>
                            </div>
                        </div>
                        <img id="selectedProductImage" src="" alt="Selected Product Image">

                            <div id="selectedProductInfo">
                                <!-- Product info will be displayed here -->
                            </div>
                        <input type="hidden" id="categoryAndSubcategoryData" value="<?php echo htmlspecialchars($ca_pro_finale_sub) ?>">
                        <div class="row">
                            <div class="table-responsive mb-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>QTY</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Tax</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="productimgname">
                                                <a class="product-img">
                                                    <img src="assets/img/product/product7.jpg" alt="product">
                                                </a>
                                                <a href="javascript:void(0);">Apple Earpods</a>
                                            </td>
                                            <td>1.00</td>
                                            <td>15000.00</td>
                                            <td>0.00</td>
                                            <td>0.00</td>
                                            <td>1500.00</td>
                                            <td>
                                                <a href="javascript:void(0);" class="delete-set"><img
                                                        src="assets/img/icons/delete.svg" alt="svg"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="productimgname">
                                                <a class="product-img">
                                                    <img src="assets/img/product/product8.jpg" alt="product">
                                                </a>
                                                <a href="javascript:void(0);">iPhone 11</a>
                                            </td>
                                            <td>1.00</td>
                                            <td>1500.00</td>
                                            <td>0.00</td>
                                            <td>0.00</td>
                                            <td>1500.00</td>
                                            <td>
                                                <a href="javascript:void(0);" class="delete-set"><img
                                                        src="assets/img/icons/delete.svg" alt="svg"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="productimgname">
                                                <a class="product-img">
                                                    <img src="assets/img/product/product1.jpg" alt="product">
                                                </a>
                                                <a href="javascript:void(0);">Macbook pro</a>
                                            </td>
                                            <td>1.00</td>
                                            <td>1500.00</td>
                                            <td>0.00</td>
                                            <td>0.00</td>
                                            <td>1500.00</td>
                                            <td>
                                                <a href="javascript:void(0);" class="delete-set"><img
                                                        src="assets/img/icons/delete.svg" alt="svg"></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Order Tax</label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Discount</label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Shipping</label>
                                    <input type="text">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select">
                                        <option>Choose Status</option>
                                        <option>Completed</option>
                                        <option>Inprogress</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Order Tax</h4>
                                                <h5>$ 0.00 (0.00%)</h5>
                                            </li>
                                            <li>
                                                <h4>Discount </h4>
                                                <h5>$ 0.00</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul>
                                            <li>
                                                <h4>Shipping</h4>
                                                <h5>$ 0.00</h5>
                                            </li>
                                            <li class="total">
                                                <h4>Grand Total</h4>
                                                <h5>$ 0.00</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <a href="javascript:void(0);" class="btn btn-submit me-2">Submit</a>
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
    function toggleInputADDcustomer() {
    var selectField = document.getElementById("customernameselectadd");
    var inputField = document.getElementById("emailInput");
    
    if (selectField.value !== "") {
        inputField.disabled = true;
        inputField.value = ""; // Reset the value
    } else {
        inputField.disabled = false;
    }
    
    if (inputField.value !== "") {
        selectField.disabled = true;
        selectField.selectedIndex = 0; // Reset the selected option
    } else {
        selectField.disabled = false;
    }
    }

        $(document).ready(function() {
            var emailList = JSON.parse($("#emailListData").val());
            var maxSuggestions = 5; // Số lượng kết quả gợi ý tối đa hiển thị

            $("#emailInput").on("input", function() {
                toggleInputADDcustomer();
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

            function handleSelectionChange(selectElement, productSelectId, imageElementId, infoElementId) {
                var selectedId = parseInt(selectElement.value);

                var categoryAndSubcategoryDataInput = document.getElementById('categoryAndSubcategoryData');
                var categoryAndSubcategory = JSON.parse(categoryAndSubcategoryDataInput.value);

                var productSelect = document.getElementById(productSelectId);
                var selectedProductImage = document.getElementById(imageElementId);
                var selectedProductInfo = document.getElementById(infoElementId);

                // Clear the product selection, product image, and product info
                productSelect.innerHTML = '<option value="">Select Product</option>';
                selectedProductImage.src = "";
                selectedProductInfo.innerHTML = "";

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
                    item.products.forEach(function (product) {
                        if (product.product_id === selectedId) {
                            // Handle product change: display product info
                            var productInfo = document.createElement('div');
                            productInfo.classList.add('product-info');

                            var productImage = document.createElement('img');
                            productImage.src = product.image;
                            productImage.alt = "Product Image";
                            productInfo.appendChild(productImage);

                            var productSku = document.createElement('span');
                            productSku.textContent = product.sku;
                            productInfo.appendChild(productSku);

                            var productName = document.createElement('span');
                            productName.textContent = product.pname;
                            productInfo.appendChild(productName);

                            selectedProductInfo.appendChild(productInfo);
                        }
                    });
                });
            }




</script>
</body>

</html>