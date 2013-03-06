<?php 

$data = array();
$lines = explode("\n", trim(file_get_contents("ssdata.csv")));

$header = str_getcsv(array_shift( $lines ), ";");

foreach( $lines as $ln ){
	
	$obj = array();
	foreach( str_getcsv( $ln, ";" ) as $i => $val ){
		$obj[ $header[$i] ] = strtolower($val);
	}
	
	$data[] = $obj;	
}

shuffle($data);

$nameKeys = array("fname", "lname");

/*
$maxLengths = array("fname" => 0, "lname" => 0);
foreach( $nameKeys as $key ){
	$maxLengths[$key] = array_reduce( $data, 
							function($res, $item) use ($key) {
								return $res + strlen($item[$key]);
					    }, 0 );
	$maxLengths[$key] = ( $maxLengths[$key] / count($data) )  * ord("z");
}
*/

$maxValues = array_fill_keys($nameKeys, array(0 => 0, 1 => 0, 2 => 0));
$payload = array();

foreach( $data as $dt ){	
	$dataEl = array();	
	$keyedSplits = array();
		
	foreach( $nameKeys as $key ){
		$dt[$key] = str_split(trim($dt[$key]));
		$dt[$key] = array_map(function($el){return ord($el);}, $dt[$key]);
		
		$keyedSplits[$key] = array_chunk($dt[$key], ceil( count($dt[$key]) / 3 )) +	 array_fill_keys(array(0, 1, 2), array());
		$keyedSplits[$key] = array_map(function($el) {
									   return count($el) ? array_sum($el) : 0;
							 }, $keyedSplits[$key]);
							 
		foreach( array_keys($maxValues[$key]) as $index ){
			$maxValues[$key][$index] = max( $maxValues[$key][$index], $keyedSplits[$key][$index] );
		}
						 
		$dataEl[$key] = $keyedSplits[$key];
	}

	$payload[] = $dataEl;
}

$payload = array_map(function($dt) use ($nameKeys, $maxValues){
		
	$obj = array();
	foreach($nameKeys as $key){
		$dt[$key] = array_map(function($el, $index) use ($maxValues, $key){ 
								return round($el / $maxValues[$key][$index], 2); 
					}, $dt[$key], array_keys($dt[$key]));	
		$obj[] = $dt[$key];
	}
	
	return $obj;
}, $payload);

$payload = array_slice($payload, 0, 10);

echo json_encode( $payload );