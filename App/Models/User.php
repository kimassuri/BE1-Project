<?php

class User extends Database {
    function register ($username, $password, $gmail) {
        // tạo câu truy vấn
        $sql = parent::$connection->prepare('INSERT INTO `users`( `username`, `password`, `gmail`) VALUES (?,?,?)');

        // băm mật khẩu
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql->bind_param('sss', $username, $password,$gmail );

        // thực hiện truy vấn
        return $sql->execute();
    }

    function login($gmail, $password) {
        $sql = parent::$connection->prepare(
            'SELECT * FROM `users` WHERE gmail = ? ');
        $sql->bind_param('s', $gmail);

        $user = parent::select($sql); // truy xuất thành công sẽ nhận được user đã tìm thấy

        if(count($user) > 0) { // nếu user tồn tại
            if(password_verify(($password), $user[0]['password'])) // nếu password trùng
            return $user[0]; // trả về user
        }
        return false;
    }


}