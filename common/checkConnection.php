<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Load libraries.
    include_once '../config/dbConfig.php';
    
//Initialize variables
    $request = $_POST['request'];

//CHECK CONNECTION:
    if($request == "CHECK_CONN")
    {
        //Connect to database.
        global $sqlConnection;
        $sqlConnection = mysqli_connect($serverName,$username,$password,$dbName);
        if (mysqli_connect_errno())
        {
            echo "ERROR";
        }
        else
        {
            echo "SUCCESS";
        }
    }
?>