<?php
// editProfile.php
include 'connection/connection.php';

if (!empty($_POST['Email']) && !empty($_POST['apiKey']) && !empty($_POST['newUsername'])) {
    $email = $_POST['Email'];
    $apiKey = $_POST['apiKey'];
    $newUsername = $_POST['newUsername'];
    
    $connectionObj = new Connect(); // Membuat objek Connect
    $con = $connectionObj->dbConn();
    if ($con) {
        // Fetching user by email to check if it exists
        $sql = "SELECT * FROM users WHERE Email = '".$email."'";
        $result = mysqli_query($con, $sql);
        $rowCount = mysqli_num_rows($result);
        
        if ($rowCount > 0) {
            // Update the Username for the user with the given Email
            $updateQuery = "UPDATE users SET Username = '".$newUsername."' WHERE Email = '".$email."'";
            $updateResult = mysqli_query($con, $updateQuery);
            
            if ($updateResult) {
                echo "success";
            } else {
                echo "Failed to update Username";
            }
        } else {
            echo "User not found";
        }
    } else {
        echo "Database connection failed";
    }
} else {
    echo "Email and Username are required fields";
}
?>