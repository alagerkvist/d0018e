<?php
  require_once 'include/Person.php';
  session_start();
  require_once 'include/conn.php';
  if(!isset($_SESSION['user'] )){
    header("Location: index.php");
    exit();
  }

  if((!isset($_SESSION['cart'])) || empty($_SESSION['cart'])){
    header("Location: index.php");
    exit();
  }


  if(isset($_POST['remove'])){

    $stmt = $conn->prepare("DELETE FROM basketItem WHERE userID = ? and productID = ? LIMIT 1");
    $stmt->bindParam(1, $_SESSION['user']->id);
    $stmt->bindParam(2, $_POST['id']);
    $stmt->execute();
    unset($_SESSION['cart'][$_POST['id']]);
    header("Location: basket.php");
    exit();

  }
?>
<!DOCTYPE html>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="/~davjom-5/CSS/styles.css" rel="stylesheet">
  <script language="javascript">

		function updatecart(inputArea, maxQty){
      let id = inputArea.id;
      let qty = inputArea.value;
      if(parseInt(qty) <= maxQty && parseInt(qty) >= 1){
  			if(window.XMLHttpRequest){
  				xmlhttp = new XMLHttpRequest();
  			}else{
  				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
  			}

  			xmlhttp.onreadystatechange = function(){
  				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            let val = document.getElementsByName("qty");
            for(let i = 0; i < val.length; i++){
              if(val[i].id == id){
                val[i].value = qty;
                document.getElementsByName("add")[i].value = (parseInt(qty)+1);
                document.getElementsByName("sub")[i].value = (qty-1);
                break;
              }
            }
            if(xmlhttp.responseText == 'Not a number'){
              document.getElementById("checkoutbut").disabled = true;
            }else{
              document.getElementById("checkoutbut").disabled = false;

            }
  					document.getElementById("products").innerHTML = xmlhttp.responseText;
  				}
  			}
  			xmlhttp.open('GET','updatecart.php?id='+id+'&qty='+qty,true);
  			xmlhttp.send();

    }else{
      let val = document.getElementsByName("qty");
      for(let i = 0; i < val.length; i++){
        if(val[i].id == id){
          if(parseInt(maxQty) < qty){
            val[i].value = maxQty;
          }else{
            val[i].value = 1;
          }
        }
      }
    }
  }
	</script>
</head>
  <?php
    require_once 'include/header.php';
  ?>
<body>
  <table>
    <tr>
      <th>Brand</th>
      <th>Model</th>
      <th>Qty</th>
    </tr>
  <?php
  if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $id => $qtyPrice){
        $stmt = $conn->prepare("SELECT prodCatTitle, title, productID, qty, price FROM product WHERE productid = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        if($qtyPrice[1] != $row['price']){
          echo 'Price has changed';
          $_SESSION['cart'][$id][1] = $row['price'];
          $updateBasketItem = $conn->prepare("UPDATE `basketItem` SET price = ? WHERE productID = ? AND userID = ?");
          $updateBasketItem->bindParam(1, $row['price']);
          $updateBasketItem->bindParam(2, $id);
          $updateBasketItem->bindParam(3, $_SESSION['user']->id);
          $updateBasketItem->execute();
        }

        echo '<tr>
        <td>'.$row['prodCatTitle'].'</td>
        <td>'.$row['title']. '</td>

        <td><input type="text" onkeyup="updatecart(this, '.$row['qty'].');"
        id="'.$row['productID'].'" name="qty" value="'.$qtyPrice[0].'" /></td>

        <td><button type="button" onclick="updatecart(this, '.$row['qty'].');"
        id="'.$row['productID'].'" name="add" value="'.($qtyPrice[0]+1).'">+</button></td>

        <td><button type="button" onclick="updatecart(this, '.$row['qty'].');"
        id="'.$row['productID'].'" name="sub" value="'.($qtyPrice[0]-1).'">-</button></td>
        <form method="post" action="basket.php">
        <input type="hidden" value="'.$row['productID'].'" name="id" />
        <td><input type="submit" name="remove" value="Remove" /></td></form>
        </tr>';
    }
  }
  ?>
</table>
<div id="products"></div>
<form method="post" action="checkout.php">
  <button type="submit" name="checkout" id="checkoutbut">Chechout!</button>
</form>

</body>
</html>
