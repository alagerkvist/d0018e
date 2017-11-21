<?php

function relocate(){
  header("Location: /index.php");
  exit();
}

if(!isset($_SESSION['user'])){
  relocate();
}

if($_SESSION['user']->usertype == "Customer"){
  relocate();
}
?>
