<?php
require_once '../../config/database.php';
spl_autoload_register(function($className) {
    require_once "../../App/Models/$className.php";
});

// thực hiện thao tác khi được gửi yêu cầu từ form, href

$userModel = new User();
if(!empty($_POST['email']) && !empty($_POST['username']) && isset($_POST['password'])) {
    if($userModel->register($_POST['username'],$_POST['password'],$_POST['email']))
    header('Location: http://localhost/PHP-PJ/Views/Guest/login.php');
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="../../Assets/css/register-login.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="register">
    <div class="container-fluid d-flex justify-content-center align-items-center form-si" style="height: 80vh; width: 80%;">
        <div class="col-md-6 p-0">
            <img src="../../Assets/images/logojpg.jpg" alt="Hình ảnh" class="logo">
        </div>
        <div class="col-12 col-md-6 d-flex justify-content-center align-items-center p-4">
            <form style="width: 100%; max-width: 400px;" action="register.php" method="post">
                <h1 class="text-center mb-4 text-success">
                    Sign in
                </h1>
                <div class="form-group form-group d-flex align-items-center">
                    <img src="../../Assets/images/gmail.png" alt="Gmail Icon" width="35" height="35" class="mr-2">
                    <input type="email" class="form-control" id="email" name= "email" placeholder="User@gmail.com" style="height: 50px;">
                </div>

                <div class="form-group d-flex align-items-center">
                    <img src="../../Assets/images/user (3).png" alt="user Icon" width="35" height="35"class="mr-2">
                    <input type="text" class="form-control" id="username" name ="username" placeholder="Username" style="height: 50px;">
                </div>
                <div class="form-group d-flex align-items-center ">
                    <img src="../../Assets/images/password.png" alt="lock Icon" width="35" height="35" class="mr-2">
                    <input type="password" class="form-control" id="password" placeholder="Password"  name = "password" style="height: 50px;">
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="login.php" style="text-decoration: none;"
                    class="btn btn-outline-primary  btn-login">Login</a>
                
                    <button type ="submit" class="btn btn-outline-success btn-signIn">Sign in</button>
                </div>
            </form>
        </div>
    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
