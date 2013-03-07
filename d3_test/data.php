<?php 

$data = array();
$lines = explode("\n", trim(file_get_contents("ssdata.csv")));
$header = str_getcsv(array_shift( $lines ), ";");

$nameKeys = array("fname", "lname", "email_address", "email_domain");

if( array_key_exists("stats", $_REQUEST) ){
    
    $totals = array("fname" => array(), "lname" => array(), "email" => array(), "track" => array());
    
    foreach( $lines as $ln ){
        
        $obj = array();
        foreach( str_getcsv( $ln, ";" ) as $i => $val ){
            
            if( !in_array($header[$i], array("fname", "lname", "email", "track")) ){
                continue;
            }
            
            $val = trim(strtolower($val));            
            if( $header[$i] == "email" ){
                list($addr, $val) = explode("@", $val);
            }                        
            
            if( !array_key_exists($val, $totals[$header[$i]]) ){
                $totals[ $header[$i] ][ $val ] = 0;
            }
            
            $totals[ $header[$i] ][ $val ] += 1;
        }
        
    }
    
    foreach( array_keys($totals) as $key ){
        ksort( $totals[$key] );
    }
    
    print_r($totals);
    
    exit(0);
}

foreach( $lines as $ln ){
	
	$obj = array();
	foreach( str_getcsv( $ln, ";" ) as $i => $val ){
	    
	    if( in_array($header[$i], $nameKeys) ){	    
		    $obj[$header[$i]] = metaphone(trim(strtolower($val)));
	    }else if( $header[$i] == "email" ){
	        list($addr, $domain) = explode("@", $val);	        
	        $obj["email_address"] = metaphone(trim(strtolower($addr)));
	        $obj["email_domain"] = metaphone(trim(strtolower($domain)));
	    }else{
	        $obj[$header[$i]] = trim(strtolower($val));
	    }
	    
	}
	
	$data[] = $obj;	
}


usort($data, function($a, $b){
   return strcmp($a["fname"],  $b["fname"]); 
});


$maxValues = array_fill_keys($nameKeys, array(0 => 0, 1 => 0, 2 => 0));
$payload = array();

foreach( $data as $dt ){	
	$dataEl = array();	
	$keyedSplits = array();
		
	foreach( $nameKeys as $key ){
		$dt[$key] = str_split(trim($dt[$key]));
		$dt[$key] = array_map(function($el){return ord($el);}, $dt[$key]);
		
		$keyedSplits[$key] = array_chunk($dt[$key], ceil( count($dt[$key]) / 3 )) +	 array_fill_keys(array(0, 1, 2), array());
		$keyedSplits[$key] = array_map(function($el) {
									   return count($el) ? array_sum($el) : 0;
							 }, $keyedSplits[$key]);
							 
		foreach( array_keys($maxValues[$key]) as $index ){
			$maxValues[$key][$index] = max( $maxValues[$key][$index], $keyedSplits[$key][$index] );
		}
						 
		$dataEl[$key] = $keyedSplits[$key];
	}

	$dataEl["track"] = $dt["track"];
	
	$payload[] = $dataEl;
}

$payload = array_map(function($dt) use ($nameKeys, $maxValues){
		
	$obj = array( 0 => array(), 1 => array() );
	
	$obj[0][] = getHSLColor( $dt, "fname", $maxValues );	
	$obj[0][] = array("h" => 336, "s" => 0, "l" => .95);
	$obj[0][] = getHSLColor( $dt, "lname", $maxValues );	

	$obj[1][] = getHSLColor( $dt, "email_address", $maxValues );
	$obj[1][] = array("h" => 336, "s" => 0, "l" => .95);
	$obj[1][] = getHSLColor( $dt, "email_domain", $maxValues );
		
	switch( $dt["track"] ){	    
	    case "sales":
	        $trackColor = array("h" => 120, "s" => .78, "l" => .75);
	        break;	        
	    case "prod":
	        $trackColor = array("h" => 293, "s" => .83, "l" => .75);
	        break;	        
	    case "mrkt":
	        $trackColor = array("h" => 31, "s" => .9, "l" => .75);
	        break;	        	    
	    default:
	    case "dev":
	        $trackColor = array("h" => 221, "s" => .83, "l" => .75);
	        break;	    
	}
	
	$obj[2] = array_fill(0, 3, $trackColor);
	
	return $obj;
}, $payload);


echo json_encode( $payload );

function getHSLColor( $dt, $key, $maxValues ){
        
    $colorVal = array("h" => 0, "s" => 0, "l" => 0);
    
    foreach( $dt[$key] as $index => $value ){
         
        $percentMax = $value / $maxValues[$key][$index];
        $colorKey = "";
        $hslVal = $percentMax;
         
        switch( $index ){
            case 0:
                $colorKey = "l";
                break;
            case 1: $colorKey = "s";
            break;
            case 2: $colorKey = "h";
            $hslVal = $percentMax * 359;
            break;
            default: break;
        }
    
        $colorVal[$colorKey] = $hslVal;
    }
    
    return $colorVal;
}