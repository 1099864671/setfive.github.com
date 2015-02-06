<?php

require_once "config.php";

$ADDRESS_KEYS = ["name", "address_line1", "address_line2", 
                 "address_city", "address_state", "address_zip"];
$key = LOB_TEST_KEY;

class Lob {
  
  private $API_KEY;
  
  public static $ADDRESS_KEYS = ["name", "address_line1", "address_line2", 
                                 "address_city", "address_state", "address_zip"];

  public function __construct($key = LOB_TEST_KEY){
  	$this->API_KEY = $key;
  }
  
  public static function getAddressForm($prefix){
  	
$str = <<<EOF

    				<div class="form-group">
    					<label for="%prefix%name" class="col-md-4 control-label">Name</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" id="%prefix%name" name="[%prefix%][name]">
    					</div>
    				</div>
    				
    				<div class="form-group">
    					<label for="%prefix%address" class="col-md-4 control-label">Address Line 1</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" id="%prefix%address" name="[%prefix%][address_line1]">
    						<span class="help-block">Street address, P.O box, company name, c/o</span>    						
    					</div>
    				</div>

    				<div class="form-group">
    					<label for="%prefix%address2" class="col-md-4 control-label">Address Line 2</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" id="%prefix%address2" name="[%prefix%][address_line2]">
    						<span class="help-block">Apartment, suite, unit, building, floor, etc.</span>
    					</div>
    				</div>
    				
    				<div class="form-group">
    					<label for="%prefix%city" class="col-md-4 control-label">City</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" id="%prefix%city" name="[%prefix%][address_city]">
    					</div>
    				</div>

    				<div class="form-group">
    					<label for="%prefix%state" class="col-md-4 control-label">State</label>
    					<div class="col-sm-8">    						
    						<select class="form-control" id="%prefix%state" name="[%prefix%][address_state]">
    							<option value="">Please select...</option>
								<option value="AL">Alabama</option>
								<option value="AK">Alaska</option>
								<option value="AZ">Arizona</option>
								<option value="AR">Arkansas</option>
								<option value="CA">California</option>
								<option value="CO">Colorado</option>
								<option value="CT">Connecticut</option>
								<option value="DE">Delaware</option>
								<option value="DC">District Of Columbia</option>
								<option value="FL">Florida</option>
								<option value="GA">Georgia</option>
								<option value="HI">Hawaii</option>
								<option value="ID">Idaho</option>
								<option value="IL">Illinois</option>
								<option value="IN">Indiana</option>
								<option value="IA">Iowa</option>
								<option value="KS">Kansas</option>
								<option value="KY">Kentucky</option>
								<option value="LA">Louisiana</option>
								<option value="ME">Maine</option>
								<option value="MD">Maryland</option>
								<option value="MA">Massachusetts</option>
								<option value="MI">Michigan</option>
								<option value="MN">Minnesota</option>
								<option value="MS">Mississippi</option>
								<option value="MO">Missouri</option>
								<option value="MT">Montana</option>
								<option value="NE">Nebraska</option>
								<option value="NV">Nevada</option>
								<option value="NH">New Hampshire</option>
								<option value="NJ">New Jersey</option>
								<option value="NM">New Mexico</option>
								<option value="NY">New York</option>
								<option value="NC">North Carolina</option>
								<option value="ND">North Dakota</option>
								<option value="OH">Ohio</option>
								<option value="OK">Oklahoma</option>
								<option value="OR">Oregon</option>
								<option value="PA">Pennsylvania</option>
								<option value="RI">Rhode Island</option>
								<option value="SC">South Carolina</option>
								<option value="SD">South Dakota</option>
								<option value="TN">Tennessee</option>
								<option value="TX">Texas</option>
								<option value="UT">Utah</option>
								<option value="VT">Vermont</option>
								<option value="VA">Virginia</option>
								<option value="WA">Washington</option>
								<option value="WV">West Virginia</option>
								<option value="WI">Wisconsin</option>
								<option value="WY">Wyoming</option>    						
    						</select>
    					</div>
    				</div>

    				<div class="form-group">
    					<label for="%prefix%zipcode" class="col-md-4 control-label">Zip code</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" id="%prefix%zipcode" name="[%prefix%][address_zip]">
    					</div>
    				</div>

EOF;
  	
  return str_replace("%prefix%", $prefix, $str);
  }
  
  public function sendPostcard( $to, $from, $urls ){
    
    $params = array_merge( ["template" => 1], $urls );
    $addressKeys = ["to" => $to, "from" => $from];
    
    foreach( $addressKeys as $addrKey => $addrVars ){
          
      foreach( self::$ADDRESS_KEYS as $key ){
      
        if( array_key_exists($key, $addrVars) ){
          $params[ $addrKey . "[" . $key . "]" ] = $addrVars[$key];
        }
              
      }
      
    }    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.lob.com/v1/postcards/");
    curl_setopt($ch, CURLOPT_USERPWD, $this->API_KEY);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    
    $output = curl_exec($ch);
    $result = json_decode( $output, true );
    
    curl_close($ch);
    
    return $result;
  }
  
}

/*
$to = ["name" => "Ashish Datta", "address_line1" => "#519 70 Lincoln St.",
       "address_city" => "Boston", "address_state" => "MA",
       "address_zip" => "02111", "address_country" => "US"];
   
$from = ["name" => "Setfive Consulting", "address_line1" => "678 Masschusetts Ave",
         "address_line2" => "Suite 1001", "address_city" => "Cambridge",
         "address_state" => "MA", "address_zip" => "02139", "address_country" => "US"];

$urls = ["front" => "https://lob.com/postcardfront.pdf", "back" => "https://lob.com/postcardback.pdf"];  

$res = Lob::sendPostcard($to, $from, $urls);

print_r( $res );
*/