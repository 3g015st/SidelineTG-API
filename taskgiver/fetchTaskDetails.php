<?php 
    date_default_timezone_set('Asia/Manila');
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
        $TASK_ID               = $_POST['TASK_ID'];

        $task_details          = array();
        $temp                  = array();

        $query                 = "SELECT task.task_id, task.title, DATE_FORMAT(task.date_time_end,'%Y-%m-%d'), address.line_one, address.city, task.task_fee, task.status, user.profile_picture, user.first_name, user.last_name, task_category.name, task.image_one, task.image_two, task.description, DATE_FORMAT(task.date_time_posted,'%m-%d-%Y %H:%i:%s')
                                  FROM `task`
                                  INNER JOIN task_address ON task_address.task_address_id = task.task_id
                                  INNER JOIN address ON address.address_id = task_address.task_address_id
                                  INNER JOIN user ON user.user_id = task.task_giver_id
                                  INNER JOIN task_category ON task_category.task_category_id = task.task_category_id
                                  WHERE task.task_id = '{$TASK_ID}'";

//LOAD TASK CATEGORIES:

    $preparedStatement = $sqlConnection->prepare($query);
    $preparedStatement->execute();
    $preparedStatement->bind_result($task_id, $title, $date_time_end, $line_one, $city, $task_fee, $status, $profile_picture, 
                                    $first_name, $last_name, $category_name, $image_one, $image_two, $description, $date_time_posted);
        
    while($preparedStatement->fetch())
    {
        $temp = array();
        $temp['task_id']            = $task_id; 
        $temp['title']              = $title; 
        $temp['date_time_end']      = date('l', strtotime($date_time_end)).", ".date("m-d-Y", strtotime($date_time_end));
        $temp['line_one']           = $line_one;
        $temp['city']               = $city;
        $temp['task_fee']           = $task_fee;
        $temp['status']             = $status;
        $temp['profile_picture']    = str_replace('../..', '', $profile_picture);
        $temp['first_name']         = $first_name;
        $temp['last_name']          = $last_name;
        $temp['category_name']      = $category_name;
        $temp['image_one']          = str_replace('../..', '', $image_one);
        $temp['image_two']          = str_replace('../..', '', $image_two);
        $temp['description']        = $description;
        $temp['date_time_posted']   = $date_time_posted;
        array_push($task_details, $temp);
    }
    
    echo json_encode($task_details);
 ?>