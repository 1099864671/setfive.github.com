<?php

$func = $_REQUEST["f"];
$domain = $_REQUEST["d"];

$file = md5($func) . "." . time();

if(!$func){
    die();
}

if(!$domain){
    $domain = "-10:10";
}

$filedata = "

set terminal png
# set xrange [ -10 : 10 ]
# set yrange [ -10 : 10 ]
set autoscale x
set autoscale y

set key left box
set samples 50
plot [{$domain}] {$func} ";

if(!file_exists($file))
	file_put_contents($file, $filedata);

chmod($file, 0644);

header('Content-Type: image/png');
passthru("gnuplot " . $file);

unlink($file);

?>
