<?php
session_start();	
	if(isset($_POST['submit']))
	{
		if(!empty($_POST['uname'])AND !empty($_POST['pass'])){
			$fields_string="";
			$fields = array(
			'Username' => urlencode($_POST['uname']),
			'Password' => urlencode($_POST['pass'])
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
			if($result['error']==true){
				$_SESSION['errMsg1']= $result['message'];
				header('Location:../index.php');
			}else{
				$_SESSION['luser'] = $_POST['uname'];
				$_SESSION['lpass'] = $_POST['pass'];
				if(isset($_POST['rememberme'])) {
					setcookie('uname', $_SESSION['luser'], time()+60*60*24*30, '/');
					setcookie('password', $_SESSION['lpass'], time()+60*60*24*30, '/');
        
				} else {
					setcookie('uname', $_SESSION['luser'], time()-60, '/');
					setcookie('password', $_SESSION['lpass'], time()-60, '/');
				}
				header("Location: profile.php");
			}
			curl_close($ch);
		}else{
			 $_SESSION['errMsg1'] = "Please enter username and password";
			 header("Location: ../index.php");
		}
	}
?>