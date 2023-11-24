<?php

function Login() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode([
            "status" => 0,
            "message" => 'Request Not Valid'
        ]);
        return;
    }

    $Connect = new Connect();
    $connection = $Connect->dbConn();

    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $query = $connection->query("SELECT * FROM users WHERE Email='".$email."' AND Password='".$password."'");
        while ($row = mysqli_fetch_object($query)) { 
            $data[] = $row;
        }
        $response = array();

        if (!empty($data)) {
            $response = [
                "status" => 1,
                "message" => 'Login Successful'
            ];
            $response['Users'] = $data; 
        } else {
            $response = [
                "status" => 0,
                "message" => 'Login Failed'
            ];
        }
    } else {
        $response = [
            "status" => 0,
            "message" => 'Missing email or password',
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

?>