<div class="header">

    <div class="header-left active">
        <a href="index.php" class="logo">
            <img src="../../../assets/img/samryshell-logo.jpg" alt="">
        </a>
        <a href="index.php" class="logo-small">
            <img src="../../../assets/img/samryshell-logo-black.png" alt="">
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
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
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
                <img src="assets/img/icons/notification-bing.svg" alt="img"> <span class="badge rounded-pill"><?php if($countNotification){echo $countNotification;}else {echo 0;}; ?></span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                    <?php if($getNotification): ?>
                    <?php foreach($getNotification as $shownotification): ?>
                        <li class="notification-message">
                            <a href="activities.php">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt="" src="assets/img/profiles/avatar-04.jpg">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title"><?php echo $shownotification['email'].' '; ?></span><span class="noti-title"><?php echo $shownotification['message']; ?></span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time"><?php echo $shownotification['created_at']; ?></span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <li class="notification-message">
                            <a href="activities.php">
                                <div class="media d-flex">
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">No Notification</span>
                                        </p>


                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="activities.php">View all Notifications</a>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-img"><img src="../../../assets/img/samryshell-logo-black.png" alt="">
                    <span class="status online"></span></span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img"><img src="../../../assets/img/samryshell-logo-black.png" alt="">
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>Samry</h6>
                            <h5>Admin</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="#"> <i class="me-2" data-feather="user"></i> My
                        Profile</a>
                    <a class="dropdown-item" href="generalsettings.php"><i class="me-2" data-feather="settings"></i>Settings</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" onclick="logOut_manager(event)"><img src="assets/img/icons/log-out.svg" class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>


    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="profile.php">My Profile</a>
            <a class="dropdown-item" href="generalsettings.php">Settings</a>
            <a class="dropdown-item" onclick="logOut_manager(event)">Logout</a>
        </div>
    </div>

</div>
<script>
        function logOut_manager(event){
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
                    url:'../controller/signin_controller.php',
                    type:'POST',
                    data : {
                        logout_manager : 1
                    },
                    success:function(){
                      window.location.href='./signin.php';
                    }
             })
          }
      }
      )
  }
</script>