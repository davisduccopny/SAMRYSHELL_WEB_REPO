<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="active">
                    <a href="index.php"><img src="assets/img/icons/dashboard.svg" alt="img"><span>
                            Dashboard</span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
                            Sản phẩm</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="productlist.php">Danh sách sản phẩm</a></li>
                        <li><a href="addproduct.php">Thêm sản phẩm</a></li>
                        <li><a href="categorylist.php">Danh mục sản phẩm</a></li>
                        <li><a href="addcategory.php">Thêm danh mục</a></li>
                        <li><a href="subcategorylist.php">Danh mục phụ</a></li>
                        <li><a href="subaddcategory.php">Thêm danh mục phụ</a></li>
                        <li <?php echo $role_show_element; ?>><a href="./manager/brandlist.php">Brand List</a></li>
                        <li <?php echo $role_show_element; ?>><a href="./manager/addbrand.php">Add Brand</a></li>
                        <li <?php echo $role_show_element; ?>><a href="./manager/importproduct.php">Import Products</a></li>
                        <li <?php echo $role_show_element; ?>><a href="./manager/barcode.php">Print Barcode</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg" alt="img"><span>
                            Bán hàng</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="saleslist.php">Danh sách đơn hàng</a></li>
                        <li><a href="pos.php">Thêm đơn hàng</a></li>
                        <!-- <li><a href="pos.php">New Sales</a></li> -->
                        <li><a href="salesreturnlist.php">Danh sách trả hàng</a></li>
                        <!-- <li><a href="createsalesreturn.php">New Sales Return</a></li> -->
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/expense1.svg" alt="img"><span>
                            Chi phí</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="expenselist.php">Danh sách chi phí</a></li>
                        <li><a href="createexpense.php">Thêm chi phí</a></li>
                        <li><a href="expensecategory.php">Danh mục chi phí</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/quotation1.svg" alt="img"><span>
                            Báo giá</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="quotationList.php">Danh sách báo giá</a></li>
                        <li><a href="addquotation.php">Thêm báo giá</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span>
                            Bài viết</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="bloglist.php">Danh sách bài viết</a></li>
                        <li><a href="addblog.php">Thêm bài viết</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span>
                            Thông tin trang web</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="publicinfo.php">Danh sách các trang</a></li>
                        <li <?php echo $role_show_element; ?>><a href="addpublicinfo.php">Add public</a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><img src="assets/img/icons/transfer1.svg" alt="img"><span>
                            Transfer</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/transferlist.php">Transfer List</a></li>
                        <li><a href="./manager/addtransfer.php">Add Transfer </a></li>
                        <li><a href="./manager/importtransfer.php">Import Transfer </a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><img src="assets/img/icons/return1.svg" alt="img"><span>
                            Return</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/salesreturnlist.php">Sales Return List</a></li>
                        <li><a href="./manager/createsalesreturn.php">Add Sales Return </a></li>
                        <li><a href="./manager/purchasereturnlist.php">Purchase Return List</a></li>
                        <li><a href="./manager/createpurchasereturn.php">Add Purchase Return </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
                            Mọi người</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="customerlist.php">Danh sách khách hàng</a></li>
                        <li><a href="addcustomer.php">Thêm khách hàng</a></li>
                        <li><a href="supplierlist.php">Danh sách nhà cung cấp</a></li>
                        <li><a href="addsupplier.php">Thêm nhà cung cấp</a></li>
                        <li><a href="userlist.php">Danh sách tài khoản khách hàng</a></li>
                        <li><a href="adduser.php">Thêm tài khoản khách hàng</a></li>
                        <!-- <li><a href="storelist.php">Store List</a></li>
                                <li><a href="addstore.php">Add Store</a></li> -->
                    </ul>
                </li>
                <!-- <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/places.svg" alt="img"><span>
                                    Places</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="newcountry.php">New Country</a></li>
                                <li><a href="countrieslist.php">Countries list</a></li>
                                <li><a href="newstate.php">New State </a></li>
                                <li><a href="statelist.php">State list</a></li>
                            </ul>
                        </li> -->
                <li <?php echo $role_show_element; ?>>
                    <a href="./manager/components.php"><i data-feather="layers"></i><span> Components</span> </a>
                </li>
                <li <?php echo $role_show_element; ?>>
                    <a href="./manager/blankpage.php"><i data-feather="file"></i><span> Blank Page</span> </a>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><i data-feather="alert-octagon"></i> <span> Error Pages
                        </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/error-404.php">404 Error </a></li>
                        <li><a href="./manager/error-500.php">500 Error </a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><i data-feather="box"></i> <span>Elements </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/manager/sweetalerts.php">Sweet Alerts</a></li>
                        <li><a href="./manager/manager/tooltip.php">Tooltip</a></li>
                        <li><a href="./manager/manager/popover.php">Popover</a></li>
                        <li><a href="./manager/manager/ribbon.php">Ribbon</a></li>
                        <li><a href="./manager/manager/clipboard.php">Clipboard</a></li>
                        <li><a href="./manager/drag-drop.php">Drag & Drop</a></li>
                        <li><a href="./manager/rangeslider.php">Range Slider</a></li>
                        <li><a href="./manager/rating.php">Rating</a></li>
                        <li><a href="./manager/toastr.php">Toastr</a></li>
                        <li><a href="./manager/text-editor.php">Text Editor</a></li>
                        <li><a href="./manager/counter.php">Counter</a></li>
                        <li><a href="./manager/scrollbar.php">Scrollbar</a></li>
                        <li><a href="./manager/spinner.php">Spinner</a></li>
                        <li><a href="./manager/notification.php">Notification</a></li>
                        <li><a href="./manager/lightbox.php">Lightbox</a></li>
                        <li><a href="./manager/stickynote.php">Sticky Note</a></li>
                        <li><a href="./manager/timeline.php">Timeline</a></li>
                        <li><a href="./manager/form-wizard.php">Form Wizard</a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><i data-feather="bar-chart-2"></i> <span> Charts </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/chart-apex.php">Apex Charts</a></li>
                        <li><a href="./manager/chart-js.php">Chart Js</a></li>
                        <li><a href="./manager/chart-morris.php">Morris Charts</a></li>
                        <li><a href="./manager/chart-flot.php">Flot Charts</a></li>
                        <li><a href="./manager/chart-peity.php">Peity Charts</a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><i data-feather="award"></i><span> Icons </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/icon-fontawesome.php">Fontawesome Icons</a></li>
                        <li><a href="./manager/icon-feather.php">Feather Icons</a></li>
                        <li><a href="./manager/icon-ionic.php">Ionic Icons</a></li>
                        <li><a href="./manager/icon-material.php">Material Icons</a></li>
                        <li><a href="./manager/icon-pe7.php">Pe7 Icons</a></li>
                        <li><a href="./manager/icon-simpleline.php">Simpleline Icons</a></li>
                        <li><a href="./manager/icon-themify.php">Themify Icons</a></li>
                        <li><a href="./manager/icon-weather.php">Weather Icons</a></li>
                        <li><a href="./manager/icon-typicon.php">Typicon Icons</a></li>
                        <li><a href="./manager/icon-flag.php">Flag Icons</a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><i data-feather="columns"></i> <span> Forms </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/form-basic-inputs.php">Basic Inputs </a></li>
                        <li><a href="./manager/form-input-groups.php">Input Groups </a></li>
                        <li><a href="./manager/form-horizontal.php">Horizontal Form </a></li>
                        <li><a href="./manager/form-vertical.php"> Vertical Form </a></li>
                        <li><a href="./manager/form-mask.php">Form Mask </a></li>
                        <li><a href="./manager/form-validation.php">Form Validation </a></li>
                        <li><a href="./manager/form-select2.php">Form Select2 </a></li>
                        <li><a href="./manager/form-fileupload.php">File Upload </a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><i data-feather="layout"></i> <span> Table </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/tables-basic.php">Basic Tables </a></li>
                        <li><a href="./manager/data-tables.php">Data Table </a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
                            Application</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="./manager/chat.php">Chat</a></li>
                        <li><a href="./manager/calendar.php">Calendar</a></li>
                        <li><a href="./manager/email.php">Email</a></li>
                    </ul>
                </li>
                <li class="submenu" <?php echo $role_show_element; ?>>
                    <a href="javascript:void(0);"><img src="assets/img/icons/time.svg" alt="img"><span>
                            Report</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaseorderreport.php">Purchase order report</a></li>
                        <li><a href="inventoryreport.php">Inventory Report</a></li>
                        <li><a href="salesreport.php">Sales Report</a></li>
                        <li><a href="invoicereport.php">Invoice Report</a></li>
                        <li><a href="purchasereport.php">Purchase Report</a></li>
                        <li><a href="supplierreport.php">Supplier Report</a></li>
                        <li><a href="customerreport.php">Customer Report</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
                            Tài khoản quản lý</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="newuser.php">Thêm tài khoản quản trị</a></li>
                        <li><a href="userlists.php">Danh sách tài khoản quản trị</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/settings.svg" alt="img"><span>
                            Settings</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="generalsettings.php">Thông tin hiển thị</a></li>
                        <li><a href="emailsettings.php">Cài đặt email</a></li>
                        <li <?php echo $role_show_element; ?>><a href="paymentsettings.php">Payment Settings</a></li>
                        <li <?php echo $role_show_element; ?>><a href="currencysettings.php">Currency Settings</a></li>
                        <li <?php echo $role_show_element; ?>><a href="grouppermissions.php">Group Permissions</a></li>
                        <li <?php echo $role_show_element; ?>><a href="taxrates.php">Tax Rates</a></li>
                    </ul>
                </li>
                <li >
                    <a href="index.php"><span>
                            Copyright SamryShell</span> </a>
                </li>
            </ul>
        </div>
    </div>
</div>