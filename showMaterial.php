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

    $sql = "SELECT * FROM materials WHERE ClassId = '$classId'";
    $result = $con->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $response['materials'] = array(); // Ganti 'tasks' menjadi 'materials' sesuai dengan yang diharapkan
    
            while ($row = $result->fetch_assoc()) {
                $material = array( // Ganti 'task' menjadi 'material' untuk sesuai dengan struktur yang diharapkan
                    'materialId' => intval($row['MaterialId']), // Sesuaikan dengan kunci yang diharapkan
                    'materialName' => $row['MaterialName'],
                    'uploadDate' => $row['UploadDate'] // Sesuaikan dengan kunci yang diharapkan
                );
    
                $response['materials'][] = $material;
            }
    
            // Output the JSON response
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            $response['materials'] = array(); // To ensure an empty array is present
    
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
