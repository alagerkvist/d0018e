<?php
    require 'Person.php';
    session_start();
    require 'conn.php';
    if(isset($_POST['login'])){
        $stmt = $conn->prepare("SELECT * FROM `user` WHERE email = ? AND password = ? LIMIT 1");
        $stmt->bindParam(1, $_POST['email']);
        $stmt->bindParam(2, $_POST['password']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0){
          $sessionUser = new Person($user['userID'], $user['fName'], $user['lName'], $user['userType']);
          $_SESSION['user'] = $sessionUser;
          /*
            //echo "LOGGED IN!";
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['fName'] = $user['fName'];
            $_SESSION['lName'] = $user['lName'];
          */
        }else{
            //echo "WRONG PASS";
        }
    }
    header("Location: /");
    exit();
?>
