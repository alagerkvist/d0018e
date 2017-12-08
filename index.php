<?php
	require 'include/Person.php';
	session_start();
	include 'include/conn.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link href="CSS/styles.css" rel="stylesheet">
		<script language="javascript">

			function searchproducts(){
				let inputElt = document.getElementById("searchword");
				let word = inputElt.value;
				console.log(word);
				if(window.XMLHttpRequest){
					xmlhttp = new XMLHttpRequest();
				}else{
					xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
				}

				xmlhttp.onreadystatechange = function(){
					if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
						document.getElementById("products").innerHTML = xmlhttp.responseText;
					}
				}
				let searchCat = document.getElementById("cars").value;
				if(searchCat == "all"){
					xmlhttp.open('GET','searchproducts.php?search='+word,true);
				}else{
					xmlhttp.open('GET','searchproducts.php?search='+word+'&cat='+searchCat,true);
				}
				xmlhttp.send();
			}

			$(document).ready(function(){
				searchproducts();
			});
		</script>
	</head>
	<?php
		require_once 'include/header.php';
	?>
	<body>
		<p>Search <input type="text" id="searchword" onkeyup="searchproducts();" />
		<?php
			$stmt = $conn->query("SELECT * FROM `prodCat` ORDER BY title");
			echo '<select id="cars" onchange="searchproducts();">
			<option value="all">All</option>';
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			while($row = $stmt->fetch()){
				echo '<option value="'.$row['title'].'">'.$row['title'].'</option>';
			}
			echo ' </select>';
		?>
	</p>
		<div id="products"></div>
	</body>
</html>
