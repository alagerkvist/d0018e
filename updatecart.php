<?php
  include 'include/Person.php';
  session_start();
  include 'include/conn.php';

  try{
    $stmt = $conn->prepare("UPDATE basketItem SET qty = ? WHERE userID = ? and productID = ? LIMIT 1");
    $stmt->bindParam(1, $_GET['qty']);
    $stmt->bindParam(2, $_SESSION['user']->id);
    $stmt->bindParam(3, $_GET['id']);
    $stmt->execute();
    $_SESSION['cart'][$_GET['id']] = $_GET['qty'];
    echo 'Product updated!';
  }catch(Exception $e){
    echo 'Not a number';
  }
?>
