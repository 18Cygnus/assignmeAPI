<?php
//dbManager.php

$con = mysqli_connect("localhost", "root", "", "assignme");

function deleteExpiredOTP($con) {
    $deleteQuery = "DELETE FROM users WHERE reset_password_created_at <= DATE_SUB(NOW(), INTERVAL 2 MINUTE)";
    mysqli_query($con, $deleteQuery);
}
?>