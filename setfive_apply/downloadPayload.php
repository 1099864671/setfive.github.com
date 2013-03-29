<?php 

$name = $_REQUEST["name"];
if( !strlen($name) ){
    die("Nope");
}

$data = json_decode(file_get_contents("startTimes.json"), true);
$data[] = array("name" => $name, "at" => date("r"), "ip" => $_SERVER["REMOTE_ADDR"]);
file_put_contents("startTimes.json", json_encode($data));

header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=setfive_apply.zip");
header("Content-Length: " . filesize("setfive_apply.zip"));

readfile("setfive_apply.zip");