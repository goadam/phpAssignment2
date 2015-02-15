<?php

include 'storedInfo.php';
$db = new mysqli('oniddb.cws.oregonstate.edu', 'martinad-db', $myPassword, 'martinad-db');
if($db->connect_errno) {
	die('No database connection');
} else {
	echo 'Database found';
}
?>