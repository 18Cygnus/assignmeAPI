<?php
require_once('connection/connection.php');

$response = array();
$connect = new Connect();
$con = $connect->dbConn();

$classId = $_POST['classId'];

$sql = "SELECT ClassName, SubjectName FROM classes WHERE ClassId = $classId";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response['status'] = "success";
        $response['className'] = $row['ClassName'];
        $response['subjectName'] = $row['SubjectName'];
    }
} else {
    $response['status'] = "error";
    $response['error'] = "Data tidak ditemukan";
}

echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($con);
?>
