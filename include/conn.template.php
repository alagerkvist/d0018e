<?php
  /*
  * To connect to your database:
  *   1. Copy file to the include folder, and rename to "conn.php"
  *   2. Change the four variables to match your database credentials
  */

  $serverAddress = "localhost";
  $dbName = "myDatabase";
  $userName = "username";
  $password = "password";

  try{
    $conn = new PDO("mysql:host=$serverAddress;dbname=$dbName", "$userName", "$password");
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    //echo "IT WORKED!";
  }catch(PDOException $e){
    $e->getMessage();
  }
?>
