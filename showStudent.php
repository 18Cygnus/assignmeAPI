<?php
require_once('connection/connection.php');

$response = array();
$connect = new Connect();
$con = $connect->dbConn();

if (isset($_POST['ClassId']) && !empty($_POST['ClassId'])) {
    $classId = $_POST['ClassId'];

    $classId = mysqli_real_escape_string($con, $classId);

    $sql = "SELECT *
            FROM users
            JOIN user_classes ON users.UserId = user_classes.UserId
            WHERE user_classes.ClassId = '$classId' AND users.Role = 'Siswa'";
    $result = $con->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $response['students'] = array();
    
            while ($row = $result->fetch_assoc()) {
                $student = array(
                    'userId' => intval($row['UserId']),
                    'userName' => $row['Username'] 
                );
    
                $response['students'][] = $student;
            }
    
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            $response['students'] = array(); 
    
            $response['status'] = "error";
            $response['message'] = "No tasks found for this ClassId";
    
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    } else {
        $response['status'] = "error";
        $response['message'] = "Database error: " . mysqli_error($con);
    
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

mysqli_close($con);
?>
