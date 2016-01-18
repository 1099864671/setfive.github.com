<?php 

echo "You submitted:<br>";
foreach($_POST as $key => $val){
    echo $key . " => " . $val . "<br>";
}