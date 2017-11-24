<?php
require_once 'include/Person.php';
session_start();
require_once 'include/conn.php';
if(!isset($_SESSION['user'])){
  header("Location index.php");
  exit();
}

if(!isset($_SESSION['cart'])){
  header("Location: index.php");
  exit();
}

if(isset($_POST['purchase'])){
  try{
    $conn->beginTransaction();
    $stmtInsert = $conn->prepare("INSERT INTO `order` (userID, totalPrice) VALUES (?, ?)");
    $stmtInsert->bindParam(1, $_SESSION['user']->id);
    $stmtInsert->bindParam(2, $_SESSION['totalPrice']);
    $stmtInsert->execute();
    $getOrderIdSQL = $conn->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'davjom5db' AND TABLE_NAME = 'order' ");
    $getOrderId = $getOrderIdSQL->fetch(PDO::FETCH_ASSOC);
    $orderID = $getOrderId['AUTO_INCREMENT']-1;
    echo $orderID;
    foreach($_SESSION['cart'] as $id => $qty){
      $stmtOrderSpec = $conn->prepare("INSERT INTO `orderSpec` (orderID, productID, qty) VALUES (".$orderID.", ?, ?)");
      $stmtOrderSpec->bindParam(1, $id);
      $stmtOrderSpec->bindParam(2, $qty);
      $stmtOrderSpec->execute();
    }
    $stmtDelete = $conn->exec("DELETE FROM `basketItem` WHERE `userID` = ".$_SESSION['user']->id);
    unset($_SESSION['cart']);
    unset($_SESSION['totalPrice']);
    $conn->commit();

    echo '<script language="javascript">alert("Thank you for your purchase");</script>';
  }catch(Exception $e){
    echo 'purcahse didnt go thru';
    echo $e;
    $conn->rollBack();
  }
}

echo 'CHECKOUT FFS!';
?>
<head>

</head>

<body>

    <?php
    if(isset($_SESSION['cart'])){
      echo '<table>
        <tr>
          <th>Brand</th>
          <th>Model</th>
          <th>Qty</th>
          <th>Price</th>
        </tr>';
        $_SESSION['totalPrice'] = 0;
      foreach($_SESSION['cart'] as $id => $qty){
          $stmt = $conn->prepare("SELECT prodCatTitle, title, productID, qty, price FROM product WHERE productid = ?");
          $stmt->bindParam(1, $id);
          $stmt->execute();
          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          $row = $stmt->fetch();
          $_SESSION['totalPrice'] += $row['price'] * $qty;

          echo '<tr>
          <td>'.$row['prodCatTitle'].'</td>
          <td>'.$row['title']. '</td>
          <td>'.$qty.'</td>
          <td>'.$row['price'].'</td>
          </tr>';
      }
      echo '
      <td></td>
      <td></td>
      <td></td>
      <td>'.$_SESSION['totalPrice'].'</td></table>
      <form method="post" action="checkout.php">
      <button type="submit" name="purchase">Purchase</button>
      </form>';
    }

    ?>

</body>
