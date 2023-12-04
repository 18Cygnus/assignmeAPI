<?php
//showCompletedAndLate

require_once 'connection/connection.php';

$result = array();
$connection = new Connect();
$con = $connection->dbConn();

if ($con) {
    $email = $_POST['Email'];

    $sqlUserId = "SELECT UserId FROM users WHERE Email = ?";
    $stmtUserId = mysqli_prepare($con, $sqlUserId);
    mysqli_stmt_bind_param($stmtUserId, "s", $email);
    mysqli_stmt_execute($stmtUserId);
    $resultUserId = mysqli_stmt_get_result($stmtUserId);

    if (mysqli_num_rows($resultUserId) > 0) {
        $row = mysqli_fetch_assoc($resultUserId);
        $userId = $row['UserId'];
    
        // Modify your query to filter tasks based on UserId
        $sql = "SELECT ts.*, t.TaskName, t.TaskDesc, t.DueDate, t.ClassId, t.Attachment
                FROM users u
                JOIN user_classes uc ON u.UserId = uc.UserId
                JOIN tasks t ON uc.ClassId = t.ClassId
                LEFT JOIN task_submits ts ON u.UserId = ts.UserId AND ts.TaskId = t.TaskId
                WHERE u.Email = ?
                AND ts.SubmitDate > t.DueDate";         
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $email);
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
