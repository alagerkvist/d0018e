<?php
  session_start();
  require_once 'include/conn.php';
  if(isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
  }

  if(isset($_POST['sendRegister'])){
    $stmt = $conn->prepare("INSERT INTO `user` (fName, lName, address, password, email) VALUES
    (?, ?, ?, ?, ?)");

    $stmt->bindParam(1, $_POST['fName']);
    $stmt->bindParam(2, $_POST['lName']);
    $stmt->bindParam(3, $_POST['address']);
    $stmt->bindParam(4, $_POST['password']);
    $stmt->bindParam(5, $_POST['email']);
    $stmt->execute();
  }

?>

<html>
<head>
  <title>Registrer</title>
</head>
<body>
  <form method="post" action="register.php">
  <p>First name: <input type="text" name="fName" /></p>
  <p>Last name: <input type="text" name="lName" /></p>
  <p>Address: <input type="text" name="address" /></p>
  <p>Password: <input type="password" name="password" /></p>
  <p>Email: <input type="text" name="email" /></p>
  <p><input type="submit" name="sendRegister" value="Register!" /></p>
  </form>
</body>
</html>
