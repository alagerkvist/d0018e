<?php
  try{
    $conn = new PDO("mysql:host=localhost;dbname=test", "root", "");
    //$conn = new PDO("mysql:host=utbweb.its.ltu.se;dbname=davjom5db", "davjom-5", "***");
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    //echo "IT WORKED!";
  }catch(PDOException $e){
    $e->getMessage();
  }
?>
