<?php
	require 'include/Person.php';
	session_start();
	include 'include/conn.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link href="CSS/styles.css" rel="stylesheet">
</head>
<?php
	require_once 'include/header.php';
?>
<body>
	<?php
		$stmt = $conn->prepare("SELECT * FROM product");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		echo '<table>
		<tr>
		  <th>Title</th>
		  <th>Quantity</th>
		  <th>Price</th>
		  <th>Category</th>
		</tr>';
		while($row = $stmt->fetch()){
		  echo '<tr>
		  <td><a href="product.php?id='.$row['productID'].'">'.$row['title'].'</a></td>
		  <td>'.$row['qty'].'</td>
		  <td>'.$row['price'].'</td>
		  <td>'.$row['prodCatTitle'].'</td>
		  </tr>';
		}
		echo '</table>';
  ?>
</body>

</html>
