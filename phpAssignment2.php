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
$up = 0;
$db = new mysqli('oniddb.cws.oregonstate.edu', 'martinad-db', $myPassword, 'martinad-db');
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
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

if($videoName && $videoCat && $len && $notValid == 0 ) {
	/* Prepared statement, stage 1: prepare */
	if (!($InsStmt = $db->prepare("INSERT INTO videoStore(name, category, length) VALUES (?, ?, ?)"))) {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}

	if (!$InsStmt->bind_param("sss", $videoName, $videoCat, $len)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	if (!$InsStmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$InsStmt->close();
}


//delete
if(isset($_POST['delete'])) {
	if (!($delStmt = $db->prepare("DELETE FROM videoStore WHERE id = ?"))) {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}

	if (!$delStmt->bind_param("i", $_POST['vid'])) {
		echo "Binding parameters failed: (" . $delStmt->errno . ") " . $delStmt->error;
	}

	if (!$delStmt->execute()) {
		echo "Execute failed: (" . $delStmt->errno . ") " . $delStmt->error;
	}
	
	$delStmt->close();
}

//update
if(isset($_POST['check'])) {
	//Select first
	if (!($stmt = $db->prepare("SELECT rented FROM videoStore WHERE id = ?"))) {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}
	
	if (!$stmt->bind_param("i", $_POST['vid'])) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->bind_result($r);
	$stmt->fetch();
	if($r == 1) {
		$r = 0;
	} else {
		$r = 1;
	}
	
	$stmt->close();
	
	if (!($stmt2 = $db->prepare("UPDATE videoStore SET rented = ? WHERE id = ?"))) {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}

	if (!$stmt2->bind_param("ii", $r, $_POST['vid'])) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	if (!$stmt2->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	$stmt2->close();
}

if(isset($_POST['delAll'])) {
	echo "delete all";
	if (!($stmt4 = $db->prepare("DELETE FROM videoStore"))) {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}

	if (!$stmt4->execute()) {
		echo "Execute failed: (" . $stmt4->errno . ") " . $stmt4->error;
	}
		
	$stmt4->close();
}



echo "<form action=phpAssignment2.php method=post>";
echo "<select name='sel'>
<option value='drama'>drama</option>
<option value='horror'>horror</option>
<option value='comedy'>comedy</option>
<option value='romance'>romance</option>
<option value='all movies'>all movies</option>
<option value='action'>action</option>
</select>";
echo "<input type=submit value='Submit'>";
echo "</form>";


echo "<form action=phpAssignment2.php method=post>";
echo "<input type=submit name=delAll value='delete all'>";
echo "</form>";

//display table

if(isset($_POST['sel'])) {
	$up = 1;
	if($_POST['sel'] == "all movies") {
		$up = 0;
	} else {
		if (!($result = $db->prepare("SELECT id, name, category, length, rented FROM videoStore WHERE category = ?"))) {
			echo "Prepare failed: (" . $db->errno . ") " . $db->error;
		}

		if (!$result->bind_param("s", $_POST['sel'])) {
			echo "Binding parameters failed: (" . $result->errno . ") " . $result->error;
		}

		if (!$result->execute()) {
			echo "Execute failed: (" . $result->errno . ") " . $result->error;
		}
			$result->bind_result($id, $videoName, $videoCat, $len, $rent);

		echo "<table border=1>
		<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Category</th>
		<th>Length</th>
		<th>Status</th>
		</tr>";
		while($result->fetch()) {
				echo "<form action=phpAssignment2.php method=post>";
				echo "<tr>";
				echo "<td>" . "<input type=text name=vid value=" . $id . " </td>";
				echo "<td>" . "<input type=text name=vName value=" . $videoName . " </td>";
				echo "<td>" . "<input type=text name=vCat value=" . $videoCat . " </td>";
				echo "<td>" . "<input type=text name=vLen value=" . $len . " </td>";
				
				if($rent == 1) {
					$stat = "available";
				} else {$stat = "checked out";}
				
				echo "<td>" . "<input type=text name=vStat value=" . $stat . " </td>";
				echo "<td>" . "<input type=submit name=delete value='delete'" . "</td>";
				echo "<td>" . "<input type=submit name=check value='check-in/check-out'" . "</td>";
				echo "</tr>";
				echo "</form>";
			}
			echo "</table>";
		$result->close();
	}
}


if($up == 0) {
	if (!($result = $db->prepare("SELECT id, name, category, length, rented FROM videoStore"))) {
		echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}

	$result->execute();
	$result->bind_result($id, $videoName, $videoCat, $len, $rent);

	echo "<table border=1>
	<tr>
	<th>ID</th>
	<th>Name</th>
	<th>Category</th>
	<th>Length</th>
	<th>Status</th>
	</tr>";
	while($result->fetch()) {
			echo "<form action=phpAssignment2.php method=post>";
			echo "<tr>";
			echo "<td>" . "<input type=text name=vid value=" . $id . " </td>";
			echo "<td>" . "<input type=text name=vName value=" . $videoName . " </td>";
			echo "<td>" . "<input type=text name=vCat value=" . $videoCat . " </td>";
			echo "<td>" . "<input type=text name=vLen value=" . $len . " </td>";
			
			if($rent == 1) {
				$stat = "available";
			} else {$stat = "checked out";}
			
			echo "<td>" . "<input type=text name=vStat value=" . $stat . " </td>";
			echo "<td>" . "<input type=submit name=delete value='delete'" . "</td>";
			echo "<td>" . "<input type=submit name=check value='check-in/check-out'" . "</td>";
			echo "</tr>";
			echo "</form>";
		}
		echo "</table>";
		$result->close();
}

?>