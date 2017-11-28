<?php
  require_once '../include/Person.php';
  session_start();
  require_once 'checksession.php';
  require_once '../include/conn.php';
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
  if(isset($_POST['updateProd'])){
    $stmt = $conn->prepare("UPDATE product SET title = ?, `desc` = ?, qty = ?, price = ?, prodCatTitle = ? WHERE productID = ?");
    $stmt->bindParam(1, $_POST['title']);
    $stmt->bindParam(2, $_POST['desc']);
    $stmt->bindParam(3, intval($_POST['qty']), PDO::PARAM_INT);
    $stmt->bindParam(4, intval($_POST['price']), PDO::PARAM_INT);
    $stmt->bindParam(5, $_POST['cat']);
    $stmt->bindParam(6, $_GET['id']);
    echo $_GET['id'];
    $stmt->execute();
    header("Location: updateProduct.php?id=".$_GET['id']);
    exit();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Add product</title>
  </head>
  <body>
    <?php
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $row = $stmt->fetch();
      echo '<form method="post" action="updateProduct.php?id='.$row['productID'].'">
      <p>Title: <input type="text" name="title" value="'.$row['title'].'"/></p>
      <p>Price: <input type="text" name="price" value="'.$row['price'].'"/></p>
      <p>Qty: <input type="text" name="qty" value="'.$row['qty'].'"/></p>
      <select name="cat">';
        $stmtCat = $conn->query("SELECT * FROM prodCat");
        $stmtCat->setFetchMode(PDO::FETCH_ASSOC);
        while($rad = $stmtCat->fetch()){
          if($rad['title'] == $row['prodCatTitle']){
            echo '<option value="'.$rad['title'].'" selected>'.$rad['title'].'</option>';
          }else{
            echo '<option value="'.$rad['title'].'">'.$rad['title'].'</option>';
          }
        }
      echo '</select>
      </p>
      <p>Description: <br /><textarea cols="50" rows="5" name="desc">'.$row['desc'].'</textarea></p>
      <p><input type="submit" name="updateProd" value="Update product!" /></p>
      </form>';
    ?>
  </body>
</html>
