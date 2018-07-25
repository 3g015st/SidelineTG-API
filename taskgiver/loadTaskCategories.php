<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    //Load libraries.
        include_once '../config/dbConfig.php';
        
    //Connect to database.
        global $sqlConnection;
        $sqlConnection = mysqli_connect($serverName,$username,$password,$dbName);
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to the server: " . mysqli_connect_error();
            die();
        }

    //Initialize variables
        $task_categories       = array();
        $temp                  = array();
        $query                 = "SELECT task_category_id, `name`, minimum_payment, task_category_img 
                                  FROM   task_category
                                  WHERE  `status` = 'AVAILABLE'";

//LOAD TASK CATEGORIES:

    $preparedStatement = $sqlConnection->prepare($query);
    $preparedStatement->execute();
    $preparedStatement->bind_result($task_category_id, $name, $minimum_payment, $task_category_img);
        
    while($preparedStatement->fetch())
    {
        $temp = array();
        $temp['task_category_id'] = $task_category_id; 
        $temp['name'] = $name; 
        $temp['minimum_payment'] = $minimum_payment;
        $temp['task_category_img'] = $task_category_img;  
        array_push($task_categories, $temp);
    }
    
    echo json_encode($task_categories);
 ?>