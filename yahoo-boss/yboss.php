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


abstract class yBossBase{
    
    // define this before you use the library
    private $APP_ID = "YOUR APP KEY";
    
    // query params
    private $_start = 0;
    private $_count = 10;
    private $_lang = "en";
    private $_region = "us";
    private $_format = "json";
    private $_sites = array();
    private $_callback = "";
    
    // shared between all the boss search classes
    protected $_totalhits = 0;
    protected $_results = array();
    protected $_query = "";
    
    
    /**
     * Abstract method to be implimented by sub classes.
     * Query the appropriate Y! BOSS URL
     * @param $query The query string to search for.
     **/
    abstract protected function query($query);
    
    /**
     * Moves the pager back one page
     **/
    public function previousPage(){
        $newStart = $this->_start - $this->_count;
        if($newStart < 0)
            return false;
        
        $this->_start = $newStart;
        return $this->query($this->_query);
    }
    
    /**
     * Moves the pager forward one page
     **/
    public function nextPage(){
         
        $newStart = $this->_start + $this->_count;
        if($newStart > $this->_totalhits + $this->_count)
            return false;
        
        $this->_start = $newStart;
        return $this->query($this->_query);
    }
    
    /**
     * Wrapper function to fetch data from a URL
     * Re-impliment with curl if file_get_contents is broken
     **/
    protected function fetchUrl($url){
        return file_get_contents($url);
    }
    
    /**
     * Sets the start cursor
     * @param $s The position to start retrieving resutls at
     **/
    public function setStart($s){
        
        if(!is_numeric($s))
            throw Exception("yboss->setStart: Start must be numeric!");
        
        $this->_start = $s;
    }
    
    /**
     * Sets the # of resutls per page
     *  @param $c The number of results to retrieve per call
     **/
    public function setCount($c){
        
        if(!is_numeric($c))
            throw Exception("yboss->setCount: Count must be numeric!");
        
        $this->_count = $c;
    }
    
    /**
     * Change the return format between JSON and XML.
     * Use with caution
     * @param $f The new format (either JSON or XML)
     **/
    public function setFormat($f){ $this->_format = $f; }
    
    /**
     * Let BOSS wrap the JSON results in a callback
     * Useful if you want to evaluate the JSON client side
     * @param $c The javascript callback function
     **/
    public function setCallback($c){ $this->_callback = $c; }
    
    /**
     * Set region and language information
     * @param $l The language to restrict results to
     * @param $r The region to restrict to
     **/
    public function setRegionLang($l, $r){
        $this->_lang = $l;
        $this->_region = $r;
    }
    
    /**
     * Add a site to the "filter by" list
     * @param $s A Url to filter by
     * @return Returns an id for the new site
     **/
    public function addSite($s){
        $this->_sites[] = $s;
        return count($this->_sites);
    }
    
    /**
     * Remove a filter-by site by it's index (returned from addSite)
     * @param $id The index of the site
     **/
    public function removeSiteById($id){
        $newArray = array();
        
        for($i=0; $i<count($this->_sites); $i++){
            if($i != $id)
                $newArray[] = $this->_sites[$i];
        }
        
        $this->_sites = $newArray;
    }
    
    /**
     * Deletes a site from the filter-by list by URL
     * @param $url The url to remove from the filter list.
     **/
    public function removeSiteByUrl($url){
        $newArray = array();
        
        for($i=0; $i<count($this->_sites); $i++){
            if(strtolower($this->_sites[$i]) != strtolower($url))
                $newArray[] = $this->_sites[$i];
        }
        
        $this->_sites = $newArray; 
    }
    
    /**
     * Removes all the filter-by sites
     **/
    public function clearSites(){
        $this->_sites = array();
    }
    
    /**
     * Builds the query string for the base search options
     * @return A query string with the default parameters
     **/
    protected function getBaseParams(){
        $sites = implode(",", $this->_sites);
        $p = "?appid={$this->APP_ID}&start={$this->_start}&count={$this->_count}&lang={$this->_lang}&region={$this->_region}&format={$this->_format}";
        
        if($this->_callback)
            $p .= "&callback={$this->_callback}";
        if(count($this->_sites) > 0)
            $p .= "&sites={$sites}";
            
        return $p;
        
    }
    
    // public gett'ers
    public function getQuery(){ return $this->$_query; }
    public function getTotalHits(){ return $this->_totalhits; }
    public function getResultSet(){ return $this->_results; }
    public function getStart(){ return $this->_start; }
    public function getCount(){ return $this->_count; }
    public function getFormat(){ return $this->_format; }
    public function getCallback(){ return $this->_callback; }
    public function getLang(){ return $this->_lang; }
    public function getRegion(){ return $this->_region; }
    public function getSites(){ return $this->_sites; }
}

?>