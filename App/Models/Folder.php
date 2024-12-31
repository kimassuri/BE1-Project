<?php
class Folder extends Database
{
    function create($idu, $nameFolder)
    {
        // tạo khi thêm deck của người khác nhưng người dùng muốn tạo cái mới
        // chỉ tạo 
        $sql = parent::$connection->prepare("INSERT INTO `folders`( `nameFolder`, `user_id`) VALUES (?, ?)");
        $sql->bind_param("si", $nameFolder, $idu);

        return $sql->execute();
    }


    function all($idu)
    {
        $sql = parent::$connection->prepare("SELECT * FROM `folders` WHERE folders.user_id = ? AND is_deleted = 0");
        $sql->bind_param("i", $idu);
        return parent::select($sql);
    }


    function add($deckID, $folderID) {
        
        $checkExist = parent::$connection->prepare("SELECT 1 FROM deck_folder WHERE deck_id = ? AND folder_id = ?");
        $checkExist->bind_param('ii', $deckID,$folderID);
        $checkExist->execute();
        if( ( $checkExist->get_result())->num_rows >0) {
            return false;
        }
        
        $checkExist->close();
        $sql = parent::$connection->prepare("INSERT INTO `deck_folder`(`deck_id`, `folder_id`) VALUES (?, ?)");
        $sql->bind_param('ii', $deckID, $folderID);
        return $sql->execute();
    }

    function findByF ( $folderID) {
        $sql = parent::$connection->prepare(
                                    "SELECT decks.name, decks.size, users.username FROM `folders`
                                    JOIN deck_folder ON folders.id = deck_folder.folder_id
                                    JOIN decks ON decks.id = deck_folder.deck_id
                                    JOIN users ON users.id = decks.user_id
                                    WHERE folders.id =?");
        $sql->bind_param("i", $folderID);
        if(!empty(parent::select($sql))) {
            return parent::select($sql);
        }
        return false;
    }

    

    public function delete($folderId)
    {
        // Cập nhật trạng thái `is_deleted` thành 1 thay vì xóa vĩnh viễn
        $sql = parent::$connection->prepare("UPDATE `folders` SET `is_deleted` = 1 WHERE `id` = ?");
        $sql->bind_param('i', $folderId);
        return $sql->execute();  // Trả về kết quả
    }

    // Lấy các folder đã xóa của người dùng
    public function getDeletedFoldersByFolderId($userId)
    {
        $sql = parent::$connection->prepare("SELECT * FROM `folders` WHERE folders.user_id = ? AND is_deleted = 1");
        $sql->bind_param("i", $userId);
        return parent::select($sql);
    }

    // Xóa tất cả folder trong thùng rác (soft delete)
    public function deleteAllInTrash($userId)
    {
        $sql = parent::$connection->prepare("DELETE FROM `folders` WHERE `user_id` = ? AND `is_deleted` = 1");
        $sql->bind_param("i", $userId);
        return $sql->execute(); // Thực thi câu lệnh SQL
    }

    // Khôi phục folder từ thùng rác
    public function restore($folderId)
    {
        $sql = parent::$connection->prepare("UPDATE `folders` SET `is_deleted` = 0 WHERE `id` = ?");
        $sql->bind_param("i", $folderId);
        return $sql->execute(); // Thực thi câu lệnh SQL
    }

    // Xóa folder vĩnh viễn
    public function deleteForever($folderId)
    {
        $sql = parent::$connection->prepare("DELETE FROM `folders` WHERE `id` = ?");
        $sql->bind_param("i", $folderId);
        return $sql->execute(); // Thực thi câu lệnh SQL
    }
}
