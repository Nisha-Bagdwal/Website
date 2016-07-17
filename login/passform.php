<?php 
	session_start();
	if(!isset($_SESSION['luser'])){
		header('Location:../index.php');
	}
?>
<!DOCTYPE html>
<html>	
	<head>
		<title>Change Password</title>
		<link href="../css/login-css.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<div id="main">
		<div id="errMsg">
            <?php 
			if(!empty($_SESSION['errMsg1'])) 
			{ 
				echo $_SESSION['errMsg1']; 
			}
			?>
		</div>
		<?php 
			unset($_SESSION['errMsg1']); 
		?>
		<div id="login" >
			<h2></h2>
			<form action="changepass.php" method="POST">
				<label>New Password:</label>
				<input name="oldpass" type="password">
				<label>Confirm New Password:</label>
				<input name="newpass" type="password">
				<input id="submit1" name="submit" type="submit" value="Change Password">
			</form>
		</div>
	</body>
</html>