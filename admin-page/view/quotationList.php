<?php require_once('./main/role_manager.php'); ?>
<?php 
    require '../model/quotation_model.php';
    $quotationModel = new QuotationModel($conn);
    $quotationList = $quotationModel->getQuotationList();
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
                        <h4>Danh sách báo giá</h4>
                        <h6>Manage your Quotations</h6>
                    </div>
                    <div class="page-btn">
                        <a href="addquotation.php" class="btn btn-added">
                            <img src="assets/img/icons/plus.svg" alt="img" class="me-2"> Thêm báo giá
                        </a>
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
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" class="datetimepicker cal-icon"
                                                placeholder="Choose Date">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter Reference ">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select">
                                                <option>Choose Customer</option>
                                                <option>Customer1</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select">
                                                <option>Choose Status</option>
                                                <option>Inprogress</option>
                                                <option>Complete</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-sm-6 col-12 ms-auto">
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
                                        <th>Tên sản phẩm</th>
                                        <th>Tham chiếu</th>
                                        <th>Tên khách hàng</th>
                                        <th>Trạng thái</th>
                                        <th>Tổng ($)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($quotationList as $results): ?>
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td class="productimgname">
                                            <a href="javascript:void(0);" class="product-img">
                                                <img src="<?php echo $results['image']; ?>" alt="product">
                                            </a>
                                            <a href="javascript:void(0);"><?php echo $results['nameproduct']; ?></a>
                                        </td>
                                        <td><?php echo $results['reference']; ?></td>
                                        <td><?php echo $results['name']; ?></td>
                                        <td><span class="badges bg-lightgreen" data-status="<?php echo $results['status']; ?>"><?php echo $results['status']; ?></span></td>
                                        <td><?php echo $results['grand_total']; ?></td>
                                        <td>
                                            <a class="me-3" href="editquotation.php?quotation_id=<?php echo $results['quotation_id']; ?>">
                                                <img src="assets/img/icons/edit.svg" alt="img">
                                            </a>
                                            <a class="me-3 confirm-text_delete_quotation" href="javascript:void(0);" data-quotation-id="<?php echo $results['quotation_id']; ?>">
                                                <img src="assets/img/icons/delete.svg" alt="img">
                                            </a>
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

    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

    <script src="assets/js/script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
                const spans = document.querySelectorAll("span.badges");

                spans.forEach(span => {
                    const status = span.getAttribute("data-status");

                    if (status === "Send") {
                        span.classList.remove("bg-lightred");
                        span.classList.add("bg-lightgreen");
                    } else if (status === "Pending") {
                        span.classList.remove("bg-lightgreen");
                        span.classList.add("bg-lightred");
                    }
                    else {
                        span.classList.add("bg-lightyellow");
                        span.classList.remove("bg-lightgreen");
                        span.classList.remove("bg-lightred");
                    }
                });
            });
    </script>
    <script>
        $(".confirm-text_delete_quotation").on("click", function () {
  var quotationId = $(this).data("quotation-id"); // Lấy giá trị từ thuộc tính data-category-id
  
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
              url: "../controller/quotation_controller.php",
              data: { quotation_id: quotationId , delete: 'delete'}, // Sử dụng giá trị lấy từ thuộc tính data
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
                          window.location.href = "quotationlist.php";
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
});
    </script>
</body>

</html>