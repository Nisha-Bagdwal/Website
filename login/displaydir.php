<?php
$ch = curl_init();  
$url = "http://localhost/v1/displaydirectory";
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

$response= curl_exec($ch);
$result = json_decode($response,true);

$cnt=count($result['tasks']);
curl_close($ch);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Directory</title>
		<link href="../css/login-css.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<div id="logWel1">
	<?php
		if ($result['error']==false) {
		// output data of each row
			echo "<table id='tabtab'>
			<tr>
				<th id='tabl'>Username</th>
				<th id='tabl'>Fullname</th>
				<th id='tabl'>Email</th>
				<th id='tabl'>Task</th>
				<th id='tabl'>Assigned By</th>
			</tr>";
			for($i=0;$i<$cnt;$i++){
				echo "<tr>";
				echo "<td id='tabl'>" . $result['tasks'][$i]['userName'] . "</td>";
				echo "<td id='tabl'>" . $result['tasks'][$i]['fullname'] . "</td>";
				echo "<td id='tabl'>" . $result['tasks'][$i]['email'] . "</td>";
				echo "<td id='tabl'>" . $result['tasks'][$i]['task']. "</td>";
				echo "<td id='tabl'>" . $result['tasks'][$i]['assignedby'] . "</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
	?>
	</div>
	</body>
</html>