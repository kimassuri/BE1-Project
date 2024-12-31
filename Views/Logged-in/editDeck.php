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

$deckModel = new Deck();
$cardModel = new Card();
$deckID;

// lấy tất cả các thẻ
if(isset($_GET['d'])) {
    $deckID = $_GET['d'];
}   

// lưu các thẻ đã được chỉnh sửa + tạo + xóa
if(isset($_POST['btn-save']) && !empty($_POST['btn-save'])) {
    $deckID = $_POST['btn-save'];
    // Danh sách xóa thì danh sách chỉnh sửa + thêm cardIDs

    // nhận danh sách xóa
    if (!empty($_POST['deleteList'])) {
        $deleteList = $_POST['deleteList'];
        $deleteList = json_decode($deleteList);
        if($cardModel->delete($deleteList, $deckID)) {
            if(empty($_POST['editedCards'])) 
                header("Location:http://localhost/PHP-PJ/Views/Logged-in/home.php");
        }
    }
    // có danh sách cần sửa gửi lên
    if(!empty($_POST['editedCards'])) {

        if(!empty($_POST['term']) && !empty($_POST['definition']) && !empty($_POST['deck-size']) && !empty($_POST['deck-name'])) { 
            // xác nhận danh sách có chỉnh sửa hoặc thêm vào
                if($cardModel->update($deckID,$_POST['term'],$_POST['definition'], $_POST['deck-name'], $_POST['deck-size'], $_POST['editedCards']))
                header("Location:http://localhost/PHP-PJ/Views/Logged-in/home.php");
        }
    }
    
}
$deck = $deckModel->detail($deckID);
$cards = $cardModel->all($deckID);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/css/cardCreate.css">
    <title>Edit cards</title>

</head>

<body style="background-color: #f8f9fc;">

    <div class="container-cardSets">

        <!-- fix-top -->
        <div class="nav-form d-flex justify-content-between align-items-center">
            <h3>Create a new flashcard set</h3>
            <!-- button submit form below -->
            <div>
                <button type="submit" form="dynamic-form" onclick="submitForm()" value="<?php echo $deck['id'] ?>" name="btn-save" 
                class="btn btn-outline-secondary">Save</button>
            </div>
        
        </div>

        <!-- form create card -->
        <div class="container-form">
            <form action="editDeck.php" method="POST" id="dynamic-form" >
                <input type="text" name="deck-name" id="deck-name" class="form-control" placeholder="Enter title, like 'nihongo - Chapter 25'" value="<?php echo $deck['name']  ?>">
                <input type="hidden" name="deck-size" value="<?= $deck['size'] ?>">
                <!-- Form Block -->
                <?php
                $i = 1;
                foreach ($cards as $card) : ?>

                <div class="form-block" data-card-id="<?= $card['id'] ?>" 
                                        data-original ='{"term": "<?= $card['term'] ?>", "definition": "<?= $card['definition'] ?>"}'>

                    <div class="btn-wrapper">
                        <input type="button" class="delete-btn" onclick="deleteBlock(this)" value="X">
                    </div>

                    <div class="form-header"></div>
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label" for="term">Term</label>

                            <input type="text" name="term[]" class="form-control term-input" placeholder="Enter term" 
                                value="<?php echo $card['term'] ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="definition">Definition</label>

                            <input type="text" name="definition[]" class="form-control definition-input" placeholder="Enter definition"  
                                 value="<?= $card['definition'] ?>">

                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <div class="image-box w-100">Image</div>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
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
    <script src="../../Assets/js/cardEdit.js">

    </script>

</body>

</html>