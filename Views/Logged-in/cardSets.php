<?php
session_start();
if(!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] === false ){
    //! chưa đăng nhập
    header('Location: http://localhost/PHP-PJ/Views/Guest/login.php');
}

require_once '../../Config/database.php';
spl_autoload_register(function($className) {
    require_once "../../App/Models/$className.php";
});


if(!empty($_POST['deck-name']) && !empty($_POST['term']) && !empty($_POST['definition'])) {
    $cardModel = new Card();
    $term = $_POST['term'];
    $definition = $_POST['definition'];
    $deckName = $_POST['deck-name'];
    if($cardModel->create( $_SESSION['idu'], $deckName, $term,$definition)) {
        header("Location:http://localhost/PHP-PJ/Views/Logged-in/librarySets.php");
    }
}

// ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/css/cardCreate.css">
    <title>Form Example</title>

</head>

<body style="background-color: #f8f9fc;">

    <div class="container-cardSets">

        <!-- fix-top -->
        <div class="nav-form d-flex justify-content-between align-items-center">
            <h3>Create a new flashcard set</h3>
            <!-- button submit form below -->
             <div>
             <button type="submit" form="dynamic-form" value="submit" class="btn btn-outline-secondary">Create</button>
             <a  href="home.php" class="btn btn-outline-danger">
                <i class="bi bi-x"></i>
            </a>
             </div>
           
        
        </div>

        <!-- form create card -->
        <div class="container-form">
            <form action="cardSets.php" method="POST" id="dynamic-form">
                <input type="text" name="deck-name" id="deck-name" class="form-control" placeholder="Enter title, like 'nihongo - Chapter 25' " required >

                <!-- Form Block 1 -->
                <div class="form-block">
                    <div class="btn-wrapper">
                        <input type="button" class="delete-btn" name="delete[]" onclick="deleteBlock(this)" value="X">
                    </div>
                    <div class="form-header">1</div>
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label" for="term">Term</label>

                            <input type="text" id="term_1" name="term[]" class="form-control"  placeholder="Enter term">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="definition">Definition</label>

                            <input type="text" id="definition_1" name="definition[]" class="form-control"  placeholder="Enter definition">

                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div class="image-box w-100">Image</div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Add new card Button -->
            <div class="text-center">
                <button type="button" class="btn btn-primary" onclick="addNewCard()">Add a card</button>
            </div>

        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript -->
    <script src="../../Assets/js/jsCardSets.js">

    </script>

</body>

</html>