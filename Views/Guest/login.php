<?php
session_start();
require_once '../../config/database.php';
spl_autoload_register(function($className) {
    require_once "../../App/Models/$className.php";
});

$userModel = new User();
if (isset($_POST['gmail']) && isset($_POST['password'])) {
    $user = $userModel->login($_POST['gmail'], $_POST['password']);
    if ($user) {
        // Đăng nhập thành công
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['idu'] = $user['id'];
        // Thực hiện chuyển hướng
        header('Location: http://localhost/PHP-PJ/Views/Logged-in/home.php');
        exit; // Quan trọng để dừng việc thực thi mã sau khi chuyển hướng
    } else {
        // Thông báo khi sai tài khoản hoặc mật khẩu
        setcookie('notifi', "red", time()+1);
        setcookie('message','The password or gmail was not valid', time()+1 );
        header("Location:http://localhost/PHP-PJ/Views/Guest/login.php");
    }
  

}
if(isset($_COOKIE['notifi']) && $_COOKIE['notifi'] == "red") {
    echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast(false, '" . $_COOKIE['message'] . "'); }); </script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../../Assets/css/register-login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/css/base-layout.css">
</head>

<body>
    
    <div id='toast'>
        <div class="toast-content">
        <i class="bi bi-x-lg icon-toast"></i>
            <div class="message">
                <span></span>
            </div>
        </div>
    </div>
    <div class="login">
    <div class="container-fluid d-flex justify-content-center align-items-center form-si" style="height: 80vh; width: 80%;">
        <div class="col-12 col-md-6 d-flex justify-content-center align-items-center p-4">

                <form style="width: 100%; max-width: 400px;" method="post" action="login.php">
                <h1 class="text-center mb-4 text-success">Welcome</h1>

                <div class="form-group d-flex align-items-center">
                    <img src="../../Assets/images/user (3).png" alt="Gmail Icon" width="35" height="35"class="mr-2">
                    <input type="text" class="form-control" id="gmail" name ="gmail"placeholder="User@gmail.com" style="height: 50px;">
                </div>
                <div class="form-group d-flex align-items-center ">
                    <img src="../../Assets/images/password.png" alt="Gmail Icon" width="35" height="35" class="mr-2">
                    <input type="password" class="form-control" id="password" name ="password"placeholder="Password" style="height: 50px;">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="register.php" style="text-decoration: none;"
                    class="btn btn-outline-primary  btn-login">Sign in</a>
                
                    <button type ="submit" class="btn btn-outline-success btn-signIn">Login</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 p-0">
            <img src="../../Assets/images/logojpg.jpg" alt="Hình ảnh" class="logo">
        </div>
    </div>
    </div>
    

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>

    <script src="../../Assets/js/jsLogin"></script>
    <script src="../../Assets/js/components"></script>

</body>
</html>