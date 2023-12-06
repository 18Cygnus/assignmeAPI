<?php
require_once('connection/connection.php'); 

$response = array();
$connect = new Connect();
$con = $connect->dbConn();

if (isset($_POST['ClassId']) && !empty($_POST['ClassId'])) {
    $classId = $_POST['ClassId'];
    $classId = mysqli_real_escape_string($con, $classId);

    $sql = "SELECT * FROM materials WHERE ClassId = '$classId'";
} elseif (isset($_POST['MaterialId']) && !empty($_POST['MaterialId'])) {
    $materialId = $_POST['MaterialId'];
    $materialId = mysqli_real_escape_string($con, $materialId);

    $sql = "SELECT * FROM materials WHERE MaterialId = '$materialId'";
} else {
    $response['status'] = "error";
    $response['message'] = "ClassId or MaterialId is required";
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

$result = $con->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $response['materials'] = array();

        while ($row = $result->fetch_assoc()) {
            $material = array(
                'materialId' => intval($row['MaterialId']),
                'classId' => intval($row['ClassId']),
                'materialName' => $row['MaterialName'],
                'materialDesc' => $row['MaterialDesc'],
                'uploadDate' => $row['UploadDate'],
                'attachment' => $row['Attachment']
            );

            $response['materials'][] = $material;
        }

        // Output the JSON response
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        $response['materials'] = array(); // To ensure an empty array is present

        // Rest of your error handling for no materials found
        $response['status'] = "error";
        $response['message'] = "No materials found for this query";

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

mysqli_close($con);
?>
