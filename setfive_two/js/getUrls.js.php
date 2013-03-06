<?php 
session_start();
$c = $_REQUEST["c"];
$urls = json_encode( print_r($_SESSION["urls"], true) );
echo $c . "(" . $urls . ");";