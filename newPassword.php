<?php
require_once 'connection/connection.php'; // Panggil file connection.php

if (!empty($_POST['Email']) && !empty($_POST['newPassword'])) {
    $email = $_POST['Email'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    
    // Menggunakan kelas Connect untuk mendapatkan koneksi
    $connection = new Connect();
    $con = $connection->dbConn();
    
    if ($con) {
        // Melakukan query untuk mengambil password lama
        $sql = "SELECT Password FROM users WHERE Email = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $oldPassword = $row['Password'];
            
            // Memeriksa apakah password baru sama dengan yang lama
            if (password_verify($_POST['newPassword'], $oldPassword)) {
                echo "New password cannot be the same as the old one";
            } else {
                // Mengupdate password jika berbeda
                $sql = "UPDATE users SET Password = ?, reset_password_created_at = NOW() WHERE Email = ?";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $newPassword, $email);
                
                if (mysqli_stmt_execute($stmt)) {
                    if (mysqli_affected_rows($con)) {
                        echo "success";
                    } else {
                        echo "Reset password failed";
                    }
                } else {
                    echo "Reset password failed2";
                }
            }
        } else {
            echo "Failed to fetch old password";
        }
        
    } else {
        echo "Database connection failed";
    }
} else {
    echo "All fields are required";
}
?>
