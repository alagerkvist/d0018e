<?php
require_once '../include/Person.php';
session_start();
require_once 'checksession.php';
require_once '../include/conn.php';

$msg = '';

if(isset($_POST['update'])){
  foreach($_POST as $id => $value){
    if($id != "update"){
      try{
        $conn->beginTransaction();
        $stmtUpdateOrder = $conn->prepare("UPDATE `order` SET `orderStatus` = ? WHERE orderID = ?");
        $stmtUpdateOrder->bindParam(1, $value);
        $stmtUpdateOrder->bindParam(2, $id);
        $stmtUpdateOrder->execute();
        $checkQty = 1;

        if($value == "Accepted"){
          $stmtSelectOrder = $conn->prepare("SELECT qty, productID FROM `orderSpec` WHERE orderID = ?");
          $stmtSelectOrder->bindParam(1, $id);
          $stmtSelectOrder->execute();
          $stmtSelectOrder->setFetchMode(PDO::FETCH_ASSOC);

          while($row = $stmtSelectOrder->fetch()){
            $stmtCheckQty = $conn->prepare("SELECT qty FROM `product` WHERE productID = ?");
            $stmtCheckQty->bindParam(1, $row['productID']);
            $stmtCheckQty->execute();
            $checkRow = $stmtCheckQty->fetch(PDO::FETCH_ASSOC);
            //$checkRow how many there is in product
            if($row['qty'] <= $checkRow['qty']){
              $stmtUpdateQty = $conn->prepare("UPDATE `product` SET `qty` = `qty`- ? WHERE productID = ?");
              $stmtUpdateQty->bindParam(1, $row['qty']);
              $stmtUpdateQty->bindParam(2, $row['productID']);
              $stmtUpdateQty->execute();
            }else{
              $conn->rollBack();
              $msg .= 'Order '.$id.' didnt go thru cause of low qty <br />';
              $checkQty = 0;
              break;
            }
          }
        }
        if($checkQty){
          $conn->commit();
          header("Location: processOrder.php");
          exit();
        }
      }catch(Exception $e){
        //echo 'BIG NO!<br />';
        echo $e;
        $conn->rollBack();
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <link href="../CSS/styles.css" rel="stylesheet">
    <script language="javascript">
      function show(x){
        let a = document.getElementById(x);
        if(a.style.display == "none" ){
          a.style.display = "";
        }else{
          a.style.display = "none";
        }
      }

    </script>
  </head>

  <body>
    <?php
      require_once '../include/header.php';
      echo $msg;
      function getData($orderStatus, $conn){
        $stmtProcessing = $conn->prepare("SELECT t1.*, t2.userID, t2.fname, t2.lname FROM `order` as t1, `user` as t2 WHERE t1.orderStatus = ? and t1.userID = t2.userID ORDER BY orderDate");
        $stmtProcessing->bindParam(1, $orderStatus);
        $stmtProcessing->execute();
        $stmtProcessing->setFetchMode(PDO::FETCH_ASSOC);
        echo '<h2>Table for '.$orderStatus.' orders</h2>';
        if($stmtProcessing->rowCount() > 0){
          echo '
          <table>
          <th>OrderID</th>
          <th>User</th>
          <th>Status</th>
          <th>Date</th>
          <th>Total price</th>';
          if($orderStatus == "Processing"){
            echo '
            <th></th>
            <th>Accept</th>
            <th>Decline</th>
            <form method="post" action="processOrder.php">';
          }
          while($row = $stmtProcessing->fetch()){
            echo '<tr>
              <td>'.$row['orderID'].'</td>
              <td>'.$row['fname'].' '.$row['lname'].'</td>
              <td>'.$row['orderStatus'].'</td>
              <td>'.$row['orderDate'].'</td>
              <td>'.$row['totalPrice'].'</td>
              <td><button type="button" onclick="show(\'table_'.$row['orderID'].'\');">more info</button></td>';
              if($orderStatus == "Processing"){
                echo '
                <td><input type="radio" name="'.$row['orderID'].'" value="Accepted" /></td>
                <td><input type="radio" name="'.$row['orderID'].'" value="Declined" /></td>';
              }
            echo '</tr>';
            $stmtOrderSpec = $conn->query("SELECT t1.*, t2.title, t2.productID FROM `orderSpec` as t1, `product` as t2 WHERE t1.orderID = ".$row['orderID']." AND t2.productID = t1.productID");
            $stmtOrderSpec->setFetchMode(PDO::FETCH_ASSOC);

            echo '
            <tr>
              <td colspan="4">
              <table id="table_'.$row['orderID'].'" style="display: none;">';
              while($rad = $stmtOrderSpec->fetch()){
                echo '<tr>
                <td>'.$rad['title'].'</td>
                <td>'.$rad['qty'].'</td>
                <td>'.$rad['price'].'</td>
                <tr>';
              }
            echo '</table>
              </td>
            </tr>
              ';
          }
          echo '</table>';
          if($orderStatus == "Processing"){

            echo '<button type="submit" name="update">Update Orders</button></form>';
          }
        }else{
          echo '<h4>No orders here</h4>';
        }
      }

      getData("Processing", $conn);
      getData("Accepted", $conn);
      getData("Declined", $conn);
    ?>
  </body>
</html>
