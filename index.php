<?php 
	session_start();
	require_once './include/DbHandler.php';
?>
<?php 
	if (isset($_COOKIE['uname']) && isset($_COOKIE['password'])) {
		$fields_string="";
		$fields = array(
		'Username' => urlencode($_COOKIE['uname']),
		'Password' => urlencode($_COOKIE['password'])
		);
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		
		//open connection
		$ch = curl_init();
		$url = "http://localhost/v1/login";

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute post
		$response= curl_exec($ch);
		$result = json_decode($response,true);
		if($result['error']==false){
			$_SESSION['luser'] = $_COOKIE['uname'];
			header("Location: ./login/profile.php");
		}
		curl_close($ch);
	}
?>
<!DOCTYPE html>
<html>	
	<head>
		<title>Login</title>
		<link href="./css/login-css.css" rel="stylesheet" type="text/css">
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
			<form action="./login/login-con.php" method="POST">
				<label>Username:</label>
				<input id="name" name="uname" type="text">
				<label>Password:</label>
				<input id="password" name="pass" type="password">
				<div style="margin-top:8px">
				<label>Remember me:</label>
				<input type="checkbox" name="rememberme" value="1">
				</div>
				<input name="submit" type="submit" value=" Login ">
			</form>
		</div>
	<div>
		<a href="./signup/signup.php"><input name="signup" type="button" value="Sign up"></a>
	</div>
	</body>
</html>

