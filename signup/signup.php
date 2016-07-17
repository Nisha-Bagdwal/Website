<?php 
session_start();?>
<!DOCTYPE html>
<html>	
	<head>
		<title>Sign-Up</title>
		<link href="../css/style-signup.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<div id="main">
		<div id="errMsg">
            <?php 
			if(!empty($_SESSION['errMsg4'])) 
			{ 
				echo $_SESSION['errMsg4']; 
			}
			?>
		</div>
		<?php 
			unset($_SESSION['errMsg4']); 
		?>
		<div id="login" >
			<h2></h2>
			<form action="sign-up-con.php" method="POST">
					<input type="text" name="name" placeholder="Name">
					<input type="text" name="email" placeholder="Email">
					<input type="text" name="user" placeholder="Username">
					<input type="password" name="pass" placeholder="Password">
					<input type="password" name="cpass" placeholder="Confirm Password">
					<input type="submit" name="submit" value="Sign-Up">
			</form>
		</div>
	</body>
</html>