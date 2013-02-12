<?php

require_once 'phpQuery-onefile.php';

switch ($_REQUEST ["action"]){
  case "view" :
    
    $owner = $_REQUEST ["owner"];
    $repo = $_REQUEST ["name"];
    $url = "https://api.github.com/repos/" . $owner . "/" . $repo . "/readme";
    
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_FAILONERROR, false );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 300 );
    curl_setopt ( $ch, CURLOPT_POST, 0 );
    $json = json_decode ( curl_exec ( $ch ), true );
    
    $markdown = base64_decode ( $json ["content"] );
    
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, "https://api.github.com/markdown/raw" );
    curl_setopt ( $ch, CURLOPT_FAILONERROR, false );
    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 300 );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $markdown );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, array ("Content-type: text/plain" ) );
    $markdown = curl_exec ( $ch );
    
    echo json_encode ( array ("content" => $markdown ) );
    break;
  
  case "search" :
    
    $queryParams = array ("repo" => "", "langOverride" => "", "type" => "Repositories", "start_value" => "1" );
    
    $filterParts = array ("forks" => array ("*", "*" ), "followers" => array ("*", "*" ), "pushed" => array ("*", "*" ) );
    
    foreach ( $_REQUEST as $k => $v ) {
      
      $v = trim ( $v ) ? $v : "*";
      
      switch ($k) {
        
        case "forks_min" :
          $filterParts ["forks"] [0] = $v;
          break;
        case "forks_max" :
          $filterParts ["forks"] [1] = $v;
          break;
        
        case "followers_min" :
          $filterParts ["followers"] [0] = $v;
          break;
        case "followers_max" :
          $filterParts ["followers"] [1] = $v;
          break;
        
        case "push_min" :
          $filterParts ["pushed"] [0] = $v;
          break;
        case "push_max" :
          $filterParts ["pushed"] [1] = $v;
          break;
        
        case "lastPage" :
          $queryParams ["start_value"] = $v;
          break;
        case "language" :
          $queryParams ["language"] = $v;
          break;
        
        default :
          break;
      }
    
    }
    
    $luceneFilters = array ();
    foreach ( $filterParts as $key => $values ) {
      if ($values [0] == "*" && $values [1] == "*") {
        continue;
      }      
      $luceneFilters [] = $key . ":[" . join ( " TO ", $values ) . "]";
    }
    
    $queryParams ["q"] = join ( " ", $luceneFilters );
    
    $returnJSON = array ();
    
    for($i = 0; $i < 3; $i ++) {
      $returnJSON = array_merge ( $returnJSON, getGitHubResults ( $queryParams ) );
      $queryParams ["start_value"] += 1;
    }
    
    shuffle($returnJSON);
    echo json_encode ( $returnJSON );
  break;
  
  default: break;

}

function getGitHubResults($queryParams) {
  
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_URL, "https://github.com/search/?" . http_build_query ( $queryParams ) );
  curl_setopt ( $ch, CURLOPT_FAILONERROR, false );
  curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt ( $ch, CURLOPT_TIMEOUT, 300 );
  curl_setopt ( $ch, CURLOPT_POST, 0 );
  $html = curl_exec ( $ch );
  
  $doc = phpQuery::newDocument ( $html );
  
  $returnJSON = array ();
  foreach ( $doc [".result"] as $resultDiv ) {
    
    $obj = array ();
    
    list ( $user, $repo ) = explode ( "/", pq ( $resultDiv )->find ( ".title a" )->text () );
    $language = trim ( str_replace ( array (")", "(" ), "", pq ( $resultDiv )->find ( ".title .language" )->text () ) );
    $detailsParts = explode ( "|", pq ( $resultDiv )->find ( ".details" )->text () );
    
    $obj ["owner"] = trim ( $user );
    $obj ["name"] = trim ( $repo );
    $obj ["language"] = $language;
    $obj ["description"] = trim ( pq ( $resultDiv )->find ( ".description" )->text () );
    $obj ["size"] = trim ( $detailsParts [0] );
    $obj ["forks"] = trim ( str_replace ( "forks", "", $detailsParts [1] ) );
    $obj ["watchers"] = trim ( str_replace ( "watchers", "", $detailsParts [2] ) );
    $obj ["last_push"] = trim ( str_replace ( "last activity", "", $detailsParts [3] ) );
    $obj ["url"] = pq ( $resultDiv )->find ( ".title a" )->attr ( "href" );
    
    $returnJSON [] = $obj;
  }
  
  return $returnJSON;
}
