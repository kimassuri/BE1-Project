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
$deckModel = new Deck();
$folderModel = new Folder();
$recentDecks = [];
// Lấy danh sách tìm kiếm
$searchResults = [];
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchQuery = trim($_GET['search']);
    $searchResults = $deckModel->searchDecks($_SESSION['idu'], $searchQuery);
}

// các deck đã xem gần đây
if (isset($_COOKIE['recentView'])) {
    $recentView = json_decode($_COOKIE['recentView']);
    $recentDecks = $deckModel->findIds($recentView);
}
$decks = $deckModel->allDecks();
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
    <link rel="stylesheet" href="../../Assets/css/hUser.css">

    <title>Home</title>
</head>



<body>
    <div class="detail overlay" id="alertOverlay"></div>
    <div class="alert-container hidden">
        <div class="alert">
            <div class="title">
                <h2> Create a new folder</h2>
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
                        <a class="dropdown-item" href="#"><i class="bi bi-folder"></i> <?php echo $folder['nameFolder'] ?></a>
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
        <div class="content">

            <!-- search -->
            <form class="d-flex" role="search" method="GET" action="home.php">
                <input class="form-control me-2" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <!-- Search Results -->
            <?php if (!empty($searchResults)) : ?>
                <div class="search-results-container">
                    <h4>Search Results</h4>
                    <div class="row gap-0 row-gap-3">
                        <?php foreach ($searchResults as $deck) : ?>
                            <div class="col-xl ">
                                <a href="detailDeck.php?d= <?php echo $deck["id"] ?>" class="text-decoration-none">
                                    <div class="card" style="width: 300px;">
                                        <img src="../../Assets/images/flash-cards.png" style="width: 30px;" alt="">
                                        <div class="card-body">
                                            <h6 class="card-title"> <?php echo $deck['name'] ?></h6>
                                            <p class="card-text">
                                                <?php echo $deck['size'] ?> Term . <?php echo $deck['user_name'] == $_SESSION['username'] ?  "By you" :  $deck['user_name']  ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="create-logout">
                <!-- create card  -->
                <button class="btn btn-outline-primary me-2 dropdown-toggle btn-css" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span>&#43;</span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item " href="cardSets.php"><i class="bi bi-file-richtext"></i> Flashcard set</a>
                    </li>
                    <li><a class="dropdown-item" id="addFolderButton" href="#"><i class="bi bi-folder"></i> Folder</a></li>
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

            <div class="recent-container d-flex flex-column flex-nowrap">
                <h4>Recents</h4>
                <div class="content-recent d-flex justify-content-between" style="width: 100%;">

                    <div class="row gap-0 row-gap-3">
                        <?php foreach ($recentDecks as $deck) : ?>
                            <div class="col-xl item">
                                <div class="card " style="width: 300px;">
                                    <img src="../../Assets/images/flash-cards.png" style="width: 30px;" alt="">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo $deck['name'] ?></h6>
                                        <p class="card-text"><?php echo $deck['size'] ?> Term .
                                            <?php echo  $deck['username'] == $_SESSION['username'] ?  "By you" :  $deck['username']  ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>

                    </div>

                </div>
            </div>

            <hr class="my-2 mt-2">

            <div class="pp-container d-flex flex-column flex-nowrap">
                <h4>Popular</h4>
                <div class="content-popular d-flex justify-content-between" style="width: 100%;">

                    <div class="row gap-0 row-gap-3">
                        <?php foreach ($decks as $deck) : ?>
                            <div class="col-xl ">
                                <a href="detailDeck.php?d= <?php echo $deck["id"] ?>" class="text-decoration-none">

                                    <div class="card" style="width: 300px;">
                                        <img src="../../Assets/images/flash-cards.png" style="width: 30px;" alt="">
                                        <div class="card-body">
                                            <h6 class="card-title"> <?php echo $deck['name'] ?></h6>
                                            <p class="card-text">
                                                <?php echo $deck['size'] ?> Term . <?php echo  $deck['username'] == $_SESSION['username'] ?  "By you" :  $deck['username']  ?></p>
                                        </div>
                                    </div>
                                </a>

                            </div>
                        <?php endforeach ?>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Assets/js/components.js"></script>

</body>

</html>