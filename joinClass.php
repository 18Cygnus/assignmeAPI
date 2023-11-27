<?php
//joinClassTest
if (!empty($_POST['ClassCode']) && !empty($_POST['Email'])) {
    $classCode = $_POST['ClassCode'];
    $email = $_POST['Email'];
    $result = array();
    $con = mysqli_connect("localhost", "root", "", "assignme");

    if ($con) {
        // Menggunakan prepared statement untuk mencegah SQL injection
        $sql = "SELECT UserId FROM users WHERE Email = ?";
        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($res) != 0) {
            $userRow = mysqli_fetch_assoc($res);
            $userId = $userRow['UserId'];

            // Check if the class code exists in the classes table
            $checkClassQuery = "SELECT ClassId FROM classes WHERE ClassCode = ?";
            $stmtCheckClass = mysqli_prepare($con, $checkClassQuery);
            mysqli_stmt_bind_param($stmtCheckClass, "s", $classCode);
            mysqli_stmt_execute($stmtCheckClass);
            $resCheckClass = mysqli_stmt_get_result($stmtCheckClass);

            if (mysqli_num_rows($resCheckClass) != 0) {
                // Check if the user is already enrolled in the class
                $checkEnrollmentQuery = "SELECT * FROM user_classes WHERE UserId = ? AND ClassId = (SELECT ClassId FROM classes WHERE ClassCode = ?)";
                $stmtCheckEnrollment = mysqli_prepare($con, $checkEnrollmentQuery);
                mysqli_stmt_bind_param($stmtCheckEnrollment, "is", $userId, $classCode);
                mysqli_stmt_execute($stmtCheckEnrollment);
                $resCheckEnrollment = mysqli_stmt_get_result($stmtCheckEnrollment);

                if (mysqli_num_rows($resCheckEnrollment) == 0) {
                    // Update table user_classes
                    $role = "Siswa";
                    $updateQuery = "INSERT INTO user_classes (UserId, ClassId, Role) VALUES (?, (SELECT ClassId FROM classes WHERE ClassCode = ?), ?)";
                    $stmtUpdate = mysqli_prepare($con, $updateQuery);
                    mysqli_stmt_bind_param($stmtUpdate, "iss", $userId, $classCode, $role);
                    
                    // Eksekusi pernyataan update
                    if (mysqli_stmt_execute($stmtUpdate)) {
                        $classInfoQuery = "SELECT ClassName, SubjectName FROM classes WHERE ClassCode = ?";
                        $stmtClassInfo = mysqli_prepare($con, $classInfoQuery);
                        mysqli_stmt_bind_param($stmtClassInfo, "s", $classCode);
                        mysqli_stmt_execute($stmtClassInfo);
                        $resClassInfo = mysqli_stmt_get_result($stmtClassInfo);
                        $rowClassInfo = mysqli_fetch_assoc($resClassInfo);

                        $result = array(
                            "status" => "success",
                            "message" => "User enrolled in class successfully",
                            "ClassName" => $rowClassInfo['ClassName'],
                            "SubjectName" => $rowClassInfo['SubjectName']
                        );
                    } else {
                        $result = array("status" => "Failed", "message" => "Failed to enroll user in class");
                    }
                } else {
                    $result = array("status" => "Failed", "message" => "User already enrolled in this class");
                }
            } else {
                $result = array("status" => "Failed", "message" => "Code not found");
            }
        } else {
            $result = array("status" => "Failed", "message" => "User not found");
        }
    } else {
        $result = array("status" => "Failed", "message" => "Database connection failed");
    }
} else {
    $result = array("status" => "Failed", "message" => "All fields are required");
}

echo json_encode($result, JSON_PRETTY_PRINT);
?>
