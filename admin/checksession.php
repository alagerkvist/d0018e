<?php
if(!isset($_SESSION['user'])){
  header("Location: /index.php");
  exit();
}

if($_SESSION['user']->usertype == "Customer"){
  header("Location: /index.php");
  exit();
}
?>
