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
        $category_name         = $_POST['category_name'];

        $available_tasks             = array();
        $temp                  = array();

        $query                 = "SELECT task.task_id, task.title, DATE_FORMAT(task.date_time_end,'%m-%d-%Y'), address.line_one, address.city, task.task_fee, task.status, user.profile_picture 
                                  FROM `task`
                                  INNER JOIN task_address ON task_address.task_address_id = task.task_id
                                  INNER JOIN address ON address.address_id = task_address.task_address_id
                                  INNER JOIN user ON user.user_id = task.task_giver_id
                                  INNER JOIN task_category ON task_category.task_category_id = task.task_category_id
                                  WHERE task_category.name = '{$category_name}'";

//LOAD TASK CATEGORIES:

    $preparedStatement = $sqlConnection->prepare($query);
    $preparedStatement->execute();
    $preparedStatement->bind_result($task_id, $title, $date_time_end, $line_one, $city, $task_fee, $status, $profile_picture);
        
    while($preparedStatement->fetch())
    {
        $temp = array();
        $temp['task_id']       = $task_id; 
        $temp['title']         = $title; 
        $temp['date_time_end'] = date('l', strtotime($date_time_end)).", ".$date_time_end;
        $temp['line_one']      = $line_one;
        $temp['city']          = $city;
        $temp['task_fee']      = $task_fee;
        $temp['status']        = $status;
        $temp['profile_picture']     = str_replace('..', '', $profile_picture);
        array_push($available_tasks, $temp);
    }
    
    echo json_encode($available_tasks);
 ?>