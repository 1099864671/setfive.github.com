<?php 

$u = sha1($_REQUEST["url"]) . ".pdf";
$res = array();

if( file_exists("pdfs/" . $u) ){
  
  header('Content-type: application/pdf');
  $pdfData = file_get_contents( "pdfs/" . $u );
  echo $pdfData;
  
}

?>