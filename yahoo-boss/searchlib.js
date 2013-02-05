
function encodeJSON(object){
	return Object.toJSON(object);
}

function decodeJSON(string){
	var s = new String(string);
        return s.evalJSON(true);
}

function ajaxRequest(url, opts){
    
        var head = document.getElementsByTagName("head")[0];
        // Create object
        oScript = document.createElement("script");
        	
	if(opts.queryString)
            url += "?" + opts.queryString;
	    
        if (url.indexOf("?") > -1) url += "&random="+Math.random();
        else url+= "?random="+Math.random();
        
        oScript.setAttribute("src",url); 
        oScript.setAttribute("id","wikiasearch-" + Math.random());
        oScript.innerHTML += 'function processJSON(json){ alert(json); }';
        head.appendChild(oScript); 
	
}

/**
 * The Wikia search API
 */
function WikiaSearchAPI(user, searchId, language){
	this.userName = user;
	this.searchId = searchId;
	this.lang = language;
}

var processJSONCallback;

/**
 * Defines what KT stat to retrieve.
 */
WikiaSearchAPI.prototype.KT_ADD = 1;
WikiaSearchAPI.prototype.KT_EDIT = 2;
WikiaSearchAPI.prototype.KT_STAR = 4;
WikiaSearchAPI.prototype.KT_DELETE = 8;
WikiaSearchAPI.prototype.KT_SPOTLIGHT = 16;
WikiaSearchAPI.prototype.KT_COMMENTS = 32;

// if the KT domain changes you'll need to change the URL that the sandboxes are using
WikiaSearchAPI.prototype.uiURL = 'http://fp029.sjc.wikia-inc.com/search/';

WikiaSearchAPI.prototype.ktURL = 'http://kt.search.isc.org/kt/new.js';
WikiaSearchAPI.prototype.ktDataURL = 'http://kt.search.isc.org/kt/kt.js';
WikiaSearchAPI.prototype.ktNow = 'http://kt.search.isc.org/kt/now.js';

WikiaSearchAPI.prototype.nutchURL = 'http://index.search.isc.org/nutchsearch';
WikiaSearchAPI.prototype.wikiURL = 'http://search.wikia.com/';

WikiaSearchAPI.prototype.userName = "toolbar";
WikiaSearchAPI.prototype.searchId = "toolbar";
WikiaSearchAPI.prototype.lang = "en";

function trace(message){
 document.writeln(message);   
}

WikiaSearchAPI.prototype.doLogin = function (userName, password, onComplete, onError){
	
	var url = this.wikiURL + "index.php?action=ajax&rs=wfDoLoginJSONPost";
	var queryString = 'wpName=' + userName + '&wpPassword=' + password + '&wpRemember=1&wpSourceForm=login';
	
	ajaxRequest(url, { 
						method: "POST", 
						queryString: queryString,
						onSuccess: function(transport){ 
							
							ajaxRequest('http://search.wikia.com/' + "index.php?action=ajax&rs=wfCheckUserLoginJSON", 
									    {	method:"GET", 
									     	onSuccess: function(transport)
									     	{ 
  					   							var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  												s.setLoginCookie = function(json){ return json; };
  												s.set_header_loggedin = function(){ return };
  												s.handle_user_logged_in = function(json){ return json.hash };
  												
  												var hash = Components.utils.evalInSandbox(transport.responseText, s);
  												onComplete(hash);
									     	} 
									     });
						
						},
						onError: onError
					  }
				);
}

function processJSON(json){
    processJSONCallback( json );
}

/**
 * Makes a call to the search server.
 * @param {} keyword The keyword to search.
 * @param {} callback A callback function to call onComplete.
 * @param {} opts Any options that the callback function needs.
 */
WikiaSearchAPI.prototype.search = function (keyword, callback, opts, onError){
	keyword = keyword.toLowerCase();
	
        processJSONCallback = callback;
        
        if(!opts)
            opts = new Object();
            
        if(!opts.lang)
            opts.lang = "en";
        if(!opts.perPage)
            opts.perPage = 15;
        if(!opts.start)
            opts.start = 0;
        
	var url = this.nutchURL + '?r=' + Math.random() + '&query=' + keyword +
                    '&hitsPerSite=1&lang=' + opts.lang + '&hitsPerPage=' + opts.perPage + '&type=json&start=' + opts.start;
	
	trace("SEARCH: " + keyword);
            
  	ajaxRequest(url, { method: 'GET', onSuccess: callback} );
	
};

/**
 * Makes a request to now.js to determine the server time and client IP.
 * @param Function callback A function call onComplete
 */
WikiaSearchAPI.prototype.getNow = function (callback, onError){
	
	var url = this.ktNow + "?r=" + Math.random() + "&f=ktNow(";
	
	trace("Get Now");
	
	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: function(transport){
  					   				var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  									s.ktNow = function(json){ return json; };
  									
  									var result = Components.utils.evalInSandbox(transport.responseText,s );
  									callback( result ); 
  						},
  						onError: onError
  						});
}

/**
 * Retrives KT info for keyword.
 * @param {} types Combination of KT constants (joined by | ) to retrive
 * @param {} keyword The keyword to retrive
 * @param {} callback A function to call onComplete
 * @param {} opts Any extra options the callback needs (keyword, state, ect)
 */
WikiaSearchAPI.prototype.getKT = function (types, keyword, callback, opts, onError){
	
	keyword = keyword.toLowerCase();
	
	var url = this.ktDataURL + '?r=' + Math.random() + "&k=" + keyword;
	
	if(types & this.KT_ADD)
		url += "&t=add";
	if(types & this.KT_EDIT)
		url += "&t=edit";
	if(types & this.KT_STAR)
		url += "&t=stars";
	if(types & this.KT_DELETE)
		url += "&t=del";
	if(types & this.KT_SPOTLIGHT)
		url += "&t=spot";
	if(types & this.KT_COMMENTS)
		url += "&t=com";
		
	url += "&f=processKT(";
	
	trace("GET KT: " + keyword + " : " + types);
	
	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: function(transport){
  								   var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  								   s.processKT = function (json){ return json; };
  								   
  								   var result = Components.utils.evalInSandbox(transport.responseText,s );
  								   callback(result, opts);
  								  },
  						onError: onError
  						});
}


/**
 * Adds an annotation to a url/keyword pair.
 * @param {} keyword The keyword to add against.
 * @param {} addURL The url to add to (must match against keyword)
 * @param {} item An item to annotate
 * @param {} callback A function called onComplete
 */

// ?t=selection&k=google&v=971e9f278619d108f7ffe9f3b437e87d&
// j={"url": "http://google.about.com/", 
// "sel": "Ok, now everyone is just getting silly. The media referred to a Microsoft-Yahoo! merger as \"Microhoo,\" and now InformationWeek is talking about \"Goohoo.\" ", "user": "Adatta", "l": "en"}

WikiaSearchAPI.prototype.annotate = function (keyword, addURL, item, callback, onError){
	keyword = keyword.toLowerCase();
	
	var obj = item;
	obj.url = addURL;
	obj.user = this.userName;
	obj.l = this.lang;
	
	var url = this.ktURL + '?r=' + Math.random() + "&t=selection&k=" + keyword + "&v=" + this.searchId + "&j=" + escape(encodeJSON(obj));
	
	trace("ANNOTATE: " + keyword + " : " + encodeJSON(obj));
	
  	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: function(transport){
									var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  									s.processKTN = function (json){ 
  													if(json=="Good"){
  														return true;
  													}else{
  														return false;
  														} 
  													};
  									var result = Components.utils.evalInSandbox(transport.responseText,s );
									callback(result);
  								},
  						onError: onError
  							} );
	
}

/**
 * Spotlights a URL for keyword.
 * @param {} keyword The keyword to spotlight
 * @param {} URL to spotlight - msut be a search result for keyword.
 * @param {} callback A callback onComplete
 */
WikiaSearchAPI.prototype.spotlight = function (keyword, addURL, callback, opts, onError){
	// t=spot&k=ashish&v=971e9f278619d108f7ffe9f3b437e87d&
	// j={"url": "http://www.facebook.com/", "user": "Adatta", "l": "en"}
	keyword = keyword.toLowerCase();
	
	var obj = new Object();
	obj.url = addURL;
	obj.user = this.userName;
	obj.l = this.lang;
	
	var url = this.ktURL + '?r=' + Math.random() + "&t=spot&k=" + keyword + "&v=" + this.searchId + "&j=" + escape(encodeJSON(obj));
	
	trace("SPLOTLIGHT: " + keyword);
	
  	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: function(transport){
  								   var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  								   s.processKTN = function (json){ 
  													if(json=="Good"){
  														return true;
  													}else{
  														return false;
  														} 
  													};
  								   var result = Components.utils.evalInSandbox(transport.responseText,s );
  								   callback(result, opts);
  								},
  					   onError: onError
  							} );
}

/**
 * Saves a comment.
 * @param {} keyword A keyword to save to
 * @param {} addURL A URL to comment on - must be a search result for keyword.
 * @param {} comment The new comment
 * @param {} callback onComplete callback
 */
WikiaSearchAPI.prototype.saveComment = function (keyword, addURL, comment, callback, opts, onError){
	// t=com&k=ashish&v=971e9f278619d108f7ffe9f3b437e87d&
	// j={"com": "this is so fake", "url": "http://www.google.com", "user": "Adatta", "l": "en"}
	keyword = keyword.toLowerCase();
	
	var obj = new Object();
	obj.url = addURL;
	obj.com = comment;
	obj.user = this.userName;
	obj.l = this.lang;
	
	var url = this.ktURL + '?r=' + Math.random() + "&t=com&k=" + keyword + "&v=" + this.searchId + "&j=" + escape(encodeJSON(obj));
	
	trace("COMMENT: " + keyword + " : " + encodeJSON(obj));
	
  	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: function(transport){
  									var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  									s.processKTN = function (json){ 
  													if(json=="Good"){
  														return true;
  													}else{
  														return false;
  														} 
  													};
  									var result = Components.utils.evalInSandbox(transport.responseText,s );
  									callback(result, opts);
  								},
  						onError: onError
  							} );
	
}

/**
 * Sets the star rating for addURL.
 * @param {} keyword A keyword to star.
 * @param {} addURL The URL to star - must be a search result off keyword.
 * @param {} stars # of stars to set [1-5]
 * @param {} callback onComplete callback.
 */
WikiaSearchAPI.prototype.setStar = function (keyword, addURL, stars, callback, onError){
	// t=stars&k=ashish&v=971e9f278619d108f7ffe9f3b437e87d&
	// j={"url": "http://en.wikipedia.org/wiki/Ashish_Vidyarthi", "rating": 5, "user": "Adatta", "l": "en"}

	keyword = keyword.toLowerCase();

	var obj = new Object();
	obj.url = addURL;
	obj.rating = stars;
	obj.user = this.userName;
	obj.l = this.lang;

	var url = this.ktURL + '?r=' + Math.random() + "&t=stars&k=" + keyword + "&v=" + this.searchId + "&j=" + escape(encodeJSON(obj));
	
	trace("ADD STAR: " + keyword + " - " + stars);
	
  	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: function(transport){
									var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  									s.processKTN = function (json){ 
  													if(json=="Good"){
  														return true;
  													}else{
  														return false;
  														} 
  													};
  									var result = Components.utils.evalInSandbox(transport.responseText,s );

  									callback(result);
  								},
  						onError: onError
  							} );
	
}

/**
 * Un-Deletes/deletes addURL from keyword.
 * @param {} keyword A keyword
 * @param {} addURL The URL to un-delete/delete from the results for keyword
 * @param {} callback onComplete callback
 * @param {} unDelete If true this will un-delete the url
 */
WikiaSearchAPI.prototype.deleteURL = function (keyword, addURL, callback, unDelete, opts, onError){
	//t=del&k=ashish&v=971e9f278619d108f7ffe9f3b437e87d&
	//j={"url": "http://www.slashdot.org", "del": 1, "user": "Adatta", "l": "en"}
	keyword = keyword.toLowerCase();
	
	var obj = new Object();
	obj.url = addURL;
	obj.del = (unDelete) ? 0 : 1;
	obj.user = this.userName;
	obj.l = this.lang;
	
	var url = this.ktURL + '?r=' + Math.random() + "&t=del&k=" + keyword + "&v=" + this.searchId + "&j=" + escape(encodeJSON(obj));
	
	trace("DELETE: " + keyword + " : " + unDelete);
	trace(url);
	
  	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: function(transport){
  									
  									var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  									s.processKTN = function (json){ 
  													if(json=="Good"){
  														return true;
  													}else{
  														return false;
  														} 
  													};
  									var result = Components.utils.evalInSandbox(transport.responseText,s );

  									callback(result, opts);
  								},
  					  onError: onError 
  							} );
}

/**
 * Adds a url to the search results for keyword
 * @param {} keyword The keyword to add to
 * @param {} addURL The URL to add
 * @param {} callback An onComplete callback
 */
WikiaSearchAPI.prototype.addURL = function (keyword, addURL, callback, opts, onError){
	keyword = keyword.toLowerCase();
	
	var obj = new Object();
	obj.url = addURL;
	obj.user = this.userName;
	obj.l = this.lang;

	var url = this.ktURL + '?r=' + Math.random() + "&t=add&k=" + keyword + "&v=" + this.searchId + "&j=" + escape(encodeJSON(obj));
	
	trace("ADD: " + keyword + " : " + addURL);
	
  	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: 
  					   function(transport){
  									var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  									s.processKTN = function (json){ 
  													if(json=="Good"){
  														return true;
  													}else{
  														return false;
  														} 
  													};
  													
  									var result = Components.utils.evalInSandbox(transport.responseText,s );
  									
  									callback(result, opts);
  								},
  					   onError: onError 
  							} );
}

/**
 * Edits the title/summary of a search result.
 * @param {} keyword The keyword to edit on
 * @param {} addURL A URL to edit - must be a search result of keyword
 * @param {} title The new title
 * @param {} summary The new summary
 * @param {} callback onComplete callback
 */
WikiaSearchAPI.prototype.saveEdit = function (keyword, addURL, title, 
												summary, callback, opts, onError){
	// t=edit&k=ashish&v=971e9f278619d108f7ffe9f3b437e87d&
	// j={"url": "http://www.google.com", "title": "ashish", "summary": "wamp wamp", "user": "Adatta", "l": "en"}
	keyword = keyword.toLowerCase();
	
	var obj = new Object();
	obj.url = addURL;
	obj.title = title;
	if(summary)
		obj.summary = summary;
	else
		obj.summary = "";
	obj.user = this.userName;
	obj.l = this.lang;

	var url = this.ktURL + '?r=' + Math.random() + "&t=edit&k=" + keyword + "&v=" + this.searchId + "&j=" + escape(encodeJSON(obj));

	trace("EDIT: " + addURL);
	trace("EDIT: " + keyword + " : " + encodeJSON(obj));
	
	ajaxRequest(url, { method: 'GET', 
  					   onSuccess: function(transport){
									var s = new Components.utils.Sandbox('http://kt.search.isc.org/');
  									s.processKTN = function (json){ 
  													if(json=="Good"){
  														return true;
  													}else{
  														return false;
  														} 
  													};
  									var result = Components.utils.evalInSandbox(transport.responseText,s );

  									callback(result, opts);
  								},
  						onError: onError
					} );
	
}