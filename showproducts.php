<?php
  session_start();
  require_once 'include/conn.php';
  if(isset($_GET['category'])){
    $headerTitle = $_GET['category'];
    $stmt = $conn->prepare("SELECT * FROM product WHERE prodCatTitle = ?");
    $stmt->bindParam(1, $_GET['category']);

  }else{
    $stmt = $conn->prepare("SELECT * FROM product");
    $headerTitle ="";
  }
  $stmt->execute();
?>
<html>
<head>
<title>Products <?php echo $headerTitle; ?></title>
</head>
<body>
  <?php
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  echo '<table>
    <tr>
      <th>Title</th>
      <th>Quantity</th>
      <th>price</th>
      <th>category</th>
    </tr>';
    while($row = $stmt->fetch()){
      echo '<tr>
      <td><a href="product.php?id='.$row['productID'].'">'.$row['title'].'</a></td>
      <td>'.$row['qty'].'</td>
      <td>'.$row['price'].'</td>
      <td>'.$row['prodCatTitle'].'</td>
      </tr>';
    }

  echo '</table>';

  ?>
