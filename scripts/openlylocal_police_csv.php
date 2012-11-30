<?php
// Download all councils from OpenlyLocal in CSV format
$data = file_get_contents("/home/mazadillon/clanhosts/myreps/data/openlylocal_police_forces.xml");
$data = json_decode(json_encode((array) simplexml_load_string($data)), 1);
foreach($data['police-force'] as $police) echo $police['id'].','.$police['name']."\n";
?>