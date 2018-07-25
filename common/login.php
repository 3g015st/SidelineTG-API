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
        $email      = $_POST['email'];
        $password   = $_POST['password'];
        $role       = $_POST['role'];
        $user       = array();
        $temp       = array();

        $query      = '';

//LOGIN:

//Check roles
        if($role == "Tasker")
        {
            $query = "SELECT user_id,password FROM user WHERE email = ? AND role = ? AND isactive = 'YES'";
            loginUser($query, $sqlConnection);
        }
        elseif($role == "Task Giver")
        {
            $query = "SELECT user_id,password FROM user WHERE email = ? AND role = ? AND isactive = 'YES'";
            loginUser($query, $sqlConnection);
        }
        

//FUNCTIONS:

    function loginUser($query, $sqlConnection)
    {
        global $user, $temp;
        global $query, $role, $email, $password;
       
        if($preparedStatement = $sqlConnection->prepare($query))
        {
            $preparedStatement->bind_param('ss', $email, $role); 
            $preparedStatement->execute();

            //Bind results to variable.
            $preparedStatement->bind_result($user_id, $encryptedPassword);
            
            $rowCount = $preparedStatement->fetch();  

            if($rowCount)
            {
                if(password_verify($password, $encryptedPassword))
                {
                    $temp['message']       = $user_id; 
                    $temp['response_code'] = "SUCCESS"; 
                    array_push($user, $temp);
                    echo json_encode($user);      
                }
                else
                {
                    //Invalid email address or password
                    $temp['message']       = "Invalid email or password";
                    $temp['response_code'] = "ERROR"; 
                    array_push($user, $temp);
                    echo json_encode($user);
                }
            }
            else
            {
                //Account does not exists.
                $temp['message']       = "Account does not exists.";
                $temp['response_code'] = "ERROR"; 
                array_push($user, $temp);
                echo json_encode($user);          
            }
        }
        else
            {echo $sqlConnection->error;}
    }
?>