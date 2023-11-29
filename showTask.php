<?php
require_once('connection/connection.php');

$response = array();
$connect = new Connect();
$con = $connect->dbConn();

$classId = $_POST['ClassId'];

$sql = "SELECT * FROM tasks WHERE ClassId = $classId"; // Sesuaikan dengan tabel dan struktur kolom yang benar
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $response['tasks'] = array(); // Inisialisasi array tasks

    while ($row = $result->fetch_assoc()) {
        // Membuat array untuk setiap tugas dan memasukkannya ke dalam array tasks
        $task = array(
            'taskId' => $row['TaskId'],
            'taskName' => $row['TaskName'],
            'taskDesc' => $row['TaskDesc'],
            'dueDate' => $row['DueDate']
        );

        // Masukkan array $task ke dalam array $response['tasks']
        $response['tasks'][] = $task;
    }
} else {
    // Tidak ada tugas yang ditemukan, atur pesan kesalahan
    $response['status'] = "error";
    $response['message'] = "No tasks found for this ClassId";
}

echo json_encode($response, JSON_PRETTY_PRINT);
error_log("ClassId received: ".$_POST['ClassId']);
mysqli_close($con);
?>