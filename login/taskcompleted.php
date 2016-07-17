<?php
session_start();

$uname=$_SESSION['luser'];
$task="";
$myname="";

$fields_string="";
$fields = array(
'Username' => urlencode($uname),
'Task' => urlencode($task),
'Myname' => urlencode($myname)
);
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();
$url = "http://localhost/v1/updateusertask";
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);

$response = curl_exec($ch);
$result = json_decode($response,true);
if($result['error']==false){
	header("Location: profile.php");
}else{
	$_SESSION['errMsg1']= $result['message'];
	header("Location: profile.php");
}
?>