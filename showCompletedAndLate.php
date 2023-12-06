<?php
//showTodo
require_once 'connection/connection.php';

$result = array();
$connection = new Connect(); // Buat instance dari kelas Connect
$con = $connection->dbConn(); // Panggil method dbConn untuk koneksi ke database

if ($con) {
    $email = $_POST['Email'];

    // Query untuk mendapatkan UserId berdasarkan Email dari tabel users
    $sqlUserId = "SELECT UserId FROM users WHERE Email = ?";
    $stmtUserId = mysqli_prepare($con, $sqlUserId);
    mysqli_stmt_bind_param($stmtUserId, "s", $email);
    mysqli_stmt_execute($stmtUserId);
    $resultUserId = mysqli_stmt_get_result($stmtUserId);

    if (mysqli_num_rows($resultUserId) > 0) {
        $row = mysqli_fetch_assoc($resultUserId);
        $userId = $row['UserId']; 

        // Query untuk mendapatkan daftar kelas yang di-join oleh pengguna berdasarkan UserId
    
        $sql = "SELECT 
        t.TaskId,
        t.TaskName,
        t.TaskDesc,
        t.DueDate,
        t.Attachment,
        ts.status,
        u.UserId
    FROM 
        tasks t
    JOIN 
        user_classes uc ON t.ClassId = uc.ClassId
    JOIN 
        users u ON uc.UserId = u.UserId
    LEFT JOIN 
        task_submits ts ON t.TaskId = ts.TaskId AND u.UserId = ts.UserId
    WHERE u.UserId = ?
        AND ts.status = 'Completed'
        AND t.DueDate < NOW()";       
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $userClasses = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $userClasses[] = $row;
            }
            echo json_encode(array("userClasses" => $userClasses));
        } else {
            echo "0 results";
        }
    } else {
        echo "UserId not found for this Email";
    }
} else {
    echo "Database connection failed";
}

$con->close();
?>
