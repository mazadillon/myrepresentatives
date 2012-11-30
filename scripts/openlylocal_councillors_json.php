<?php
include '../config.php';
$data = json_decode(file_get_contents("/home/mazadillon/clanhosts/myreps/data/openlylocal_councillors.json"),1);
$data = json_decode($data['members'],1);
$count = 0;
foreach($data as $member) {
	#id, name, council, ward, party, elected
	$id = mysql_real_escape_string($member['member']['id']);
	$name = mysql_real_escape_string($member['member']['first_name']).' '.mysql_real_escape_string($member['member']['last_name']);
	$council = mysql_real_escape_string($member['member']['council']['id']);
	$ward = mysql_real_escape_string($member['member']['ward']['id']);
	$ward_name = mysql_real_escape_string($member['member']['ward']['name']);
	$party = mysql_real_escape_string($member['member']['party']['name']);
	$elected = mysql_real_escape_string($member['member']['party']['date_elected']);
	mysql_query("INSERT INTO councillors (id, name, council, ward, party, elected) VALUES ('".$id."','".$name."','".$council."','".$ward."','".$party."','".$elected."')");
	mysql_query("INSERT IGNORE INTO wards (id, name, council) VALUES('".$ward."','".$ward_name."','".$council."')");
	$count++;
}
echo $count.' councillors imported.';
?>