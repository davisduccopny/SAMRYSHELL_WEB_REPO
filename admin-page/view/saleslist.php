<?php require_once('./main/role_manager.php'); ?>
<?php 
    require '../model/sale_model.php';
    $SaleModel = new SaleModel($conn);
    $Salemodelresults= $SaleModel->getAllSales();
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if (isset($_POST['id'])){
                $id = $_POST['id'];
                require '../controller/product_controller.php';
                $productController = new ProductController($conn);
                $deleteproductimage = $productController-> addImageproductTrashcontroller($id);
            $deleteproduct = $productModel->deleteProduct($id);
            if($deleteproduct){
                echo "success";
            }
            else {
                echo "error";
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

    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

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
                        <h4>Danh sách đơn hàng</h4>
                        <h6>Manage your sales</h6>
                    </div>
                    <div class="page-btn">
                        <a href="pos.php" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img"
                                class="me-1">Thêm đơn hàng</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="assets/img/icons/filter.svg" alt="img">
                                        <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                                    </a>
                                </div>
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

                        <div class="card" id="filter_inputs">
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Name">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Reference No">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select">
                                                <option>Completed</option>
                                                <option>Paid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6 col-12">
                                        <div class="form-group">
                                            <a class="btn btn-filters ms-auto"><img
                                                    src="assets/img/icons/search-whites.svg" alt="img"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="checkboxs">
                                                <input type="checkbox" id="select-all">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </th>
                                        <th>Ngày</th>
                                        <th>Tên khách hàng</th>
                                        <th>Tham chiếu</th>
                                        <th>Trạng thái</th>
                                        <th>Thanh toán</th>
                                        <th>Tổng</th>
                                        <th>Đã thanh toán</th>
                                        <th>Nợ</th>
                                        <th>Người tạo bill</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($Salemodelresults as $Salemodelresult): ?>

                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td><?php echo $Salemodelresult['created_at']; ?></td>
                                        <td><?php echo $Salemodelresult['name']; ?></td>
                                        <td><?php echo $Salemodelresult['reference']; ?></td>
                                        <td><span class="badges bg-lightgreen" data-status="<?php echo $Salemodelresult['status']; ?>"><?php echo $Salemodelresult['status']; ?></span></td>
                                        <td><span class="badges bg-lightgreen" data-status="<?php echo $Salemodelresult['payment']; ?>"><?php echo $Salemodelresult['payment']; ?></span></td>
                                        <td><?php echo $Salemodelresult['grand_total']; ?></td>
                                        <td><?php echo $Salemodelresult['paid']; ?></td>
                                        <td class="text-red"><?php echo $Salemodelresult['due']; ?></td>
                                        <td><?php echo $Salemodelresult['biller']; ?></td>
                                        <td class="text-center">
                                            <a class="action-set" href="javascript:void(0);" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="sales-details.php?sale_id=<?php echo $Salemodelresult['sale_id']; ?>" class="dropdown-item"><img
                                                            src="assets/img/icons/eye1.svg" class="me-2" alt="img">Thông tin đơn hàng</a>
                                                </li>
                                                <li>
                                                    <a href="edit-sales.php?sale_id=<?php echo $Salemodelresult['sale_id']; ?>" class="dropdown-item"><img
                                                            src="assets/img/icons/edit.svg" class="me-2" alt="img">Chỉnh sửa đơn hàng</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"
                                                        data-bs-toggle="modal" data-bs-target="#showpayment" data-sale-id="<?php echo $Salemodelresult['sale_id']; ?>"><img
                                                            src="assets/img/icons/dollar-square.svg" class="me-2"
                                                            alt="img">Thông tin thanh toán</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"
                                                        data-bs-toggle="modal" data-bs-target="#createpayment" data-sale-id="<?php echo $Salemodelresult['sale_id']; ?>" ><img
                                                            src="assets/img/icons/plus-circle.svg" class="me-2"
                                                            alt="img">Tạo thanh toán</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"><img
                                                            src="assets/img/icons/download.svg" class="me-2"
                                                            alt="img">Download pdf</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item confirm-text_saledelete" data-sale-id="<?php echo $Salemodelresult['sale_id']; ?>"><img
                                                            src="assets/img/icons/delete1.svg" class="me-2"
                                                            alt="img">Xóa đơn hàng</a>
                                                </li>
                                            </ul>
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


    <div class="modal fade" id="showpayment" tabindex="-1" aria-labelledby="showpayment" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Show Payments</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Amount </th>
                                    <th>Paid By </th>
                                    <th>Paid By </th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="createpayment" tabindex="-1" aria-labelledby="createpayment" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <form onsubmit="addpayment_detailajax (event)" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                  
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-submit" >Submit</button>
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="paymentconstvalue" value="salepayment">
    <div class="modal fade" id="editpayment" tabindex="-1" aria-labelledby="editpayment" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <form onsubmit="updatepayment_detailajax (event)" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-submit">Submit</button>
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
                </div>
                </form>
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
                                        
            document.addEventListener("DOMContentLoaded", function () {

                const saleLinks = document.querySelectorAll('.dropdown-item[data-bs-toggle="modal"]');
                const modalshowpayment =document.getElementById('showpayment');
                const modalTableBody = modalshowpayment.querySelector('.modal-body tbody');
                const modalcreat = document.getElementById('createpayment');
                const modalupdate = document.getElementById('editpayment');
                const modaletablebodyupdate = modalupdate.querySelector('.modal-body');
                const modalTableBodycreat =modalcreat.querySelector('.modal-body');
              

                saleLinks.forEach(link => {
                    link.addEventListener('click', event => {
                    const saleId = link.getAttribute('data-sale-id');
                    console.log(saleId);
                    fetchPaymentData(saleId);
                    fetchPaymentDatacreate(saleId);
                    

                    });
                });
                modalshowpayment.addEventListener('click', event => {
                const editButton = event.target.closest('.editshowpayment');
                if (editButton && editButton.classList.contains('editshowpayment')) {
                    const paymentId = editButton.getAttribute('data-paymentdetail-id');
                    fetchPaymentDataupdate(paymentId);
                }
                });
                

                            function fetchPaymentData(saleId) {
                            
                            modalTableBody.innerHTML = '';
                            fetch(`../../admin-page/view/API/payment_detailAPI.php?action=getProductDetailsbyid&sale_id=${saleId}`)
                            .then(response => response.json())
                            .then(data => {
                            // Xóa dữ liệu cũ trong bảng trong modal
                            modalTableBody.innerHTML = '';

                            // Điền thông tin vào bảng trong modal
                            data.forEach(payment => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${payment.updated_at}</td>
                                <td>${payment.reference}</td>
                                <td>${payment.valueplus}</td>
                                <td>${payment.payment_name}</td>
                                <td>
                                <a class="me-2" href="javascript:void(0);">
                                    <img src="assets/img/icons/printer.svg" alt="img">
                                </a>
                                <a class="me-2 editshowpayment" href="javascript:void(0);" 
                                    data-bs-toggle="modal" data-bs-dismiss="modal" data-paymentdetail-id="${payment.id}">
                                    <img src="assets/img/icons/edit.svg" alt="img">
                                </a>
                                <a class="me-2 confirmination_payment" href="javascript:void(0);" data-paymentdetail-id="${payment.id}">
                                    <img src="assets/img/icons/delete.svg" alt="img">
                                </a>
                                </td>
                            `;

                            modalTableBody.appendChild(row);
                            });
                            
                            
                            
                            })
                            .catch(error => {
                                console.error('Error fetching payment data:', error);
                            });
                        }
                        modalshowpayment.addEventListener('show.bs.modal', () => {
                        modalshowpayment.querySelector('.modal-body').addEventListener('click', function(event) {
                            if (event.target.closest('.confirmination_payment')) {
                                // var paymentdetail_id = event.target.dataset.paymentdetailId;
                                var paymentdetail_id = $(event.target.closest('.confirmination_payment')).data("paymentdetail-id");

                                console.log(paymentdetail_id);
                                Swal.fire({
                                    title: "Are you sure?",
                                    text: "You won't be able to revert this!",
                                    type: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Yes, delete it!",
                                    confirmButtonClass: "btn btn-primary",
                                    cancelButtonClass: "btn btn-danger ml-1",
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        $.ajax({
                                            type: "POST",
                                            url: "../model/payment_detail_model.php",
                                            data: { paymentdetail_id_delete: paymentdetail_id },
                                            success: function (response) {
                                                console.log("Server response:", response);
                                                if (response === "success") {
                                                    Swal.fire({
                                                        type: "success",
                                                        title: "Deleted!",
                                                        text: "Your file has been deleted.",
                                                        confirmButtonClass: "btn btn-success",
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            location.reload();
                                                        }
                                                    });
                                                    
                                                } else {
                                                    Swal.fire({
                                                        type: "error",
                                                        title: "Error!",
                                                        text: "Failed to delete the file.",
                                                        confirmButtonClass: "btn btn-danger",
                                                    });
                                                }
                                            },
                                            error: function () {
                                                Swal.fire({
                                                    type: "error",
                                                    title: "Error!",
                                                    text: "An error occurred. Please try again later.",
                                                    confirmButtonClass: "btn btn-danger",
                                                });
                                            },
                                        });
                                    }
                                });
                            }
                        });
                    });
                                        function fetchPaymentDataupdate(payment_Id) {
                                                modaletablebodyupdate.innerHTML = '';
                                                fetch(`../../admin-page/view/API/payment_detailAPI.php?action=getpaymentdetailByid&payment_detailID=${payment_Id}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    // Xóa dữ liệu cũ trong bảng trong modal
                                                    modaletablebodyupdate.innerHTML = '';
                    
                                                    // Điền thông tin vào bảng trong modal
                                                    data.forEach(payment => {
                                                    const row = document.createElement('div');
                                                        row.classList.add('row');

                                                    row.innerHTML = `
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Customer</label>
                                                            <div class="input-groupicon">
                                                                <input type="date"  id="updatedatAmountaddpayment" value="${payment.created_at}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Reference</label>
                                                            <input type="text" value="${payment.reference}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Received Amount</label>
                                                            <input type="text" value="${payment.paid}"   disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Paying Amount</label>
                                                            <input type="number" value="${payment.valueplus}" min="0.00" id="PayingAmountupdatepayment">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Payment type</label>
                                                            <select class="select" type="select" id="paymenttypeupdatepayment">
                                                                <option value="Cash">Cash</option>
                                                                <option value="MoMo">MoMo</option>
                                                                <option value="Debit">Debit</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input value ="${payment.id}"  id="idpaymenthiddenupdate" hidden>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Payment status</label>
                                                            <select class="select"  type="select" id="statusupdatepayment">
                                                                <option value="success">success</option>
                                                                <option value="pending">pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group mb-0">
                                                            <label>Note</label>
                                                            <textarea class="form-control" id="noteupdatepayment">${payment.note}</textarea>
                                                        </div>
                                                    </div>
                                                        
                                                    `;
                                                  
                                                    modaletablebodyupdate.appendChild(row);
                                                    });
                                                    
                                                    new bootstrap.Modal(modalupdate).show();
                                                })
                                                .catch(error => {
                                                    console.error('Error fetching payment data:', error);
                                                });
                                               
                                            }
                                            function fetchPaymentDatacreate(saleId) {
                                                fetch(`../../admin-page/view/API/payment_detailAPI.php?action=getsaleIDdetailbysaleid&sale_id=${saleId}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    // Xóa dữ liệu cũ trong bảng trong modal
                                                    modalTableBodycreat.innerHTML = '';
                    
                                                    // Điền thông tin vào bảng trong modal
                                                    data.forEach(payment => {
                                                    const row = document.createElement('div');
                                                        row.classList.add('row');

                                                    row.innerHTML = `
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Customer</label>
                                                            <div class="input-groupicon">
                                                                <input type="date"  id="createdatAmountaddpayment" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Reference</label>
                                                            <input type="text" value="${payment.reference}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Received Amount</label>
                                                            <input type="text" value="${payment.paid}"   disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Paying Amount</label>
                                                            <input type="number" value="" min="0.00" id="PayingAmountaddpayment" max="${payment.grand_total - payment.paid}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Payment type</label>
                                                            <select class="select" type="select" id="paymenttypeaddpayment">
                                                                <option value="Cash">Cash</option>
                                                                <option value="MoMo">MoMo</option>
                                                                <option value="Debit">Debit</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input value ="${payment.sale_id}"  id="sale_idhiddencreate" hidden>
                                                    <div class="col-lg-6 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>Payment status</label>
                                                            <select class="select"  type="select" id="statusaddpayment">
                                                                <option value="success">success</option>
                                                                <option value="pending">pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group mb-0">
                                                            <label>Note</label>
                                                            <textarea class="form-control" id="noteaddpayment"></textarea>
                                                        </div>
                                                    </div>
                                                        
                                                    `;
                    
                                                    modalTableBodycreat.appendChild(row);
                                                    });
                    
                                                    // Hiển thị modal
                                                    
                                                    const dateInput = document.getElementById("createdatAmountaddpayment");

                                                    
                                                        const currentDateUTC = new Date();

                                                        
                                                        const vnOffset = 7;
                                                        const vietnamTime = new Date(currentDateUTC.getTime() + vnOffset * 3600000); // 3600000 milliseconds = 1 hour

                                                        
                                                        const formattedDateVN = vietnamTime.toISOString().slice(0, 10);

                                                    
                                                        dateInput.value = formattedDateVN;

                                                })
                                                .catch(error => {
                                                    console.error('Error fetching payment data:', error);
                                                });
                                                // modalcreat.show();
                                            }            
                                           
        });

                                            function addpayment_detailajax (event){
                                                event.preventDefault();
                                                var sale_ud_create = $("#sale_idhiddencreate").val();
                                                var createdatAmount = $("#createdatAmountaddpayment").val();
                                                var PayingAmount = $("#PayingAmountaddpayment").val();
                                                var paymenttype = $("#paymenttypeaddpayment").val();
                                                var status = $("#statusaddpayment").val();
                                                var note = $("#noteaddpayment").val();
                                                var paymentconstvalue = $("#paymentconstvalue").val();
                                                $.ajax({
                                                    url: "../model/payment_detail_model.php",
                                                    type: "POST",
                                                    data: {
                                                        sale_id: sale_ud_create,
                                                        created_at: createdatAmount,
                                                        valueplus: PayingAmount,
                                                        payment_name: paymenttype,
                                                        status: status,
                                                        note: note,
                                                        paymentconstvalue: paymentconstvalue,
                                                    },
                                                    success: function (response) {
                                                        console.log(response);
                                                        try {
                                                            var responseData = JSON.parse(response); // Chuyển đổi dữ liệu JSON thành đối tượng JavaScript

                                                            if (responseData.status === "success") {
                                                                Swal.fire({
                                                                    icon: "success",
                                                                    title: "Success",
                                                                    text: "Add payment success",
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        location.reload();
                                                                    }
                                                                });
                                                            } else {
                                                                Swal.fire({
                                                                    icon: "error",
                                                                    title: "Error",
                                                                    text: "Add payment error",
                                                                });
                                                            }
                                                        } catch (error) {
                                                            console.error("An error occurred:", error);
                                                        }
                                                    }
                                                        
                                                });



                                            }
                                            function updatepayment_detailajax (event){
                                                event.preventDefault();
                                                var sale_ud_create = $("#idpaymenthiddenupdate").val();
                                                var createdatAmount = $("#updatedatAmountaddpayment").val();
                                                var PayingAmount = $("#PayingAmountupdatepayment").val();
                                                var paymenttype = $("#paymenttypeupdatepayment").val();
                                                var status = $("#statusupdatepayment").val();
                                                var note = $("#noteupdatepayment").val();
                                                var paymentconstvalue = $("#paymentconstvalue").val();
                                                $.ajax({
                                                    url: "../model/payment_detail_model.php",
                                                    type: "POST",
                                                    data: {
                                                        paymentdetail_id: sale_ud_create,
                                                        created_at: createdatAmount,
                                                        valueplus: PayingAmount,
                                                        payment_name: paymenttype,
                                                        status: status,
                                                        note: note,
                                                        paymentconstvalue: paymentconstvalue,
                                                    },
                                                    success: function (response) {
                                                        console.log(response);
                                                        try {
                                                            var responseData = JSON.parse(response); // Chuyển đổi dữ liệu JSON thành đối tượng JavaScript

                                                            if (responseData.status === "success") {
                                                                Swal.fire({
                                                                    icon: "success",
                                                                    title: "Success",
                                                                    text: "Add payment success",
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        location.reload();
                                                                    }
                                                                });
                                                            } else {
                                                                Swal.fire({
                                                                    icon: "error",
                                                                    title: "Error",
                                                                    text: "Add payment error",
                                                                });
                                                            }
                                                        } catch (error) {
                                                            console.error("An error occurred:", error);
                                                        }
                                                    }
                                                        ,
                                                });



                                            }
                                           
             
                
    </script>
    <script>
            document.addEventListener("DOMContentLoaded", function () {
                const spans = document.querySelectorAll("span.badges");

                spans.forEach(span => {
                    const status = span.getAttribute("data-status");

                    if (status === "Complete" || status === "Paid") {
                        span.classList.remove("bg-lightred");
                        span.classList.add("bg-lightgreen");
                    } else if (status === "Pending" || status === "Due") {
                        span.classList.remove("bg-lightgreen");
                        span.classList.add("bg-lightred");
                    }
                });
            });

// delete sale 
$(".confirm-text_saledelete").on("click", function () {
  var saleID = $(this).data("sale-id"); // Lấy giá trị từ thuộc tính data-category-id
  
  Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
      confirmButtonClass: "btn btn-primary",
      cancelButtonClass: "btn btn-danger ml-1",
      buttonsStyling: false,
  }).then(function (result) {
      if (result.value) {
          $.ajax({
              type: "POST",
              url: "../controller/sale_controller.php",
              data: { sale_id: saleID , delete: 'delete'}, // Sử dụng giá trị lấy từ thuộc tính data
              success: function (response) {
                  // Xử lý phản hồi từ server
                  console.log("Server response:", response);
                  if (response === "success") {
                      Swal.fire({
                          type: "success",
                          title: "Deleted!",
                          text: "Your file has been deleted.",
                          confirmButtonClass: "btn btn-success",
                      })
                      .then((result) => {
                        if (result.isConfirmed) {
                          window.location.href = "saleslist.php";
                        }
                    });
                      
                  } else {
                      Swal.fire({
                          type: "error",
                          title: "Error!",
                          text: "Failed to delete the file.",
                          confirmButtonClass: "btn btn-danger",
                      });
                  }
              },
              error: function () {
                  // Xử lý lỗi
                  Swal.fire({
                      type: "error",
                      title: "Error!",
                      text: "An error occurred. Please try again later.",
                      confirmButtonClass: "btn btn-danger",
                  });
              },
          });
      }
  });
})


    </script>

</body>

</html>