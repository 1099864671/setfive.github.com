<?php 

$u = urlencode($_REQUEST["url"]);
$targetUrl = "http://71.232.15.205:9999/generate?u=" . $u;
file_get_contents( $targetUrl );

$res = array("res" => true);
echo json_encode( $res );
?>