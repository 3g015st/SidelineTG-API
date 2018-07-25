<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    //Load libraries.
    include_once '../config/dbConfig.php';
    include_once '../functions/functions.php';

    //Connect to database.
    global $sqlConnection;
    $sqlConnection = mysqli_connect($serverName,$username,$password,$dbName);
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to the server: " . mysqli_connect_error();
        die();
    }

    // Initialize variables.
    $email = $_POST['email'];

    // Check if email exists
    if(isEmailExists($email, $sqlConnection))
    {
        echo "ERROR";
    }
    else
    {
        echo "SUCCESS";
    }

?>