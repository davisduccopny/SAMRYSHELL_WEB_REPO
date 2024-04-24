<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Dreams Pos admin template</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/animate.css">

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

        <div class="header">

            <div class="header-left active">
                <a href="index.php" class="logo">
                    <img src="assets/img/logo.png" alt="">
                </a>
                <a href="index.php" class="logo-small">
                    <img src="assets/img/logo-small.png" alt="">
                </a>
                <a id="toggle_btn" href="javascript:void(0);">
                </a>
            </div>

            <a id="mobile_btn" class="mobile_btn" href="#sidebar">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>

            <ul class="nav user-menu">

                <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa fa-search"></i>
                        </a>
                        <form action="#">
                            <div class="searchinputs">
                                <input type="text" placeholder="Search Here ...">
                                <div class="search-addon">
                                    <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                                </div>
                            </div>
                            <a class="btn" id="searchdiv"><img src="assets/img/icons/search.svg" alt="img"></a>
                        </form>
                    </div>
                </li>


                <li class="nav-item dropdown has-arrow flag-nav">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);"
                        role="button">
                        <img src="assets/img/flags/us1.png" alt="" height="20">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/us.png" alt="" height="16"> English
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/fr.png" alt="" height="16"> French
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/es.png" alt="" height="16"> Spanish
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item">
                            <img src="assets/img/flags/de.png" alt="" height="16"> German
                        </a>
                    </div>
                </li>


                <li class="nav-item dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <img src="assets/img/icons/notification-bing.svg" alt="img"> <span
                            class="badge rounded-pill">4</span>
                    </a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                            <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="activities.php">
                                        <div class="media d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img alt="" src="assets/img/profiles/avatar-02.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">John Doe</span> added
                                                    new task <span class="noti-title">Patient appointment booking</span>
                                                </p>
                                                <p class="noti-time"><span class="notification-time">4 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.php">
                                        <div class="media d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img alt="" src="assets/img/profiles/avatar-03.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Tarah Shropshire</span>
                                                    changed the task name <span class="noti-title">Appointment booking
                                                        with payment gateway</span></p>
                                                <p class="noti-time"><span class="notification-time">6 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.php">
                                        <div class="media d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img alt="" src="assets/img/profiles/avatar-06.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Misty Tison</span>
                                                    added <span class="noti-title">Domenic Houston</span> and <span
                                                        class="noti-title">Claire Mapes</span> to project <span
                                                        class="noti-title">Doctor available module</span></p>
                                                <p class="noti-time"><span class="notification-time">8 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.php">
                                        <div class="media d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img alt="" src="assets/img/profiles/avatar-17.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Rolland Webber</span>
                                                    completed task <span class="noti-title">Patient and Doctor video
                                                        conferencing</span></p>
                                                <p class="noti-time"><span class="notification-time">12 mins ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="activities.php">
                                        <div class="media d-flex">
                                            <span class="avatar flex-shrink-0">
                                                <img alt="" src="assets/img/profiles/avatar-13.jpg">
                                            </span>
                                            <div class="media-body flex-grow-1">
                                                <p class="noti-details"><span class="noti-title">Bernardo Galaviz</span>
                                                    added new task <span class="noti-title">Private chat module</span>
                                                </p>
                                                <p class="noti-time"><span class="notification-time">2 days ago</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="activities.php">View all Notifications</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown has-arrow main-drop">
                    <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                        <span class="user-img"><img src="assets/img/profiles/avator1.jpg" alt="">
                            <span class="status online"></span></span>
                    </a>
                    <div class="dropdown-menu menu-drop-user">
                        <div class="profilename">
                            <div class="profileset">
                                <span class="user-img"><img src="assets/img/profiles/avator1.jpg" alt="">
                                    <span class="status online"></span></span>
                                <div class="profilesets">
                                    <h6>John Doe</h6>
                                    <h5>Admin</h5>
                                </div>
                            </div>
                            <hr class="m-0">
                            <a class="dropdown-item" href="profile.php"> <i class="me-2" data-feather="user"></i> My
                                Profile</a>
                            <a class="dropdown-item" href="generalsettings.php"><i class="me-2"
                                    data-feather="settings"></i>Settings</a>
                            <hr class="m-0">
                            <a class="dropdown-item logout pb-0" href="signin.php"><img
                                    src="assets/img/icons/log-out.svg" class="me-2" alt="img">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>


            <div class="dropdown mobile-user-menu">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile.php">My Profile</a>
                    <a class="dropdown-item" href="generalsettings.php">Settings</a>
                    <a class="dropdown-item" href="signin.php">Logout</a>
                </div>
            </div>

        </div>


        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li>
                            <a href="index.php"><img src="assets/img/icons/dashboard.svg" alt="img"><span>
                                    Dashboard</span> </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
                                    Product</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="productlist.php">Product List</a></li>
                                <li><a href="addproduct.php">Add Product</a></li>
                                <li><a href="categorylist.php">Category List</a></li>
                                <li><a href="addcategory.php">Add Category</a></li>
                                <li><a href="subcategorylist.php">Sub Category List</a></li>
                                <li><a href="subaddcategory.php">Add Sub Category</a></li>
                                <li><a href="brandlist.php">Brand List</a></li>
                                <li><a href="addbrand.php">Add Brand</a></li>
                                <li><a href="importproduct.php">Import Products</a></li>
                                <li><a href="barcode.php">Print Barcode</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg" alt="img"><span>
                                    Sales</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="saleslist.php">Sales List</a></li>
                                <li><a href="pos.php">POS</a></li>
                                <li><a href="pos.php">New Sales</a></li>
                                <li><a href="salesreturnlists.php">Sales Return List</a></li>
                                <li><a href="createsalesreturns.php">New Sales Return</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span>
                                    Purchase</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="purchaselist.php">Purchase List</a></li>
                                <li><a href="addpurchase.php">Add Purchase</a></li>
                                <li><a href="importpurchase.php">Import Purchase</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/expense1.svg" alt="img"><span>
                                    Expense</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="expenselist.php">Expense List</a></li>
                                <li><a href="createexpense.php">Add Expense</a></li>
                                <li><a href="expensecategory.php">Expense Category</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/quotation1.svg" alt="img"><span>
                                    Quotation</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="quotationList.php">Quotation List</a></li>
                                <li><a href="addquotation.php">Add Quotation</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/transfer1.svg" alt="img"><span>
                                    Transfer</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="transferlist.php">Transfer List</a></li>
                                <li><a href="addtransfer.php">Add Transfer </a></li>
                                <li><a href="importtransfer.php">Import Transfer </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/return1.svg" alt="img"><span>
                                    Return</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="salesreturnlist.php">Sales Return List</a></li>
                                <li><a href="createsalesreturn.php">Add Sales Return </a></li>
                                <li><a href="purchasereturnlist.php">Purchase Return List</a></li>
                                <li><a href="createpurchasereturn.php">Add Purchase Return </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
                                    People</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="customerlist.php">Customer List</a></li>
                                <li><a href="addcustomer.php">Add Customer </a></li>
                                <li><a href="supplierlist.php">Supplier List</a></li>
                                <li><a href="addsupplier.php">Add Supplier </a></li>
                                <li><a href="userlist.php">User List</a></li>
                                <li><a href="adduser.php">Add User</a></li>
                                <li><a href="storelist.php">Store List</a></li>
                                <li><a href="addstore.php">Add Store</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/places.svg" alt="img"><span>
                                    Places</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="newcountry.php">New Country</a></li>
                                <li><a href="countrieslist.php">Countries list</a></li>
                                <li><a href="newstate.php">New State </a></li>
                                <li><a href="statelist.php">State list</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="components.php"><i data-feather="layers"></i><span> Components</span> </a>
                        </li>
                        <li>
                            <a href="blankpage.php"><i data-feather="file"></i><span> Blank Page</span> </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="alert-octagon"></i> <span> Error Pages
                                </span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="error-404.php">404 Error </a></li>
                                <li><a href="error-500.php">500 Error </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="box"></i> <span>Elements </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="sweetalerts.php">Sweet Alerts</a></li>
                                <li><a href="tooltip.php">Tooltip</a></li>
                                <li><a href="popover.php">Popover</a></li>
                                <li><a href="ribbon.php">Ribbon</a></li>
                                <li><a href="clipboard.php">Clipboard</a></li>
                                <li><a href="drag-drop.php">Drag & Drop</a></li>
                                <li><a href="rangeslider.php">Range Slider</a></li>
                                <li><a href="rating.php">Rating</a></li>
                                <li><a href="toastr.php">Toastr</a></li>
                                <li><a href="text-editor.php">Text Editor</a></li>
                                <li><a href="counter.php">Counter</a></li>
                                <li><a href="scrollbar.php">Scrollbar</a></li>
                                <li><a href="spinner.php">Spinner</a></li>
                                <li><a href="notification.php">Notification</a></li>
                                <li><a href="lightbox.php">Lightbox</a></li>
                                <li><a href="stickynote.php">Sticky Note</a></li>
                                <li><a href="timeline.php">Timeline</a></li>
                                <li><a href="form-wizard.php">Form Wizard</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="bar-chart-2"></i> <span> Charts </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="chart-apex.php">Apex Charts</a></li>
                                <li><a href="chart-js.php">Chart Js</a></li>
                                <li><a href="chart-morris.php">Morris Charts</a></li>
                                <li><a href="chart-flot.php">Flot Charts</a></li>
                                <li><a href="chart-peity.php" class="active">Peity Charts</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="award"></i><span> Icons </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="icon-fontawesome.php">Fontawesome Icons</a></li>
                                <li><a href="icon-feather.php">Feather Icons</a></li>
                                <li><a href="icon-ionic.php">Ionic Icons</a></li>
                                <li><a href="icon-material.php">Material Icons</a></li>
                                <li><a href="icon-pe7.php">Pe7 Icons</a></li>
                                <li><a href="icon-simpleline.php">Simpleline Icons</a></li>
                                <li><a href="icon-themify.php">Themify Icons</a></li>
                                <li><a href="icon-weather.php">Weather Icons</a></li>
                                <li><a href="icon-typicon.php">Typicon Icons</a></li>
                                <li><a href="icon-flag.php">Flag Icons</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="columns"></i> <span> Forms </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="form-basic-inputs.php">Basic Inputs </a></li>
                                <li><a href="form-input-groups.php">Input Groups </a></li>
                                <li><a href="form-horizontal.php">Horizontal Form </a></li>
                                <li><a href="form-vertical.php"> Vertical Form </a></li>
                                <li><a href="form-mask.php">Form Mask </a></li>
                                <li><a href="form-validation.php">Form Validation </a></li>
                                <li><a href="form-select2.php">Form Select2 </a></li>
                                <li><a href="form-fileupload.php">File Upload </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="layout"></i> <span> Table </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="tables-basic.php">Basic Tables </a></li>
                                <li><a href="data-tables.php">Data Table </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
                                    Application</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="chat.php">Chat</a></li>
                                <li><a href="calendar.php">Calendar</a></li>
                                <li><a href="email.php">Email</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
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
                                    Users</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="newuser.php">New User </a></li>
                                <li><a href="userlists.php">Users List</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="assets/img/icons/settings.svg" alt="img"><span>
                                    Settings</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="generalsettings.php">General Settings</a></li>
                                <li><a href="emailsettings.php">Email Settings</a></li>
                                <li><a href="paymentsettings.php">Payment Settings</a></li>
                                <li><a href="currencysettings.php">Currency Settings</a></li>
                                <li><a href="grouppermissions.php">Group Permissions</a></li>
                                <li><a href="taxrates.php">Tax Rates</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="page-wrapper cardhead">
            <div class="content container-fluid">

                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Peity Chart</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Peity Charts</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Donut Chart</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="donut"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>1/5</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="donut"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>226/360</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="donut"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>0.52/1.561</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="donut"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>1,4</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="donut"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>226,134</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="donut"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>0.52,1.041</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Pie Chart</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="pie"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>1/5</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="pie"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>226/360</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="pie"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>0.52/1.561</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="pie"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>1,4</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="pie"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>226,134</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <span class="pie"
                                                data-peity='{ "fill": ["#7638ff", "rgba(67, 87, 133, .09)"]}'>0.52,1.041</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Line Charts</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card-body ">
                                            <span class="peity-line"
                                                data-width="100%">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-body ">
                                            <span class="peity-line"
                                                data-width="100%">6,2,8,4,-3,8,1,-3,6,-5,9,2,-8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-body ">
                                            <span class="peity-line"
                                                data-width="100%">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Bar Charts</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card-body ">
                                            <span class="bar"
                                                data-peity='{ "fill": ["#5b73e8", "#38cb89"]}'>6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-body ">
                                            <span class="bar"
                                                data-peity='{ "fill": ["#38cb89", "#3e80eb"]}'>6,2,8,4,-3,8,1,-3,6,-5,9,2,-8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-body ">
                                            <span class="bar"
                                                data-peity='{ "fill": ["#ef4b4b", "#ffab00"]}'>6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data attributes</h3>
                            </div>
                            <div class="text-center">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <p class="data-attributes">
                                                <span
                                                    data-peity='{ "fill": ["#664dc9", "rgba(67, 87, 133, .09)"],    "innerRadius": 10, "radius": 40 }'>1/7</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <p class="data-attributes">
                                                <span
                                                    data-peity='{ "fill": ["#5b73e8", "rgba(67, 87, 133, .09)"], "innerRadius": 14, "radius": 36 }'>2/7</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <p class="data-attributes">
                                                <span
                                                    data-peity='{ "fill": ["#38cb89", "rgba(67, 87, 133, .09)"], "innerRadius": 16, "radius": 32 }'>3/7</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <p class="data-attributes">
                                                <span
                                                    data-peity='{ "fill": ["#ef4b4b", "rgba(67, 87, 133, .09)"],  "innerRadius": 18, "radius": 28 }'>4/7</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <p class="data-attributes">
                                                <span
                                                    data-peity='{ "fill": ["#ffab00", "rgba(67, 87, 133, .09)"],   "innerRadius": 20, "radius": 24 }'>5/7</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4">
                                        <div class="card-body ">
                                            <p class="data-attributes">
                                                <span
                                                    data-peity='{ "fill": ["#3e80eb", "rgba(67, 87, 133, .09)"], "innerRadius": 18, "radius": 20 }'>6/7</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Setting Colours Dynamically</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="card-body ">
                                            <span class="bar-colours-1">5,3,9,6,5,9,7,3,5,2</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-body ">
                                            <span class="bar-colours-2">5,3,2,-1,-3,-2,2,3,5,2</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-body ">
                                            <span class="bar-colours-3">0,-3,-6,-4,-5,-4,-7,-3,-5,-2</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-body ">
                                            <span class="pie-colours-2">5,3,9,6,5</span>
                                        </div>
                                    </div>
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

    <script src="assets/plugins/peity/jquery.peity.min.js"></script>
    <script src="assets/plugins/peity/chart-data.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>