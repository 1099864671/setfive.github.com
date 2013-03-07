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

if( array_key_exists("filters", $_REQUEST) ){
	$trackFilters = $_REQUEST["filters"];
}else{
	$trackFilters = array();
}

$maxLengths = array_fill_keys( $nameKeys, 0 );

foreach( $lines as $ln ){
	
	$obj = array();
	foreach( str_getcsv( $ln, ";" ) as $i => $val ){
	    
	    if( in_array($header[$i], $nameKeys) ){	    
		    $obj[$header[$i]] = metaphone(trim(strtolower($val)));		    
		    $obj[$header[$i] . "_text"] = trim(strtolower($val));
		    $maxLengths[$header[$i]] = max( $maxLengths[$header[$i]], strlen($obj[$header[$i] . "_text"]) );		    
	    }else if( $header[$i] == "email" ){
	        list($addr, $domain) = explode("@", $val);	        
	        $obj["email_address"] = metaphone(trim(strtolower($addr)));
	        $obj["email_domain"] = metaphone(trim(strtolower($domain)));
	    }else{
	        $obj[$header[$i]] = trim(strtolower($val));
	    }
	    
	}
	
	if( in_array($obj["track"], $trackFilters) ){
		$data[] = $obj;
	}
		
}


$sortKey = "fname_text";
if( array_key_exists("sortBy", $_REQUEST) ){
    $sortKey = $_REQUEST["sortBy"];
}

usort($data, function($a, $b) use($sortKey) {
   return strcmp($a[ $sortKey ],  $b[ $sortKey ]); 
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
		$dataEl[$key ."_text"] = $dt[$key . "_text"];
	}

	$dataEl["track"] = $dt["track"];	
	
	$payload[] = $dataEl;
}

$payload = array_map(function($dt) use ($nameKeys, $maxValues, $maxLengths){

	$obj = array( 0 => array(), 1 => array() );
	
	$fnamePercent = getLetterHSLColor( $dt["fname_text"][0] );	
	$fnameLengthPercent = strlen($dt["fname_text"]) / $maxLengths["fname"];
		
	$obj[0][] = array("h" => 40, "s" => 100, "l" => $fnamePercent);
	$obj[0][] = array("h" => 340, "s" => 100, "l" => $fnameLengthPercent);
	$obj[0][] = getHSLColor( $dt, "fname", $maxValues );	

	$lnamePercent = getLetterHSLColor( $dt["lname_text"][0] );
	$lnameLengthPercent = strlen($dt["lname_text"]) / $maxLengths["lname"];
	
	$obj[1][] = array("h" => 40, "s" => 100, "l" => $lnamePercent);
	$obj[1][] = array("h" => 340, "s" => 100, "l" => $lnameLengthPercent);
	$obj[1][] = getHSLColor( $dt, "lname", $maxValues );	
	
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
	
	$obj[2][] = $trackColor;
	$obj[2][] = getHSLColor( $dt, "email_address", $maxValues );
	$obj[2][] = getHSLColor( $dt, "email_domain", $maxValues );	
	
	return $obj;
}, $payload);


// $payload = array_slice($payload, 0, 3);

echo json_encode( $payload );


function getLetterHSLColor( $letter ){
	return (ord("z") - ord($letter)) / 25;
}

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