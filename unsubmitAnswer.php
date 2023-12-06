<?php
require_once 'connection/connection.php';

if (!empty($_POST['Email']) && !empty($_POST['TaskId'])) {
    $email = $_POST['Email'];
    $taskId = $_POST['TaskId'];
    
    $connectionObj = new Connect();
    $con = $connectionObj->dbConn();
    if ($con) {
        // Fetching user by email to get UserId
        $sql = "SELECT UserId FROM users WHERE Email = '".$email."'";
        $result = mysqli_query($con, $sql);
        $rowCount = mysqli_num_rows($result);
        
        if ($rowCount > 0) {
            $row = mysqli_fetch_assoc($result);
            $userId = $row['UserId'];
            
            // Delete entry from task_submits
            $deleteQuery = "DELETE FROM task_submits WHERE UserId = '$userId' AND TaskId = '$taskId'";
            $deleteResult = mysqli_query($con, $deleteQuery);
            
            if ($deleteResult) {
                echo json_encode(array("status" => "success"));
            } else {
                echo json_encode(array("status" => "Failed to delete"));
            }
        } else {
            echo json_encode(array("status" => "User not found"));
        }
    } else {
        echo json_encode(array("status" => "Database connection failed"));
    }
} else {
    echo json_encode(array("status" => "Email and TaskId are required fields"));
}
?>
