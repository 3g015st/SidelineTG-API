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

    //Initialize variables
    $amount         = $_POST['amount'];
    $message        = $_POST['message'];
    $tasker_id      = $_POST['tasker_id'];
    $task_id        = $_POST['task_id'];
  
// POST A TASK:
    if(sendOffer($amount, $message, $tasker_id, $task_id, $sqlConnection))
    {
        {echo "SUCCESS";}
    }
    else
        {echo "ERROR";}


// FUNCTIONS:
    function sendOffer($amount, $message, $tasker_id, $task_id, $sqlConnection)
    {
        $query = "INSERT INTO offer (amount, `message`, tasker_id, task_id) 
                  VALUES (?,?,?,?)";

        if($preparedStatement = $sqlConnection->prepare($query))
        {
            // If successful bind parameters.
            $preparedStatement->bind_param("ssss", $amount, $message, $tasker_id, $task_id);
            
            if ($preparedStatement->execute()) 
            {
                return true;
            } 

            $preparedStatement->close();
            $sqlConnection->close();
        }
        else
        {
            echo $sqlConnection->error;
        }
    }
?>