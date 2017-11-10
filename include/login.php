<?php
    session_start();
    include 'conn.php';
    if(isset($_POST['login'])){
        $stmt = $conn->prepare("SELECT * FROM `user` WHERE email = ? AND password = ? LIMIT 1");
        $stmt->bindParam(1, $_POST['email']);
        $stmt->bindParam(2, $_POST['password']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() > 0){
            //echo "LOGGED IN!";
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['fName'] = $user['fName'];
            $_SESSION['lName'] = $user['lName'];

        }else{
            //echo "WRONG PASS";
        }
    }
    header("Location: /");
    exit();
?>
