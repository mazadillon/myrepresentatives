<?php
include '../config.php';
$result = mysql_query("SELECT * FROM policecrimecommissioners");
while($row=mysql_fetch_assoc($result)) {
	$match = mysql_fetch_assoc(mysql_query("SELECT * FROM policeauthorities WHERE name LIKE '".$row['authority']." %'"));
	echo $row['authority'].' = '.$match['name'].' ('.$match['id'].')<br />';
	mysql_query("UPDATE policecrimecommissioners SET authority_id='".$match['id']."' WHERE authority='".$row['authority']."'");
}
?>