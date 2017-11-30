<?php
  require_once 'include/Person.php';
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

  if(isset($_POST['addToCart'])){
    if(!isset($_SESSION['user'])){
      echo '<script language="javascript">alert("Login first!"); </script>';
    }else{
      $_SESSION['cart'][$_GET['id']] = array(1, $row['price']);
      $stmt = $conn->prepare("INSERT INTO basketItem (userID, productID, qty, price) VALUES
      (?, ?, 1, ?)");
      $stmt->bindParam(1, $_SESSION['user']->id);
      $stmt->bindParam(2, $_GET['id']);
      $stmt->bindParam(3, $row['price']);

      $stmt->execute();
    }
  }
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
            <td>'.$row['desc'].'</td>';
            if(!isset($_SESSION['cart'][$_GET['id']])){
              echo '
              <form method="post" action="product.php?id='.$row['productID'].'">
              <td><input type="submit" name="addToCart" value="Add to cart!" /></td>
              </form>';
            }else{
              echo '<td>Product already in cart!</td>';
            }
          echo '
          </tr>
        </table>';
      ?>
  </body>
</html>
