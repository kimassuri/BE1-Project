<?php 
class Database
{
    static $connection =NULL;

    function __construct() {
        //1 tạo connection
        if(self::$connection == null) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, 3306);
            self::$connection->set_charset('utf8mb4');
        }
    }

    function select($sql) {
        // 3 thực hiện query
        $sql->execute();
        //4 xử lý kết quả trả về
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
