<?php
  require_once '../include/Person.php';
  session_start();
  require_once 'checksession.php';
  require_once '../include/conn.php';

  if(isset($_POST['updateProd'])){
    if($_POST['qty'] >= 0){
      $stmt = $conn->prepare("UPDATE product SET title = ?, `desc` = ?, qty = ?, price = ?, prodCatTitle = ?, visible = ? WHERE productID = ?");
      $stmt->bindParam(1, $_POST['title']);
      $stmt->bindParam(2, $_POST['desc']);
      $stmt->bindParam(3, intval($_POST['qty']), PDO::PARAM_INT);
      $stmt->bindParam(4, intval($_POST['price']), PDO::PARAM_INT);
      $stmt->bindParam(5, $_POST['cat']);
      $stmt->bindParam(6, $_POST['visible']);
      $stmt->bindParam(7, $_GET['id']);
      if(getimagesize($_FILES["pic"]["tmp_name"]) !== false) {
        $target_dir = "../pic/";
        $target_file = $target_dir . basename($_FILES["pic"]["name"]);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir.$_GET['id'])) {

        }else{
          echo 'File didnt go thur';
        }
      }else {
        echo 'Wrong file';
      }
      $stmt->execute();
    }
    header("Location: updateProduct.php");
    exit();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Update product</title>
    <link href="../CSS/styles.css" rel="stylesheet">
  </head>
  <body>
    <?php
      require_once '../include/header.php';
      if(isset($_GET['id'])){
        $stmt = $conn->prepare("SELECT * FROM product WHERE productID = ? LIMIT 1");
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();
        if($stmt->rowCount() <= 0){
           header("Location: updateProduct.php");
           exit();
        }
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        echo '<form method="post" action="updateProduct.php?id='.$row['productID'].'" enctype="multipart/form-data">
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
        <p>Visible</p>';
        if($row['visible'] == 1){
            echo '<p><input type="radio" name="visible" value="1" checked/> Yes <input type="radio" name="visible" value="0" /> No</p>';
        }else{
          echo '<p><input type="radio" name="visible" value="1" /> Yes <input type="radio" name="visible" value="0" checked/> No</p>';
        }

        echo '
        <p>Picture to the car: <input type="file" name="pic" id="pic"/> Pic right now : <img src="/pic/'.$_GET['id'].'" width="20" height="20" /></p>
        <p>Description: <br /><textarea cols="50" rows="5" name="desc">'.$row['desc'].'</textarea></p>
        <p><input type="submit" name="updateProd" value="Update product!" /><a href="updateProduct.php"><button type="button">Cancel</button></a></p>
        </form>';
      }else{
        $stmt = $conn->query("SELECT * FROM `product` ORDER BY prodCatTitle, title");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        echo '<table>
        <th>Title</th>
        <th>Category</th>
        <th>Visible</th>';
        while($row = $stmt->fetch()){
          $visible = ($row['visible'] == 1) ? 'Active' : 'Removed';
          echo '<tr>
          <td>'.$row['title'].'</td>
          <td>'.$row['prodCatTitle'].'</td>
          <td>'.$visible.'</td>
          <td><a href="updateProduct.php?id='.$row['productID'].'"><button type="button">Edit</button></a></td>
          </tr>';
        }
      }
    ?>
  </body>
</html>
