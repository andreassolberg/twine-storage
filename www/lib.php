<?php

$twines = array(
	'0000ed20baxxxxxx' => 'Twine 1',
	'000016bfe1xxxxxx' => 'Twine 2',
	'0000f70872xxxxxx' => 'Twine Kitchen',
	'0000c2b595xxxxxx' => 'Twine Garage',
);


class M {

	protected $con = 'mongodb://USERNAME:PASSWORD@linus.mongohq.com:10017/twine';
	protected $client = null;
	protected $db = null;

	function __construct() {
		$this->client = new MongoClient($this->con);
		$this->db = $this->client->twine;
	}

	function getList($id, $ago) {
		$result = array();

		$criteria = array('id' => $id, 'ts' => array('$gt' => time()-$ago));

		// echo '<h3>CRITERIA</h3>';
		// echo(json_encode($criteria));

		$cursor = $this->db->data->find($criteria);
		if (!$cursor->hasNext()) return $result;
		foreach($cursor AS $element) $result[] = $element;	
		return $result;
	}

	function insert($data) {

		$criteria = array(
			'id' => $data['id'],
			'ts' => $data['ts']
		);
		if ($this->exists($criteria)) {
			// echo "\nAlready added. Not adding.";
			return;
		}

		$this->db->data->save($data, array("safe" => true));
	}

	function exists($criteria) {
		$cursor = $this->db->data->find($criteria);
		return ($cursor->count() >= 1);
	}


}

class T {
	protected $id, $name;
	function __construct($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}
	function get() {
		$cookie = 'user_id="XXXX==|XXXX"; temperatureScale=C; __utma=xxx1359493230.7; __utmb=xxx; __utmc=xxx; __utmz=xxx.1.1.utmcsr=twinesetup.com|utmccn=(referral)|utmcmd=referral|utmcct=/';
		$url = 'https://twine.supermechanical.com/' . $this->id . '/rt?cached=1';

		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Accept-language: en\r\n" .
		              "Cookie: " . $cookie . "\r\n"
		  )
		);
		$context = stream_context_create($opts);
		$raw = file_get_contents($url, false, $context);
		$data = json_decode($raw, true);
		$data['v'] = self::pvalues($data['values']);
		$data['id'] = $this->id;
		$data['name'] = $this->name;
		// unset($data['values']);
		return $data;
	}

	static function pvalues($values) {
		$map = array(
			'0000ed20ba33dc4001' => 'temperature',
		);
		$pr = array();

		$pr['temperature'] = number_format(($values[1][1]/100 - 32) * (5/9), 1, ',', '');
		// foreach($values AS $k => $v) {
		// 	if (isset($map[$v[0]])) {
		// 		if ($map[$v[0]] === 'temperature') $v[1] = ;
		// 		$pr[$map[$v[0]]] = $v[1];
		// 	}
		// }
		return $pr;
	}
}
