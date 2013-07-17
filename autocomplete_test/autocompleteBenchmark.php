<?php

require_once "algorithms.php";

function runBenchmark( $targetFn, $args, $iterations = 1000 ){
    
    $totalTimes = array();
        
    for($i=0; $i < $iterations; $i++){
        $startTime = microtime(true);
        $targetFn($args);
        $totalTimes[] = microtime(true) - $startTime;        
    }
    
    $avg = array_sum($totalTimes) / count($totalTimes);
    
    return array("average" => $avg, "min" => min($totalTimes), "max" => max($totalTimes));        
}

function createSortedCSVFile(){
    $results = array();
    $data = explode("\n", trim(file_get_contents("schoollist.csv")));
    
    foreach( $data as $line ){
        $csvParts = str_getcsv($line) + array("", "", "");
        $csvParts = array_map(function($el){ return trim($el); }, $csvParts);
                            
        if( !array_key_exists($csvParts[2], $results) ){
            $results[ $csvParts[2] ] = array();
        }
                
        $results[ $csvParts[2] ][] = array("name" => $csvParts[1], "code" => $csvParts[0]);
    }
        
    ksort( $results );
    $outputLines = array();
    
    foreach( $results as $state => $list ){
        
        usort($list, function($a, $b){
           if ($a["name"] == $b["name"]) {
               return 0;
           }
           return ($a["name"] < $b["name"]) ? -1 : 1; 
        });
        
        foreach($list as $el){
            $outputLines[] = $el["code"] . "," . $el["name"] . "," . $state;
        }
    }
    
    file_put_contents("schoollist_sorted.csv", join("\n", $outputLines));
}

function createSerializedDataFile(){
    $results = array();
    $data = explode("\n", trim(file_get_contents("schoollist.csv")));

    foreach( $data as $line ){
        $csvParts = str_getcsv($line) + array("", "", "");
        $csvParts = array_map(function($el){
            return trim($el);
        }, $csvParts);

        $stub = substr($csvParts[1], 0, 3);

        if( !array_key_exists($csvParts[2], $results) ){
            $results[ $csvParts[2] ] = array();
        }

        if( !array_key_exists($stub, $results[$csvParts[2]]) ){
            $results[ $csvParts[2]] [ $stub ] = array();
        }

        $results[$csvParts[2]][$stub][] = array("name" => $csvParts[1], "code" => $csvParts[0]);
    }

    file_put_contents( "school_list.json", serialize($results) );
}

echo "test_name, min, max, average\n";

$res = runBenchmark( "readMemoryScan", array("state" => "MA", "startsWith" => "Cam"), 10 );
echo "readMemoryScan," . $res["min"] . ", " . $res["max"] . ", " . $res["average"] . "\n";

$res = runBenchmark( "unsortedTableScan", array("state" => "MA", "startsWith" => "Cam"), 10 );
echo "unsortedTableScan," . $res["min"] . ", " . $res["max"] . ", " . $res["average"] . "\n";

$res = runBenchmark( "sortedTableScan", array("state" => "MA", "startsWith" => "Cam"), 10 );
echo "sortedTableScan," . $res["min"] . ", " . $res["max"] . ", " . $res["average"] . "\n";

$res = runBenchmark( "serializednFileScan", array("state" => "MA", "startsWith" => "Cam"), 10 );
echo "serializednFileScan," . $res["min"] . ", " . $res["max"] . ", " . $res["average"] . "\n";
