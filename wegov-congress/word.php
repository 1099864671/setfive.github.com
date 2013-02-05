<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Words of Congress</title>

<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />

<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="jquery.autocomplete.pack.js"></script>
<script type="text/javascript" src="jquery.scrollTo-min.js"></script>

<style>
body {
  background-color: black;
  color: white;
  font-family: Arial;
  font-size: 11px;
}

#word-container {
  /* width: 200000px; */
}

#word-container, #word-search {
  padding: 20px;
  font-size: 16px !important;
}

#word-search {
  font-size: 14px;
  font-weight: bold;
}

.cloud-word {
  padding-left: 10px;
  padding-top: 7px;
  float: left;
}

.blue-bar {
  padding-left: 3px;
}

.percent {
  font-size: 10px;
  color: white;
}

a img {
  border: none;
}

#logo {
  font-size: 18px;
}

a {
  color: #009cff;
  text-decoration: none;
}

</style>
</head>
<body>

<script type="text/javascript">
function sort( isPopular ){

	if(isPopular){
		  $("#alpha").hide();
		  $("#popularity").show();
	}else{
	    $("#alpha").show();
	    $("#popularity").hide();
	}
}
</script>

 <div id="word-container">
 
  <div id="logo">
    <a href="http://www.setfive.com" target="_blank">
      <img src="http://setfive.com/logo.png" />
    </a>
    
    <p>
      The following words were extracted from the bills that were proposed during the 111th congress.
      <br /> The blue bars represent the % that the word appeared proportionally to the most popular word.
      <br /> The words are colored from white to black depending on their popularity rank.
      
      <br /><br />Sort By: <a href="#" onclick="sort(true); return false;">Popularity</a> 
                          | <a href="#" onclick="sort(false); return false;">Alphabetically</a>
    </p>
  </div>
 
 <div id="popularity">
  <?php 
    
    $MAX_COUNT = 1;
    $arr = explode("\n", file_get_contents("wordlist.txt")); 
    $data = array();
    $positionHash = array();
    $wordCount = 0;
    
    foreach( $arr as $line ){
      list( $word, $count ) = explode(", ", $line);
      $word = trim( $word );
      
      if( strlen($word) < 6 ){ continue; }
      
      $data[ $count ] = $word;
      $positionHash[ $word ] = 1 - ( $wordCount / count($arr) ); 
      $MAX_COUNT = $count > $MAX_COUNT ? $count : $MAX_COUNT;
      $wordCount += 1;
    }
    
    // asort($data);
    
    $wordCount = 0;
    
    foreach( $data as $count => $word ){
      $fontMultiplier = ( $count / $MAX_COUNT );
      $fontSize = ( 200 * $fontMultiplier ) . "px";
      
      $colorPercent = $positionHash[ $word ];
      $colorPart = dechex( ceil(hexdec("ff") * ($colorPercent)) );
      
      if( hexdec($colorPart) < 16 ){
        $colorPart = "0" . $colorPart;
      }
      
      $color = "#"  . $colorPart . $colorPart . $colorPart;
      
      $maxWidth = (strlen($word)*6) . "px";
      $barWidth = (strlen($word)*6) * $fontMultiplier;
      
      if( $barWidth < 1 ){ $barWidth = "1"; }
      
      $wordCount += 1;
      ?>
      <span class="cloud-word" title="<?php echo ceil($fontMultiplier*100) ?>%" style="color: <?php echo $color ?>;">
        <img class="blue-bar" src="blue.png" style="width: <?php echo $barWidth?>px; height: 12px" />
        <span class="percent"><?php echo ceil($fontMultiplier*100) ?>%</span> 
        <br />
        <?php echo $word;?></span>
      <?php } ?>
  </div>
  
 <div id="alpha" style="display: none">
  <?php 
    
    $MAX_COUNT = 1;
    $arr = explode("\n", file_get_contents("wordlist.txt")); 
    $data = array();
    $positionHash = array();
    $wordCount = 0;
    
    foreach( $arr as $line ){
      list( $word, $count ) = explode(", ", $line);
      $word = trim( $word );
      
      if( strlen($word) < 6 ){ continue; }
      
      $data[ $count ] = $word;
      $positionHash[ $word ] = 1 - ( $wordCount / count($arr) ); 
      $MAX_COUNT = $count > $MAX_COUNT ? $count : $MAX_COUNT;
      $wordCount += 1;
    }
    
    asort($data);
    
    $wordCount = 0;
    
    foreach( $data as $count => $word ){
      $fontMultiplier = ( $count / $MAX_COUNT );
      $fontSize = ( 200 * $fontMultiplier ) . "px";
      
      $colorPercent = $positionHash[ $word ];
      $colorPart = dechex( ceil(hexdec("ff") * ($colorPercent)) );
      
      if( hexdec($colorPart) < 16 ){
        $colorPart = "0" . $colorPart;
      }
      
      $color = "#"  . $colorPart . $colorPart . $colorPart;
      
      $maxWidth = (strlen($word)*6) . "px";
      $barWidth = (strlen($word)*6) * $fontMultiplier;
      
      if( $barWidth < 1 ){ $barWidth = "1"; }
      
      $wordCount += 1;
      ?>
      <span class="cloud-word" title="<?php echo ceil($fontMultiplier*100) ?>%" style="color: <?php echo $color ?>;">
        <img class="blue-bar" src="blue.png" style="width: <?php echo $barWidth?>px; height: 12px" />
        <span class="percent"><?php echo ceil($fontMultiplier*100) ?>%</span> 
        <br />
        <?php echo $word;?></span>
      <?php } ?>
  </div>
  
 </div>
 
</body>
</html>