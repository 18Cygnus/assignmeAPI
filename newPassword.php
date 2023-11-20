<?php
//newPassword.php
if (!empty($_POST['Email']) && !empty($_POST['newPassword'])) {
    $email = $_POST['Email'];
    $newPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    
    $con = mysqli_connect("localhost", "root", "", "assignme");
    if($con) {
        // Fetching old password
        $sql = "SELECT Password FROM users WHERE Email = '".$email."'";
        $result = mysqli_query($con, $sql);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $oldPassword = $row['Password'];
            
            // Check if the new password is the same as the old one
            if (password_verify($_POST['newPassword'], $oldPassword)) {
                echo "New password cannot be the same as the old one";
            } else {
                // Update password if it's different
                $sql = "UPDATE users SET Password = '".$newPassword."', reset_password_created_at = NOW() WHERE Email = '".$email."' ";
                if(mysqli_query($con, $sql)){
                    if (mysqli_affected_rows($con)){
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