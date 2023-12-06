<?php

// checkDueDate.php
include 'connection/connection.php';

if (!empty($_POST['TaskId'])) {
    $taskId = $_POST['TaskId'];
    
    $connectionObj = new Connect();
    $con = $connectionObj->dbConn();
    if ($con) {
        // Fetching DueDate from tasks table
        $sql = "SELECT DueDate FROM tasks WHERE TaskId = '".$taskId."'";
        $result = mysqli_query($con, $sql);
        $rowCount = mysqli_num_rows($result);
        
        if ($rowCount > 0) {
            $row = mysqli_fetch_assoc($result);
            $dueDate = $row['DueDate'];
            
            // Get current date
            $currentDate = date('Y-m-d H:i:s'); // Current datetime
            
            $lateStatus = ($currentDate > $dueDate) ? "Late" : "On Time";
            
            echo json_encode(array("status" => "success", "lateStatus" => $lateStatus));
        } else {
            echo json_encode(array("status" => "Task not found"));
        }
    } else {
        echo json_encode(array("status" => "Database connection failed"));
    }
} else {
    echo json_encode(array("status" => "TaskId is required"));
}


?>