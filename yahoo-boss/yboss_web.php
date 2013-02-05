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

require_once("yboss.php");

class yBossWeb extends yBossBase{
    
    // The url to ping for results
    public static $BOSSWEB_URL = "http://boss.yahooapis.com/ysearch/web/v1/";
    
    // Restrict by type constants
    public static $TYPE_HTML = "html";
    public static $TYPE_TEXT = "text";
    public static $TYPE_PDF = "pdf";
    public static $TYPE_EXCEL = "xl";
    public static $TYPE_MSWORD = "msword";
    public static $TYPE_PPT = "ppt";
    
    // Restrict by group constants
    public static $TYPE_MSOFFICE = "msoffice";
    public static $TYPE_NOHTML = "nonhtml";
    
    // Filter by constants
    public static $FILTER_HATE = 1;
    public static $FILTER_PORN = 2;
    
    // what if anything we are filtering or restricting by
    private $_filter = 0;
    private $_types = array();
    
    /**
     * Queries Web BOSS and returns the data.
     * Useful for processing the data client-side
     * @param $query The query string to search for
     * @return Returns whatever BOSS returns (JSON or XML)
     **/
    public function rawQuery($query){
        $this->_query = $query;
        $url = yBossWeb::$BOSSWEB_URL . urlencode($query) . $this->getBaseParams() . $this->getSearchParams();
        $res = $this->fetchUrl($url);
    
        return $res;
    }
    
    /**
     * Queries BOSS and returns the result set
     * @param $query The query to search for.
     * @return Returns an array containing the processed JSON results
     **/
    public function query($query){
        
        $res = $this->rawQuery($query);
        
        if(!$res)
            throw new Exception("yBossWeb->query: fetchURL returned null.");
        
        $json = json_decode($res, true);
        $dataset = $json["ysearchresponse"];
        
        $this->_results = $dataset["resultset_web"];
        $this->_totalhits = $dataset["totalhits"];
        
        return $this->_results;
    }
    
    /**
     * Creates the query string specific to WEB search.
     * @return Returns part of a query string.
     **/
    private function getSearchParams(){
        
        $filter = array();
        
        if($this->_filter & yBossWeb::$FILTER_HATE)
            $filter[] = "-hate";
        
        if($this->_filter & yBossWeb::$FILTER_PORN)
            $filter[] = "-porn";
        
        $q = "";
        $filterString = implode(" ", $filter);
        $docTypes = implode(",", $this->_types);
        
        if(count($this->_types) > 0)
            $q = "&type={$docTypes}";
        
        if(count($filter) > 0)
            $q .= "&filter={$filterString}";
        
        return $q;
    }
    
    /**
     * Adds a doc type filter.
     * @param $t One of the $TYPE_* constants to add.
     **/
    public function addDocType($t){
        $this->_types[] = $t;
    }
    
    /**
     * Adds a NOT doc type filter
     * @param $t One of the $TYPE_* constants to add.
     **/
    public function excludeDocType($t){
        $this->_types[] = "-" . $t;
    }
    
    /**
     * Clears any doc-type filters.
     **/
    public function clearDocTypes(){
        $this->_types = array();
    }
    
    /**
     * Clears any content filters
     **/
    public function clearFilters(){
        $this->_filter = 0;
    }
    
    /**
     * Adds a content filter
     * @param $f One of the $FILTER_* constants.
     **/
    public function addFilter($f){
        $this->_filter = $this->_filter | $f;
    }
    
}

?>