<?php 

$data = json_decode( file_get_contents("contacts.json"), true );
$data[] = $_REQUEST;
file_put_contents( "contacts.json", json_encode($data) );

header("Location: game.php?n=2");