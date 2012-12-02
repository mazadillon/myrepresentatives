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

function fetchPCC($force) {
	$result = mysql_query("SELECT *,policeauthorities.name as authority_name FROM policeauthorities JOIN policecrimecommissioners ON policecrimecommissioners.authority_id=policeauthorities.id WHERE policeauthorities.id='".mysql_real_escape_string($force)."'") or die(mysql_error());
	if(mysql_num_rows($result) < 1) return false;
	else return mysql_fetch_assoc($result);
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
	if(isset($areas['UTA'])) {
		$council = matchCouncil($areas['UTA'],'Unitary');
	}
	if($council['police_force_id']) {
		$pcc = fetchPCC($council['police_force_id']);
		if($pcc) {
			echo '<h2>'.$pcc['name'].'</h2>';
			echo '<p>Your elected police crime commissioner for '.$pcc['authority_name'];
			if($pcc['party']!='independent') echo ', a member of the '.ucwords($pcc['party']).' party.</p>';
			else echo ', an independent</p>';
		}
	}
	//print_r($areas);
}
?>