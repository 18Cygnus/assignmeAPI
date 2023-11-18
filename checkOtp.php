<?php
//checkOtp.php
if (!empty($_POST['Email']) && !empty($_POST['Otp'])){
    $email = $_POST['Email'];
    $otp = $_POST['Otp'];


    $con = mysqli_connect("localhost", "root", "", "assignme");
    if ($con) {
        $sql = "UPDATE users SET reset_password_otp = '' WHERE Email = ? AND reset_password_otp = ?";
        $stmt = mysqli_prepare($con, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
            mysqli_stmt_execute($stmt);

            $affectedRows = mysqli_stmt_affected_rows($stmt);
            if ($affectedRows > 0) {
                echo "success";
            } else {
                echo "Invalid OTP"; // Tidak ada baris yang terpengaruh, artinya OTP tidak cocok dengan yang ada di database
            }
            
            mysqli_stmt_close($stmt);

        } else echo "Failed to prepare statement";

        mysqli_close($con);

    } else echo "Database connection failed";
} else echo "All fields are required";
?>