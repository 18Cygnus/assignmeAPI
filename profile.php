<?php
require_once 'connection/connection.php'; // Panggil file connection.php

if(!empty($_POST['Email']) && !empty($_POST['apiKey']) ){
    $email = $_POST['Email'];
    $apiKey = $_POST['apiKey'];

    // Membuat instance dari kelas Connect
    $connection = new Connect();
    $con = $connection->dbConn();
    
    if($con){
        $sql = "select * from users where Email = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($res) != 0){
            $row = mysqli_fetch_assoc($res);
            $result = $row['Username'];
        } else {
            $result = array("status"=>"Failed", "message"=>"Unauthorized access");
        }
    } else {
        $result = array("status"=>"Failed", "message"=>"Database connection failed");
    }
} else {
    $result = array("status"=>"Failed", "message"=>"All fields are required");
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
