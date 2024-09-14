<?php session_start(); ?>

<?php
// if(!isset($_SESSION['valid'])) {
// 	header('Location: login.php');
// }
?>

<?php
include_once("connection.php");
$result = mysqli_query($mysqli, "SELECT * FROM vidaxl_products");
?>
<!DOCTYPE html>

<html>
<head>	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<title>vidaXL API</title>
	<link rel="icon" type="image/png" href="favicon.png">
	<link rel="stylesheet" type="text/css" href="assets/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="assets/select.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="assets/style.css">
</head>

<body>
	<a href="index.php">Home</a> | <a href="add.html">Add New Data</a> | <a href="logout.php">Logout</a>
	<br /><br />
	<table id="example" class="display" style="width:100%">
		<thead>
			<tr>
				<th>Id</th>
				<th>Name</th>
				<th>Code</th>
				<th>Category Path</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Currency</th>
				<th>Create</th>
				<th>Update</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			while($res = mysqli_fetch_array($result)) {		
				echo "<tr class='product'>";
				echo "<td><div>".$res['product_id']."</div></td>";
				echo "<td><div>".$res['name']."</div></td>";
				echo "<td><div>".$res['code']."</div></td>";
				echo "<td><div>".$res['category_path']."</div></td>";
				echo "<td><div>".$res['quantity']."</div></td>";
				echo "<td><div>".$res['price']."</div></td>";
				echo "<td><div>".$res['currency']."</div></td>";
				echo "<td><div>".$res['created_at']."</div></td>";
				echo "<td><div>".$res['updated_at']."</div></td>";
				echo "<td><a href=\"edit.php?id=$res[id]\">Edit</a> | <a href=\"delete.php?id=$res[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td></tr>";		
			}
			?>
		</tbody>
	</table>

	<script src="assets/jquery-3.5.1.js"></script>
	<script src="assets/jquery.dataTables.min.js"></script>
	<script src="assets/dataTables.select.min.js"></script>
	
	<script>
		$(document).ready(function () {
			$('#example').DataTable({
				select: {
					ajax: '../ajax/data/arrays.txt',
					style: 'multi'
				}
			});
		});
	</script>
</body>

</html>