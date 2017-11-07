<?php
  session_start();
  require_once 'include/conn.php';
  if(isset($_POST['login'])){
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE email = ? AND password = ? LIMIT 1");

    $stmt->bindParam(1, $_POST['email']);
    $stmt->bindParam(2, $_POST['password']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0){
      echo "LOGGED IN!";
    }else{
      echo "WRONG PASS";
    }

  }
?>

<html>
<head>
  <title>Login</title>
</head>
<body>
  <form method="post" action="login.php">
    <p>Email: <input type="text" name="email" /></p>
    <p>Password: <input type="password" name="password" /></p>
    <p><input type="submit" name="login" value="Login!" /></p>
  </form>
</body>
</html>
