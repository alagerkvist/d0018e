<?php
require_once '../include/Person.php';
session_start();
require_once 'checksession.php';
require_once '../include/conn.php';

if(isset($_POST['update'])){
  foreach($_POST as $id => $value){
    if($id != "update"){
      $stmt = $conn->prepare("UPDATE `order` SET `orderStatus` = ? WHERE orderID = ?");
      $stmt->bindParam(1, $value);
      $stmt->bindParam(2, $id);
      $stmt->execute();

      //UPDATE `product` SET `qty` = `qty`- qty WHERE productID = productID
    }
  }
  header("Location: processOrder.php");
  exit();
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

      function getData($orderStatus, $conn){
        $stmtProcessing = $conn->query("SELECT * FROM `order` WHERE orderStatus = '".$orderStatus."' ORDER BY orderDate");
        $stmtProcessing->setFetchMode(PDO::FETCH_ASSOC);
        echo '
        <h2>Table for '.$orderStatus.' orders</h2>
        <table>
        <th>OrderID</th>
        <th>UserID</th>
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
            <td>'.$row['userID'].'</td>
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
      }

      getData("Processing", $conn);
      getData("Accepted", $conn);
      getData("Declined", $conn);
    ?>
  </body>
</html>
