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

	<input type="submit" value="Add"/><br/>
	</form>

</body>
</html>

<?php

include 'storedInfo.php';
$notValid = 0;
$db = new mysqli('oniddb.cws.oregonstate.edu', 'martinad-db', $myPassword, 'martinad-db');
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}


if(isset($_GET['name'])) {
	$videoName = $_GET['name'];
	if(trim($videoName) == "") {
	echo 'You must enter a video name.', '<br>';
	$notValid = 1;
}
}

if(isset($_GET['category'])) {
	$videoCat = $_GET['category'];
	if(trim($videoCat) == "") {
	echo 'You must enter a category.', '<br>';
	$notValid = 1;
} 
}

if(isset($_GET['length'])) {
	$len = trim($_GET['length']);
	if(empty($len)) {
		echo 'You must enter a length.';
	} else if (!(ctype_digit($len))) {
		echo 'Length must be a numeric value';
		$notValid = 1;
	}
}

//delete
if(isset($_POST['delete'])) {
	echo 'delete';
	if (!($stmt = $db->prepare("DELETE FROM videoStore WHERE id = ?"))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt->bind_param("i", $_POST['vid'])) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
}



if($videoName && $videoCat && $len && $valid == 0 ) {
	/* Prepared statement, stage 1: prepare */
if (!($stmt = $db->prepare("INSERT INTO videoStore(name, category, length) VALUES (?, ?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$stmt->bind_param("sss", $videoName, $videoCat, $len)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

}

//display table
if (!($result = $db->prepare("SELECT id, name, category, length FROM videoStore"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$result->execute();
$result->bind_result($id, $videoName, $videoCat, $len);

echo "<table border=1>
<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Length</th>
</tr>";
while($result->fetch()) {
	    echo "<form action=phpAssignment2.php method=post>";
	    echo "<tr>";
		echo "<td>" . "<input type=text name=vid value=" . $id . " </td>";
		echo "<td>" . "<input type=text name=vName value=" . $videoName . " </td>";
		echo "<td>" . "<input type=text name=vCat value=" . $videoCat . " </td>";
		echo "<td>" . "<input type=text name=vLen value=" . $len . " </td>";
		echo "<td>" . "<input type=submit name=delete value='delete'" . "</td>";
		echo "</tr>";
		echo "</form>";
	}
	echo "</table>";
?>