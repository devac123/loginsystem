<?php
$server = "localhost";
$username = "root";
$password = "";
// $database = "devologixadmin";

// $conn = mysqli_connect($server, $username, $password);
$conn = mysqli_connect();
if (!$conn){
    echo "success";
}
else{
    die("Error". mysqli_connect_error());
}
?>