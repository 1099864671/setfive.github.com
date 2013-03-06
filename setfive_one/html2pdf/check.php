<?php 

$u = sha1($_REQUEST["url"]) . ".pdf";
$c = $_REQUEST["c"];

$res = array();
$res["isReady"] = file_exists("pdfs/" . $u);
echo "$c(" . json_encode( $res ) . ")";

?>