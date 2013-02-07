<?php

include('./lib.php');



$dataset = array();

$m = new M();

foreach($twines AS $k => $name) {

	$tw = new T($k, $name);
	$r = $tw->get($k);
	$m->insert($r);

	$dataset[$name] = $r;
}
