<?php require_once('./main/role_manager.php'); ?>
<?php
    require './API/salereturn_API.php';
    $APIsalererturn = new saleReturnAPI($conn);
    $saleinfo = $APIsalererturn->getALLsaleforreturnAPI();
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
    width:22%;
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
                        <h4>Tạo đơn trả hàng</h4>
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
                                                <option value="">Select Customer</option>
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
                                        <input type="date" placeholder="DD-MM-YYYY" id="datereturninput">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mã tham chiếu (tìm mã này trước).</label>
                                    <input type="text" id="searchInputreference" placeholder="Input reference no">
                                    <div id="detailsaleSuggestions" class="suggestions"></div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Mô tả sản phẩm</label>
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
                                        <div class="addonset ">
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
                                            <th>Tạm tính($) </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tableeditdetail">
                        
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
                                            <h5 id="taxContextall">$ 0.00 (0.00%)</h5>
                                        </li>
                                        <li>
                                            <h4>Giảm giá </h4>
                                            <h5 id="DiscountContextall">$ 0.00</h5>
                                        </li>
                                        <li>
                                            <h4>Phí ship</h4>
                                            <h5 id="shippingContextall">$ 0.00</h5>
                                        </li>
                                        <li class="total">
                                            <h4>Tổng đơn hàng</h4>
                                            <h5 id="grandTotalcontextall">$ 0.00</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Thuế đơn hàng</label>
                                    <input type="text" id="inputOrdertax">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Giảm giá</label>
                                    <input type="text" id="inputshippingDisconunt">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Ship</label>
                                    <input type="text" id="inputshippingreturn">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select class="select" id="SelectStatusreturn">
                                        <option>Choose Status</option>
                                        <option value="Complete">Complete</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Odered">Odered</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Trạng thái thanh toán</label>
                                    <select class="select" id="SelectPaymentStatusreturn">
                                        <option>Choose Status</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Due">Due</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Loại thanh toán</label>
                                    <select class="select" id="SelectPaymentnamereturn">
                                        <option>Choose payment</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Debit">Debit</option>
                                        <option value="MoMo">MoMo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Lý do</label>
                                    <textarea class="form-control" id="reasontexreturn"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <a href="javascript:void(0);" class="btn btn-submit me-2" onclick=" CreateItem_saleReturn(event)">Submit</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
       $(document).ready(function() {
    var emailList = JSON.parse($("#hiddenhtmlsalereference").val());
    var maxSuggestions = 5; // Số lượng kết quả gợi ý tối đa hiển thị
    var suggestionsHtml = ""; // Biến để lưu trữ HTML của gợi ý

    function updateSuggestions(query) {
        var suggestions = [];
        for (var i = 0; i < emailList.length; i++) {
            // Kiểm tra nếu trường "reference" của đối tượng i chứa chuỗi query
            if (emailList[i].reference.toLowerCase().includes(query.toLowerCase())) {
                suggestions.push(emailList[i].reference);
            }
        }

        suggestionsHtml = "";
        for (var i = 0; i < Math.min(suggestions.length, maxSuggestions); i++) {
            suggestionsHtml += "<div class='suggestion'>" + suggestions[i] + "</div>";
        }
        if (suggestions.length > maxSuggestions) {
            suggestionsHtml += "<div id='moreSuggestions' class='suggestion'>See more suggestions...</div>";
        }
        $("#detailsaleSuggestions").html(suggestionsHtml);
            }

            $("#searchInputreference").on("input", function() {
                var query = $(this).val();
                updateSuggestions(query);
            });

            $("#detailsaleSuggestions").on("click", ".suggestion", function() {
                var selectedEmail = $(this).text();
                $("#searchInputreference").val(selectedEmail);
                $("#detailsaleSuggestions").html("");
                console.log(selectedEmail);
                $.ajax({
                url: "../../admin-page/view/API/salereturn_API.php",
                method: "POST", // Hoặc "POST" nếu cần
                data: { reference: selectedEmail }, // Truyền reference cho API
                success: function(response) {
                    var saleDetails = JSON.parse(response);
                    $("#saledetailresponse234").val(JSON.stringify(saleDetails));
                    // bat index cua option
                    var selectedOptionIndex = -1;
                    var selectOptionsHtml = "";
                    selectOptionsHtml += "<option value=''>Choose product</option>";
                    for (var i = 0; i < saleDetails.length; i++) {
                        selectOptionsHtml += "<option value='" + saleDetails[i].product_id + "'  data-image='"+ saleDetails[i].image +"'>" +
                            saleDetails[i].name + " (SKU: " + saleDetails[i].sku + ")" +
                            "</option>";
                    }
                   
                    // Cập nhật thẻ <select> với các option mới
                    $("#detailProductsale").html(selectOptionsHtml);
                    var selectOptionsHtmlcustomer = "";
                    selectOptionsHtmlcustomer += "<option value='"+saleDetails[0].email+"'>"+saleDetails[0].email+"</option>";
                    $("#customeremail").html(selectOptionsHtmlcustomer);
                     // tra gia tri index 
                    
                   

                    // Gui ajax cap nhat the input moi
                    $.ajax({
                        url: "../../admin-page/view/API/salereturn_API.php",
                        method: "POST", // Hoặc "POST" nếu cần
                        data: { sale_id:  saleDetails[0].sale_id}, // Truyền reference cho API
                        success: function(response) {
                            var saleDetails234 = JSON.parse(response);
                            $("#saledetailresponse").val(JSON.stringify(saleDetails234));
                                
                            },
                            error: function(error) {
                                console.error("Lỗi khi gọi API: " + error);
                                console.log(error);
                            }
                            });
                            },
                            error: function(error) {
                                console.error("Lỗi khi gọi API: " + error);
                                console.log(error);
                            }
                        
                    });
            });

            $("#detailsaleSuggestions").on("click", "#moreSuggestions", function() {
                var allSuggestionsHtml = "";
                for (var i = maxSuggestions; i < emailList.length; i++) {
                    allSuggestionsHtml += "<div class='suggestion'>" + emailList[i].reference + "</div>";
                }
                allSuggestionsHtml += "<div id='lessSuggestions' class='suggestion'>See fewer suggestions...</div>";
                $("#detailsaleSuggestions").html(allSuggestionsHtml);
            });

            $("#detailsaleSuggestions").on("click", "#lessSuggestions", function() {
                $("#detailsaleSuggestions").html(suggestionsHtml); // Hiển thị lại các gợi ý trước đó
            });
});

function changeproductSaleDetail(selectElement) {
    var searchInputreference = document.getElementById('searchInputreference');
    searchInputreference.disabled = true;
    var selectedId = parseInt(selectElement.value);
    var saledetail = JSON.parse($("#saledetailresponse234").val());
    var salereturn = JSON.parse($("#saledetailresponse").val());
    var tableBody = document.querySelector('.tableeditdetail');
    var existingProductIds = Array.from(tableBody.querySelectorAll('tr td[data-product-id]')).map(td => parseInt(td.dataset.productId));
    var hasReturnedProduct = false; // Biến cờ để kiểm tra xem đã hiển thị thông báo cảnh báo

    // Kiểm tra đã hiển thị thông báo trước đó chưa
    if (!hasReturnedProduct) {
        for (let item of saledetail) {
            if (hasReturnedProduct) break; // Dừng vòng lặp nếu đã hiển thị thông báo

            for (let item2 of salereturn) {
                if (item.product_id == item2.product_id && item.product_id == selectedId) {
                    // Hiển thị thông báo
                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: "Product has been returned.",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            hasReturnedProduct = true;
                            selectElement.selectedIndex = 0;
                        }
                    });
                    return; // Dừng hàm
                }
            }

            if (!hasReturnedProduct && selectedId == item.product_id) {
                if (existingProductIds.includes(selectedId)) {
                    // Hiển thị thông báo
                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: "Product has been added.",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            hasReturnedProduct = true;
                            selectElement.selectedIndex = 0;
                        }
                    });
                    return; // Dừng hàm
                } else if (existingProductIds.length > 0) {
                    // Hiển thị thông báo
                    Swal.fire({
                        icon: "warning",
                        title: "Warning",
                        text: "Only 1 product can be returned in one operation",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            hasReturnedProduct = true;
                            selectElement.selectedIndex = 0;
                        }
                    });
                    return; // Dừng hàm
                } else {
                    // Thêm sản phẩm vào bảng
                    var newRow = document.createElement('tr');
                    newRow.className = "tableeditdetail"; // Add the class for the row
                    newRow.innerHTML = `
                        <td data-product-id="${item.product_id}" class="productimgname">
                            <a class="product-img">
                                <img src="${item.image}" alt="product">
                            </a>
                            <a href="javascript:void(0);">${item.name}</a>
                        </td>
                        <td>${item.price}</td>
                        <td>${item.stock}</td>
                        <td>${item.quantity}</td>
                        <td>${item.discount}</td>
                        <td>${item.tax}</td>
                        <td>${item.total}</td>
                        <td>
                            <a class="delete-set_detailproduct" onclick="deletechildsaledetail(this)"><img src="assets/img/icons/delete.svg"
                                    alt="svg"></a>
                        </td>`;
                    tableBody.appendChild(newRow);
                    // cap nhat contextall
                    $("#inputOrdertax").val(parseFloat(item.tax)); 
                    $("#inputshippingDisconunt").val(parseFloat(item.discountvalue));
                    $("#inputshippingreturn").val(item.shipsale);
                    $("#taxContextall").text("$ "+parseFloat(item.tax*item.total));
                    $("#DiscountContextall").text("$ "+parseFloat(item.discountvalue*item.total));
                    $("#shippingContextall").text("$ "+item.shipsale);
                    $("#grandTotalcontextall").text("$ "+parseFloat(item.total + item.tax*item.total - item.discountvalue*item.total));
                    $("#idproductreturn").val(item.product_id);
                }
            }
        }
    }
}

function deletechildsaledetail(event) {
    var row = event.parentNode.parentNode;
    row.parentNode.removeChild(row);
    var searchInputreference = document.getElementById('searchInputreference');
    searchInputreference.disabled = false;
}

function CreateItem_saleReturn() {
    var referenceinput = $("#searchInputreference").val();
    var statussaleselect = $("#SelectStatusreturn").val();
    var statuspayment = $("#SelectPaymentStatusreturn").val();
    var returndate = $("#datereturninput").val();
    var paymentname = $("#SelectPaymentnamereturn").val();
    var reason = $("#reasontexreturn").val();
    var productid = $("#idproductreturn").val();
    var create = "create";
    console.log(productid);

    var productsData = new FormData();
    productsData.append("reference", referenceinput);
    productsData.append("status", statussaleselect);
    productsData.append("statuspayment", statuspayment);
    productsData.append("returndate", returndate);
    productsData.append("paymentname", paymentname);
    productsData.append("reason", reason);
    productsData.append("product_id", productid);
    productsData.append("create", create);
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
                                text: "Edit sale success",
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