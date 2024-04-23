<?php
    session_start();
    if (empty($_SESSION['token_check'])){
        exit();
      }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once('../../config/database.php');
        require_once('../../model/usermanager_model.php');
        $usermanager_model = new UserManagerModel($conn);
        $token = $_SESSION['token_check'];
        $p_password= $_POST['confirm_password'];

        if ($_POST['token'] === $token) {
            $resetpass = $usermanager_model->update_password($_SESSION['email_check'],$p_password);
            if ($resetpass){
                $_SESSION['email_manager'] = $_SESSION['email_check']; 
                unset($_SESSION['token_check']);
                unset($_SESSION['email_check']);
              echo "Mật khẩu đã được đặt lại thành công!";
               header("Location: ../index.php");
            }
            else {
                echo "<script> alert('Đặt mật khẩu thất bại!')</script>";
            }
        } else {
            echo "Mã xác thực không hợp lệ!";
        }
        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Đặt Lại Mật Khẩu</h4>
                        <form id="resetPasswordForm" method="POST">
                            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu mới" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Xác nhận mật khẩu mới" required>
                            </div>
                            <p id="passwordMatchError" class="text-danger" style="display: none;">Mật khẩu và xác nhận mật khẩu không khớp.</p>
                            <button type="submit" name="submitbutton" id="submitButton" class="btn btn-primary" disabled>Đặt lại mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("confirmPassword");
        const passwordMatchError = document.getElementById("passwordMatchError");
        const submitButton = document.getElementById("submitButton");

        confirmPasswordInput.addEventListener("input", () => {
            if (passwordInput.value !== confirmPasswordInput.value) {
                passwordMatchError.style.display = "block";
                submitButton.disabled = true;
            } else {
                passwordMatchError.style.display = "none";
                submitButton.disabled = false;
            }
        });
    </script>
</body>
</html>
