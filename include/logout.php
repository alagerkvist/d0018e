<?php
    if(isset($_POST['logout'])){
        session_start();
        //session_unset();
        //session_destroy();
        unset($_SESSION['user']);
        unset($_SESSION['cart']);
        //unset($_SESSION['fName']);
        //unset($_SESSION['lName']);
        header("Location: /");
        exit();
    }
?>
