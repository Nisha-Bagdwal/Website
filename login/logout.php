<?php
   session_start();
		setcookie('uname',$_SESSION['luser'], time()-60, '/');
		setcookie('password',$_SESSION['lpass'], time()-60, '/');
		session_destroy();
		header("Location: ../index.php");
?>