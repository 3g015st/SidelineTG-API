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
    $image_one          = $_POST['image_one'];
    $image_two          = $_POST['image_two'];

    $title              = $_POST['title'];
    $description        = $_POST['description'];
    $date_time_end      = date('Y-m-d', strtotime($_POST['date_time_end']));
    $task_fee           = $_POST['task_fee'];
    $task_giver_id      = $_POST['task_giver_id'];
    $task_category_id   = $_POST['task_category_id'];
    $line_one           = $_POST['line_one'];
    $city               = $_POST['city'];
    $latitude           = $_POST['latitude'];
    $longitude          = $_POST['longitude'];
  
    $TASK_ID            = '';
    $TASK_IMAGE_FOLDER  = '';

// POST A TASK:
    if(insertTaskInformation($title, $description, $date_time_end, $task_fee, $task_giver_id, $task_category_id, $sqlConnection) && $ADDRESS_ID = insertAddress($line_one, $city, $sqlConnection))
    {
        if(insertTaskAddress($latitude, $longitude, $ADDRESS_ID, $TASK_ID, $sqlConnection) && uploadTaskPhotos($image_one, $image_two, $sqlConnection))
            {echo "SUCCESS";}
        else
            {echo "ERROR";}
    }
    else
        {echo "ERROR";}


// FUNCTIONS:
    function insertTaskInformation($title, $description, $date_time_end, $task_fee, $task_giver_id, $task_category_id, $sqlConnection)
    {
        $query = "INSERT INTO task (title, `description`, date_time_end, task_fee, task_giver_id, task_category_id) 
                  VALUES (?,?,?,?,?,?)";

        if($preparedStatement = $sqlConnection->prepare($query))
        {
            // If successful bind parameters.
            $preparedStatement->bind_param("ssssss", $title, $description, $date_time_end, $task_fee, $task_giver_id, $task_category_id);
            
            if ($preparedStatement->execute()) 
            {
                global $TASK_ID;
                $TASK_ID = $sqlConnection->insert_id;

                // Make task photos folder
                global $TASK_IMAGE_FOLDER;
                $TASK_IMAGE_FOLDER = "../../uploads/images/task_imgs/$TASK_ID";
                if(!file_exists($TASK_IMAGE_FOLDER))
                {
                    mkdir($TASK_IMAGE_FOLDER, 0777, true);
                }
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

    function insertTaskAddress($latitude, $longitude, $ADDRESS_ID, $TASK_ID, $sqlConnection)
    {
        $query = "INSERT INTO task_address (latitude, longitude, address_id, task_id) 
                  VALUES (?,?,?,?)";

        if($preparedStatement = $sqlConnection->prepare($query))
        {
            // If successful bind parameters.
            $preparedStatement->bind_param("ssss", $latitude, $longitude, $ADDRESS_ID, $TASK_ID);
            
            if ($preparedStatement->execute()) 
                {return true;} 
            else 
                {return false;}
            $preparedStatement->close();
            $sqlConnection->close();
        }
        else
        {echo $sqlConnection->error;}
    }

    function uploadTaskPhotos($image_one, $image_two, $sqlConnection)
    {
        global $TASK_ID;
        global $TASK_IMAGE_FOLDER;
        $query = "UPDATE task SET image_one = ?, image_two = ? WHERE task_id = ?";

        $IMAGE_ONE_DIR = $TASK_IMAGE_FOLDER."/"."image_one".".png";
        $IMAGE_TWO_DIR = $TASK_IMAGE_FOLDER."/"."image_two".".png";
    
        if($preparedStatement = $sqlConnection->prepare($query))
        {
            // If successful bind parameters.
            $preparedStatement->bind_param("sss", $IMAGE_ONE_DIR, $IMAGE_TWO_DIR, $TASK_ID);
            
            if($preparedStatement->execute()) 
            {
                if(file_put_contents($IMAGE_ONE_DIR, base64_decode($image_one)) && file_put_contents($IMAGE_TWO_DIR, base64_decode($image_two)))
                    {return true;}
                else
                    {return false;}
            } 
            else 
                {return false;}
            $preparedStatement->close();
            $sqlConnection->close();
        }
        else
        {echo $sqlConnection->error;}
    }
?>