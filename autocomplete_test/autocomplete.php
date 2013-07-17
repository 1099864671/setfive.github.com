<?php 

require_once "algorithms.php";

if( !in_array($_REQUEST["algo"], array("readMemoryScan", "sortedTableScan", "unsortedTableScan", "serializednFileScan", )) ){
    die("bye!");
}

$results = $_REQUEST["algo"]( array("state" => $_REQUEST["state"], "startsWith" => $_REQUEST["search"]) );

echo json_encode( $results );