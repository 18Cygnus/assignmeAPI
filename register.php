<?php
//register.php
if(!empty($_POST['Username']) && !empty($_POST['Email']) && !empty($_POST['Password'])) {

    $con = mysqli_connect("localhost", "root", "", "assignme");
    $username = $_POST['Username'];
    $email = $_POST['Email'];
    $password = password_hash($_POST['Password'], PASSWORD_DEFAULT);

    if($con){
        $sql = "insert into users (Username, Email, Password) values ('" . $username . "','" . $email . "','" . $password . "')";
        if(mysqli_query($con, $sql)) {
            echo "success";
        } else echo "Registration failed";
    } else echo "Database connection failed";
} else echo "All fields are required";
?>