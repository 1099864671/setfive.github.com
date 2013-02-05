// Taken from http://www.quirksmode.org/js/detect.html
var BrowserDetect = {
  init: function () {
    this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
    this.version = this.searchVersion(navigator.userAgent)
      || this.searchVersion(navigator.appVersion)
      || "an unknown version";
    this.OS = this.searchString(this.dataOS) || "an unknown OS";
  },
  searchString: function (data) {
    for (var i=0;i<data.length;i++) {
      var dataString = data[i].string;
      var dataProp = data[i].prop;
      this.versionSearchString = data[i].versionSearch || data[i].identity;
      if (dataString) {
        if (dataString.indexOf(data[i].subString) != -1)
          return data[i].identity;
      }
      else if (dataProp)
        return data[i].identity;
    }
  },
  searchVersion: function (dataString) {
    var index = dataString.indexOf(this.versionSearchString);
    if (index == -1) return;
    return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
  },
  dataBrowser: [
    {
      string: navigator.userAgent,
      subString: "Chrome",
      identity: "Chrome"
    },
    {   string: navigator.userAgent,
      subString: "OmniWeb",
      versionSearch: "OmniWeb/",
      identity: "OmniWeb"
    },
    {
      string: navigator.vendor,
      subString: "Apple",
      identity: "Safari",
      versionSearch: "Version"
    },
    {
      prop: window.opera,
      identity: "Opera"
    },
    {
      string: navigator.vendor,
      subString: "iCab",
      identity: "iCab"
    },
    {
      string: navigator.vendor,
      subString: "KDE",
      identity: "Konqueror"
    },
    {
      string: navigator.userAgent,
      subString: "Firefox",
      identity: "Firefox"
    },
    {
      string: navigator.vendor,
      subString: "Camino",
      identity: "Camino"
    },
    {   // for newer Netscapes (6+)
      string: navigator.userAgent,
      subString: "Netscape",
      identity: "Netscape"
    },
    {
      string: navigator.userAgent,
      subString: "MSIE",
      identity: "Explorer",
      versionSearch: "MSIE"
    },
    {
      string: navigator.userAgent,
      subString: "Gecko",
      identity: "Mozilla",
      versionSearch: "rv"
    },
    {     // for older Netscapes (4-)
      string: navigator.userAgent,
      subString: "Mozilla",
      identity: "Netscape",
      versionSearch: "Mozilla"
    }
  ],
  dataOS : [
    {
      string: navigator.platform,
      subString: "Win",
      identity: "Windows"
    },
    {
      string: navigator.platform,
      subString: "Mac",
      identity: "Mac"
    },
    {
         string: navigator.userAgent,
         subString: "iPhone",
         identity: "iPhone/iPod"
      },
    {
      string: navigator.platform,
      subString: "Linux",
      identity: "Linux"
    }
  ]

};

BrowserDetect.init();
var sfWebWorkers = new Array();
var SF_WORKER_SERVER = "192.168.1.102";
var SF_WORKER_PORT = "9999";
var sfWwConduitIsLoaded = false;

function sfWebWorkersRecieveData(msg){
  var obj = $.evalJSON( msg );
  var e = new Object();
  e.data = obj.data;
  
  sfWebWorkers[ obj.sfWebWorkerId ].onmessage( e );
}

function sfWebWorkersSWFReady(isReady){
  sfWwConduitIsLoaded = true;
}

if(!((BrowserDetect.browser == "Firefox" && BrowserDetect.version == "3.5")
	    || (BrowserDetect.browser == "Safari" && BrowserDetect.version == "4")) ){
	
  $(document).ready( function(){
   
    var params = "{\"allowscriptaccess\": \"always\"}";
    var vars = "{\"server\": \"" + SF_WORKER_SERVER + "\"" 
                + ", \"port\": \"" + SF_WORKER_PORT + "\"}";
                             
    $("body").append( "<div id='sfWebWorker'></div>" );                
    $("body").append( "<script type='text/javascript'>swfobject.embedSWF('WebWorkerConduit.swf', 'sfWebWorker', '1', '1', '9.0.0', false, "+vars+", "+params+");</script>" );
  
  });
	
  var Worker = function(fileName){ 
                
                 this.messages = new Array();
	               this.fileName = fileName;
	               this.id = sfWebWorkers.length;
	               this.isLoaded = false;
	               
	               sfWebWorkers.push( this );
	                              
	               var pathToFile = "http://" + window.location.hostname 
	                                 + ":" + window.location.port + "/" + fileName;
                 var myId = this.id;
                 
                 var loadWorker = function(){
                    
                    if( sfWwConduitIsLoaded ){
                      sfWebWorkers[ myId ].isLoaded = true;
                      getFlashMovie("sfWebWorker").sendDataToFlash( 
                          $.toJSON( { message_type: 1, id: myId, message: pathToFile } ) );
                    }else{
                        window.setTimeout( function(){ loadWorker(); }, 500 );
                    }
                    
                 };
                  
                 loadWorker(); 
	             };
  
    Worker.prototype.postMessage = 
	    
	    function(data){
	    
	     var myId = this.id;
	     var isLoaded = this.isLoaded;
	     
	     var sendData = function(data){
	       
	       if( sfWwConduitIsLoaded ){
            var e = new Object();
            e.data = data;
            getFlashMovie("sfWebWorker").sendDataToFlash( 
                          $.toJSON( { message_type: 2, id: myId, message: $.toJSON(e) } ) );
         }else{
            window.setTimeout( function(){ sendData(data); }, 500 );
         }
          
	     };
	     
	     sendData(data);
	      
	   };
	   
  }

function getFlashMovie(movieName) {
  var isIE = navigator.appName.indexOf("Microsoft") != -1;
  return (isIE) ? window[movieName] : document[movieName];  
}  