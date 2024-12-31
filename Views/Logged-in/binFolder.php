<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] === false) {
    header('Location: http://localhost/PHP-PJ/Views/Guest/login.php');
}

require_once '../../Config/database.php';
spl_autoload_register(function ($className) {
    require_once "../../App/Models/$className.php";
});

$folderModel = new Folder();
$decks = new Deck();

// Khởi tạo biến $folders với giá trị mặc định là một mảng rỗng
$folders = [];


$folders = $folderModel->getDeletedFoldersByFolderId($_SESSION['idu']); // Lấy các folder đã bị xóa trong folder đó

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra nếu người dùng muốn xóa tất cả folder
    if (isset($_POST['deleteAllFolders'])) {
        if($folderModel->deleteAllInTrash($_SESSION['idu']))  // Xóa tất cả folder trong thùng rác
        header("Location:http://localhost/PHP-PJ/Views/Logged-in/binFolder.php");

    }

    // Các hành động khác (khôi phục và xóa vĩnh viễn folder)
    if (isset($_POST['restoreFolderId'])) {
        $folderId = $_POST['restoreFolderId'];
        if($folderModel->restore($folderId))  // Khôi phục folder
        header("Location:http://localhost/PHP-PJ/Views/Logged-in/binFolder.php");

    }

    if (isset($_POST['deleteFolderId'])) {
        $folderId = $_POST['deleteFolderId'];
        if($folderModel->deleteForever($folderId))  // Xóa vĩnh viễn folder
        header("Location:http://localhost/PHP-PJ/Views/Logged-in/binFolder.php");
    }
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
    <link rel="stylesheet" href="../../Assets/css/libraryFolder.css">
    <title>Folder sets</title>
</head>



<body>
    <div class="detail overlay" id="alertOverlay"></div>
    <div class="alert-container hidden">
        <div class="alert">
            <div class="title">
                <h2>Create a new folder</h2>

                <i class="bi bi-x-lg close-btn"></i>
            </div>

            <form action="libraryFolder.php" method="post" class="formCreate">
                <input type="text" name="namefolder" placeholder="Name folder" class="form-control">
                <button type="submit" class="text-sm-end btn btn-outline-primary" name="btn-create">Create</button>
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
                    <li><a class="dropdown-item" href="#" id="addFolderButton"><i class="bi bi-folder"></i>Folder</a></li>
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
            <h4>Your folder</h4>
            <!-- Navigation Bar -->
            <div class="container-library g-0">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="bin.php">Flashcard sets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Folders</a>
                    </li>

                </ul>

            </div>
            <hr class="my-2">

            <div class="nav-2 d-flex justify-content-between align-items-center">
                <p class="m-0">Recent</p>
                <form class="d-flex search-bar ">
                    <!-- Search Input -->
                    <input type="text" class="form-control mx-1 " placeholder="Search flashcards" aria-label="Search">

                    <!-- Search Button -->
                    <button class="btn btn-outline-secondary btn-search" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <div class="container-content d-flex flex-column mt-4">
                <div class="block-folder">
                    <!-- Xóa hoàn toàn folder -->
                    <?php if (!empty($folders)): ?>
                        <?php foreach ($folders as $folder) : ?>
                            <div class="item">
                            <div class="folder-title"> <?php echo $folder['nameFolder'] ?> </div>

                                    <!-- Khôi phục folder -->
                                    <form method="post" style="display:inline;" action="binFolder.php">
                                        <input type="hidden" name="restoreFolderId" value="<?php echo $folder['id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm" name="restore-btn">Restore</button>
                                    </form>
                                    <!-- Xóa hoàn toàn folder -->
                                    <form method="post" style="display:inline;"  action="binFolder.php">
                                        <input type="hidden" name="deleteFolderId" value="<?php echo $folder['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete Forever</button>
                                    </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No deleted folders available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Assets/js/components.js"></script>
    <script src="../../Assets/js/jsFolderSets.js"></script>

</body>

</html>