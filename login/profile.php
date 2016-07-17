<?php
session_start();
if(!isset($_SESSION['luser'])){
	header('Location:../index.php');
}

$fields_string="";
$fields = array(
'Username' => urlencode($_SESSION['luser'])
);
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();
$url = "http://localhost/v1/session";

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute post
$response= curl_exec($ch);
$result = json_decode($response,true);

if($result['error']==false){
	$_SESSION['lname'] = $result['name'];
	$_SESSION['lemail'] = $result['email'];
	$_SESSION['ltask'] = $result['task'];
	$_SESSION['lassignedby'] = $result['assignedby'];
}

curl_close($ch);
?>
<!DOCTYPE html>
	<html>
		<head>
			<title>Home</title>
				<link href="../css/login-css.css" rel="stylesheet" type="text/css">
		</head>
		<body id="image">
		<div id="main">
			<div id="logWel">
				<b id="welcome"><i>Hello <?php echo $_SESSION['luser']; ?>, welcome to your home page. I'm Nisha Bagdwal, creator of this website and now you are a registered user of my website, hope you like it :) </i></b></br></br>
				
				<b id="welcome"><i>Your Fullname: <?php echo $_SESSION['lname']; ?></i></b></br></br>
				<b id="welcome"><i>Your Email: <?php echo $_SESSION['lemail']; ?></i></b></br></br>
				<b id="welcome"><i>Your Task: <?php echo $_SESSION['ltask']; ?></i></b></br></br>
				<b id="welcome"><i>Assigned By: <?php echo $_SESSION['lassignedby']; ?></i></b>
			</div>
			<div id="logSub">
			<table>
				<tr>
					<td><a href="logout.php"><input id="button1" name="logout" type="button" value="Log Out"></a></td><td><a href="unsubscribe.php"><input id = "button2" name="unsubscribe" type="button" value="Unsubscribe"></a></td>
				</tr>
			</table>
			<div>
			<div>
				<table>
				<tr>
					<td><a href="passform.php"><input id="button3" name="changepass" type="button" value="Change Password"></a></td><td><a href="displaydir.php"><input id="button4" name="unsubscribe" type="button" value="Display Directory"></a></td>
				</tr>
			</table>
			</div>
			<div>
				<table>
				<tr>
					<td><a href="taskform.php"><input style="background-color:#000099;border:2px solid #000099;" id="button3" name="task" type="button" value="Assign Task"></a></td><td><a href="taskcompleted.php"><input style="background-color:#ff6699;border:2px solid #ff6699;" id="button4" name="taskcompleted" type="button" value="Task Completed"></a></td>
				</tr>
			</table>
			</div>
		</div>
		</body>
	</html>
