<?php 

if( $_REQUEST["key"] != "jedi" ){
  die("INVALID SECRET KEY!");
}

$url = $_REQUEST["url"];
$dest = sha1( $url ) . ".pdf";
move_uploaded_file($_FILES["pdffile"]["tmp_name"], dirname(__FILE__) . "/pdfs/" . $dest );
chmod( dirname(__FILE__) . "/pdfs/" . $dest, 0755 );

?>