<?php
//resetPassword.php

/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";*/

if (!empty($_POST['Email'])) {
    $email = $_POST['Email'];
    
    $con = mysqli_connect("localhost", "root", "", "assignme");
    if($con) {
        // Query untuk memeriksa apakah email ada dalam database
        $checkQuery = "SELECT * FROM users WHERE Email = ?";
        $checkStmt = mysqli_prepare($con, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if(mysqli_num_rows($checkResult) > 0) {
            // Email ditemukan, maka kirim OTP
            try {
                $otp = random_int(100000, 999999);
            } catch (Exception $e) {
                $otp = rand(100000, 999999);
            }
            $currentTime = date('Y-m-d H:i:s');
            $updateQuery = "UPDATE users SET reset_password_otp = ?, reset_password_created_at = ? WHERE Email = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "iss", $otp, $currentTime, $email);
            $result = mysqli_stmt_execute($updateStmt);
            if ($result) {
                echo "success";
            } else {
                echo "Failed to update";
            }

            mysqli_stmt_close($updateStmt);
        } else {
            echo "Email not found";
        }

        mysqli_close($con);
    } else {
        echo "Database connection failed"  . mysqli_connect_error();
    }
} else {
    echo "All fields are required";
}





/*if(mysqli_query($con, $sql)){
                if (mysqli_affected_rows($con)){
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'user@example.com';                     //SMTP username
                        $mail->Password   = 'secret';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                        //Recipients
                        $mail->setFrom('from@example.com', 'Mailer');
                        $mail->addAddress($email);           //Add a recipient
                        $mail->addReplyTo('info@example.com', 'Information');

                        //Content
                        $mail->isHTML(true);                                        //Set email format to HTML
                        $mail->Subject = 'Reset Password - AssignMe';
                        $mail->Body    = 'Your OTP code to reset password is [ ' . $otp . ' ].';
                        $mail->AltBody = 'Reset password to access your AssignMe account.';
                    
                        if ($mail->send()){
                            echo 'success';
                        }else echo 'Failed to send OTP';
                        
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }else echo "Reset password failed";
            }else echo "Reset password failed2";*/
?>