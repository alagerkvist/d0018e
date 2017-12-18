<header>
	<a class="navButton" href="/~davjom-5/">Home</a>
  <?php
    if(isset($_SESSION['user'])){
			if($_SESSION['user']->usertype != "Customer"){
				echo '<div class="dropdown">

					<button class="navButton">Admin</button>
					<div class="dropdownContent">
						<a href="/~davjom-5/admin/addProduct.php">Add Product/Category</a>
						<a href="/~davjom-5/admin/updateProduct.php">Update/remove product</a>
						<a href="/~davjom-5/admin/changeadmin.php">Manage Accounts</a>
						<a href="/~davjom-5/admin/processOrder.php">Manage Orders</a>
					</div>
				</div>';
			}
      echo'<div class="login">
	      <div style="float:left;">
		      <p>Logged in as:
		      <br/>
		      '.$_SESSION['user']->fname.' '.$_SESSION['user']->lname.'
					<p>
	      </div>
				<a class="navButton" href="/~davjom-5/basket.php">Basket</a>
				<a class="navButton" href="/~davjom-5/myorders.php">Orders</a>
	      <form method="post" action="/~davjom-5/include/logout.php">
					<input class="navButton" type="submit" name="logout" value="Logout"/>
				</form>
			</div>';
			/*if(isset($_SESSION['cart'])){
				print_r($_SESSION['cart']);
			}*/
    }
    else{
    echo'<div class="login">
	    <form method="post" action="/~davjom-5/include/login.php">
				<div style="float:left; margin-top: 4px;">
					<input type="text" name="email" placeholder="E-mail"/>
					<br>
					<input type="password" name="password" placeholder="Password"/>
				</div>
				<input class="navButton" type="submit" name="login" value="Login"/>
			</form>
			<a class="navButton" href="/~davjom-5/register.php">Register</a>
		</div>';
    }
  ?>
</header>
