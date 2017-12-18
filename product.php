<?php
  require_once 'include/Person.php';
  session_start();
  require_once 'include/conn.php';
  function relocate(){
    header("Location: index.php");
    exit();
  }
  if(isset($_GET['id'])){
    $stmt = $conn->prepare("SELECT * FROM product WHERE productID = ? LIMIT 1");
    $stmt->bindParam(1, $_GET['id']);
    $stmt->execute();
    if($stmt->rowCount() <= 0){
      relocate();
    }
  }else{
    relocate();
  }

  if(isset($_POST['commentAndRate'])){

    if(isset($_POST['rating']) || ($_POST['commentArea'] != NULL)){

      if(!isset($_POST['rating'])){
        $_POST['rating'] = NULL;
      }
      try{
        $conn->beginTransaction();
        if(isset($_SESSION['update'])){

          $stmtUpdateRatings = $conn->prepare("UPDATE `ratings` SET rating = ?, comment = ? WHERE productID = ? AND userID = ?");
          $stmtUpdateRatings->bindParam(1, $_POST['rating']);
          $stmtUpdateRatings->bindParam(2, $_POST['commentArea']);
          $stmtUpdateRatings->bindParam(3, $_GET['id']);
          $stmtUpdateRatings->bindParam(4, $_SESSION['user']->id);
          $stmtUpdateRatings->execute();

        }else{

          $stmtInsertRatings = $conn->prepare("INSERT INTO `ratings` (userID, productID, rating, comment) VALUES (?, ?, ?, ?)");
          $stmtInsertRatings->bindParam(1, $_SESSION['user']->id);
          $stmtInsertRatings->bindParam(2, $_GET['id']);
          $stmtInsertRatings->bindParam(3, $_POST['rating']);
          $stmtInsertRatings->bindParam(4, $_POST['commentArea']);
          $stmtInsertRatings->execute();

        }

        $stmtGetCountRating = $conn->prepare("SELECT rating FROM ratings WHERE productID = ?");
        $stmtGetCountRating->bindParam(1, $_GET['id']);
        $stmtGetCountRating->execute();
        $stmtGetCountRating->setFetchMode(PDO::FETCH_ASSOC);
        $counter = 0;
        $totalRating = 0;
        while($rad = $stmtGetCountRating->fetch()){
          if($rad['rating'] != NULL){
            $counter++;
            $totalRating += $rad['rating'];
          }
        }

        if($counter != 0){
          $avg = $totalRating / $counter;
          $stmtUpdateProd = $conn->prepare("UPDATE `product` SET avgRating = ? WHERE productID = ?");
          $stmtUpdateProd->bindParam(1, $avg);
          $stmtUpdateProd->bindParam(2, $_GET['id']);
          $stmtUpdateProd->execute();
        }
        $conn->commit();
        header("Location: product.php?id=".$_GET['id']);
        exit();

      }catch(Exception $e){
        $conn->rollBack();
        echo 'Something went wrong';
      }
    }else{
      echo 'You have to send something in';
    }

  }

  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $row = $stmt->fetch();

  if($row['visible'] == 0){
    header("Location: /~davjom-5/");
    exit();
  }

  if(isset($_POST['addToCart'])){
    if(!isset($_SESSION['user'])){
      echo '<script language="javascript">alert("Login first!"); </script>';
    }else{
      $_SESSION['cart'][$_GET['id']] = array(1, $row['price']);
      $stmt = $conn->prepare("INSERT INTO basketItem (userID, productID, qty, price) VALUES
      (?, ?, 1, ?)");
      $stmt->bindParam(1, $_SESSION['user']->id);
      $stmt->bindParam(2, $_GET['id']);
      $stmt->bindParam(3, $row['price']);

      $stmt->execute();

    }
    //Add header location
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $row['prodCatTitle'].' - '.$row['title']; ?></title>
    <link href="/~davjom-5/CSS/styles.css" rel="stylesheet">
    <script language="javascript">

      function showHideComment(but){
        let formElt = document.getElementById("formRate");
        if(formElt.style.display == "none"){
          formElt.style.display = "";
          but.innerHTML = "Hide";
          console.log(but);
        }else{
          formElt.style.display = "none";
          but.innerHTML = "Show";
        }

      }
    </script>
  </head>
  <?php
  require_once 'include/header.php';
  ?>
  <body>
    <?php
    echo'
        <table>
          <tr>
            <th>Title</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Category</th>
            <th>Description</th>
          </tr>
          <tr>
            <td>'.$row['title'].'</td>
            <td>'.$row['qty'].'</td>
            <td>'.$row['price'].'</td>
            <td>'.$row['prodCatTitle'].'</td>
            <td>'.$row['desc'].'</td>';
            if(!isset($_SESSION['cart'][$_GET['id']])){
              if($row['qty'] > 0){
                echo '
                <form method="post" action="product.php?id='.$row['productID'].'">
                <td><input type="submit" name="addToCart" value="Add to cart!" /></td>
                </form>';
              }else{
                echo '<td> Out of stock</td>';
              }
            }else{
              echo '<td>Product already in cart!</td>';
            }
          echo '
          </tr>
        </table>
        <img src="pic/'.$_GET['id'].'" style="width: auto; height: auto; max-width: 20%; max-height: 20%;" /><br />
        Average rating: '.round($row['avgRating'], 2).'
        <h3>Comment Section</h3>';

        $stmtRating = $conn->prepare("SELECT t1.*, t2.fname, t2.lname FROM ratings as t1, user as t2 WHERE t1.productID = ? AND t1.userID = t2.userID ORDER BY t1.commentDate");
        $stmtRating->bindParam(1, $_GET['id']);
        $stmtRating->execute();
        $stmtRating->setFetchMode(PDO::FETCH_ASSOC);
        while($rad = $stmtRating->fetch()){
          $rating = ($rad['rating'] != NULL) ? 'Giving rating: '.$rad['rating'] : '';
          $comment = ($rad['comment'] != NULL) ? $rad['comment'] : '';
          echo '<div id="comment" style="background-color: grey; width: 40%; ">
          <p>'.$rad['fname'].' '.$rad['lname'].' '.$rating.'</p>
          <p>'.nl2br($comment).'</p></div>';
        }
      ?>
  </body>

  <div class="commentbox">
    <?php
      if(isset($_SESSION['user'])){
    ?>
    <h3 style="display: inline;">Your rating</h3>
    <button type="button" id="buttonComment" onclick="showHideComment(this);" style="float: right;">Hide</button>
    <?php
      $stmtComment = $conn->prepare("SELECT * FROM ratings  WHERE productID = ? AND userID = ? LIMIT 1");
      $stmtComment->bindParam(1, $_GET['id']);
      $stmtComment->bindParam(2, $_SESSION['user']->id);
      $stmtComment->execute();
      $commentsAndRating = $stmtComment->fetch(PDO::FETCH_ASSOC);
      $ratingValue = ($commentsAndRating['rating'] != NULL) ? $commentsAndRating['rating'] : '';
      $commentValue = ($commentsAndRating['comment'] != NULL) ? $commentsAndRating['comment'] : '';

      echo '<form method="post" action="product.php?id='.$_GET['id'].'" id="formRate">';

      if($stmtComment->rowCount() > 0){
        echo '<script language="javascript">
          document.getElementById("formRate").style.display = "none";
          document.getElementById("buttonComment").innerHTML = "Show";
        </script>';
        $_SESSION['update'] = true;
      }else{
        if(isset($_SESSION['update'])){
          unset($_SESSION['update']);
        }
      }

      echo '
        <p><textarea cols="70" rows="10" name="commentArea" style="resize: none;">'.$commentValue.'</textarea></p>
        <p>';
        for($i = 1; $i <= 5; $i++){
          if($i == $ratingValue){
            echo '<input type="radio" name="rating" checked value="'.$i.'" id="'.$i.'">
            <label for="'.$i.'">'.$i.'</label>';
          }else{
            echo '<input type="radio" name="rating" value="'.$i.'" id="'.$i.'">
            <label for="'.$i.'">'.$i.'</label>';
          }
        }
        echo '
        <input type="submit" name="commentAndRate" value="Comment" style="float: right;"/></p>
        </form>';
    }else{
      echo 'Login to comment';
    }

    ?>
  </div>
</html>
