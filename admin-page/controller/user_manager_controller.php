<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require('../config/database.php');
    require('../model/usermanager_model.php');
    $usermanagerModel = new UserManagerModel($conn);

 

    if (isset($_POST['check_email']) && $_POST['check_email']) {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $sql = "SELECT COUNT(*) FROM user_manager WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
    
            echo json_encode(['exists' => ($count > 0)]);
        } else {
            echo json_encode(['error' => 'Missing email']);
        }
    }
    else if (isset($_POST['check_pass']) && $_POST['check_pass']) {
        if (isset($_POST['pass'])) {
            $pass = $_POST['pass'];
            $usermanager_id = $_POST['usermanager_id'];
            $sql = "SELECT password FROM user_manager WHERE  id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usermanager_id);
            $stmt->execute();
            $stmt->bind_result($storehashpassword);
            $stmt->fetch();
            $stmt->close();
            
            if ($storehashpassword && password_verify($pass, $storehashpassword)){
                echo json_encode(['exists' => true]);
            } else {
                echo json_encode(['exists' => false]);
            }
        } else {
            echo json_encode(['error' => 'Missing password']);
        }
    

    } elseif (isset($_POST['create']) && $_POST['create']) {
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phone = $_POST['phone'];
        $image = $_FILES['image']['name'];
        $imagetmp = $_FILES['image']['tmp_name'];
        $role = $_POST['role'];
        $status = $_POST['status'];
        $userinsert = $usermanagerModel->insertUsermanager($email, $password, $firstname, $lastname, $phone, $image, $imagetmp, $role, $status);

            if ($userinsert) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
    }
    else if (isset($_POST['update']) && $_POST['update']) {
        $user_id = $_POST['usermanager_id'];
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $phone = $_POST['phone'];
        $image = !empty($_FILES['image']['name']) ? $_FILES['image']['name'] : 0;
        $imagetmp = !empty($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : 0;

        $role = $_POST['role'];
        $status = $_POST['status'];
        $userupdate = $usermanagerModel->updateUserManager($user_id, $email, $password, $firstname, $lastname, $phone, $image, $imagetmp, $role, $status);
        if ($userupdate) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
    else if (isset($_POST['delete']) && $_POST['delete']) {
        $user_id = $_POST['usermanager_id'];
        $unsetimage = $usermanagerModel->unsetimageusermanager($user_id);
        
        $userdelete = $usermanagerModel->deleteUserManager($user_id);
       
        if ($userdelete) {
            ob_clean();
            echo 'success';
        } else {
            ob_clean();
            echo 'fail';
        }
    }
     else {
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}


$conn->close();
?>
