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

    $sql = "SELECT * FROM users
            JOIN user_classes ON users.UserId = user_classes.UserId
            WHERE user_classes.ClassId = '$classId' AND users.Role = 'Guru'";
    $result = $con->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $response['teachers'] = array();
    
            while ($row = $result->fetch_assoc()) {
                $teacher = array( // Ganti 'task' menjadi 'material' untuk sesuai dengan struktur yang diharapkan
                    'userId' => intval($row['UserId']), // Sesuaikan dengan kunci yang diharapkan
                    'userName' => $row['Username'] // Sesuaikan dengan kunci yang diharapkan
                );
    
                $response['teachers'][] = $teacher;
            }
    
            // Output the JSON response
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            $response['teachers'] = array(); // To ensure an empty array is present
    
            // Rest of your error handling for no materials found
            $response['status'] = "error";
            $response['message'] = "No materials found for this ClassId";
    
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
