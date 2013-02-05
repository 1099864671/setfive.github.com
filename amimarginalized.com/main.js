import("storage");
import("lib-json");

/* appjet:server */

var BOSS_KEY = "YOUR KEY";
var postType = parseInt(request.params.isPost);

switch(postType){
    case 0:
        doMain();
        break;
    case 1:
        doAjax();
        break;
    case 2:
        getAutocomplete();
        break;
    default:
        doMain();
        break;
}

function getAutocomplete(){
    page.setMode("plain");
    
    var param = new String(request.params.jobField).toLowerCase();
    var ap = storage.jobList;
    var str = new String();
    var matches = new Array();
    
    ap.forEach(function(e){
        var title = new String(e.title).substring(0, param.length).toLowerCase();
        if(title == param &&
           matches.indexOf(e.title) == -1){
            matches.push( e.title );
        }
    });
    
    matches.sort(
            function(a, b){           
                if( a.length == b.length )
                    return 0;
                return (a.length < b.length) ? -1 : 1;
    });
    
    matches = matches.slice(0, 7);
    
    for(var i=0; i < matches.length; i++){
        str += html(LI(matches[i]));
    }
    
    print(UL(html(str)));
}

function doAjax(){
    // turn off the page formatting
    page.setMode("plain");
   
    var searchFor = request.params.jobField;
    var url = "http://boss.yahooapis.com/ysearch/web/v1/" + escape(searchFor) + "?appid=" + BOSS_KEY;
    var response = wget(url);
    var objData = JSON.parse(response);
    var totalHits = objData["ysearchresponse"]["deephits"];
    
    var more = 0;
    var closeJob;
    var closeNum = -1;
    var ap = storage.jobList;
    var sum = 0;
    var mean = 0;
    
    ap.forEach(function(e){
        
        if(e.score > totalHits){
            more += 1;
        }
                
        if(new String(e.title).toLowerCase() != new String(searchFor).toLowerCase()
            && (closeNum < 0 || Math.abs(e.score-totalHits) < closeNum)){
                closeNum = Math.abs(e.score - totalHits);
                closeJob = e;
        }
        
        sum += e.score;
        
    });
    
    mean = sum / ap.size();
    sum = 0;
    
    ap.forEach(function(e){
        sum += Math.pow((e.score - mean), 2);
    });
    
    sum = sum * (1/ap.size());
    sum = Math.sqrt(sum);
    
    var stdscore = Math.round( ((totalHits - mean) / sum) * 100 );
    var arr = new Object();
    
    arr["stdScore"] = stdscore;
    arr["closeJob"] = closeJob.title;
    arr["more"] = Math.round((more / ap.size()) * 100);
    
    print(JSON.stringify(arr));
}

// for the default main page
function doMain(){
    
    var tryStr = "";
    var ap = storage.jobList;
    var sel = new Array();
    var rand = 1 + (Math.random() * 5);
    
    ap.sort().skip(Math.random() * ap.size()).forEach(
                                                        function(e){
                                                            if(sel.length < 3)
                                                                sel.push(e.title);
                                                            else
                                                                return false;
                                                        } );
    
    tryStr = "Try: ";
    for(var i=0; i < sel.length; i++){
        tryStr += html(link("javascript:loadLink('" + sel[i] + "')", sel[i])) + "&nbsp&nbsp&nbsp";
    }
    
    page.setTitle("Am I marginalized?");
    
    print(SCRIPT({src: "http://www.google.com/jsapi", type:"text/javascript", onload: "loadJS()"}));
    
    page.head.write(
        SCRIPT({src: "http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js", type:"text/javascript"}) + 
        SCRIPT({src: "http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/scriptaculous.js", type:"text/javascript"})
    );
    
    print(html("<div id='centerBox'>"));
    
    print( H1({id: "page-title"}, "Am I marginalized?"), 
                P({id: "blurb"}, "Does your job feel meaningless? Praying for a layoff? \n Find out if your profession has been marginalized.")
    );
    
    print( FORM({action:"/", method:"get", onsubmit: "javascript: return submitJob()"},
                    SPAN({id: "job-label"}, "Job Title: "), 
                    INPUT({text:"text", name:"jobField", id: "jobField"}),
                    INPUT({type:"hidden", name:"isPost", id: "isPost", value: "1"}),
                    INPUT({type: "submit", value: "Submit"})
               )
    );
    
    print(DIV({id:"autocomplete_choices", 'class':"autocomplete"}));
    
    print(DIV({id:"space"}, P()));
    
    print(DIV({id:"ajax-result"}, html(tryStr)));
    
    print(DIV({id:"space"}, P()));
    
    print(DIV({id:"ajax-image"}));
    
    print(DIV({id:"space"}, P()));
    
    print(DIV({id:"bottom-div"}, link("http://shout.setfive.com", "setfive.com")));
    
    print(html("</div>"));
}

/* appjet:client */

Event.observe(window, "load",
              function(){
                
                var hash = new String(window.location.hash).replace("#", "");
                
                if(hash.length > 0){
                    $('jobField').value = hash;
                    submitJob();
                }
                    
                new Ajax.Autocompleter("jobField", "autocomplete_choices", "?isPost=2");
              }
);

function loadJS(){
    google.load("visualization", "1", {packages:["piechart"]});
}


function loadLink(str){
    $('jobField').value = str;
    submitJob();
}

function submitJob(){
    
    $('autocomplete_choices').hide();
    $('ajax-result').innerHTML = "Loading...";
    $('ajax-image').innerHTML = "";
    
    window.location.hash = $F('jobField');
    
    new Ajax.Request("?isPost=1&jobField=" + escape($F('jobField')), {
      method: 'get',
      onSuccess: function(transport) {
        var parts = new String(transport.responseText).evalJSON();
        
        $('ajax-result').innerHTML = parts.more + "% of careers are <span style='color: white'>more</span> important than yours! <br/>" + 
                                     "Society considers <span id='job-name'>" + parts.closeJob.toLowerCase() + "s</span> about as important as you.";
              
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Breakdown');
        data.addColumn('number', '% Marginalization');
        data.addRows(2);
        data.setValue(0, 0, 'Your Job');
        data.setValue(0, 1, (100-parts.more));
        data.setValue(1, 0, 'Everyone Else');
        data.setValue(1, 1, parts.more);
        
        var chart = new google.visualization.PieChart(document.getElementById('ajax-image'));
        chart.draw(data, {width: 400, height: 240, is3D: true, title: 'How important you are'});
        
      }
    });

    return false;
}

var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
try {
var pageTracker = _gat._getTracker("YOUR GA ID");
pageTracker._trackPageview();
} catch(err) {}


/* appjet:css */

#job-name{
    color: #ffffff;
}

#ajax-result{
    color: #ff4411;
    font-size: 18px;
}

#ajax-result a {
    color: #ff4411;
}

#page-title{
    color: #ffffff;
    font-family: Arial;
    font-size: 42px;
}

#blurb{
    font-family: Arial;
    font-size: 14px;
    color: white;
}

#centerBox{
    margin-top: 100px;
    text-align: center;
}

#job-label{
    font-size: 24px;
    color: #0066bb;
}

#bottom-div {
    font-size: 12px;
}

body{
    background-color: #000000;
    color: white;
}

div.autocomplete {
  position:absolute;
  width:350px;
  background-color:white;
  border:1px solid #888;
  margin:0;
  padding:0;
  color:black;
}
div.autocomplete ul {
  list-style-type:none;
  margin:0;
  padding:0;
}
div.autocomplete ul li.selected { background-color: #ffb;}

div.autocomplete ul li {
  font-size: 12px;
  list-style-type:none;
  display:block;
  margin:0;
  padding:2px;
  height:32px;
  cursor:pointer;
}