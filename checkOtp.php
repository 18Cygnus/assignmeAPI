<?php
require_once 'connection/connection.php'; // Memanggil file connection.php

if (!empty($_POST['Email']) && !empty($_POST['Otp'])){
    $email = $_POST['Email'];
    $otp = $_POST['Otp'];

    $sql = "UPDATE users SET reset_password_otp = '' WHERE Email = ? AND reset_password_otp = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
        mysqli_stmt_execute($stmt);

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        if ($affectedRows > 0) {
            echo "success";
        } else {
            echo "Invalid OTP";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare statement";
    }
} else {
    echo "All fields are required";
}

mysqli_close($con);
?>
