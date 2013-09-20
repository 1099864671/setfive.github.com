<?php 

function readMemoryScan($args){
    	
    $startsWith = strtoupper($args["startsWith"]);
    $state = $args["state"];

    $results = array();
    $dataArray = array();
    $data = explode("\n", trim(file_get_contents("schoollist.csv")));
    
    foreach( $data as $line ){
    	$csvParts = explode(",", $line) + array("", "", "");
        for($i = 0; $i < count($csvParts); $i++){
        	$csvParts[$i] = trim( $csvParts[$i] );
        }        
        $dataArray[] = array("id" => $csvParts[0], "state" => $csvParts[2], "name" => $csvParts[1]);
    }
    
    return $dataArray;
    
    foreach( $dataArray as $obj ){
        if( $obj["state"] == $state && strpos($obj["name"], $startsWith) === 0 ){
            $results[] = array("id" => $obj["id"], "name" => $obj["name"]);
        }
    }

    return $results;
}

function sortedTableScan($args){

    $startsWith = strtoupper($args["startsWith"]);
    $state = $args["state"];

    $results = array();
    $data = explode("\n", trim(file_get_contents("schoollist_sorted.csv")));

    foreach( $data as $line ){
       	$csvParts = explode(",", $line) + array("", "", "");
        for($i = 0; $i < count($csvParts); $i++){
        	$csvParts[$i] = trim( $csvParts[$i] );
        } 

        if( $state == $csvParts[2] && strpos($csvParts[1], $startsWith) === 0 ){
            $results[] = array("id" => $csvParts[0], "name" => $csvParts[1]);
        }else if( count($results) ){
            break;
        }
    }

    return $results;
}

function unsortedTableScan($args){

    $startsWith = strtoupper($args["startsWith"]);
    $state = $args["state"];

    $results = array();
    $data = explode("\n", trim(file_get_contents("schoollist.csv")));

    foreach( $data as $line ){
        $csvParts = explode(",", $line) + array("", "", "");
        for($i = 0; $i < count($csvParts); $i++){
        	$csvParts[$i] = trim( $csvParts[$i] );
        } 

        if( $state == $csvParts[2] && strpos($csvParts[1], $startsWith) === 0 ){
            $results[] = array("id" => $csvParts[0], "name" => $csvParts[1]);
        }
    }

    return $results;
}

function serializednFileScan($args){

    $startsWith = strtoupper($args["startsWith"]);
    $state = $args["state"];

    $stub = substr($startsWith, 0, 3);

    $results = array();
    $data = unserialize(file_get_contents("school_list.json"));

    foreach( $data[$state][$stub] as $el ){
        if( strpos($el["name"], $startsWith) === 0 ){
            $results[] = $el;
        }
    }

    return $results;
}