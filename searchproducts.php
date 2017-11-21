<?php
  include 'include/conn.php';
  $stmt = $conn->prepare("SELECT * FROM product WHERE title LIKE CONCAT('%', ?, '%') OR prodCatTitle LIKE CONCAT('%', ?, '%')");
  $stmt->bindParam(1, $_GET['search']);
  $stmt->bindParam(2, $_GET['search']);
  $stmt->execute();
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  echo '<table>
  <tr>
    <th>Title</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Category</th>
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
