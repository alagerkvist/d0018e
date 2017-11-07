<?php
  session_start();
  require_once '../include/conn.php';
  if(isset($_POST['addCat'])){
    $stmt = $conn->prepare("INSERT INTO prodCat (title) VALUES (?)");
    $stmt->bindParam(1, $_POST['title']);
    $stmt->execute();
  }

?>

<html>
<head>
  <title>Add product</title>
</head>
<body>
  <form method="post" action="addcategory.php">
    <p>Title: <input type="text" name="title" /></p>
    <p><input type="submit" name="addCat" value="Add category!" /></p>
  </form>
</body>
</html>
