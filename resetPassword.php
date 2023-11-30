<?php
//resetPassword.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";
require_once 'connection/connection.php';

if (!empty($_POST['Email'])) {
    $email = $_POST['Email'];
    
    $connection = new Connect();
    $con = $connection->dbConn(); 
    
    if ($con) {
        $checkQuery = "SELECT UserId FROM users WHERE Email = ?";
        $checkStmt = mysqli_prepare($con, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if ($row = mysqli_fetch_assoc($checkResult)) {
            $userId = $row['UserId'];
            
            $deletePreviousQuery = "DELETE FROM verifications WHERE UserId = ? AND reset_password_expiry > NOW()";
            $deletePreviousStmt = mysqli_prepare($con, $deletePreviousQuery);
            mysqli_stmt_bind_param($deletePreviousStmt, "i", $userId);
            mysqli_stmt_execute($deletePreviousStmt);
            mysqli_stmt_close($deletePreviousStmt);
            
            $otp = rand(100000, 999999);
            $currentTime = date('Y-m-d H:i:s');
            
            $insertQuery = "INSERT INTO verifications (UserId, otp, reset_password_created_at, reset_password_expiry) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 2 MINUTE))";
            $insertStmt = mysqli_prepare($con, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "iis", $userId, $otp, $currentTime);
            $insertResult = mysqli_stmt_execute($insertStmt);
            
            if ($insertResult) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'aryasanjaya001@gmail.com';
                    $mail->Password   = 'wujiqraukevrwmyc';
                    $mail->SMTPSecure = "tls";
                    $mail->Port       = 587;
                    $mail->setFrom('aryasanjaya001@gmail.com', 'Mailer');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Reset Password - AssignMe';
                    $mail->Body    = 'Your OTP code to reset password is [ ' . $otp . ' ].';
                    $mail->AltBody = 'Reset password to access your AssignMe account.';
                
                    if ($mail->send()) {
                        echo 'success';
                    } else {
                        echo 'Failed to send OTP';
                    }
                    
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                echo "Failed to insert OTP";
            }
            
            mysqli_stmt_close($insertStmt);
        } else {
            echo "Email not found";
        }

        mysqli_close($con);
    } else {
        echo "Database connection failed"  . mysqli_connect_error();
    }
} else {
    echo "Email field is required";
}
?>