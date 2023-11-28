<?php
require_once 'connection/connection.php'; // Memanggil file connection.php

if (!empty($_POST['Email']) && !empty($_POST['apiKey'])) {
    $email = $_POST['Email'];
    $apiKey = $_POST['apiKey'];

    // Menggunakan kelas Connect untuk mendapatkan koneksi
    $connection = new Connect();
    $con = $connection->dbConn();

    if ($con) {
        $sql = "SELECT * FROM users WHERE Email = ? AND apiKey = ?";
        $stmt = mysqli_prepare($con, $sql);
        
        mysqli_stmt_bind_param($stmt, "ss", $email, $apiKey);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($res) != 0) {
            $row = mysqli_fetch_assoc($res);
            $sqlUpdate = "UPDATE users SET apiKey = '' WHERE Email = ?";
            $stmtUpdate = mysqli_prepare($con, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "s", $email);

            if (mysqli_stmt_execute($stmtUpdate)) {
                echo "success";
            } else {
                echo "Logout failed";
            }
        } else {
            echo "Unauthorized access";
        }
    } else {
        echo "Database connection failed";
    }
} else {
    echo "All fields are required";
}
?>
