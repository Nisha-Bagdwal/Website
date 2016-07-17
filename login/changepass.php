<?php
session_start();

	if(isset($_POST['submit']))
	{
		if(!empty($_POST['oldpass'])AND !empty($_POST['newpass'])) {
			//ChangePass($con);
			$newpass=$_POST['newpass'];
			$options = [
					'cost' => 12, 
				];
			$newpass = password_hash($newpass, PASSWORD_BCRYPT, $options);
			$fields_string="";
			$fields = array(
			'Username' => urlencode($_SESSION['luser']),
			'Password' => urlencode($newpass)
			);
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			
			//open connection
			$ch = curl_init();
			$url = "http://localhost/v1/updatepass";
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);

			$response = curl_exec($ch);
			$result = json_decode($response,true);
			if($result['error']==false){
					setcookie('uname',$_SESSION['luser'], time()-60, '/');
					setcookie('password',$_SESSION['lpass'], time()-60, '/');
					session_destroy();
					header("Location: passchanged.php");
			}else{
				$_SESSION['errMsg1']= $result['message'];
				header('Location:passform.php');
			}
		}else{
			$_SESSION['errMsg1'] = "Please enter your new password and confirm it.";
			header("Location: passform.php");
		}
	}
?>