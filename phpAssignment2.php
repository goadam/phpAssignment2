<html>
<head>
	<title>mySql</title>
</head>
<body>
	<form action="phpAssignment2.php" method="get">
	<label for="name">Name</label>
	<input type="text" name="name" id="name" /><br/>
	
	<label for="category">Category</label>
	<input type="text" name="category" id="category" /><br/>
	
	<label for="length">Length</label>
	<input type="text" name="length" id="length" /><br/>

	<input type="submit" value="Add"/>

</body>
</html>

<?php

include 'storedInfo.php';
$db = new mysqli('oniddb.cws.oregonstate.edu', 'martinad-db', $myPassword, 'martinad-db');
if($db->connect_errno) {
	die('No database connection');
} else {
	echo 'Database found', '<br>';
}

if(isset($_GET['name'])) {
	if(trim($_GET['name']) == "") {
	echo 'You must enter a video name.', '<br>';
}
}

if(isset($_GET['category'])) {
	if(trim($_GET['category']) == "") {
	echo 'You must enter a category.', '<br>';
} 
}

if(isset($_GET['length'])) {
	$len = trim($_GET['length']);
	if(empty($len)) {
		echo 'You must enter a length.';
	} else if (!(ctype_digit($len))) {
		echo 'Length must be a numeric value';
	}
}
	
?>