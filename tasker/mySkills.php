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
        $tasker_id         = $_POST['tasker_id'];    

        $skills                = array();
        $temp                  = array();
        $query                 = "SELECT skill.skill_id, skill.name FROM skill INNER JOIN skillset ON skill.skill_id = skillset.skillset_id WHERE skillset.tasker_id = '{$tasker_id}'";

//LOAD TASK CATEGORIES:

    $preparedStatement = $sqlConnection->prepare($query);
    $preparedStatement->execute();
    $preparedStatement->bind_result($skill_id, $name);
        
    while($preparedStatement->fetch())
    {
        $temp = array();
        $temp['skill_id'] = $skill_id; 
        $temp['name'] = $name; 
        array_push($skills, $temp);
    }
    
    echo json_encode($skills);
 ?>