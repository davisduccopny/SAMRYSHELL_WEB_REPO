# import os

# def list_php_files(directory):
#     php_files = []
#     # Lặp qua các tệp tin trong thư mục
#     for file in os.listdir(directory):
#         # Kiểm tra xem tệp tin có phải là tệp tin PHP không
#         if file.endswith(".php"):
#             # Thêm tên tệp tin vào danh sách
#             php_files.append(file)
#     return php_files

# # Thư mục cần kiểm tra
# directory_path = "../view/"

# # Lấy danh sách các tệp tin PHP trong thư mục
# php_files_list = list_php_files(directory_path)

# # In danh sách các tệp tin PHP
# print("PHP Files:")
# for php_file in php_files_list:
#     print(php_file)
# Vòng lặp qua từng tệp và chèn đoạn văn bản vào đầu




file_names = [
    "activities.php",
    "add-sales.php",
    "addblog.php",
    "addcategory.php",
    "addcustomer.php",
    "addproduct.php",
    "addpublicinfo.php",
    "addquotation.php",
    "addsupplier.php",
    "adduser.php",
    "bloglist.php",
    "blog_samry.php",
    "categorylist.php",
    "createexpense.php",
    "createexpense_category.php",
    "createpermission.php",
    "createsalesreturn.php",
    "createsalesreturns.php",
    "customerlist.php",
    "customerreport.php",
    "edit-sales.php",
    "editcategory.php",
    "editcategory_expense.php",
    "editcustomer.php",
    "editexpense.php",
    "editpermission.php",
    "editproduct.php",
    "editpurchasereturn.php",
    "editquotation.php",
    "editsalesreturn.php",
    "editsalesreturns.php",
    "editsubcategory.php",
    "editsupplier.php",
    "edituser.php",
    "emailsettings.php",
    "expensecategory.php",
    "expenselist.php",
    "forgetpassword.php",
    "generalsettings.php",
    "importpurchase.php",
    "inventoryreport.php",
    "invoicereport.php",
    "newuser.php",
    "newuseredit.php",
    "pos.php",
    "product-details.php",
    "productlist.php",
    "profile.php",
    "publicinfo.php",
    "purchaseorderreport.php",
    "purchasereport.php",
    "quotationList.php",
    "sales-details.php",
    "saleslist.php",
    "salesreport.php",
    "salesreturnlist.php",
    "salesreturnlists.php",
    "signup.php",
    "subaddcategory.php",
    "subcategorylist.php",
    "supplierlist.php",
    "supplierreport.php",
    "test_quotation.php",
    "userlist.php",
    "userlists.php"
]

# text_to_insert = """<?php
#     session_start();
#     if (!isset($_SESSION['email_manager']) &&  !$_SESSION['email_manager']) {
#         echo "<script> alert('Bạn chưa đăng nhập!')</script>";
#         header("refresh:1.5;url=signin.php");
#     }
#     if (isset($_POST['logout'])) {
#         session_destroy();
#         header("location:signin.php");
#     }
#     if (isset($_SESSION['role_manager']) && $_SESSION['role_manager'] !== 'admin'){
#         $role_show_element = 'hidden';
#         $role_active_element = 'disabled';
#     }
#     else {
#         $role_show_element = '';
#     }
# ?>"""
# # Vòng lặp qua từng tệp và chèn đoạn văn bản vào đầu
# for file_name in file_names:
#     # Đọc nội dung của tệp
#     with open(file_name, "r", encoding="utf-8") as file:
#         content = file.read()
    
#     # Mở tệp để ghi nội dung mới
#     with open(file_name, "w", encoding="utf-8") as file:
#         # Ghi đoạn văn bản vào đầu tệp
#         file.write(text_to_insert + content)

import os

def find_replace_in_file(file_path, old_str, new_str):
    # Đọc nội dung từ tệp với encoding UTF-8
    with open(file_path, 'r', encoding='utf-8') as file:
        file_content = file.read()

    # Thực hiện thay thế chuỗi
    new_content = file_content.replace(old_str, new_str)

    # Ghi nội dung mới vào tệp với encoding UTF-8
    with open(file_path, 'w', encoding='utf-8') as file:
        file.write(new_content)
        print('success')

# # Thư mục chứa các tệp cần tìm và thay thế
# directory_path = "../../admin-page/view/"

# # Chuỗi cần tìm và thay thế
# old_string = """<div class="sidebar" id="sidebar">
#             <div class="sidebar-inner slimscroll">
#                 <div id="sidebar-menu" class="sidebar-menu">
#                     <ul>
#                         <li>
#                             <a href="index.php"><img src="assets/img/icons/dashboard.svg" alt="img"><span>
#                                     Dashboard</span> </a>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
#                                     Product</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="productlist.php">Product List</a></li>
#                                 <li><a href="addproduct.php">Add Product</a></li>
#                                 <li><a href="categorylist.php">Category List</a></li>
#                                 <li><a href="addcategory.php">Add Category</a></li>
#                                 <li><a href="subcategorylist.php">Sub Category List</a></li>
#                                 <li><a href="subaddcategory.php">Add Sub Category</a></li>
#                                 <li><a href="brandlist.php">Brand List</a></li>
#                                 <li><a href="addbrand.php">Add Brand</a></li>
#                                 <li><a href="importproduct.php">Import Products</a></li>
#                                 <li><a href="barcode.php">Print Barcode</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg" alt="img"><span>
#                                     Sales</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="saleslist.php" class="active">Sales List</a></li>
#                                 <li><a href="pos.php">POS</a></li>
#                                 <li><a href="pos.php">New Sales</a></li>
#                                 <li><a href="salesreturnlists.php">Sales Return List</a></li>
#                                 <li><a href="createsalesreturns.php">New Sales Return</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span>
#                                     Purchase</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="purchaselist.php">Purchase List</a></li>
#                                 <li><a href="addpurchase.php">Add Purchase</a></li>
#                                 <li><a href="importpurchase.php">Import Purchase</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/expense1.svg" alt="img"><span>
#                                     Expense</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="expenselist.php">Expense List</a></li>
#                                 <li><a href="createexpense.php">Add Expense</a></li>
#                                 <li><a href="expensecategory.php">Expense Category</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/quotation1.svg" alt="img"><span>
#                                     Quotation</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="quotationList.php">Quotation List</a></li>
#                                 <li><a href="addquotation.php">Add Quotation</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/transfer1.svg" alt="img"><span>
#                                     Transfer</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="transferlist.php">Transfer List</a></li>
#                                 <li><a href="addtransfer.php">Add Transfer </a></li>
#                                 <li><a href="importtransfer.php">Import Transfer </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/return1.svg" alt="img"><span>
#                                     Return</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="salesreturnlist.php">Sales Return List</a></li>
#                                 <li><a href="createsalesreturn.php">Add Sales Return </a></li>
#                                 <li><a href="purchasereturnlist.php">Purchase Return List</a></li>
#                                 <li><a href="createpurchasereturn.php">Add Purchase Return </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
#                                     People</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="customerlist.php">Customer List</a></li>
#                                 <li><a href="addcustomer.php">Add Customer </a></li>
#                                 <li><a href="supplierlist.php">Supplier List</a></li>
#                                 <li><a href="addsupplier.php">Add Supplier </a></li>
#                                 <li><a href="userlist.php">User List</a></li>
#                                 <li><a href="adduser.php">Add User</a></li>
#                                 <li><a href="storelist.php">Store List</a></li>
#                                 <li><a href="addstore.php">Add Store</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/places.svg" alt="img"><span>
#                                     Places</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="newcountry.php">New Country</a></li>
#                                 <li><a href="countrieslist.php">Countries list</a></li>
#                                 <li><a href="newstate.php">New State </a></li>
#                                 <li><a href="statelist.php">State list</a></li>
#                             </ul>
#                         </li>
#                         <li>
#                             <a href="components.php"><i data-feather="layers"></i><span> Components</span> </a>
#                         </li>
#                         <li>
#                             <a href="blankpage.php"><i data-feather="file"></i><span> Blank Page</span> </a>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><i data-feather="alert-octagon"></i> <span> Error Pages
#                                 </span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="error-404.php">404 Error </a></li>
#                                 <li><a href="error-500.php">500 Error </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><i data-feather="box"></i> <span>Elements </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="sweetalerts.php">Sweet Alerts</a></li>
#                                 <li><a href="tooltip.php">Tooltip</a></li>
#                                 <li><a href="popover.php">Popover</a></li>
#                                 <li><a href="ribbon.php">Ribbon</a></li>
#                                 <li><a href="clipboard.php">Clipboard</a></li>
#                                 <li><a href="drag-drop.php">Drag & Drop</a></li>
#                                 <li><a href="rangeslider.php">Range Slider</a></li>
#                                 <li><a href="rating.php">Rating</a></li>
#                                 <li><a href="toastr.php">Toastr</a></li>
#                                 <li><a href="text-editor.php">Text Editor</a></li>
#                                 <li><a href="counter.php">Counter</a></li>
#                                 <li><a href="scrollbar.php">Scrollbar</a></li>
#                                 <li><a href="spinner.php">Spinner</a></li>
#                                 <li><a href="notification.php">Notification</a></li>
#                                 <li><a href="lightbox.php">Lightbox</a></li>
#                                 <li><a href="stickynote.php">Sticky Note</a></li>
#                                 <li><a href="timeline.php">Timeline</a></li>
#                                 <li><a href="form-wizard.php">Form Wizard</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><i data-feather="bar-chart-2"></i> <span> Charts </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="chart-apex.php">Apex Charts</a></li>
#                                 <li><a href="chart-js.php">Chart Js</a></li>
#                                 <li><a href="chart-morris.php">Morris Charts</a></li>
#                                 <li><a href="chart-flot.php">Flot Charts</a></li>
#                                 <li><a href="chart-peity.php">Peity Charts</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><i data-feather="award"></i><span> Icons </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="icon-fontawesome.php">Fontawesome Icons</a></li>
#                                 <li><a href="icon-feather.php">Feather Icons</a></li>
#                                 <li><a href="icon-ionic.php">Ionic Icons</a></li>
#                                 <li><a href="icon-material.php">Material Icons</a></li>
#                                 <li><a href="icon-pe7.php">Pe7 Icons</a></li>
#                                 <li><a href="icon-simpleline.php">Simpleline Icons</a></li>
#                                 <li><a href="icon-themify.php">Themify Icons</a></li>
#                                 <li><a href="icon-weather.php">Weather Icons</a></li>
#                                 <li><a href="icon-typicon.php">Typicon Icons</a></li>
#                                 <li><a href="icon-flag.php">Flag Icons</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><i data-feather="columns"></i> <span> Forms </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="form-basic-inputs.php">Basic Inputs </a></li>
#                                 <li><a href="form-input-groups.php">Input Groups </a></li>
#                                 <li><a href="form-horizontal.php">Horizontal Form </a></li>
#                                 <li><a href="form-vertical.php"> Vertical Form </a></li>
#                                 <li><a href="form-mask.php">Form Mask </a></li>
#                                 <li><a href="form-validation.php">Form Validation </a></li>
#                                 <li><a href="form-select2.php">Form Select2 </a></li>
#                                 <li><a href="form-fileupload.php">File Upload </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><i data-feather="layout"></i> <span> Table </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="tables-basic.php">Basic Tables </a></li>
#                                 <li><a href="data-tables.php">Data Table </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
#                                     Application</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="chat.php">Chat</a></li>
#                                 <li><a href="calendar.php">Calendar</a></li>
#                                 <li><a href="email.php">Email</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/time.svg" alt="img"><span>
#                                     Report</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="purchaseorderreport.php">Purchase order report</a></li>
#                                 <li><a href="inventoryreport.php">Inventory Report</a></li>
#                                 <li><a href="salesreport.php">Sales Report</a></li>
#                                 <li><a href="invoicereport.php">Invoice Report</a></li>
#                                 <li><a href="purchasereport.php">Purchase Report</a></li>
#                                 <li><a href="supplierreport.php">Supplier Report</a></li>
#                                 <li><a href="customerreport.php">Customer Report</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
#                                     Users</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="newuser.php">New User </a></li>
#                                 <li><a href="userlist.php">Users List</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/settings.svg" alt="img"><span>
#                                     Settings</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="generalsettings.php">General Settings</a></li>
#                                 <li><a href="emailsettings.php">Email Settings</a></li>
#                                 <li><a href="paymentsettings.php">Payment Settings</a></li>
#                                 <li><a href="currencysettings.php">Currency Settings</a></li>
#                                 <li><a href="grouppermissions.php">Group Permissions</a></li>
#                                 <li><a href="taxrates.php">Tax Rates</a></li>
#                             </ul>
#                         </li>
#                     </ul>
#                 </div>
#             </div>
#         </div>"""
# new_string = """<div class="sidebar" id="sidebar">
#             <div class="sidebar-inner slimscroll">
#                 <div id="sidebar-menu" class="sidebar-menu">
#                     <ul>
#                         <li class="active">
#                             <a href="index.php"><img src="assets/img/icons/dashboard.svg" alt="img"><span>
#                                     Dashboard</span> </a>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
#                                     Product</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="productlist.php">Product List</a></li>
#                                 <li><a href="addproduct.php">Add Product</a></li>
#                                 <li><a href="categorylist.php">Category List</a></li>
#                                 <li><a href="addcategory.php">Add Category</a></li>
#                                 <li><a href="subcategorylist.php">Sub Category List</a></li>
#                                 <li><a href="subaddcategory.php">Add Sub Category</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="manager/brandlist.php">Brand List</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="manager/addbrand.php">Add Brand</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="manager/importproduct.php">Import Products</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="manager/barcode.php">Print Barcode</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg" alt="img"><span>
#                                     Sales</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="saleslist.php" >Sales List</a></li>
#                                 <li><a href="pos.php">POS</a></li>
#                                 <!-- <li><a href="pos.php">New Sales</a></li> -->
#                                 <li><a href="salesreturnlist.php">Sales Return List</a></li>
#                                 <!-- <li><a href="createsalesreturn.php">New Sales Return</a></li> -->
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/expense1.svg" alt="img"><span>
#                                     Expense</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="expenselist.php">Expense List</a></li>
#                                 <li><a href="createexpense.php">Add Expense</a></li>
#                                 <li><a href="expensecategory.php">Expense Category</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/quotation1.svg" alt="img"><span>
#                                     Quotation</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="quotationList.php">Quotation List</a></li>
#                                 <li><a href="addquotation.php">Add Quotation</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span>
#                                     Blogs</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="bloglist.php">Blogs List</a></li>
#                                 <li><a href="addblog.php">Add Blogs</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span>
#                                     Public-info</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="publicinfo.php">Public-information</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="addpublicinfo.php">Add public</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><img src="assets/img/icons/transfer1.svg" alt="img"><span>
#                                     Transfer</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/transferlist.php">Transfer List</a></li>
#                                 <li><a href="manager/addtransfer.php">Add Transfer </a></li>
#                                 <li><a href="manager/importtransfer.php">Import Transfer </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><img src="assets/img/icons/return1.svg" alt="img"><span>
#                                     Return</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/salesreturnlist.php">Sales Return List</a></li>
#                                 <li><a href="manager/createsalesreturn.php">Add Sales Return </a></li>
#                                 <li><a href="manager/purchasereturnlist.php">Purchase Return List</a></li>
#                                 <li><a href="manager/createpurchasereturn.php">Add Purchase Return </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
#                                     People</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="customerlist.php">Customer List</a></li>
#                                 <li><a href="addcustomer.php">Add Customer </a></li>
#                                 <li><a href="supplierlist.php">Supplier List</a></li>
#                                 <li><a href="addsupplier.php">Add Supplier </a></li>
#                                 <li><a href="userlist.php">User List</a></li>
#                                 <li><a href="adduser.php">Add User</a></li>
#                                 <!-- <li><a href="storelist.php">Store List</a></li>
#                                 <li><a href="addstore.php">Add Store</a></li> -->
#                             </ul>
#                         </li>
#                         <!-- <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/places.svg" alt="img"><span>
#                                     Places</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="newcountry.php">New Country</a></li>
#                                 <li><a href="countrieslist.php">Countries list</a></li>
#                                 <li><a href="newstate.php">New State </a></li>
#                                 <li><a href="statelist.php">State list</a></li>
#                             </ul>
#                         </li> -->
#                         <li <?php echo $role_show_element; ?>>
#                             <a href="components.php" ><i data-feather="layers"></i><span> Components</span> </a>
#                         </li>
#                         <li <?php echo $role_show_element; ?>>
#                             <a href="blankpage.php"><i data-feather="file"></i><span> Blank Page</span> </a>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><i data-feather="alert-octagon"></i> <span> Error Pages
#                                 </span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/error-404.php">404 Error </a></li>
#                                 <li><a href="manager/error-500.php">500 Error </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><i data-feather="box"></i> <span>Elements </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/manager/sweetalerts.php">Sweet Alerts</a></li>
#                                 <li><a href="manager/manager/tooltip.php">Tooltip</a></li>
#                                 <li><a href="manager/manager/popover.php">Popover</a></li>
#                                 <li><a href="manager/manager/ribbon.php">Ribbon</a></li>
#                                 <li><a href="manager/manager/clipboard.php">Clipboard</a></li>
#                                 <li><a href="manager/drag-drop.php">Drag & Drop</a></li>
#                                 <li><a href="manager/rangeslider.php">Range Slider</a></li>
#                                 <li><a href="manager/rating.php">Rating</a></li>
#                                 <li><a href="manager/toastr.php">Toastr</a></li>
#                                 <li><a href="manager/text-editor.php">Text Editor</a></li>
#                                 <li><a href="manager/counter.php">Counter</a></li>
#                                 <li><a href="manager/scrollbar.php">Scrollbar</a></li>
#                                 <li><a href="manager/spinner.php">Spinner</a></li>
#                                 <li><a href="manager/notification.php">Notification</a></li>
#                                 <li><a href="manager/lightbox.php">Lightbox</a></li>
#                                 <li><a href="manager/stickynote.php">Sticky Note</a></li>
#                                 <li><a href="manager/timeline.php">Timeline</a></li>
#                                 <li><a href="manager/form-wizard.php">Form Wizard</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><i data-feather="bar-chart-2"></i> <span> Charts </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/chart-apex.php">Apex Charts</a></li>
#                                 <li><a href="manager/chart-js.php">Chart Js</a></li>
#                                 <li><a href="manager/chart-morris.php">Morris Charts</a></li>
#                                 <li><a href="manager/chart-flot.php">Flot Charts</a></li>
#                                 <li><a href="manager/chart-peity.php">Peity Charts</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><i data-feather="award"></i><span> Icons </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/icon-fontawesome.php">Fontawesome Icons</a></li>
#                                 <li><a href="manager/icon-feather.php">Feather Icons</a></li>
#                                 <li><a href="manager/icon-ionic.php">Ionic Icons</a></li>
#                                 <li><a href="manager/icon-material.php">Material Icons</a></li>
#                                 <li><a href="manager/icon-pe7.php">Pe7 Icons</a></li>
#                                 <li><a href="manager/icon-simpleline.php">Simpleline Icons</a></li>
#                                 <li><a href="manager/icon-themify.php">Themify Icons</a></li>
#                                 <li><a href="manager/icon-weather.php">Weather Icons</a></li>
#                                 <li><a href="manager/icon-typicon.php">Typicon Icons</a></li>
#                                 <li><a href="manager/icon-flag.php">Flag Icons</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><i data-feather="columns"></i> <span> Forms </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/form-basic-inputs.php">Basic Inputs </a></li>
#                                 <li><a href="manager/form-input-groups.php">Input Groups </a></li>
#                                 <li><a href="manager/form-horizontal.php">Horizontal Form </a></li>
#                                 <li><a href="manager/form-vertical.php"> Vertical Form </a></li>
#                                 <li><a href="manager/form-mask.php">Form Mask </a></li>
#                                 <li><a href="manager/form-validation.php">Form Validation </a></li>
#                                 <li><a href="manager/form-select2.php">Form Select2 </a></li>
#                                 <li><a href="manager/form-fileupload.php">File Upload </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><i data-feather="layout"></i> <span> Table </span> <span
#                                     class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/tables-basic.php">Basic Tables </a></li>
#                                 <li><a href="manager/data-tables.php">Data Table </a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu" <?php echo $role_show_element; ?>>
#                             <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
#                                     Application</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="manager/chat.php">Chat</a></li>
#                                 <li><a href="manager/calendar.php">Calendar</a></li>
#                                 <li><a href="manager/email.php">Email</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/time.svg" alt="img"><span>
#                                     Report</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="purchaseorderreport.php">Purchase order report</a></li>
#                                 <li><a href="inventoryreport.php">Inventory Report</a></li>
#                                 <li><a href="salesreport.php">Sales Report</a></li>
#                                 <li><a href="invoicereport.php">Invoice Report</a></li>
#                                 <li><a href="purchasereport.php">Purchase Report</a></li>
#                                 <li><a href="supplierreport.php">Supplier Report</a></li>
#                                 <li><a href="customerreport.php">Customer Report</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
#                                     Users</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="newuser.php">New User </a></li>
#                                 <li><a href="userlists.php">Users List</a></li>
#                             </ul>
#                         </li>
#                         <li class="submenu">
#                             <a href="javascript:void(0);"><img src="assets/img/icons/settings.svg" alt="img"><span>
#                                     Settings</span> <span class="menu-arrow"></span></a>
#                             <ul>
#                                 <li><a href="generalsettings.php">General Settings</a></li>
#                                 <li><a href="emailsettings.php">Email Settings</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="paymentsettings.php">Payment Settings</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="currencysettings.php">Currency Settings</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="grouppermissions.php">Group Permissions</a></li>
#                                 <li <?php echo $role_show_element; ?>><a href="taxrates.php">Tax Rates</a></li>
#                             </ul>
#                         </li>
#                     </ul>
#                 </div>
#             </div>
#         </div>"""

for filename in file_names:
    # Tạo đường dẫn đầy đủ đến từng tệp
    file_path = os.path.join(directory_path, filename)
    # Thực hiện thay thế trong từng tệp
    find_replace_in_file(file_path, old_string, new_string)
    print(f"Đã thay thế trong tệp: {file_path}")

print("Hoàn thành quá trình thay thế cho tất cả các tệp.")

# START XÓA THẺ PHP


# END XÓA THẺ PHP