<?php
//showJoinedClass.php

$result = array();

$con = mysqli_connect("localhost", "root", "", "assignme");
if ($con->connect_error) {
    die("Koneksi gagal: " . $con->connect_error);
}

$email = $_POST['Email'];

// Query untuk mendapatkan UserId berdasarkan Email dari tabel users
$sqlUserId = "SELECT UserId FROM users WHERE Email = '$email'";
$resultUserId = $con->query($sqlUserId);

if ($resultUserId->num_rows > 0) {
    $row = $resultUserId->fetch_assoc();
    $userId = $row['UserId'];
    // Query untuk mendapatkan daftar kelas yang di-join oleh pengguna berdasarkan UserId
    $sql = "SELECT classes.ClassId, classes.ClassName, classes.SubjectName FROM user_classes
            JOIN classes ON user_classes.ClassId = classes.ClassId
            WHERE user_classes.UserId = '$userId'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $userClasses = array();
        while ($row = $result->fetch_assoc()) {
            $userClasses[] = $row;
        }
        echo json_encode(array("userClasses" => $userClasses));
    } else {
        echo "0 results";
    }
} else {
    echo "UserId not found for this Email";
}

$con->close();

echo json_encode($result, JSON_PRETTY_PRINT);
?>