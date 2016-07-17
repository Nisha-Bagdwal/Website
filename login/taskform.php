<?php 
	session_start();
?>
<!DOCTYPE html>
<html>	
	<head>
		<title>Assign Task</title>
		<link href="../css/login-css.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<div id="main">
		<div id="errMsg">
            <?php 
			if(!empty($_SESSION['errMsg1'])){
				echo $_SESSION['errMsg1']; 
			}
			?>
		</div>
		<?php 
			unset($_SESSION['errMsg1']);
		?>
		<div id="login" >
			<h2></h2>
			<form action="task.php" method="POST">
				<label>Assign task to:</label>
				<input name="uname" type="text" placeholder="Username">
				<label>Task:</label>
				<div><textarea name="task" rows="4" cols="46"></textarea></div>
				<input id="submit1" name="submit" type="submit" value="Assign Task">
			</form>
		</div>
	</body>
</html>