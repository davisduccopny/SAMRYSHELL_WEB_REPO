<?php
session_start();
require '../config/database.php';
require '../model/signin_model.php';
require '../model/sale_model.php';
require '../model/cart_model.php';
require '../model/usercustomer_model.php';
require '../model/customer_model.php';
if (isset($_POST['login_manager'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $signin = new SigninModal($conn);
    $result = $signin->signin($email, $password);
    if ($result) {
        $_SESSION['email_manager'] = $email;
        $_SESSION['role_manager'] = $result['role'];
        ob_clean();
        echo "success";
    } else {
        echo "Email or password is incorrect";
    }
}
if (isset($_POST['login_customer'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $signin = new SigninModal($conn);
    $result = $signin->signin_customer($email, $password);
    if ($result) {
        $_SESSION['email_customer'] = $email;
        ob_clean();
        echo "success";
    } else {
        echo "Email or password is incorrect";
    }
}
if (isset($_POST['insert_salecart'])) {
    $saleModel = new SaleModel($conn);
    $cartModel = new CartModel($conn);
    $emailinsertcart = $_POST['email'];
    $listproduct = $cartModel->getCart($emailinsertcart);
    $payment = 'Due';
    $status = 'Pending';

    $hasProductId = false;


    foreach ($listproduct as $product) {
        if (isset($product['product_id'])) {
            $hasProductId = true;
            break;
        }
    }

    if ($hasProductId) {
        $addsalecart = $saleModel->createSaleAndSaleDetail_cart($emailinsertcart, $status, $payment);
        require('./send_mail_controller.php');
        if ($addsalecart && $sendmail) {
            ob_clean();
            echo 'success';
        } else {
            ob_clean();
            echo 'error';
        }
    } else {

        ob_clean();
        echo 'error';
    }
}
if (isset($_POST['login_status']) && $_POST['login_status'] == 'check_login') {
    if (!isset($_SESSION['email_customer']) || empty($_SESSION['email_customer'])) {

        echo 'error';
    } else {
        ob_clean();
        echo 'logged_in';
    }
}
if (isset($_POST['currentPass'])) {
    $currenpass = $_POST['currentPass'];
    $email = $_SESSION['email_customer'];
    $signin = new SigninModal($conn);
    $result = $signin->signin_customer($email, $currenpass);
    if ($result) {
        ob_clean();
        echo "password_correct";
    } else {
        echo "Email or password is incorrect";
    }
}
if (isset($_POST['check_action']) && $_POST['check_action'] == 'update_info_user') {
    $userModel = new UserCustomerModel($conn);
    $customModel = new CustomerModel($conn);
    $email = $_POST['email'];
    $name = $_POST['name'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $district = $_POST['district'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    if (isset($_POST['password'])) {
        $confirmpass = $_POST['password'];
        $updatepass = $userModel->update_password($email, $confirmpass);
    }

    $updateinfo = $customModel->updateCustomer_byUser($name, $phone, $email, $country, $city, $district, $address, $zipcode);
    if ($updateinfo) {
        echo 'success';
    } else {
        echo 'error';
    }
}
if (isset($_POST['logout_manager'])) {
    unset($_SESSION['email_manager']);
    unset($_SESSION['role_manager']);
    header('Location: ../view/signin.php');
}
