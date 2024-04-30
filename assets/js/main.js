function LoginManager(event) {
  var EmailLoginManager = $("#EmailLoginManager").val();
  var passloginManager = $("#passloginManager").val();
  event.preventDefault();
  $.ajax({
    url: "../controller/signin_controller.php",
    type: "POST",
    data: {
      email: EmailLoginManager,
      password: passloginManager,
      login_manager: true,
    },
    success: function (response) {
      console.log(response);
      if (response == "success") {
        toastr.success("Đăng nhập thành công!", "Thành công", {
          timeOut: 1500,
          progressBar: true,
          positionClass: "toast-top-right",
        });
        setTimeout(() => {
          window.location.href = "index.php";
        }, 1500);
      } else {
        toastr.error("Lỗi trong quá trình đăng nhập!", "Lỗi", {
          timeOut: 3000,
          progressBar: true,
          positionClass: "toast-top-right",
        });
        return;
      }
    },
  });
}
function AddSaleCart(event) {
  var Emailinsert = $("#email_login_insert_cart").val();
  event.preventDefault();
  $.ajax({
    url: "./admin-page/controller/signin_controller.php",
    type: "POST",
    data: {
      login_status: "check_login",
    },
    success: function (response) {
      console.log(response);
      if (response == "logged_in") {
        $.ajax({
          url: "./admin-page/controller/signin_controller.php",
          type: "POST",
          data: {
            email: Emailinsert,
            insert_salecart: true,
          },
          success: function (response) {
            console.log(response);
            if (response === "success") {
              toastr.success("Đã gửi thông tin sản phẩm", "Thành công", {
                timeOut: 1500,
                progressBar: true,
                positionClass: "toast-top-right",
              });
              setTimeout(() => {
                window.location.reload();
              }, 1500);
            } else {
              toastr.error("Lỗi trong quá trình gửi thông tin!", "Lỗi", {
                timeOut: 3000,
                progressBar: true,
                positionClass: "toast-top-right",
              });
            }
          },
        });
      } else {
        // Nếu chưa đăng nhập
        toastr.error("Bạn chưa đăng nhập!", "Lỗi", {
          timeOut: 3000,
          progressBar: true,
          positionClass: "toast-top-right",
        });
        setTimeout(() => {
          window.location.href = "login-register.php"; // Điều hướng đến trang đăng nhập
        }, 3000);
      }
    },
  });
}
    document.addEventListener("DOMContentLoaded", function () {
    const data_product_id = document.querySelectorAll('.button_showdetail[data-toggle="modal"]');
    const modalshowproduct =document.getElementById('quickView');
    const modalTableBody = modalshowproduct.querySelector('.modal-body');

    data_product_id.forEach(link => {
        link.addEventListener('click', event => {
        const product_id = link.getAttribute('data-product-id');
        console.log(product_id);
        fetchProductData(product_id);
        });
    });

    function fetchProductData(Product_id) {
        modalTableBody.innerHTML = '';
        fetch(`controller/modal_show_productAPI.php?action=getProductDetailsbyid&product_id=${Product_id}`)
            .then(response => response.json())
            .then(data => {
                  modalTableBody.innerHTML = '';
                const detail = data;
                
                const row = document.createElement('div');
                row.classList.add('quick-view-content', 'single-product-page-content');
                row.innerHTML = `
                    <div class="row">
                        <!-- Product Thumbnail Start -->
                        <div class="col-lg-5 col-md-6">
                             <div class="product-thumbnail-wrap">
                                <div class="product-thumb-carousel">
                                  <div class="single-thumb-item">
                                    <a href="single-product.php">
                                        <img class="img-fluid" src="${'admin-page/' + detail.images[0].substring(2)}" alt="Product"/>
                                    </a>
                                </div>

                                </div>
                            </div>
                        </div>
                        <!-- Product Details Start -->
                    <div class="col-lg-7 col-md-6 mt-5 mt-md-0">
                        <div class="product-details">
                            <h2><a href="single-product.php">${detail.name}</a></h2>

                            <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-half"></i>
                                <i class="fa fa-star-o"></i>
                            </div>

                            <span class="price">$${detail.price}</span>
                            <input value ="${detail.id}"  id="idproduct_addcart" hidden>
                            <div class="product-info-stock-sku">
                                <span class="product-stock-status">In Stock</span>
                                <span class="product-sku-status ml-5"><strong>SKU</strong> ${detail.sku}</span>
                            </div>

                            <p class="products-desc">${detail.short_description}</p>
                            <div class="shopping-option-item">
                                <h4>Color</h4>
                                <ul class="color-option-select d-flex">
                                    <li class="color-item black">
                                        <div class="color-hvr">
                                            <span class="color-fill"></span>
                                            <span class="color-name">Black</span>
                                        </div>
                                    </li>

                                    <li class="color-item green">
                                        <div class="color-hvr">
                                            <span class="color-fill"></span>
                                            <span class="color-name">green</span>
                                        </div>
                                    </li>

                                    <li class="color-item orange">
                                        <div class="color-hvr">
                                            <span class="color-fill"></span>
                                            <span class="color-name">Orange</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="product-quantity d-flex align-items-center">
                                <div class="quantity-field">
                                    <label for="qty">Qty</label>
                                    <input type="number" id="qty" min="1" max="100" value="1"/>
                                </div>

                                <a href="single-product.php?productid=${detail.id}" class="btn btn-add-to-cart">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                        <!-- Product Details End -->
                    </div>
                `;
                modalTableBody.appendChild(row);
            })
            
            .catch(error => {
                console.error('Error fetching payment data:', error);
            });
    }


    

    });
    function logOut(event){
      event.preventDefault();
      Swal.fire(
          {
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor:'#3085d6',
              cancelButtonColor:'#d33',
              confirmButtonText:'Yes, Logout!'
          }
      ).then((result)=>{
          if(result.isConfirmed){
             $.ajax({
                    url:'my-account.php',
                    type:'POST',
                    data : {
                        logout : 1
                    },
                    success:function(){
                      window.location.href='login-register.php';
                    }
             })
          }
      }
      )
  }
  function UpdateInfoUser_customer(event) {
    var Emailinsert = $("#email_login_update_info").val();
    var currentPass = $("#current-pwd").val();
    var newPass = $("#new-pwd").val();
    var ConfirmPass = $("#confirm-pwd").val();
    var fullname_update = $("#fullname_update").val();
    var countryId = $("#countryId").val();
    var stateId = $("#stateId").val();
    var cityId = $("#cityId").val();
    var ZipCode_update = $("#ZipCode_update").val();
    var phone_update = $("#phone_update").val();
    var address = $("#Addess_id").val();

    event.preventDefault();
    if (newPass && newPass!==ConfirmPass){
      toastr.error("Passwords do not match !", "Error", {
        timeOut: 3000,
        progressBar: true,
        positionClass: "toast-top-right",
      });
      exit();
    }
    if (currentPass){
      $.ajax({
        url: "./admin-page/controller/signin_controller.php",
        type: "POST",
        data: {
          currentPass: currentPass
  
        },
        success: function (response) {
          console.log(response);
          if (response == "password_correct") {
            $.ajax({
              url: "./admin-page/controller/signin_controller.php",
              type: "POST",
              data: {
                email: Emailinsert,
                password: ConfirmPass,
                name: fullname_update,
                country: countryId,
                district: cityId,
                city: stateId,
                zipcode: ZipCode_update,
                phone: phone_update,
                address:address,
                check_action: "update_info_user"
  
              },
              success: function (response) {
                console.log(response);
                if (response === "success") {
                  toastr.success("Information has been updated", "Success", {
                    timeOut: 1500,
                    progressBar: true,
                    positionClass: "toast-top-right",
                  });
                  setTimeout(() => {
                    window.location.reload();
                  }, 1500);
                } else {
                  toastr.error("Error while sending information!", "Error", {
                    timeOut: 3000,
                    progressBar: true,
                    positionClass: "toast-top-right",
                  });
                }
              },
            });
          } else {
            // Nếu chưa đăng nhập
            toastr.error(" Current Passwords incorrect!", "Error", {
              timeOut: 3000,
              progressBar: true,
              positionClass: "toast-top-right",
            });
          }
        },
      });
    }
    else {
      $.ajax({
        url: "./admin-page/controller/signin_controller.php",
        type: "POST",
        data: {
          email: Emailinsert,
          name: fullname_update,
          country: countryId,
          district: cityId,
          city: stateId,
          zipcode: ZipCode_update,
          phone: phone_update,
          address:address,
          check_action: "update_info_user"

        },
        success: function (response) {
          console.log(response);
          if (response === "success") {
            toastr.success("Information has been updated", "Success", {
              timeOut: 1500,
              progressBar: true,
              positionClass: "toast-top-right",
            });
            setTimeout(() => {
              window.location.reload();
            }, 1500);
          } else {
            toastr.error("Error while sending information!", "Error", {
              timeOut: 3000,
              progressBar: true,
              positionClass: "toast-top-right",
            });
          }
        },
      });
    }

  }
  function generateCommentDiv(author, content) {
    // Tạo một đối tượng Date mới đại diện cho thời gian hiện tại
    var currentDate = new Date();

    // Lấy thông tin về ngày, tháng, năm, giờ, phút và chuẩn bị cho việc hiển thị
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

    var currentDay = currentDate.getDate();
    var currentMonth = months[currentDate.getMonth()];
    var currentYear = currentDate.getFullYear();
    var currentHour = currentDate.getHours();
    var currentMinute = currentDate.getMinutes();
    var period = currentHour >= 12 ? 'PM' : 'AM';
    currentHour = currentHour % 12 || 12;

    // Format thời gian thành chuỗi
    var formattedDateTime = currentDay + ' ' + currentMonth + ' ' + currentYear + ', ' + currentHour + ':' + (currentMinute < 10 ? '0' : '') + currentMinute + ' ' + period;

    // Tạo chuỗi HTML cho phần bình luận
    var html = '<div class="single-comment d-block d-md-flex">';
    html += '<div class="comment-author">';
    html += '<a href="#"><img src="assets/img/user-comment_81638.png" class="img-fluid" alt="Comment User"/></a>';
    html += '</div>';
    html += '<div class="comment-info mt-3 mt-md-0">';
    html += '<div class="comment-info-top d-flex justify-content-between">';
    html += '<h3>' + author + '</h3>';
    html += '<a href="#" class="btn-add-to-cart"><i class="fa fa-reply"></i> Reply</a>';
    html += '</div>';
    html += '<a href="#" class="comment-date">' + formattedDateTime + '</a>';
    html += '<p>' + content + '</p>';
    html += '</div>';
    html += '</div>';

      // Tìm phần tử comments-preview-area
      var previewArea = document.querySelector('.comments-preview-area');

      // Kiểm tra xem phần tử này có tồn tại không
      if (previewArea) {
          // Kiểm tra xem có thẻ h2 bên trong không
          var h2Element = previewArea.querySelector('h2');
          if (h2Element) {
              // Nếu có, chèn chuỗi HTML vào sau thẻ h2
              h2Element.insertAdjacentHTML('afterend', html);
          } else {
              // Nếu không, chèn chuỗi HTML vào đầu phần tử comments-preview-area
              previewArea.insertAdjacentHTML('afterbegin', html);
          }
      }
}

function AddBlog_comment(event) {
  var Emailcomment = $("#email_comment").val();
  var nameComment =  $("#name_comment").val();
  var Contentcomment =  $("#content_comment").val();
  var Blog_id = $("#blog_id_inset_comment").val();

  event.preventDefault();
  if (nameComment && Contentcomment && nameComment!==''&& Contentcomment !=='' && Blog_id && Blog_id >0){
    $.ajax({
      url: "controller/comment_controller.php",
      type: "POST",
      data: {
        email: Emailcomment,
        name: nameComment,
        content:Contentcomment,
        blog_id: Blog_id,
        insert_comment: true,
      },
      success: function (response) {
        console.log(response);
        if (response === "success_insert_comment") {
          generateCommentDiv(nameComment,Contentcomment);
          document.getElementById('content_comment').value = '';
          var countcomment = document.getElementById('inputcountcomment').value;

          // Tạo chuỗi có định dạng "Comment (countcomment)"
          var newCount = parseInt(countcomment) + 1;
          var commentText = 'Comment (' + newCount + ')';

          // Gán chuỗi đã định dạng vào nội dung của thẻ h2 có id là "count-comment"
          document.getElementById('countcomment').textContent = commentText;
          document.getElementById('inputcountcomment').value=newCount;
          toastr.info("Success send Comment!", "Success", {
            timeOut: 3000,
            progressBar: true,
            positionClass: "toast-top-right",
          });
        } else {
          toastr.error("Error Server!", "Error", {
            timeOut: 3000,
            progressBar: true,
            positionClass: "toast-top-right",
          });
        }
      },
    });
  }
  else {
    toastr.error("Please enter content!", "Error", {
      timeOut: 3000,
      progressBar: true,
      positionClass: "toast-top-right",
    });
  }

}

// Hàm để tải thêm comment từ vị trí comment cuối cùng đã tải
function loadMoreComments(event) {
  var Comment_id =  $("#Comment_id_last").val();
  var Blog_id =document.getElementById("blog_id_inset_comment").value;
  var elementToRemove = document.getElementById('Comment_id_last');
  event.preventDefault();
  // Gửi yêu cầu AJAX
  $.ajax({
      url: 'controller/comment_controller.php',
      type: 'GET',
      data: { Comment_id: Comment_id,
              blog_id: Blog_id,
              load_more_comment: true },
      success: function(response) {
          $('.comments-preview-area').append(response);
          if (elementToRemove) {
            elementToRemove.remove();
        }
          toastr.info("Load success!", "Success", {
            timeOut: 1000,
            progressBar: true,
            positionClass: "toast-top-right",
          });
      },
      error: function() {
        toastr.error("Load error!", "Error", {
          timeOut: 1000,
          progressBar: true,
          positionClass: "toast-top-right",
        });
          console.error('Error loading comments.');
      }
  });
}
function LoginCustomer(event){
  var EmailLoginManager = $('#email_login').val();
  var passloginManager = $('#pass_login').val();
  event.preventDefault();
  $.ajax({
      url: 'admin-page/controller/signin_controller.php',
      type: 'POST',
      data: {
          email: EmailLoginManager,
          password: passloginManager,
          login_customer: true
      },
      success: function(response){
          console.log(response);
          if(response == 'success'){
              toastr.success('Đăng nhập thành công!', 'Thành công', {
              timeOut: 1500, 
              progressBar: true, 
              positionClass: 'toast-top-right'
          });
              setTimeout(() => {
                  window.location.href = 'trang-chu.html';
              }, 1500);
          }
          else{
              toastr.error('Lỗi trong quá trình đăng nhập!', 'Lỗi', {
                  timeOut: 3000, 
                  progressBar: true, 
                  positionClass: 'toast-top-right'
              });
              return;
          }
      }
  });
  
}

// POPUP SHOW

document.addEventListener("DOMContentLoaded", function() {
  var popup = document.getElementById("myPopup");
  popup.classList.add("show");
  window.addEventListener("click", function(event) {
      if (!event.target.matches('.popup')) {
          popup.classList.remove("show");
      }
  });

});
// END POPUP SHOW