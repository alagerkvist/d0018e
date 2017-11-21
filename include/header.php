<header>
	<a class="navButton" href="/">Home</a>
	<a class="navButton" href="/basket.php">Basket</a>


            <?php

                if(isset($_SESSION['user'])){
									if($_SESSION['user']->usertype != "Customer"){
										echo '<a class="navButton" href="/admin/addProduct.php">Admin</a>';
									}
                    echo'<div class="login">
                    <div style="float:left; margin-top: 11px; color: white;">
                        Logged in as:
                        <br />
                        '.$_SESSION['user']->fname.' '.$_SESSION['user']->lname.'
                    </div>
                    <form method="post" action="/include/logout.php">
            				<input class="navButton" type="submit" name="logout" value="Logout"/>
            			</form>';
									if(isset($_SESSION['cart'])){
										print_r($_SESSION['cart']);
									}
                }
                else{
                    echo'<div class="login">
                    <form method="post" action="/include/login.php">
            				<div style="float:left; margin-top: 8px">
            				<input type="text" name="email" placeholder="E-mail"/>
            				<br />
            				<input type="password" name="password" placeholder="Password"/>
            			</div>
            			<input class="navButton" type="submit" name="login" value="Login"/>
            		</form>
            		<a class="navButton" href="register.php">Register</a>';
                }
            ?>
    	</div>
</header>
