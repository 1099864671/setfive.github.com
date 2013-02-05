<?php

/**
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
    Ashish Datta 2008
    Setfive LLC
    http://shout.setfive.com
**/

require_once("yboss_web.php");

$query = $_REQUEST["q"];
$callback = $_REQUEST["c"];

if(!$query){
    die(json_encode(false));
}

$resultArray = array();
$yboss = new yBossWeb();
$yboss->setCount(50);
$arr = $yboss->query($query);

for($i=0; $i<2; $i++){
    $resultArray = array_merge($arr, $resultArray);
    $arr = $yboss->nextPage();
}

$query = strtolower( $_REQUEST["q"] );
if( strpos( $query, "when" ) !== false ){
    $res = extractWhen($resultArray);
}

if( strpos( $query, "who" ) !== false || strpos( $query, "where" ) !== false || strpos( $query, "what" ) !== false ){
    $res = extractWWW($resultArray);
}

if($callback){
    echo $callback . "(" . json_encode( $res ) . ");";
}else{
    echo json_encode( $res );
}

function extractWWW($arr){
    
    $query = strtolower( $_REQUEST["q"] );
    $debug = $_REQUEST["d"];
    
    $tolkens = array();
    $url = $arr[0]["url"];
    $count = array();
    
    foreach($arr as $item){
        
        $abstract = toTolkens($item["abstract"], 2);
        $title = toTolkens($item["title"], 2);
        
        $tolkens = array_merge($abstract, $title);
        
        foreach($tolkens as $a){
            
            // check if its uppercased
            if( strpos($query, strtolower($a)) === false && strlen($a) > 3 && ucwords($a) == $a){
            
                if( array_key_exists($a, $count) )
                    $count[$a] += 1;
                else
                    $count[$a] = 0;
            
            }
        }
        
    }
    
    arsort($count);
    
    if($debug){
        print_r($count);
        die();
    }
    
    $ret = array();
    $str = "";
    
    $keys = array_keys($count);
    $max = $count[$keys[0]];
    
    $str = array($keys[0]);
    
    for($i=1; $i<count($keys); $i++){
        
        if($count[$keys[$i]] / $max >= .75)
            $str[] = $keys[$i];
    }
    
    $ret["msg"] = implode(" ", $str);
    $ret["url"] = $url;
    
    return $ret;
}


function extractWhen($arr){
    
    $shortMonth = array();
    $shortMonth["jan"] = "january";
    $shortMonth["feb"] = "february";
    $shortMonth["mar"] = "march";
    $shortMonth["apr"] = "april";
    $shortMonth["may"] = "may";
    $shortMonth["jun"] = "june";
    $shortMonth["jul"] = "july";
    $shortMonth["aug"] = "august";
    $shortMonth["oct"] = "october";
    $shortMonth["sep"] = "september";
    $shortMonth["nov"] = "november";
    $shortMonth["dec"] = "december";
    
    $tolkens = array();
    $years = array();
    $days = array();
    
    $url = $arr[0]["url"];
    
    foreach($shortMonth as $key => $val){
        $tolkens[$val] = 0;
    }
    
    foreach($arr as $item){
        $abstract = toTolkens($item["abstract"], 1);
        
        foreach($abstract as $a){
            
            $a = strtolower($a);
            
            // check for months
            if( array_key_exists( $a, $tolkens) ){
                $tolkens[$a] += 1;
            }
            
            // check for years
            if( is_numeric($a) && ($a > 1300 && $a < 2050) ){
                if( array_key_exists( $a, $years) ){
                    $years[$a] += 1;
                }else{
                    $years[$a] = 0;
                }
            }
            
            // try days
            if( is_numeric($a) && ($a > 1 && $a < 31) ){
                if( array_key_exists( $a, $days ) ){
                    $days[$a] += 1;
                }else{
                    $days[$a] = 0;
                }
            }
        }
    }
    
    $day = "";
    $month = "";
    $year = "";
    
    $maxVal = -1;
    foreach($tolkens as $key => $val){
        if( $val > $maxVal ){
            $month = $key;
            $maxVal = $val;
        }
    }
    
    $maxVal = -1;
    foreach($years as $key => $val){
        if( $val > $maxVal ){
            $year = $key;
            $maxVal = $val;
        }
    }
    
    $maxVal = -1;
    foreach($days as $key => $val){
        if( $val > $maxVal ){
            $day = $key;
            $maxVal = $val;
        }
    }
    
    $ret = array();
    $ret["msg"] = ucwords($month) . " " . $day . ", " . $year;
    $ret["url"] = $url;
    
    return $ret;
}

function toTolkens($str, $minlen = 1){
    $arr = explode(" ", $str);
    $ret = array();
    
    for($i=0; $i < count($arr); $i++){
        $s = htmlspecialchars_decode($arr[$i]);
        $s = trim($s, ".,!?<>");
        $s = trim($s);
        
        if(strlen($s) >= $minlen && strpos($s, "<b>") === false)
            $ret[] = $s;
    }
    
    return $ret;
}

?>