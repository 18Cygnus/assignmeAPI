<?php
require_once 'connection/connection.php'; // Memanggil file connection.php

if(!empty($_POST['Email']) && !empty($_POST['Password'])){
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $result = array();

    // Menggunakan kelas Connect untuk mendapatkan koneksi
    $connection = new Connect();
    $con = $connection->dbConn();

    if ($con) {
        $sql = "SELECT * FROM users WHERE Email = ?";
        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($res) != 0) {
            $row = mysqli_fetch_assoc($res);
            if($email == $row['Email'] && password_verify($password, $row['Password'])){
                try {
                    $apiKey = bin2hex(random_bytes(23));
                } catch (Exception $e) {
                    $apiKey = bin2hex(uniqid($email, true));
                }
                $sqlUpdate = "UPDATE users SET apiKey = ? WHERE Email = ?";
                $stmtUpdate = mysqli_prepare($con, $sqlUpdate);
                mysqli_stmt_bind_param($stmtUpdate, "ss", $apiKey, $email);

                if(mysqli_stmt_execute($stmtUpdate)){
                    $result = array(
                        "status"=>"success",
                        "message"=>"Login successful",
                        "Email"=>$row['Email'],
                        "Password"=>$row['Password'],
                        "apiKey"=> $apiKey
                    );
                } else {
                    $result = array("status"=>"Failed1", "message"=>"Login failed");
                }
            } else {
                $result = array("status"=>"Failed2", "message"=>"Retry with correct email and password");
            }
        } else {
            $result = array("status"=>"Failed3", "message"=>"Retry with correct email and password");
        }
    } else {
        $result = array("status"=>"Failed4", "message"=>"Database connection failed");
    }
} else {
    $result = array("status"=>"Failed5", "message"=>"All fields are required");
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>

