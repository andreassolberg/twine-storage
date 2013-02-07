<?php

include('./lib.php');


$dataset = array();
$m = new M();

foreach($twines AS $k => $name) {


	$list = $m->getList($k, 3600*24); // Last 24 hours to report
	// echo '<pre>'; print_r($list);

	$dataset[$k] = array('name' => $name, 'data' => array());
	foreach($list AS $el) {

		echo '<p>' . date('c', $el['ts']) . '  ' . $el['v']['temperature'];

		$dataset[$k]['data'][] = array($el['ts']*1000.0, floatval(str_replace(',', '.', $el['v']['temperature'])));
	}

	// $dataset[$name] = $r;
}



header('Content-type: application/json; charset: utf-8');
echo json_encode($dataset);