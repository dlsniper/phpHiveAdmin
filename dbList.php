<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

function multidimensional_search($parents, $searched) {
	if (empty($searched) || empty($parents)) {
		return false;
	}

	foreach ($parents as $key => $value) {
		$exists = true;
		foreach ($searched as $skey => $svalue) {
			$exists = ($exists && IsSet($parents[$key][$skey]) && $parents[$key][$skey] == $svalue);
		}
		if($exists){ return $key; }
	}

	return false;
}

$transport->open();

$client->execute('show databases');

$db_array = $client->fetchAll();

$i = 0;
echo '<br />';
$a = array("mobile" => array("ip" => "1","only" => "2"));
echo multidimensional_search("2",$a);
while('' != @$db_array[$i]) {
	echo '<a href="javascript:showsd(\'dbStructure.php?database='.$db_array[$i].'\', \'tableList.php?database='.$db_array[$i].'\')" target="left"><img src=images/database.png>'.$db_array[$i].'</a><br />'."\n";
	$i++;
}
$transport->close();
?>