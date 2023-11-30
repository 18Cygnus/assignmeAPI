<?php
require_once 'connection/connection.php'; // Memanggil file connection.php 

if (!empty($_POST['Email']) && !empty($_POST['Otp'])) {
    $email = $_POST['Email'];
    $otp = $_POST['Otp'];

    $connection = new Connect(); // Membuat instance dari kelas Connect
    $con = $connection->dbConn();

    // Mencari UserId dari Email yang diberikan
    $findUserQuery = "SELECT UserId FROM users WHERE Email = ?";
    $findUserStmt = mysqli_prepare($con, $findUserQuery);

    if ($findUserStmt) {
        mysqli_stmt_bind_param($findUserStmt, "s", $email);
        mysqli_stmt_execute($findUserStmt);
        mysqli_stmt_store_result($findUserStmt);

        if (mysqli_stmt_num_rows($findUserStmt) > 0) {
            mysqli_stmt_bind_result($findUserStmt, $userId);
            mysqli_stmt_fetch($findUserStmt);

            // Memeriksa apakah ada baris di verifications dengan UserId yang sesuai dan OTP yang cocok
            $verifyOtpQuery = "SELECT UserId FROM verifications WHERE UserId = ? AND otp = ?";
            $verifyOtpStmt = mysqli_prepare($con, $verifyOtpQuery);

            if ($verifyOtpStmt) {
                mysqli_stmt_bind_param($verifyOtpStmt, "is", $userId, $otp);
                mysqli_stmt_execute($verifyOtpStmt);
                mysqli_stmt_store_result($verifyOtpStmt);

                if (mysqli_stmt_num_rows($verifyOtpStmt) > 0) {
                    // Menghapus baris yang sesuai jika OTP cocok
                    $deleteOtpQuery = "DELETE FROM verifications WHERE UserId = ?";
                    $deleteOtpStmt = mysqli_prepare($con, $deleteOtpQuery);

                    if ($deleteOtpStmt) {
                        mysqli_stmt_bind_param($deleteOtpStmt, "i", $userId);
                        mysqli_stmt_execute($deleteOtpStmt);

                        $affectedRows = mysqli_stmt_affected_rows($deleteOtpStmt);
                        if ($affectedRows > 0) {
                            echo "success";
                        } else {
                            echo "Failed to delete OTP";
                        }

                        mysqli_stmt_close($deleteOtpStmt);
                    } else {
                        echo "Failed to prepare delete statement";
                    }
                } else {
                    echo "Invalid OTP";
                }

                mysqli_stmt_close($verifyOtpStmt);
            } else {
                echo "Failed to prepare OTP verification statement";
            }
        } else {
            echo "Email not found";
        }

        mysqli_stmt_close($findUserStmt);
    } else {
        echo "Failed to prepare statement";
    }
} else {
    echo "All fields are required";
}

mysqli_close($con);
?>
