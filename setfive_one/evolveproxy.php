<?php
  $q = strip_tags($_REQUEST["q"]);
  
  $link = mysql_connect('localhost', 'evolve', 'genes11');
  mysql_select_db("evolve");
  
  if(!is_null($q) && strlen($q) > 1){
    echo file_get_contents("http://setfive.com:9999/?q=" . urlencode($q));
    
    $sql = "INSERT INTO queries (query, at) 
              VALUES ('" . mysql_real_escape_string($q) . "', NOW())";
    $res = mysql_query($sql);
  }else{
  	$sql = "SELECT query FROM queries WHERE 1 ORDER BY at DESC LIMIT 5";
  	$res = mysql_query($sql);
  	while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
  		echo "<li><a href='' onclick='setQuery(\"{$row["query"]}\"); return false;'>{$row["query"]}</a></li>";  	
  	}
  }
?>