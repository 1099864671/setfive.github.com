<?php

highlight_file("scan.php");
die();

$KeywordArray = array(

array( "keywords" => "voted mccain -not", "callbacks" => array("red" => "keyTen", "blue" => "keyZero") ),
array( "keywords" => "voted obama -not", "callbacks" => array("red" => "keyZero", "blue" => "keyTen") ),

array( "keywords" => "vote mccain -not", "callbacks" => array("red" => "keyTen", "blue" => "keyZero") ),
array( "keywords" => "vote obama -not", "callbacks" => array("red" => "keyZero", "blue" => "keyTen") ),

array( "keywords" => "go obama", "callbacks" => array("red" => "keyZero", "blue" => "keyOne") ),
array( "keywords" => "go mccain", "callbacks" => array("red" => "keyOne", "blue" => "keyZero") )

);

function keyOne($item){
    return 1;
}

function keyZero($item){
    return 0;
}

function keyTen($item){
    return 10;
}

class TwitterScan{
    
    private $userFile;
    private $userList;
    
    function main(){
        
        global $KeywordArray;
        
        $this->userFile = time() . ".users";
        $pid = pcntl_fork();
        if(!$pid){
            $link = mysql_connect('MYSQL_SERVER', 'MYSQL_DBUSER', 'MYSQL_PASSWORD');
            if(!mysql_selectdb('MYSQL_DBUSER'))
                die( mysql_error() );
            $sql = "SELECT * FROM user WHERE 1;";
            $res = mysql_query($sql);
            $users = array();
            
            while( $row = mysql_fetch_assoc($res) ){
                $users[] = $row["user_id"];    
            }
            
            file_put_contents($this->userFile, serialize($users));
            
            mysql_close();
            die();
        }else{
            $status = null;
            pcntl_waitpid($pid, $status);
        }
        
        $this->userList = unserialize( file_get_contents($this->userFile) );
        
        $syncFiles = array();
        $pids = array();
        
        for($i=0; $i < count($KeywordArray); $i++){
            
            $syncTo = time() . "." . $i . ".sync";
            $syncFiles[] = $syncTo;
            $pid = pcntl_fork();
            if(!$pid){
                $this->scan($i, -1, $syncTo);
            }else{
                $pids[] = $pid;
            }
        }
        
        $status = null;
        
        while( count($pids) ){
            pcntl_waitpid(array_pop($pids), $status);
        }
        
        $link = mysql_connect('MYSQL_SERVER', 'MYSQL_DBUSER', 'MYSQL_PASSWORD');
        if(!mysql_selectdb('MYSQL_DBUSER'))
            die( mysql_error() );
        
        $bigArr = array();
        $bigArr["red"] = array();
        $bigArr["blue"] = array();
        
        foreach($syncFiles as $s){
            $arr = unserialize(file_get_contents($s));
            unlink($s);
            
            foreach($arr["red"] as $key => $val){
                if(!array_key_exists($key, $bigArr["red"])){
                    $bigArr["red"][$key] = array("score" => 0, "count" => 0);
                }
                
                $bigArr["red"][$key]["count"] += $val["count"];
                $bigArr["red"][$key]["score"] += $val["score"];
            }
            
            foreach($arr["blue"] as $key => $val){
                if(!array_key_exists($key, $bigArr["blue"])){
                    $bigArr["blue"][$key] = array("score" => 0, "count" => 0);
                }
                
                $bigArr["blue"][$key]["count"] += $val["count"];
                $bigArr["blue"][$key]["score"] += $val["score"];
            }
        }
        
        echo "Blue:" . count($bigArr["blue"]) . "\t" . "Red:" . count($bigArr["red"]) . "\n";
        
        $link = mysql_connect('MYSQL_SERVER', 'MYSQL_DBUSER', 'MYSQL_PASSWORD');
        if(!mysql_selectdb('MYSQL_DBUSER'))
            die( mysql_error() );
        
        while( file_exists("dblock.pid") && ( time() - filemtime("dblock.pid") ) < 900 ){
            sleep(1);
        }
        
        touch("dblock.pid");
        
        $sql = "SELECT * FROM red WHERE 1;";
        $res = mysql_query($sql);
        
        $insertSql = "INSERT INTO red (state, score, count) VALUES ";
        $redInsert = array();
        while( $row = mysql_fetch_assoc($res) ){
            
            $count = (int) $row["count"];
            $score = (int) $row["score"];
            
            if( array_key_exists( $row["state"], $bigArr["red"] ) ){
                $count += $bigArr["red"][ $row["state"] ]["count"];
                $score += $bigArr["red"][ $row["state"] ]["score"];
            }
            
            $redInsert[] = "('" . $row["state"] . "'," . $score . "," . $count . ")";
        }
        
        $sql = "DELETE FROM red WHERE 1";
        mysql_query($sql);
        
        $sql = $insertSql . implode(", ", $redInsert);
        mysql_query($sql);
        
        
        $sql = "SELECT * FROM blue WHERE 1;";
        $res = mysql_query($sql);
        
        $insertSql = "INSERT INTO blue (state, score, count) VALUES ";
        $redInsert = array();
        while( $row = mysql_fetch_assoc($res) ){
            $count = (int) $row["count"];
            $score = (int) $row["score"];
            
            if( array_key_exists( $row["state"], $bigArr["red"] ) ){
                $count += $bigArr["blue"][ $row["state"] ]["count"];
                $score += $bigArr["blue"][ $row["state"] ]["score"];
            }
            
            $redInsert[] = "('" . $row["state"] . "'," . $score . "," . $count . ")";
        }
        
        $sql = "DELETE FROM blue WHERE 1";
        mysql_query($sql);
        
        $sql = $insertSql . implode(", ", $redInsert);
        mysql_query($sql);
        
        unlink( "dblock.pid" );
        unlink( $this->userFile );
        mysql_close();
    }
    
    function processTweet($arr, $callbackArray, $syncFile){
        
        $link = mysql_connect('MYSQL_SERVER', 'MYSQL_DBUSER', 'MYSQL_PASSWORD');
        if(!mysql_selectdb('MYSQL_DBUSER'))
            die( mysql_error() );
        
        $redStateHash = array();
        $blueStateHash = array();
        
        foreach($arr as $res){
        
            if(strlen($res["from_user"]) == 0)
                continue;
            
            $userid = $res["from_user_id"];
            if( in_array($userid, $this->userList) )
                continue;
            
            $sql = "INSERT INTO user (user_id) VALUES (" . $userid . ");";
            if(!mysql_query($sql))
                echo mysql_error();
            
            $url = "http://twittervision.com/user/current_status/" . urlencode($res["from_user"]) . ".json";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = json_decode( curl_exec($ch), true );
            curl_close($ch);
            
            if(is_array($data)){
                $loc = explode( "," , $data["location"]["address"] );
                $state = null;
                
                if( trim($loc[ 2 ]) == "US" || trim($loc[ 2 ]) == "USA" ){
                    $state = trim(strtoupper( $loc[ 1 ] ));
                }
                
                if( trim($loc[ 1 ]) == "US" || trim($loc[ 1 ]) == "USA" ){
                    $state = trim(strtoupper( $loc[ 0 ] ));
                }
                
                if(!is_null($state)){
                    if(!array_key_exists($state, $redStateHash))
                        $redStateHash[$state] = array("score" => 0, "count" => 0);
                    if(!array_key_exists($state, $blueStateHash))
                        $blueStateHash[$state] = array("score" => 0, "count" => 0);
                    
                    $redScore = call_user_func( $callbackArray["red"], $res["text"] );
                    $blueScore = call_user_func( $callbackArray["blue"], $res["text"] );
                    
                    $redStateHash[$state]["count"] += ($redScore > 0) ? 1 : 0;
                    $blueStateHash[$state]["count"] += ($blueScore > 0) ? 1 : 0;
                    
                    $redStateHash[$state]["score"] += (int) $redScore;
                    $blueStateHash[$state]["score"] += (int) $blueScore;
                    
                }
            }
        }
        
        $sync = array();
        $sync["red"] = $redStateHash;
        $sync["blue"] = $blueStateHash;
        
        file_put_contents($syncFile, serialize($sync));
        mysql_close();
        die();
    }
    
    function scan($lineId, $sinceId, $syncTo){
        
        global $KeywordArray;
        
        $keyword = $KeywordArray[$lineId]["keywords"];
       
        $fileSince = -1;
        
        if( file_exists($lineId . ".since") )
            $fileSince = file_get_contents( $lineId . ".since" );
        
        if( $sinceId < 0 ){
            $sinceId = $fileSince;
        }
        
        if($sinceId > 0){
            $url = "http://search.twitter.com/search.json?lang=en&q=" . urlencode($keyword) . "&rpp=100&since_id=" . $sinceId;
        }else{
            $url = "http://search.twitter.com/search.json?lang=en&q=" . urlencode($keyword) . "&rpp=10";
        }
         
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($data, true);
        $res = $json["results"];
        $lastSince = $res[ count($res) ]["id"];
        
        // if( $fileSince > 0 && count($res) == 100 )
        //    echo exec("/usr/bin/php5 scan.php " . $lineId . " " . $lastSince) . "\n";
        
        echo "Found " . count($res) . " tweets \n";
        
        $syncFiles = array();
        $pids = array();
        
        for($i=0; $i < count($res); $i+= 25){
            
            $tmp = array_slice($res, $i, 25);
            $syncFile = trim(time()) . "." . $i . "." . $lineId . ".sync";
            $syncFiles[] = $syncFile;
            
            $pid = pcntl_fork();
            if(!$pid){
                $this->processTweet($tmp, $KeywordArray[$lineId]["callbacks"], $syncFile);
            }else{
                $pids[] = $pid;
            }
            
        }
        
        $status = null;
        while( count($pids) ){
            pcntl_waitpid(array_pop($pids), $status);
        }
        
        $bigArr = array();
        $bigArr["red"] = array();
        $bigArr["blue"] = array();
        
        foreach($syncFiles as $s){
            
            $arr = unserialize(file_get_contents($s));
             
            foreach($arr["red"] as $key => $val){
                if( !array_key_exists($key, $bigArr["red"]) ){
                    $bigArr["red"][$key] = array("score" => 0, "count" => 0);
                }
                
                
                $bigArr["red"][$key]["count"] += $val["count"];
                $bigArr["red"][$key]["score"] += $val["score"];
            }
            
            foreach($arr["blue"] as $key => $val){
                if(!array_key_exists($key, $bigArr["blue"])){
                    $bigArr["blue"][$key] = array("score" => 0, "count" => 0);
                }
                
                $bigArr["blue"][$key]["count"] += $val["count"];
                $bigArr["blue"][$key]["score"] += $val["score"];
            }
            
            unlink($s);
        }
        
        file_put_contents( $lineId . ".since", $res[0]["id"] );
        file_put_contents( $syncTo, serialize($bigArr) );
        die();
    }
    
}

$twitter = new TwitterScan();
$twitter->main();

?>