<?php
// api.php

header('Content-Type: application/json');

if (!empty($_POST['Email']) && !empty($_POST['apiKey'])) {
    $email = $_POST['Email'];
    $apiKey = $_POST['apiKey'];
    $result = array();
    $con = mysqli_connect("localhost", "root", "", "assignme");

    if ($con) {

        // $sql = "SELECT ClassName, SubjectName FROM classes c 
        // JOIN user_classes uc ON c.ClassId = uc.UserClassId
        // JOIN users u ON uc.UserId = u.UserId
        // WHERE u.Email = ? AND u.apiKey = ?";

        $sql="SELECT c.ClassName, c.SubjectName
        FROM classes c
        JOIN users u ON c.UserId = u.UserId 
        WHERE c.UserId =?";

        $stmt = mysqli_prepare($con, $sql);

        // Binding parameter
        mysqli_stmt_bind_param($stmt, "ss", $email, $apiKey);

        // Eksekusi statement
        mysqli_stmt_execute($stmt);

        // Mengambil hasail
        $res = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($res) != 0) {
            $kelasList = array();

            while ($row = mysqli_fetch_assoc($res)) {
                // Membuat array untuk setiap kelas
                $kelas = array(
                    "nama_kelas" => $row['ClassName'],
                    "nama_mapel" => $row['SubjectName']
                );

                // Menambahkan kelas ke dalam list
                $kelasList[] = $kelas;
            }

            $result = array(
                "status" => "success",
                "message" => "Data fetched successfully",
                "data" => array("kelasList" => $kelasList)
            );
        } else {
            $result = array("status" => "Failed", "message" => "Unauthorized access");
        }
    } else {
        $result = array("status" => "Failed", "message" => "Database connection failed");
    }
} else {
    $result = array("status" => "Failed", "message" => "All fields are required");
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
