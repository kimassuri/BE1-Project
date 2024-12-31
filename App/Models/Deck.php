<?php
class Deck extends Database
{
    function all($userID)
    {
        $sql = parent::$connection->prepare("SELECT decks.*, users.username AS user_name
                                            FROM `decks`
                                            JOIN `users` ON decks.user_id = users.id
                                            WHERE `decks`.`is_deleted` = 0 AND users.id =?");
        $sql->bind_param("i", $userID);
        return parent::select($sql);
    }

    function allDecks()
    {
        $sql = parent::$connection->prepare(
            "SELECT users.username, decks.size, decks.name, decks.favorites, decks.id 
					FROM `decks`
                    JOIN users ON users.id = decks.user_id
 					ORDER BY decks.favorites DESC"
        );
        return parent::select($sql);
    }

    //
    function detail($id)
    {
        $sql = parent::$connection->prepare(
            "SELECT users.username, decks.*
                            FROM decks
                            JOIN users ON users.id = decks.user_id
                            WHERE decks.id = ?"
        );
        $sql->bind_param("i", $id);
        return parent::select($sql)[0];
    }

    function like($deckID)
    {
        $sql = parent::$connection->prepare(
            "UPDATE `decks` SET `favorites`= `favorites` + 1 WHERE `id` = ?"
        );
        $sql->bind_param("i", $deckID);
        return $sql->execute();
    }


    function findIds($deckIDs)
    {
        // lấy các deck.id có trong deckIDs
        // tạo chuỗi kiểu ?,?,? bằng với số lượng id
        $insertPlace = str_repeat("?,", count($deckIDs) - 1) . "?";
        // tạo chuỗi iii x2 
        $insertType = str_repeat('i', count($deckIDs) * 2);

        // tạo câu truy vấn
        $sql = parent::$connection->prepare(
            "SELECT
                    decks.id, decks.name, decks.size, decks.favorites, users.username
                    FROM `decks`
                    JOIN users ON users.id = decks.user_id
                    WHERE decks.id IN ( $insertPlace)
                    ORDER BY FIELD(decks.id, $insertPlace) DESC"
        );
        $sql->bind_param($insertType, ...$deckIDs, ...$deckIDs);
        return parent::select($sql);
    }

    function delete($deckID)
    {
        $sql = parent::$connection->prepare("DELETE FROM `decks` WHERE `id` =?");
        $sql->bind_param("i", $deckID);
        return $sql->execute();
    }

    // Khôi phục deck
    function restore($deckID)
    {
        $sql = parent::$connection->prepare("UPDATE `decks` SET `is_deleted` = 0 WHERE `id` = ?");
        $sql->bind_param("i", $deckID);
        return $sql->execute();
    }

    // Xóa hoàn toàn deck
    function deleteForever($deckID)
    {
        $sql = parent::$connection->prepare("DELETE FROM `decks` WHERE `id` = ?");
        $sql->bind_param("i", $deckID);
        if ($sql->execute()) {
            $sql->execute();
            // Reset lại AUTO_INCREMENT nếu muốn ID tiếp theo bắt đầu từ 1
            $this->resetIds("cards");
            $this->resetIds("decks");
        }
        return true;
    }
    function moveToTrash($deckID)
    {
        $sql = parent::$connection->prepare("UPDATE `decks` SET `is_deleted` = 1 WHERE `id` = ?");
        $sql->bind_param("i", $deckID);
        return $sql->execute();
    }

    function getDeletedDecks($userID)
    {
        $sql = parent::$connection->prepare("SELECT decks.*, users.username AS user_name
        FROM `decks`
        JOIN `users` ON decks.user_id = users.id
        WHERE `decks`.`is_deleted` = 1 AND users.id =?");
        $sql->bind_param("i", $userID);
        return parent::select($sql);
    }

    function resetIds($tableName)
    {
        $sql = "SET @num := 0; UPDATE $tableName SET id = @num := (@num+1); ALTER TABLE `$tableName` AUTO_INCREMENT = 1;";

        if (parent::$connection->multi_query($sql)) {
            do {
                // Tiến tới truy vấn tiếp theo, nếu có
                if ($result = parent::$connection->store_result()) {
                    $result->free();
                }
            } while (parent::$connection->next_result());

            return true;
        }

        return false; // Nếu có lỗi xảy ra
    }
    public function deleteAllInTrash($userID)
    {
        // Xóa tất cả các deck trong thùng rác của người dùng
        $sql = parent::$connection->prepare("DELETE FROM `decks` WHERE `user_id` = ? AND `is_deleted` = 1");
        $sql->bind_param("i", $userID);
        return $sql->execute(); // Thực thi câu lệnh SQL
    }

    public function searchByName($userID, $name)
    {
        // Tìm kiếm bộ sưu tập theo tên (case-insensitive)
        $sql = parent::$connection->prepare("
            SELECT 
    decks.*, 
    users.username AS user_name
FROM `decks`
JOIN `users` ON decks.user_id = users.id
LEFT JOIN `cards` ON decks.id = cards.deck_id
WHERE `decks`.`is_deleted` = 0 
AND `decks`.`name` LIKE '%English%'
GROUP BY decks.id, users.username
        ");
        $searchTerm = '%' . $name . '%';
        $sql->bind_param("is", $userID, $searchTerm);
        return parent::select($sql);
    }

    // Phương thức tìm kiếm deck theo từ khóa
    public function searchDecks($userId, $searchQuery)
    {
        $sql = parent::$connection->prepare("
            SELECT decks.*, users.username AS user_name
            FROM `decks`
            JOIN `users` ON decks.user_id = users.id
            WHERE decks.is_deleted = 0 AND 
                  (decks.name LIKE ? OR users.username LIKE ?)
                  AND decks.user_id = ?
        ");
        $likeQuery = '%' . $searchQuery . '%';
        $sql->bind_param("ssi", $likeQuery, $likeQuery, $userId);
        return parent::select($sql);
    }
}
