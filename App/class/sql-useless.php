<?php
include $_SERVER['DOCUMENT_ROOT'].'/App/conf/config.php';

function sql_connect_check()
{
        $servername = constant("DB_HOST");
        $username = constant("DB_USER");
        $password = constant("DB_PASSWORD");
        // Create connection
        $conn = new mysqli($servername, $username, $password);
        // Check connection
        if ($conn->connect_error) 
            {
            die("Connection failed: " . $conn->connect_error);
            }
        echo "Connected successfully";
};


function SQLQuery($query)
{
    $sql = new mysqli(constant("DB_HOST"),constant("DB_USER"),constant("DB_PASSWORD"),constant("DB_DB"));
    
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit;
    }
    
    $result = $sql->query($query);     
    if (!$result) {
      printf("Query failed: %s\n", $mysqli->error);
      exit;
    }      
    while($row = $result->fetch_assoc()) {
      $rows[]=$row;
    }
    $result->close();
    $sql->close();
    return $rows;




}





function getColoumn($query, $params = [])
{        
    return "";
}

function getRow($query, $params = [])
{        
    
    $sql = new mysqli(constant("DB_HOST"),constant("DB_USER"),constant("DB_PASSWORD"),constant("DB_DB"));
    
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit;
    }
    
    $result = $sql->query($query);     
    if (!$result) {
      printf("Query failed: %s\n", $mysqli->error);
      exit;
    }      
    
    $row = $result->fetch_assoc();

    $result->close();
    $sql->close();
    return $row;
}

function getRows($query, $params = [])
{        
    $sql = new mysqli(constant("DB_HOST"),constant("DB_USER"),constant("DB_PASSWORD"),constant("DB_DB"));
    
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit;
    }
    
    $result = $sql->query($query);     
    if (!$result) {
      printf("Query failed: %s\n", $mysqli->error);
      exit;
    }      
    while($row = $result->fetch_assoc()) {
      $rows[]=$row;
    }
    $result->close();
    $sql->close();
    return $rows;
}

function setRow($query, $params = [])
{        
    //$stmt=$this->bd->prepare($sql);
    //$stmt->execute($params);
    return "";
}















?>