<?php
if(isset($_POST['submit']))
{
	if(!empty($_POST['user']) AND !empty($_POST['name']) AND !empty($_POST['email'])AND !empty($_POST['pass']) AND!empty($_POST['cpass'])) {
		$fields_string="";
		$fields = array(
		'Name' => urlencode($_POST['name']),
		'Email' => urlencode($_POST['email']),
		'Username' => urlencode($_POST['user']),
		'Password' => urlencode($_POST['cpass'])
		);
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		
		//open connection
		$ch = curl_init();
		$url = "http://localhost/v1/register";

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//echo $fields_string;
		//execute post
		$response= curl_exec($ch);
		$result = json_decode($response,true);
		
		if($result['error']==true){
			session_start();
			$_SESSION['errMsg4']= $result['message'];
			header('Location:signup.php');
		}else{
			header("Location:signedin.php");
		}
		curl_close($ch);
	}else{
		session_start();
		$_SESSION['errMsg4']= "Enter all the credentials";
		header('Location:signup.php');
	}
}
?>