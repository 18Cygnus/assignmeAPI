<?php
//logout.php
if (!empty($_POST['Email']) && !empty($_POST['apiKey'])){
    $email = $_POST['Email'];
    $apiKey = $_POST['apiKey'];
    $con = mysqli_connect("localhost", "root", "", "assignme");
    if($con) {
        $sql = "select * from users where Email = '".$email."' and apiKey = '".$apiKey."'";
        $res = mysqli_query($con, $sql);
        if(mysqli_num_rows($res) != 0) {
            $row = mysqli_fetch_assoc($res);
            $sqlUpdate = "update users set apiKey = '' where Email = '".$email."'";
            if (mysqli_query($con, $sqlUpdate)){
                echo "success";
            } else echo "Logout failed";
        } else echo "Unauthorized access";
    } else echo "Database connection failed";
} else echo "All fields are required";

?>