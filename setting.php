<?php
//setting.php
if (!empty($_POST['Email']) && !empty($_POST['apiKey'])) {
    $email = $_POST['Email'];
    $apiKey = $_POST['apiKey'];
    $result = array();
    $con = mysqli_connect("localhost", "root", "", "assignme");

    if ($con) {
        // Menggunakan prepared statement untuk mencegah SQL injection
        $sql = "SELECT Username, Email FROM users WHERE Email = ? AND apiKey = ?";
        $stmt = mysqli_prepare($con, $sql);

        // Binding parameter
        mysqli_stmt_bind_param($stmt, "ss", $email, $apiKey);

        // Eksekusi statement
        mysqli_stmt_execute($stmt);

        // Mengambil hasil
        $res = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($res) != 0) {
            $row = mysqli_fetch_assoc($res);
            $result = array(
                "status" => "success",
                "message" => "Data fetched successfully",
                "Username" => $row['Username'],
                "Email" => $row['Email']
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
