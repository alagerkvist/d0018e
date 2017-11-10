<header>
	<a class="navButton" href="/">Home</a>
	<a class="navButton" href="basket.php">Basket</a>
	<a class="navButton" href="admin/addProduct.php">Admin</a>
        <div class="login">
            <?php
                if(isset($_SESSION['userID'])){

                    echo'
                    <div style="float:left; margin-top: 11px; color: white;">
                        Logged in as:
                        <br />
                        '.$_SESSION['fName'].' '.$_SESSION['lName'].'
                    </div>
                    <form method="post" action="/include/logout.php">
            			<input class="navButton" type="submit" name="logout" value="Logout"/>
            		</form>';

                }
                else{
                    echo'
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
