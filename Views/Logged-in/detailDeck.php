<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] === false) {
    //! chưa đăng nhập
    header('Location: http://localhost/PHP-PJ/Views/Guest/login.php');
}
require_once '../../Config/database.php';
spl_autoload_register(function ($className) {
    require_once "../../App/Models/$className.php";
});

$deckID = null;
$cardModel = new Card();
$deckModel = new Deck();
$folderModel = new Folder();

$notifi = false;

if (isset($_GET['d'])) {
    $deckID = $_GET['d'];
};

if (isset($_POST["btn-like"])) {
    $deckID = $_POST["btn-like"];
    // lưu các deck đã like tại thời điểm cookie còn hiệu lực
    $decksLiked = [];

    // nếu đã like các deck trước đó
    if (isset($_COOKIE['decksLiked'])) {
        // lấy tất cả các deck đã like bỏ vào
        $decksLiked = json_decode($_COOKIE['decksLiked']);


        // nếu sản phẩm đang chọn chưa được like
        if (in_array($deckID, $decksLiked) === false) {
            $deckModel->like($deckID);
            array_push($decksLiked, $deckID);
            setcookie('decksLiked', json_encode($decksLiked), time() + 1000);
        }
    
    }
    // like lần đầu tiên -> chưa like deck nào
    else {
        $deckModel->like($deckID);
        array_push($decksLiked, $deckID);
        setcookie('decksLiked', json_encode($decksLiked), time() + 1000);
    }
}

// recent decks
$recentView = [];
if (isset($_COOKIE['recentView'])) {
    $recentView = json_decode($_COOKIE['recentView']);

    if (in_array($deckID, $recentView) === true) {
        // lấy phần tử trùng sau đó reset lại thứ phần tử của mảng
        $recentView = array_values(array_diff($recentView, [$deckID]));
    }
    //  vẫn thêm vào phần tử vào (trình tự sẽ được sort bên truy vấn), giữ tối đa 5 deck
    if (count($recentView) === 5) {
        array_shift($recentView);
    }
    array_push($recentView, $deckID);
} else {
    array_push($recentView, $deckID);
}


// deck_folder
if (isset($_GET['d']) && isset($_GET['f']) && isset($_GET['t'])) {
        $d = $_GET['d'];
        $f = $_GET['f'];
    if ($folderModel->add($_GET['d'], $_GET['f'])) {
        setcookie('notifi', "green", time()+1);
        setcookie('message','Added successfully!', time()+1 );
        header("Location:http://localhost/PHP-PJ/Views/Logged-in/detailDeck?d=$d&f=$f");
    }else{
        setcookie('notifi', "red", time()+1);
        setcookie('message','Failed to add!', time()+1 );
        header("Location:http://localhost/PHP-PJ/Views/Logged-in/detailDeck?d=$d&f=$f");
    }
}


if(isset($_COOKIE['notifi']) && $_COOKIE['notifi'] == "green") {
    echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast(true, '" .  $_COOKIE['message'] . "'); }); </script>";
}else if(isset($_COOKIE['notifi']) && $_COOKIE['notifi'] == "red") {
    echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast(false, '" . $_COOKIE['message'] . "'); }); </script>";
}

setcookie('recentView', json_encode($recentView), time() + 10);


$cards = $cardModel->all($deckID);
$deckInfo = $deckModel->detail($deckID);
$folders = $folderModel->all($_SESSION['idu']);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/css/base-layout.css">
    <link rel="stylesheet" href="../../Assets/css/detailDeck.css">

    
    <title>Detail</title>
</head>
    <div class="detail overlay" id="alertOverlay"></div>
    
    <div id='toast'>
        <div class="toast-content">
        <i class="bi bi-x-lg icon-toast"></i>
            <div class="message">
                <span></span>
            </div>
        </div>
    </div>

    <div class="alert-container hidden">
        <div class="alert">
            <div class="title">
                <h2>Add to folder</h2>

                <i class="bi bi-x-lg close-btn"></i>
            </div>

            <form action="libraryFolder.php" method="post" class="formCreate">
                <input type="text" name="namefolder" placeholder="Name folder" class="form-control">
                <button type="submit" class="text-sm-end btn btn-outline-primary" name="btn-create" value="true">Create</button>
            </form>

            <div class="create-folder">
                <button type="button" class="btn btn-secondary" id="createFolderButton"> + Create a new folder</button>
                <div class="folder-user">
                    <?php foreach ($folders as $folder) : ?>
                        <a class="dropdown-item folder"
                            href="detailDeck?d=<?php echo $deckInfo['id'] ?>&f=<?php echo $folder['id'] ?>&t=o">
                            <i class="bi bi-folder"></i> <?php echo $folder['nameFolder'] ?></a>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Toggle Button -->
        <div class="menu-toggle menu-icon py-3">
            <i class="bi bi-list " style="cursor: pointer;" onclick="toggleSidebar()"></i>
        </div>

        <!-- Menu Items -->
        <div class="items ">
            <a href="home.php" class="menu-item">
                <i class="bi bi-house"></i>
                <span class="menu-text">Home</span>
            </a>
            <a href="librarySets.php" class="menu-item">
                <i class="bi bi-folder"></i>
                <span class="menu-text">Your Library</span>
            </a>
            <a href="bin.php" class="menu-item">
                <i class="bi bi-trash"></i>
                <span class="menu-text">Your deleted</span>
            </a>
        </div>

    </div>
    <div class="container-user d-flex flex-column ;">

        <!-- search - creat -->
        <div class="content p-0 d-flex justify-content-between ">

            <!-- search -->
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <div class="create-logout">
                <!-- create card  -->
                <button class="btn btn-outline-primary me-2 dropdown-toggle btn-css" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span>&#43;</span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="cardSets.php"><i class="bi bi-file-richtext"></i> Flashcard set</a>
                    </li>
                    <li><a class="dropdown-item add-folder-btn" href="#"><i class="bi bi-folder"></i> Folder</a></li>
                </ul>

                <!-- user avatar -->
                <p style="display: inline;">Hello <?php echo $_SESSION["username"] ?></p>
                <img src="../../Assets/images/memory-card.gif" class="avatar" alt="avatar">

                <!-- Logout -->
                <a href="logout.php"><i class="bi bi-box-arrow-right btn btn-outline-danger btn-css"></i></a>


            </div>

        </div>


        <!-- main content -->
        <div class="home-container ">

            <div class="deck">
                <div class="card-cotainer">

                <div class="stack">
                    <?php foreach ($cards as $card) : ?>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-front">
                                    <p><?php echo $card["term"] ?></p>
                                </div>
                                <div class="card-back">
                                    <p><?php echo $card["definition"] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                    <div class="controls d-flex justify-content-between ">
                        <div class="btn btn-outline-danger" id="prev">Prev</div>
                        <div class="btn btn-outline-success" id="next">Next</div>
                    </div>
                </div>


                <div class="info">
                    <h1><?php echo $deckInfo['name'] ?></h1>
                        <a href="user.php?u=" ?>
                            <p class="user-select-none">Created by : <?php echo  $deckInfo['username'] == $_SESSION['username'] ?  "By you" :  $deckInfo['username']  ?></p>
                        </a>

                    <div class="like">
                        <form action="detailDeck.php" method="post">
                            <button type="submit" class="btn btn-outline-danger" 
                                name="btn-like" value="<?php echo $deckInfo['id'] ?>">
                                <i class="bi bi-bookmark-heart"></i> <?php echo $deckInfo['favorites'] ?></button>
                        </form>
                    </div>
                    <div class="function">
                        <div class="btn btn-outline-success add" id="addFolderButton">
                            <i class="bi bi-folder-plus"> <span>Add folder</span> </i>
                        </div>

                        <div class="btn btn-outline-secondary test">
                        <i class="bi bi-caret-right-fill"> <span>Test</span> </i>
                        </div>
                        <?php echo  $deckInfo['username'] == $_SESSION['username'] ? 
                            '<div class="btn btn-primary">
                                <a href="editDeck.php?d=' . $deckInfo["id"] . '" class="custom-link">
                                    <i class="bi bi-files-alt"> <span>Edit</span></i>
                                </a>
                            </div>' 
                            : '';
                        ?>
                    </div>
                </div>

            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
        <script src="../../Assets/js/components.js"></script>
        <script src="../../Assets/js/jsDetailDeck.js"></script>
    
        


</body>

</html>