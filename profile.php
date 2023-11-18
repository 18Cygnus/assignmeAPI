<?php
//profile.php
if(!empty($_POST['Email']) && !empty($_POST['apiKey']) && !empty($_POST['apiKey'])){
    $email = $_POST['Email'];
    $apiKey = $_POST['apiKey'];
    $username = $_POST['Username'];
    $result = array();
    $con = mysqli_connect("localhost", "root", "", "assignme");
    if($con){
        $sql = "select * from users where Email = '".$email."' ";
        $res = mysqli_query($con, $sql);
        if (mysqli_num_rows($res) != 0){
            $row = mysqli_fetch_assoc($res);
            $result = $row['Username'];
        }else $result = array("status"=>"Failed", "message"=>"Unauthorized access");
    } else $result = array("status"=>"Failed", "message"=>"Database connection failed");
} else $result = array("status"=>"Failed", "message"=>"All fields are required");

echo json_encode($result, JSON_PRETTY_PRINT);
/*array("status"=>"success","message"=>"Data fetched successfully",
"Username"=>$row['Username'], "Email"=>$row['Email'], "apiKey"=>$row['apiKey']);

and Username = '".$username."'

*/
?>