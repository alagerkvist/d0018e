<?php
require_once 'include/Person.php';
session_start();
require_once 'include/conn.php';
if(!isset($_SESSION['user'] )){
  header("Location: index.php");
  exit();
}

function getData($orderStatus, $conn){
  $stmtProcessing = $conn->query("SELECT * FROM `order` WHERE orderStatus = '".$orderStatus."' AND userID = '".$_SESSION['user']->id."' ORDER BY orderDate");
  $stmtProcessing->setFetchMode(PDO::FETCH_ASSOC);
  echo '
  <h2>'.$orderStatus.' orders</h2>';
  if($stmtProcessing->rowCount() > 0){
    echo '
    <table>
    <tr>
      <th>OrderID</th>
      <th>UserID</th>
      <th>Status</th>
      <th>Date</th>
      <th>Total price</th>
    </tr>';

    while($row = $stmtProcessing->fetch()){
      echo '<tr>
        <td>'.$row['orderID'].'</td>
        <td>'.$row['userID'].'</td>
        <td>'.$row['orderStatus'].'</td>
        <td>'.$row['orderDate'].'</td>
        <td>'.$row['totalPrice'].'</td>
        <td><button type="button" onclick="show(\'table_'.$row['orderID'].'\');">more info</button></td>';
      echo '</tr>';
      $stmtOrderSpec = $conn->prepare("SELECT t1.*, t2.title, t2.productID, t3.orderID, t3.userID FROM
        `orderSpec` as t1, `product` as t2, `order` as t3
        WHERE t1.orderID = ? AND t2.productID = t1.productID AND t3.orderID = t1.orderID AND t3.userID = ?");

      $stmtOrderSpec->bindParam(1, $row['orderID']);
      $stmtOrderSpec->bindParam(2, $_SESSION['user']->id);
      $stmtOrderSpec->execute();

      $stmtOrderSpec->setFetchMode(PDO::FETCH_ASSOC);
      echo '
      <tr>
        <td colspan="4">
        <table id="table_'.$row['orderID'].'" style="display: none;">
          <tr>
            <th>Title</th>
            <th>Qty</th>
            <th>Price</th>
          </tr>';

        while($rad = $stmtOrderSpec->fetch()){
          echo '<tr>
          <td>'.$rad['title'].'</td>
          <td>'.$rad['qty'].'</td>
          <td>'.$rad['price'].'</td>
          </tr>';
        }
      echo '</table>
        </td>
        </tr>';
    }
    echo '  </table>';
  }else{
    echo '<h4>No orders yet, go and buy a car!</h4>';
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>My orders</title>
    <link href="/CSS/styles.css" rel="stylesheet">
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
  <?php
  require_once 'include/header.php';
  ?>
  <body>
    <?php
      getData('Processing', $conn);
      getData('Accepted', $conn);

    ?>
  </body>
</html>
