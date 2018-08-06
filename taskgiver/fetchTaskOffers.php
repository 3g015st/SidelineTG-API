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
        $task_id               = $_POST['task_id'];

        $task_offers           = array();
        $temp                  = array();
        $query                 = "SELECT user.user_id, user.profile_picture, user.first_name, user.last_name, COUNT(evaluation.review), AVG(evaluation.rating), offer.amount, offer.message
                                  FROM `offer`
                                  INNER JOIN user ON user.user_id = offer.tasker_id
                                  LEFT JOIN evaluation ON evaluation.tasker_id = user.user_id
                                  WHERE offer.task_id = '{$task_id}'
                                  GROUP BY offer.offer_id";

//LOAD TASK OFFERS:

    if($preparedStatement = $sqlConnection->prepare($query))
    {
        $preparedStatement->execute();
        $preparedStatement->bind_result($tasker_id, $profile_picture, $first_name, $last_name, $reviews, $rating, $amount, $message);
   
        while($preparedStatement->fetch())
        {
            $temp = array();

            if(is_null($rating)) 
            {
                $rating = "0";
            }

            $temp['tasker_id']       = $tasker_id;
            $temp['profile_picture'] = str_replace('../..', '', $profile_picture); 
            $temp['first_name']      = $first_name; 
            $temp['last_name']       = $last_name; 
            $temp['rating']          = $rating;
            $temp['reviews']         = $reviews;  
            $temp['amount']          = $amount;  
            $temp['message']         = $message;  
            array_push($task_offers, $temp);
     
        }
        echo json_encode($task_offers);
    }
    else
    {
        echo $sqlConnection->error;
    }
    
 ?>