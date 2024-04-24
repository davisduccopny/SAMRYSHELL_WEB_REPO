<?php 
require 'admin-page/model/cart_model.php';
$cartmodel = new CartModel($conn);
session_start();
if(isset($_SESSION['email_customer'])) {
    $emaillist = $_SESSION['email_customer'];
    $listcart = $cartmodel->getCart($emaillist);
    $countcart = count($listcart);
}
else {
    $emaillist = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['login_addcart'])){
    if (!isset($_SESSION['email_customer']) || empty($_SESSION['email_customer'])) {
        $id = $_POST['product_id_addcart_new'];
        echo "<script> alert('Bạn chưa đăng nhập!')</script>";
        header("refresh:1.5;url=/login-register.php");
    }
    else {

    $quantity_addcart = $_POST['quantity_addcart'];
    // CHECK PRODUCT
    $checkarrayproduct = [];
    foreach ($listcart  as $check) {
        $checkarrayproduct[] = $check['product_id'];
    }
    
    function checkProduct($productid, $id) {
        $found = false;
    
        for ($i = 0; $i < count($productid); $i++) {
            if ($productid[$i] == $id) {
                $found = true;
                break;
            }
        }

        return $found;
    }
      // CHECK PRODUCT
    $checkproduct = checkProduct($checkarrayproduct, $id);
    if ($checkproduct) {
        $updatecart = $cartmodel->updateCart($id,$quantity_addcart,$emaillist);
        if ($updatecart){
            header("Refresh:0");
            
        }
     
    }
    else {
      
    $addcart = $cartmodel->insertCart($id,$quantity_addcart,$emaillist);
    if ($addcart){
        header("Refresh:0");
    }
    }

    }
}
if (isset($_POST['delete_cart'])){
    $product_id = $_POST['product_id_delete'];
    $deletecontroller = $cartmodel->deleteCart($product_id,$emaillist);
    if ($deletecontroller){
        header("Refresh:0");
    }
}

}
?>