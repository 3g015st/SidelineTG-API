<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load libraries.
        include_once '../config/dbConfig.php';

    // Verify Email if it exists
        function isEmailExists($email, $sqlConnection)
        {
            $query = "SELECT email FROM user WHERE email = ?";

            if($preparedStatement = $sqlConnection->prepare($query))
            {
                $preparedStatement->bind_param("s", $email);
                $preparedStatement->execute();
                $rowCount = $preparedStatement->fetch();
            
                if($rowCount > 0)
                    {return true;}
                else
                    {return false;}

                $preparedStatement->close();
                $sqlConnection->close();
            }
            else
            {
                echo $sqlConnection->error;
            }
        }

    // Encrypt Password
        function encryptPassword($password)
        {
            $encryptedPassword = '';
            $options  = ['cost' => 11];
            $encryptedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
        
            return $encryptedPassword;
        }

    // Insert address to the database
       function insertAddress($line_one, $city, $sqlConnection)
       {
           $query = "INSERT INTO address (line_one,city) 
                     VALUES (?,?)";
   
           if($preparedStatement = $sqlConnection->prepare($query))
           {
               // If successful bind parameters.
               $preparedStatement->bind_param("ss",$line_one,$city);
               
               if ($preparedStatement->execute()) 
               {
                   $ADDRESS_ID = $sqlConnection->insert_id;
                   return $ADDRESS_ID;
               } 
               else 
               {
                   return false;
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