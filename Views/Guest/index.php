<?php
session_start();
require_once '../../config/database.php';
spl_autoload_register(function($className) {
    require_once "../../App/Models/$className.php";
});

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_SESSION['username']) ){
        header("Location:http://localhost/PHP-PJ/Views/Logged-in/home.php");
    }
    else {
        setcookie('notifi', "red", time()+1);
        setcookie('message','Please login to continue', time()+1 );
    }

    if(isset($_COOKIE['notifi']) && $_COOKIE['notifi'] == "red") {
        echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast(false, '" . $_COOKIE['message'] . "'); }); </script>";
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../Assets/css/guest.css">
    

    <title>Iris Cardmaker</title>
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
    <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary ">
        <div class="container ">
            <a class="navbar-brand d-flex align-items-center  " href="index.php">
                <img src="../../Assets/images/memory-card.gif" Lazy-load alt="Logo" width="80" height="75" class="d-inline-block align-text-top">
                <span>
                    Iris Cardmaker
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center ">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
            <a class="nav-link" href="index.php"><button class="btn btn-outline-primary"><span>&#43;</span>Flashcard</button></a>
            <a href="login.php"><button type="button" class="btn btn-outline-success">Login</button></a>
            <a href="register.php"><button type="button" class="btn btn-outline-danger">Register</button></a>
        </div>

    </nav>


    <div class="banner">
        <div class="container-banner">
            <div class="text">Quick Learning, Lasting Knowledge</div>
            <div class="cards">
                <div class="card">
                    <div class="card-inner">
                    <div class="img"><img src="../../Assets/images/khiem.png" alt=""></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-inner">
                    <div class="img"><img src="../../Assets/images/fugue.png" alt=""></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-inner">
                        <div class="img"><img src="../../Assets/images/ben.jpg" alt=""></div>
                    </div>
                </div>
                <div class="ellipse"></div> <!-- Hình tròn dẹp -->
                <div class="ellipse-small"></div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
        <script src="../../Assets/js/guest" defer></script>

</body>

</html>