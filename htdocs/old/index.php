<?php
// Include the database connection file
require_once("dbConnection.php");

// Fetch data in descending order (lastest entry first)
$result = mysqli_query($mysqli, "SELECT * FROM user");
?>

<!DOCTYPE html>

<html>
<head>	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<title>vidaXL API</title>
	<link rel="icon" type="image/png" href="favicon.png">
</head>

<body>
	<h2>Homepage</h2>
	<!-- <p>
		<a href="add.php">Add New Data</a>
	</p>
	<table width='80%' border=0>
		<tr bgcolor='#DDDDDD'>
			<td><strong>Name</strong></td>
			<td><strong>Age</strong></td>
			<td><strong>Email</strong></td>
			<td><strong>Action</strong></td>
		</tr> -->
		<?php

		// while ($res = mysqli_fetch_assoc($result)) {
		// 	echo "<tr>";
		// 	echo "<td>".$res['name']."</td>";
		// 	echo "<td>".$res['age']."</td>";
		// 	echo "<td>".$res['email']."</td>";	
		// 	echo "<td><a href=\"edit.php?id=$res[id]\">Edit</a> | 
		// 	<a href=\"delete.php?id=$res[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";
		// }
		?>
	<!-- </table> -->
</body>
</html>
