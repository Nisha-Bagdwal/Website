<?php
	session_start();
	$username=$_SESSION['luser'];
	$fields_string="";
	$fields = array(
	'Username' => urlencode($username)
	);
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	
	$url ="http://nishabagdwal.co.nf/v1/deleteuser";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
    $result = curl_exec($ch);
    $result = json_decode($result,true);
	
	if($result['error']==false){
			setcookie('uname',$_SESSION['luser'], time()-60, '/');
			setcookie('password',$_SESSION['lpass'], time()-60, '/');
			session_destroy();
			header("Location: unsubscribed_now.php");
	}else{
		echo $result['message'];
	}
    curl_close($ch);
?>