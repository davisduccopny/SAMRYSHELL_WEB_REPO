<?php require_once('./main/role_manager.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('./main/head.php'); ?>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/animate.css">

    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

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
                        <h4>Tất cả thông báo</h4>
                        <h6>Xem tất cả thông báo của bạn</h6>
                    </div>
                </div>

                <div class="activity">
                    <div class="activity-box">
                        <ul class="activity-list">
                        <?php if($getNotification): ?>
                        <?php foreach( $getNotification as $showactivities ):?>
                            <li>
                                <div class="activity-user">
                                    <a href="profile.php" title="" data-toggle="tooltip"
                                        data-original-title="Lesley Grauer">
                                    </a>
                                </div>
                                <div class="activity-content">
                                    <div class="timeline-content">
                                        <a href="profile.php" class="name"><?php echo $showactivities['email'].' '; ?></a>
                                        <a href="#"><?php echo $showactivities['message']; ?></a>
                                        <span class="time"><?php echo $showactivities['created_at']; ?></span>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <p>No activities</p>
                            <?php endif; ?>
                        </ul>
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

    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>