<?php
$showError = false;
$ini = parse_ini_file('../app.ini');
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = false;
    include '../includes/conn.php';
        $username = $_POST["username"];
        $pass = $_POST["password"]; 
        $sql = "Select * from devologixadmin where username='$username' AND password='$pass'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1){
            $login = true;
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            echo "login";
            header("location: ../");
        } 
        else{
            $showError = "Invalid Credentials";
        }
    }
    else {
        session_start();
        if(isset($_SESSION['loggedin']) and $_SESSION['loggedin']=true){
            header("location: ../");
            exit;
        }
    }
?>