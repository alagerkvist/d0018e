<?php
  require_once '../include/Person.php';
  session_start();
  require_once 'checksession.php';
  require_once '../include/conn.php';
  if(isset($_POST['addProd'])){
    $stmt = $conn->prepare("INSERT INTO product (title,`desc`, qty, price, prodCatTitle) VALUES (?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $_POST['title']);
    $stmt->bindParam(2, $_POST['desc']);
    $stmt->bindParam(3, $_POST['qty'], PDO::PARAM_INT);
    $stmt->bindParam(4, $_POST['price'], PDO::PARAM_INT);
    $stmt->bindParam(5, $_POST['cat']);
    $stmt->execute();
    //header("Location: addProduct.php");
    //exit();
  }
  if(isset($_POST['addCat'])){
    $stmt = $conn->prepare("INSERT INTO prodCat (title) VALUES (?)");
    $stmt->bindParam(1, $_POST['title']);
    $stmt->execute();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Add product</title>
    <link href="../CSS/styles.css" rel="stylesheet">
  </head>
  <?php
  	require_once '../include/header.php';
  ?>
  <body>
    <form method="post" action="addProduct.php">
      <p>Title: <input type="text" name="title" /></p>
      <p>Price: <input type="text" name="price" /></p>
      <p>Qty: <input type="text" name="qty" /></p>
      <p>
        <select name="cat">
        <?php
          $stmt = $conn->query("SELECT * FROM prodCat");
          $stmt->setFetchMode(PDO::FETCH_ASSOC);
          while($row = $stmt->fetch()){
            echo '<option value='.$row['title'].'>'.$row['title'].'</option>';
          }
        ?>
        </select>
      </p>
      <p>Description: <br /><textarea cols="50" rows="5" name="desc"></textarea></p>
      <p><input type="submit" name="addProd" value="Add product!" /></p>
    </form>
    <br>
    <form method="post" action="addProduct.php">
      <p>Title: <input type="text" name="title" /></p>
      <p><input type="submit" name="addCat" value="Add category!" /></p>
    </form>
  </body>
</html>
