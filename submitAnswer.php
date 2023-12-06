<?php
include 'connection/connection.php';

if (!empty($_POST['Email']) && !empty($_POST['TaskId']) && !empty($_POST['Answers'])) {
    $email = $_POST['Email'];
    $taskId = $_POST['TaskId'];
    $answers = $_POST['Answers'];
    
    $connectionObj = new Connect(); // Membuat objek Connect
    $con = $connectionObj->dbConn();
    if ($con) {
        // Fetching user by email to get UserId
        $sql = "SELECT UserId FROM users WHERE Email = '".$email."'";
        $result = mysqli_query($con, $sql);
        $rowCount = mysqli_num_rows($result);
        
        if ($rowCount > 0) {
            $row = mysqli_fetch_assoc($result);
            $userId = $row['UserId'];
            
            // Simpan jawaban ke tabel task_submits
            $submitDate = date('Y-m-d H:i:s'); // Waktu saat ini
            $insertQuery = "INSERT INTO task_submits (TaskId, UserId, Answers, SubmitDate) VALUES ('$taskId', '$userId', '$answers', '$submitDate')";
            $insertResult = mysqli_query($con, $insertQuery);
            
            if ($insertResult) {
                echo json_encode(array("status" => "success"));
            } else {
                echo json_encode(array("status" => "Failed to insert"));
            }
        } else {
            echo json_encode(array("status" => "User not found"));
        }
    } else {
        echo json_encode(array("status" => "Database connection failed"));
    }
} else {
    echo json_encode(array("status" => "Email, TaskId, and Answers are required fields"));
}
?>