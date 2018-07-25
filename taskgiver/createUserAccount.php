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
        $email          = $_POST['email'];
        $password       = $_POST['password'];
        $first_name     = $_POST['first_name'];
        $last_name      = $_POST['last_name'];
        $gender         = $_POST['gender'];
        $birth_date     = date('Y-m-d', strtotime($_POST['birth_date']));
        $mobile_number  = $_POST['mobile_number'];
        $line_one       = $_POST['line_one'];
        $city           = $_POST['city'];
        $role           = $_POST['role'];

        $USER_ID        = '';

// USER ACCOUNT CREATION:
    if(insertUserInformation($first_name, $last_name, $birth_date, $mobile_number, $gender, $email, $password, $role, $sqlConnection) && 
       $ADDRESS_ID = insertAddress($line_one, $city, $sqlConnection))
    {
        if(insertUserAddress($ADDRESS_ID, $USER_ID, $sqlConnection))
            {echo "SUCCESS";}
        else
            {echo "ERROR";}
    }
    else
        {echo "ERROR";}


// FUNCTIONS:

    // Insert task giver's info.
    function insertUserInformation($first_name, $last_name, $birth_date, $mobile_number, $gender, $email, $password, $role, $sqlConnection)
    {
        $query = "INSERT INTO user (first_name, last_name, birth_date, mobile_number, gender, email, `password`, `role`) 
                  VALUES (?,?,?,?,?,?,?,?)";

        // Encrypt password before insertion.
        $password = encryptPassword($password);

        if($preparedStatement = $sqlConnection->prepare($query))
        {
            // If successful bind parameters.
            $preparedStatement->bind_param("ssssssss", $first_name, $last_name, $birth_date, $mobile_number, $gender, $email, $password, $role);
            
            if ($preparedStatement->execute()) 
            {
                global $USER_ID;
                $USER_ID = $sqlConnection->insert_id;
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
   
    // Insert task giver's address to the database
    function insertUserAddress($ADDRESS_ID, $USER_ID, $sqlConnection)
    {
        $query = "INSERT INTO user_address (address_id, user_id) 
                  VALUES (?,?)";

        if($preparedStatement = $sqlConnection->prepare($query))
        {
            // If successful bind parameters.
            $preparedStatement->bind_param("ss", $ADDRESS_ID, $USER_ID);
            
            if ($preparedStatement->execute()) 
                {
                    return true;
                } 
            else 
                {return false;}
            $preparedStatement->close();
            $sqlConnection->close();
        }
        else
            {echo $sqlConnection->error;}
    }

