<?php
require_once('connection/connection.php'); 

$response = array();
$connect = new Connect();
$con = $connect->dbConn();

if (isset($_POST['ClassId']) && !empty($_POST['ClassId'])) {
    $classId = $_POST['ClassId'];
    
    // Perform proper sanitation/validation for $classId
    // Here's an example with mysqli_real_escape_string for basic protection
    $classId = mysqli_real_escape_string($con, $classId);

    $sql = "SELECT * FROM tasks WHERE ClassId = '$classId'";
    $result = $con->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $response['tasks'] = array();
    
            while ($row = $result->fetch_assoc()) {
                $task = array(
                    'taskId' => intval($row['TaskId']),
                    'taskName' => $row['TaskName'],
                    'taskDesc' => $row['TaskDesc'],
                    'dueDate' => $row['DueDate']
                );
    
                $response['tasks'][] = $task;
            }
    
            // Output the JSON response
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            $response['tasks'] = array(); // To ensure an empty array is present
    
            // Rest of your error handling for no tasks found
            $response['status'] = "error";
            $response['message'] = "No tasks found for this ClassId";
    
            // Output the JSON response
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    } else {
        // Rest of your error handling for database error
        $response['status'] = "error";
        $response['message'] = "Database error: " . mysqli_error($con);
    
        // Output the JSON response
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

mysqli_close($con);
?>
