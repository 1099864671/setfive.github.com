<?php 

  $words = json_decode(stripslashes($_REQUEST["words"]), true);
  $arr = array();
  
  foreach($words as $w){
    $res = json_decode( file_get_contents("http://search.twitter.com/search.json?q={$w}&rpp=1"), true );
    $result = array_pop($res["results"]);
    print_r($result);
  }
  
  echo json_encode($arr);
?>