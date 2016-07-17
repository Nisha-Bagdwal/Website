<?php
session_start();
?>
<!DOCTYPE html>
<html>	
	<head>
		<title>Task Assigned</title>
	</head>
	<body>
	<div>
		<fieldset style="width:50%;margin-top:10%;margin-left:25%"><legend>Task Assigned</legend>
				<p>You've successfully assigned task to <?php echo $_SESSION['taskuname']?>.</p>
				<p><a href="profile.php">Back to profile page</a></p>
			</fieldset>
		</div>
	</body>
</html>