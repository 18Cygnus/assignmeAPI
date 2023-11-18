<?php
//login.php
if(!empty($_POST['Email']) && !empty($_POST['Password'])){
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $result = array();

    $con = mysqli_connect("localhost", "root", "", "assignme");
    if($con){
        $sql = "select * from users where Email = '" . $email . "'";
        $res = mysqli_query($con, $sql);
        if(mysqli_num_rows($res) != 0) {
            $row = mysqli_fetch_assoc($res);
            if($email == $row['Email'] && password_verify($password, $row['Password'])){
                try {
                    $apiKey = bin2hex(random_bytes(23));
                } catch (Exception $e) {
                    $apiKey = bin2hex(uniqid($email, true));
                }
                $sqlUpdate = "update users set apiKey = '".$apiKey."' where Email = '".$email."'";
                if(mysqli_query($con, $sqlUpdate)){
                    $result = array("status"=>"success","message"=>"Login successful",
                        "Email"=>$row['Email'], "Password"=>$row['Password'], "apiKey"=> $apiKey);
                    
                } else $result = array("status"=>"Failed1", "message"=>"Login failed");
            } else $result = array("status"=>"Failed2", "message"=>"Retry with correct email and password");
        } else $result = array("status"=>"Failed3", "message"=>"Retry with correct email and password2");
    } else $result = array("status"=>"Failed4", "message"=>"Database connection failed");
} else $result = array("status"=>"Failed5", "message"=>"All fields are required");

echo json_encode($result, JSON_PRETTY_PRINT);
?>