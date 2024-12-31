<?php 
session_start();
session_destroy();
header("Location:http://localhost/PHP-PJ/Views/Guest/index.php");