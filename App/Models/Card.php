<?php
class Card extends Database {

    public function create($idu,$deckName,$terms, $definitions) {
        
        // var_dump($terms);
        // var_dump($definitions);

        // * ? là (?,?) -> lấy size terms đại diện cho số lần lặp  (?, ?)
        $sizeTerms = count($terms);


        //TODO Tạo chuỗi kiểu (?, ?), (?, ?), ....
        $insertPlace =  str_repeat("(?, ?, ?),",  $sizeTerms -1 ). "(?, ?, ?)";

        //TODO Tạo chuỗi s |  ? = ss
        $insertType = str_repeat('sss', $sizeTerms  );
        
        // var_dump($term_defi);

        // TODO query deck
        $sql = parent::$connection->prepare("INSERT INTO `decks` (`name`, `size`, `user_id`) VALUES (?, ?, ?)");
        $sql->bind_param("ssi", $deckName, $sizeTerms, $idu);
        $sql->execute();

        $deckId = parent::$connection->insert_id;

        //TODO gom deckID, terms và definitions thành 1 mảng 
        $deck_term_defi= [];
        for ($i = 0; $i < $sizeTerms; $i++) {
            array_push($deck_term_defi, $deckId ,$terms[$i], $definitions[$i]);
        }

        //TODO query 
        $sql = parent::$connection->prepare("INSERT INTO `cards` ( `deck_id`, `term`, `definition`) VALUES $insertPlace");
        $sql->bind_param($insertType,...$deck_term_defi);
    
        return $sql->execute();
    }

    function update ($deckID ,$terms, $definitions, $deckName, $deckSizeOrigin, $editedCards) {
        $editedCards = json_decode($editedCards, true);
        $editedCardSize = count($editedCards);
        $size = count($terms);

        $editedTerms = [];
        $editedDefinitions = [];
        $ids = [];
        $result1 =false;
        $result2 =false;
        // Xử lý các thẻ cần chỉnh sửa
        if ($editedCardSize > 0) {
        for ($i = 0; $i < $editedCardSize; $i++) {
            array_push($editedTerms, $editedCards[$i]['id'], $editedCards[$i]['term']);
            array_push($editedDefinitions, $editedCards[$i]['id'], $editedCards[$i]['definition']);
            array_push($ids, $editedCards[$i]['id']); // các id card
        }

        $insertType = str_repeat("is", $editedCardSize*2); // 1 thẻ tốn 2x is
        $insertPlaces = str_repeat("WHEN id = ? THEN ? ", $editedCardSize); // 1 thẻ có thể chỉnh term definition
        $idPlaces = implode(",", array_fill(0, count($ids), "?"));
        $idInsertType = str_repeat("i", count($ids));

        $insertType .= $idInsertType;
        
        var_dump($insertPlaces);

        $sql = parent::$connection->prepare("
            UPDATE cards
            SET 
                term = CASE 
                    $insertPlaces 
                ELSE term END,
                definition = CASE 
                    $insertPlaces 
                ELSE definition END
            WHERE id IN ($idPlaces)
                                            ");
        $sql->bind_param($insertType, ...$editedTerms,...$editedDefinitions ,...$ids);
        $result1 = $sql->execute();
        }
        else {
            $result1 = true; // vì không có bản thay đổi trả về true
        }

        var_dump("size ".$size);
        var_dump("size origin ".$deckSizeOrigin);

        if($deckSizeOrigin < $size) // có thẻ cần được khởi tạo
        { 
            $term_definition = [];
            $newCardTerm = array_slice($terms, $deckSizeOrigin);
            $newCardDefinition = array_slice($definitions, $deckSizeOrigin);

            var_dump($newCardTerm);
            
            for ($i = 0; $i < count($newCardTerm); $i++) { 
                array_push($term_definition, $newCardTerm[$i], $newCardDefinition[$i]);   
            }

            $insertPlaces = str_repeat("($deckID,?,?),", count($newCardTerm) - 1) . "($deckID,?,?)";
            $insertType = str_repeat("ss", count($newCardDefinition));

            // Debug
            var_dump($insertPlaces . "  " . $insertType . "  ", $term_definition);
            
            $sql = parent::$connection->prepare("INSERT INTO `cards` (`deck_id`, `term`, `definition`) VALUES $insertPlaces");
            $sql->bind_param($insertType,...$term_definition);

            $check= $sql->execute();
            // cập nhật xong và thành công
            if($check) {
                $sql= parent::$connection->prepare("UPDATE `decks` SET `size`= $size WHERE id =?");
                $sql->bind_param("i", $deckID);
                $result2 = $sql->execute();
            }
        }
        else {
            $result2 = true;
        }
        return $result1 == true && $result2 == true ? true : false ;
            
    }

    function all($id) {
        $sql = parent::$connection->prepare("SELECT * FROM `cards` WHERE cards.deck_id = ?");
        $sql->bind_param("i", $id);
        
        return parent::select($sql);
    }

    function delete($cardIDs, $deckID) {
        // Giải mã JSON thành mảng
        if(empty($cardIDs)) return false;


        var_dump("Chui vô thanh công ". " ". $cardIDs);

        // Tạo chuỗi dấu hỏi chấm "?" cho mỗi phần tử trong mảng
        $insertPlace = implode(',', array_fill(0, count($cardIDs), '?'));
    
        // Tạo chuỗi 'i' cho việc bind tham số integer
        $insertTypes = str_repeat('i', count($cardIDs));
        
        var_dump(value: $insertPlace . $insertTypes);


        // Câu lệnh SQL DELETE
        $sql = parent::$connection->prepare("DELETE FROM `cards` WHERE id IN ($insertPlace)");
        $sql->bind_param($insertTypes, ...$cardIDs);
        $sql->execute();
    
        // Tính số lượng thẻ đã xóa
        $countDeleted = count($cardIDs);
    
        // Câu lệnh SQL UPDATE
        $sql = parent::$connection->prepare("UPDATE `decks` SET `size` = `size` - ? WHERE id = ?");
        $sql->bind_param("ii", $countDeleted, $deckID);
        $sql->execute();

        $size = parent::$connection->prepare("SELECT `size` FROM `decks` WHERE id = ?");
        $size->bind_param("i", $deckID);
        $size->execute();
        $result = $size->get_result();
        $row = $result->fetch_assoc();

        if ($row['size'] <= 0) {
            $sql = parent::$connection->prepare("DELETE FROM `decks` WHERE id = ?");
            $sql->bind_param("i", $deckID);
            $sql->execute();
            $this->resetIds("decks");
        }

        // Reset lại AUTO_INCREMENT nếu muốn ID tiếp theo bắt đầu từ 1
        $this->resetIds("cards");

        return true;
    }
    
    function resetIds($tableName) {
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
    
    
}