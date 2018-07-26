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
        $task_giver_id         = $_POST['task_giver_id'];

        $my_tasks              = array();
        $temp                  = array();

        $query                 = "SELECT task.task_id, task.title, task.image_one, DATE_FORMAT(task.date_time_end,'%m-%d-%Y'), address.line_one, task.task_fee, task.status FROM `task` 
                                  INNER JOIN task_address ON task_address.task_address_id = task.task_id
                                  INNER JOIN address ON address.address_id = task_address.task_address_id
                                  WHERE task.task_giver_id = '{$task_giver_id}'";

//LOAD TASK CATEGORIES:

    $preparedStatement = $sqlConnection->prepare($query);
    $preparedStatement->execute();
    $preparedStatement->bind_result($task_id, $title, $image_one, $date_time_end, $address, $task_fee, $status);
        
    while($preparedStatement->fetch())
    {
        $temp = array();
        $temp['task_id']       = $task_id; 
        $temp['title']         = $title; 
        $temp['image_one']     = str_replace('..', '', $image_one);
        $temp['date_time_end'] = date('l', strtotime($date_time_end)).", ".$date_time_end;
        $temp['address']       = $address;
        $temp['task_fee']      = $task_fee;
        $temp['status']        = $status;
        array_push($my_tasks, $temp);
    }
    
    echo json_encode($my_tasks);
 ?>