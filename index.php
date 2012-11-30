<?php
require 'config.php';

function matchCouncil($name,$type) {
	$result = mysql_query("SELECT * FROM councils WHERE type='".mysql_real_escape_string($type)."' AND name='".mysql_real_escape_string($name)."'");
	return mysql_fetch_assoc($result);
}

if(isset($_GET['postcode'])) {
	$data = json_decode(file_get_contents("http://mapit.mysociety.org/postcode/".urlencode($_GET['postcode']).'.json'),1);
	print_r($data);
	foreach($data['areas'] as $area) {
		$areas[$area['type']] = $area['name'];
	}
	if(isset($areas['UTA'])) print_r(matchCouncil($areas['UTA'],'Unitary'));
	//print_r($areas);
}
?>