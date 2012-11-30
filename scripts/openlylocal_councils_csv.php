<?php
// Download all councils from OpenlyLocal in CSV format
$data = json_decode(file_get_contents("http://www.openlylocal.com/councils/all.json"),1);
foreach($data['councils'][0] as $key => $value) echo $key.',';
echo "\n";
foreach($data['councils'] as $council) {
	foreach($council as $value) echo '"'.str_replace(array("\r", "\n", "\r\n"),' ',$value).'",';
	echo "\n";
}
?>