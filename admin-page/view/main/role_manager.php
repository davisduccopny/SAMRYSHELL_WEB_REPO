<?php
    session_start();
    if (!isset($_SESSION['email_manager']) ||  empty($_SESSION['email_manager'])) {
        echo "<script> alert('Bạn chưa đăng nhập!')</script>";
        header("refresh:1.5;url=signin.php");
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        header("location:signin.php");
    }
    if (isset($_SESSION['role_manager'])) {
        if ($_SESSION['role_manager'] !== 'Admin') {
            $role_show_element = 'hidden';
            $role_active_element = 'disabled';
        } else {
            $role_show_element = '';
        }
    } else {
        $role_show_element = 'hidden';
        $role_active_element = 'disabled';
    }
    require '../config/database.php';
    require_once('../model/notification_model.php');
    $notificationModel = new NotificationModel($conn);
    $getNotification = $notificationModel->getNotification();
    $countNotification = num_rowsCount_item("SELECT * FROM notification", $conn);
?>