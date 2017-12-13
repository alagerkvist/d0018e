<?php
  require_once '../include/Person.php';
  session_start();
  require_once 'checksession.php';
  require_once '../include/conn.php';
  if(isset($_POST['addProd'])){
    if($_POST['qty'] >= 0){
      $getProductIdSQL = $conn->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'davjom5db' AND TABLE_NAME = 'product' ");
      $getProductId = $getProductIdSQL->fetch(PDO::FETCH_ASSOC);
      $productID = $getProductId['AUTO_INCREMENT'];
      $stmt = $conn->prepare("INSERT INTO product (title,`desc`, qty, price, prodCatTitle) VALUES (?, ?, ?, ?, ?)");
      $stmt->bindParam(1, $_POST['title']);
      $stmt->bindParam(2, $_POST['desc']);
      $stmt->bindParam(3, $_POST['qty'], PDO::PARAM_INT);
      $stmt->bindParam(4, $_POST['price'], PDO::PARAM_INT);
      $stmt->bindParam(5, $_POST['cat']);

      //From WC3School
      if(getimagesize($_FILES["pic"]["tmp_name"]) !== false) {
        $target_dir = "../pic/";
        $target_file = $target_dir . basename($_FILES["pic"]["name"]);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        if($imageFileType == "png") {
          if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir.$productID)) {
            $stmt->execute();
          }else{
            echo 'File didnt go thur';
          }
        }else {
          echo 'Wrong file type';
        }
      }
    }
    header("Location: addProduct.php");
    exit();
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
    <form method="post" action="addProduct.php" enctype="multipart/form-data">
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
      <p>Picture to the car: <input type="file" name="pic" id="pic"/></p>
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
