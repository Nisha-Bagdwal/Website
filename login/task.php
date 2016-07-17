<?php
session_start();

	if(isset($_POST['submit']))
	{
		if(!empty($_POST['uname'])AND !empty($_POST['task'])) {
			$uname=$_POST['uname'];
			$task=$_POST['task'];
			$myname=$_SESSION['luser'];
			
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
				$_SESSION['taskuname']=$uname;
				header("Location: taskassigned.php");
			}else{
				$_SESSION['errMsg1']= $result['message'];
				header('Location:taskform.php');
			}
		}else{
			 $_SESSION['errMsg1'] = "Enter the username and the task.";
			 header("Location: taskform.php");
		}
	}
?>