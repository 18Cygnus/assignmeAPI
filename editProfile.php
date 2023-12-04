<?php
// editProfile.php
include 'connection/connection.php';

if (!empty($_POST['Email']) && !empty($_POST['apiKey'])) {
    $email = $_POST['Email'];
    $apiKey = $_POST['apiKey'];
    
    $connectionObj = new Connect(); // Membuat objek Connect
    $con = $connectionObj->dbConn();
    if ($con) {
        // Fetching user by email to check if it exists
        $sql = "SELECT * FROM users WHERE Email = '".$email."'";
        $result = mysqli_query($con, $sql);
        $rowCount = mysqli_num_rows($result);
        
        if ($rowCount > 0) {
            if (!empty($_POST['Gender'])) {
                // Update Gender for the user with the given Email
                $gender = $_POST['Gender'];
                $updateQuery = "UPDATE users SET Gender = '".$gender."' WHERE Email = '".$email."'";
            } elseif (!empty($_POST['newUsername'])) {
                // Update Username for the user with the given Email
                $newUsername = $_POST['newUsername'];
                $updateQuery = "UPDATE users SET Username = '".$newUsername."' WHERE Email = '".$email."'";
            } else {
                echo "No fields to update";
                exit();
            }
            
            $updateResult = mysqli_query($con, $updateQuery);
            
            if ($updateResult) {
                echo "success";
            } else {
                echo "Failed to update";
            }
        } else {
            echo "User not found";
        }
    } else {
        echo "Database connection failed";
    }
} else {
    echo "Email and apiKey are required fields";
}
?>