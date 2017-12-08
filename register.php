<?php
  session_start();
  include 'include/conn.php';
  if(isset($_SESSION['user'])){
    header("Location: /");
    exit();
  }

  if(isset($_POST['sendRegister'])){
    try{

      $check = 1;
      $msg = '';
      if(empty($_POST['fName'])){
        $check = 0;
        $msg .= 'Enter firstname<br />';
      }

      if(empty($_POST['lName'])){
        $check = 0;
        $msg .= 'Enter lastname<br />';
      }

      if(empty($_POST['address'])){
        $check = 0;
        $msg .= 'Enter address<br />';
      }

      if(empty($_POST['password'])){
        $check = 0;
        $msg .= 'Enter password<br />';
      }

      if(empty($_POST['email'])){
        $check = 0;
        $msg .= 'Enter email<br />';
      }

      if($_POST['password'] != $_POST['reenter']){
        $check = 0;
        $msg .= 'Password didnt match<br />';
      }

      if($check == 1){

        $stmt = $conn->prepare("INSERT INTO `user` (fName, lName, address, password, email) VALUES
        (?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $_POST['fName']);
        $stmt->bindParam(2, $_POST['lName']);
        $stmt->bindParam(3, $_POST['address']);
        $stmt->bindParam(4, $_POST['password']);
        $stmt->bindParam(5, $_POST['email']);
        $stmt->execute();
        header("Location: /");
        exit();
      }else{
        echo $msg;
      }
    }catch(Exception $e){
      echo 'Email is already used';
    }
  }
?>

<html>
<head>
  <title>Register</title>
  <link href="/CSS/styles.css" rel="stylesheet">
</head>
<?php
	require_once 'include/header.php';
?>
<body>
  <?php
    $fname = (isset($_POST['fName'])) ? $_POST['fName'] : '';
    $lName = (isset($_POST['fName'])) ? $_POST['lName'] : '';
    $address = (isset($_POST['fName'])) ? $_POST['address'] : '';
    $email = (isset($_POST['fName'])) ? $_POST['email'] : '';
  ?>
  <form method="post" action="register.php">
  <p>First name: <input type="text" name="fName" value="<?php echo $fname; ?>" /></p>
  <p>Last name: <input type="text" name="lName" value="<?php echo $lName; ?>" /></p>
  <p>Address: <input type="text" name="address" value="<?php echo $address; ?>" /></p>
  <p>Email: <input type="text" name="email" value="<?php echo $email; ?>" /></p>
  <p>Password: <input type="password" name="password" /></p>
  <p>Re-enter password: <input type="password" name="reenter" /></p>
  <p><input type="submit" name="sendRegister" value="Register!" /></p>
  </form>
</body>
</html>
