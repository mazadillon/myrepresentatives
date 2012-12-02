<?php
require 'config.php';

function matchCouncil($name,$type) {
	$result = mysql_query("SELECT * FROM councils WHERE type='".mysql_real_escape_string($type)."' AND name='".mysql_real_escape_string($name)."'");
	return mysql_fetch_assoc($result);
}

function fetchMP($postcode) {
	global $config;
	return json_decode(file_get_contents("http://www.theyworkforyou.com/api/getMP?postcode=".urlencode($postcode)."&output=js&key=".$config['twfy_api']),1);
}

function fetchMEPs($region) {
	$result = mysql_query("SELECT * FROM meps WHERE region = '".mysql_real_escape_string($region)."'");
	if(mysql_num_rows($result) < 1) return false;
	else {
		$meps = array();
		while($row=mysql_fetch_assoc($result)) $meps[] = $row;
		return $meps;
	}
}

if(isset($_GET['postcode'])) {
	$data = json_decode(file_get_contents("http://mapit.mysociety.org/postcode/".urlencode($_GET['postcode']).'.json'),1);
	print_r($data);
	foreach($data['areas'] as $area) {
		$areas[$area['type']] = $area['name'];
	}
	if(isset($areas['WMC'])) {
		$mp = fetchMP($_GET['postcode']);
		echo '<h2>'.$mp['first_name'].' '.$mp['last_name'].'</h2>';
		echo '<p>Parliamentary MP for your constituency &quot;'.$mp['constituency'].'&quot;, they were last elected on '.$mp['entered_house'].'. They represent the '.$mp['party'].' party.</p>';
	}
	if(isset($areas['EUR'])) { 
		$meps = fetchMEPs($areas['EUR']);
		echo '<h2>'.$areas['EUR'].' European Parliament Region</h2>';
		echo '<ul>';
		foreach($meps as $mep) {
			echo '<li><img src="'.$mep['image'].'" /> '.$mep['name'].' a member of the '.$mep['party'].'</li>';
		}
		echo '</ul>';
	}
	if(isset($areas['UTA'])) print_r(matchCouncil($areas['UTA'],'Unitary'));
	//print_r($areas);
}
?>