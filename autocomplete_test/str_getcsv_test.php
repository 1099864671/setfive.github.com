<?php 

$startTime = microtime(true);

$data = explode("\n", file_get_contents("schoollist.csv"));
foreach( $data as $line ){
	$csvParts = str_getcsv($line);
}

echo "Took " . (microtime(true) - $startTime) . "\n";