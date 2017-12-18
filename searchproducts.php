<?php
  include 'include/conn.php';
  if(isset($_GET['cat'])){
    $stmt = $conn->prepare("SELECT * FROM product WHERE title LIKE CONCAT ('%', ?, '%') AND prodCatTitle = ? AND visible = 1");
    $stmt->bindParam(1, $_GET['search']);
    $stmt->bindParam(2, $_GET['cat']);
  }else{
    $stmt = $conn->prepare("SELECT * FROM product WHERE (title LIKE CONCAT('%', ?, '%') OR prodCatTitle LIKE CONCAT('%', ?, '%')) AND visible = 1 ORDER BY prodCatTitle, title");
    $stmt->bindParam(1, $_GET['search']);
    $stmt->bindParam(2, $_GET['search']);
  }
  $stmt->execute();
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  echo '<table>
  <tr>
    <th></th>
    <th>Title</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Category</th>
  </tr>';
  while($row = $stmt->fetch()){
    echo '<tr>
    <td><img src="/pic/'.$row['productID'].'" width="20" height="20" /></td>
    <td><a href="product.php?id='.$row['productID'].'">'.$row['title'].'</a></td>
    <td>'.$row['qty'].'</td>
    <td>'.$row['price'].'</td>
    <td>'.$row['prodCatTitle'].'</td>
    </tr>';
  }
  echo '</table>';
?>
