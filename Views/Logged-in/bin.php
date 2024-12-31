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

$folderModel = new folder();
$decks = new Deck();
if (isset($_POST['namefolder']) && isset(($_SESSION['idu']))) {
    $folderModel->create($_SESSION['idu'], $_POST['namefolder']);
    $decks->all($_SESSION['idu']);
}

$folders = $folderModel->all($_SESSION['idu']);

if (isset($_POST['deleteDeckId'])) {
    $deckId = $_POST['deleteDeckId'];
    $decks->delete($deckId); // Gọi hàm delete từ model Deck
    header('Location: bin.php'); // Reload lại trang sau khi xóa
    exit();
}

// Lấy danh sách deck trong thùng rác
$deletedDecks = $decks->getDeletedDecks($_SESSION['idu']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteAll'])) {
        // Xóa tất cả các deck trong thùng rác
        $decks->deleteAllInTrash($_SESSION['idu']);
        // Sau khi xóa tất cả, chuyển hướng đến thùng rác để cập nhật giao diện
        header("Location: bin.php");
        exit();
    }

    if (isset($_POST['restoreDeckId'])) {
        // Khôi phục deck
        $deckId = $_POST['restoreDeckId'];
        $decks->restore($deckId);
    } elseif (isset($_POST['deleteDeckId'])) {
        // Xóa hoàn toàn deck
        $deckId = $_POST['deleteDeckId'];
        $decks->deleteForever($deckId);
    }

    // Sau khi xử lý, reload lại trang để cập nhật danh sách
    header("Location:http://localhost/PHP-PJ/Views/Logged-in/librarySets.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/css/base-layout.css">
    <link rel="stylesheet" href="../../Assets/css/components.css">
    <link rel="stylesheet" href="../../Assets/css/libraryCard.css">
    <title>Folder sets</title>
</head>



<body>
    <div class="detail overlay" id="alertOverlay"></div>
    <div class="alert-container hidden">
        <div class="alert">
            <div class="title">
                <h2>Add to folder</h2>

                <i class="bi bi-x-lg close-btn"></i>
            </div>

            <form action="libraryFolder.php" method="post" class="formCreate">
                <input type="text" name="namefolder" placeholder="Name folder" class="form-control">
                <button type="submit" class="text-sm-end btn btn-outline-primary">Create</button>
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
                <span class="menu-text">deleted</span>
            </a>
        </div>


    </div>

    <div class="container-user d-flex flex-column ;">

        <!-- search - creat -->
        <div class="content">

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
                        <a class="dropdown-item " href="cardSets.php"><i class="bi bi-file-richtext"></i>Flashcard set</a>
                    </li>
                    <li><a class="dropdown-item" href="folderSets.php" id="addFolderButton"><i class="bi bi-folder"></i>Folder</a></li>
                </ul>
                <!-- user avatar -->
                <p style="display: inline;">Hello <?php echo $_SESSION["username"] ?></p>
                <img src="../../Assets/images/memory-card.gif" class="avatar" alt="avatar">
                <!-- logout -->
                <a href="logout.php"><i class="bi bi-box-arrow-right btn btn-outline-danger btn-css"></i></a>

            </div>
        </div>


        <!-- main content -->
        <div class="library">
            <h4>Your deck deleted</h4>
            <!-- Navigation Bar -->
            <div class="container-library g-0">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Flashcard deleted</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="binFolder.php">Folders</a>
                    </li>

                </ul>

                <form method="post" onsubmit="return confirm('Are you sure you want to delete all decks?');" style="display:inline;">
                    <button type="submit" name="deleteAll" class="btn btn-danger btn-sm">Delete All</button>
                </form>

            </div>
            <hr class="my-2">

            <div class="container mt-5">
                    <?php foreach ($deletedDecks as $deck) : ?>
                        <div class="block-item">
                            <div class="grid-container">
                                <div class="grid-item"><?php echo $deck['name']; ?></div>
                                <div class="grid-item"><?php echo $deck['size']; ?> Term</div>
                                <!-- Nút Khôi phục -->
                                <form method="post" style="display:inline;" class="grid-item">
                                    <input type="hidden" name="restoreDeckId" value="<?php echo $deck['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                </form>
                                <!-- Nút Xóa Hoàn Toàn -->
                                <form method="post" style="display:inline;"class="grid-item" >
                                    <input type="hidden" name="deleteDeckId" value="<?php echo $deck['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete Forever</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Assets/js/components.js"></script>
    <script src="../../Assets/js/jsFolderSets.js"></script>

</body>