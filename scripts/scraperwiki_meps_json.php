<?php
include '../config.php';
$data = json_decode(file_get_contents("/home/mazadillon/clanhosts/myreps/data/scraperwiki_uk_meps.json"),1);

$count = 0;
foreach($data as $member) {
	#name, region, party, image, url, email, elected
	$name = mysql_real_escape_string($member['name']);
	$region = mysql_real_escape_string($member['region']);
	$party = mysql_real_escape_string($member['party']);
	$image = mysql_real_escape_string($member['image']);
	$url = mysql_real_escape_string($member['website']);
	$email = mysql_real_escape_string($member['email']);
	#$elected = mysql_real_escape_string($member['member']['party']['date_elected']);
	mysql_query("INSERT INTO meps (name, region, party, image, url, email) VALUES ('".$name."','".$region."','".$party."','".$image."','".$url."','".$email."')");
	$count++;
}
echo $count.' MEPs imported.';
?>