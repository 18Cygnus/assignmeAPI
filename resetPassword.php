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
        try {
            $otp = random_int(1000, 9999);
        } catch (Exception $e) {
            $otp = rand(1000, 9999);
        }
        $currentTime = date('Y-m-d H:i:s');
        $sql = "UPDATE users SET reset_password_otp = ?, reset_password_created_at = ? WHERE Email = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $otp, $currentTime, $email);
        $result = mysqli_stmt_execute($stmt);

            if ($result) {
            echo "success";
        } else {
            echo "Failed to update";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

    } else echo "Database connection failed"  . mysqli_connect_error();;
} else echo "All fields are required";



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