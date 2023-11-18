<?php
//newPassword.php
if (!empty($_POST['Email']) && !empty($_POST['newPassword'])) {
    $email = $_POST['Email'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    
    $con = mysqli_connect("localhost", "root", "", "assignme");
    if($con) {
        $sql = "UPDATE users set Password = '".$newPassword."', reset_password_created_at = NOW(), reset_password_otp = '' WHERE Email = '".$email."' ";
            if(mysqli_query($con, $sql)){
                if (mysqli_affected_rows($con)){
                    echo "success";
                }else echo "Reset password failed";
            }else echo "Reset password failed2";
    } else echo "Database connection failed";
} else echo "All fields are required";

?>