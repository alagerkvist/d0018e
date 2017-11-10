<?php
  session_start();
  require_once 'include/conn.php';
  function relocate(){
    header("Location: index.php");
    exit();
  }
  if(isset($_GET['id'])){
    $stmt = $conn->prepare("SELECT * FROM product WHERE productID = ? LIMIT 1");
    $stmt->bindParam(1, $_GET['id']);
    $stmt->execute();
    if($stmt->rowCount() <= 0){
      relocate();
    }
  }else{
    relocate();
  }
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $row = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $row['prodCatTitle'].' - '.$row['title']; ?></title>
    <link href="/CSS/styles.css" rel="stylesheet">
  </head>
  <?php
  require_once 'include/header.php';
  ?>
  <body>
    <?php
      echo'
        <table>
          <tr>
            <th>Title</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Category</th>
            <th>Description</th>
          </tr>
          <tr>
            <td>'.$row['title'].'</td>
            <td>'.$row['qty'].'</td>
            <td>'.$row['price'].'</td>
            <td>'.$row['prodCatTitle'].'</td>
            <td>'.$row['desc'].'</td>
          </tr>
        </table>';
      ?>
  </body>
</html>
