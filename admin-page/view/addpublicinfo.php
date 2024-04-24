<?php
    session_start();
    if (!isset($_SESSION['email_manager']) &&  !$_SESSION['email_manager']) {
        echo "<script> alert('Bạn chưa đăng nhập!')</script>";
        header("refresh:1.5;url=signin.php");
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        header("location:signin.php");
    }
    if (isset($_SESSION['role_manager']) && $_SESSION['role_manager'] !== 'admin'){
        $role_show_element = 'hidden';
        $role_active_element = 'disabled';
    }
    else {
        $role_show_element = '';
    }
?>