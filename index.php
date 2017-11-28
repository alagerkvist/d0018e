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
				xmlhttp.open('GET','searchproducts.php?search='+word,true);
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
		<p>Search <input type="text" id="searchword" onkeyup="searchproducts();" /></p>
		<div id="products"></div>
	</body>

</html>
