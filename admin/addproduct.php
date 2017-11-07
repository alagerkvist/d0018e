<?php
  session_start();
  require_once '../include/conn.php';
  if(isset($_POST['addProd'])){
    $stmt = $conn->prepare("INSERT INTO product (title,`desc`, qty, price, prodCatTitle) VALUES (?, ?, ?, ?, ?)");

    $stmt->bindParam(1, $_POST['title']);
    $stmt->bindParam(2, $_POST['desc']);
    $stmt->bindParam(3, intval($_POST['qty']), PDO::PARAM_INT);
    $stmt->bindParam(4, intval($_POST['price']), PDO::PARAM_INT);
    $stmt->bindParam(5, $_POST['cat']);

    $stmt->execute();
    header("Location: addproduct.php");
    exit();
  }

?>

<html>
<head>
  <title>Add product</title>
</head>
<body>
  <form method="post" action="addproduct.php">
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
</body>
</html>
