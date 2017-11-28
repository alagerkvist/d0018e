<?php
  require_once '../include/Person.php';
  session_start();
  require_once 'checksession.php';
  require_once '../include/conn.php';

  if(isset($_POST['downgrade'])){
    $stmt = $conn->prepare("UPDATE `user` SET userType = 'Customer' WHERE userID = ?");
    $stmt->bindParam(1, $_POST['users']);
    $stmt->execute();
    header("Location: changeadmin.php");
    exit();
  }

  if(isset($_POST['admin'])){
    $stmt = $conn->prepare("UPDATE `user` SET userType = 'Admin' WHERE userID = ?");
    $stmt->bindParam(1, $_POST['users']);
    $stmt->execute();
    header("Location: changeadmin.php");
    exit();
  }
  ?>
  
<!DOCTYPE html>
<html>
  <head>
  <title>Manage Accounts</title>
  <link href="../CSS/styles.css" rel="stylesheet">
  </head>
  <?php
  	require_once '../include/header.php';
  ?>
  <body>
    <?php
      echo '<form method="post" action="changeadmin.php">
      <p>
      <select name="users" onchange="showUser(this)">';
        $stmtCat = $conn->query("SELECT userID, fname, lname FROM user");
        $stmtCat->setFetchMode(PDO::FETCH_ASSOC);
        while($rad = $stmtCat->fetch()){
          echo '<option value="'.$rad['userID'].'">'.$rad['fname'].' '.$rad['lname'].'</option>';
        }
      echo '</select>
      </p>
      <p><input type="submit" name="downgrade" value="Downgrade" /><input type="submit" name="admin" value="Make admin" /></p>
      </form>';
    ?>
  </body>
</html>
