<?php
require_once 'connection/connection.php'; // Panggil file connection.php

if (!empty($_POST['Username']) && !empty($_POST['Email']) && !empty($_POST['Password'])) {
    $connection = new Connect(); // Membuat instance dari kelas Connect
    $con = $connection->dbConn(); // Memanggil method dbConn untuk koneksi ke database
    
    $username = $_POST['Username'];
    $email = $_POST['Email'];
    $password = password_hash($_POST['Password'], PASSWORD_DEFAULT);

    if ($con) {
        // Periksa apakah email sudah terdaftar sebelumnya
        $checkEmailQuery = "SELECT * FROM users WHERE Email = ?";
        $stmt = mysqli_prepare($con, $checkEmailQuery);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            // Email belum terdaftar, lakukan pendaftaran
            $sql = "INSERT INTO users (Username, Email, Password, Role) VALUES (?, ?, ?, 'Siswa')";
            $stmtInsert = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmtInsert, "sss", $username, $email, $password);

            if (mysqli_stmt_execute($stmtInsert)) {
                echo "success";
            } else {
                echo "Registration failed";
            }
        } else {
            echo "Email already exists"; // Email telah terdaftar sebelumnya
        }
    } else {
        echo "Database connection failed";
    }
} else {
    echo "All fields are required";
}
?>
