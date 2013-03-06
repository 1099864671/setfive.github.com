<?php
  include('functions.php');
  $TITLE = "Word Evolver";
  $TITLE.=checkSubPageTitle();
  include_once("header.php");
?>
<style>
  .result{
    font-size: 14px; 
    font-weight: bold;
    padding-top: 20px;
  }
  
  .maincontent {
    height: 100% !important;
  }
  
  .ul-list {
    padding-left: 15px;
  }
  
  #results{
    float: left;
    padding-left: 30px;
  }
  
  #result-graph {
    float: left;
    height: 300px;
    width: 350px;
  }
  
  #enter-word {
    float: left;
  }
  
  #gen-list, #gen-current {
    font-size: 14px;
    font-weight: bold;
    display: inline;
  }
  
  #gen-progress {
    font-size: 14px;
    font-weight: bold;  
  }
  
</style>
  <div><h1>word evolver</h1></div>
   
  <div class="sidebar">
    
    <p>
      This application uses the <a target="new" 
        href="http://jenes.ciselab.org/">Jenes</a> library to "evolve" a word or phrase. 
      The system starts from a population of random strings and then uses evolutionary 
      processes to evolve towards the goal string. The system starts with a population of 300 random strings and attempts a maximum of 
      50,000 generations to evolve a string before timing out.
    </p>
    
    <p>
      A more thorough explanation is available 
        <a target="_new" href="http://shout.setfive.com/2009/04/30/monkeys-and-shakespeare-genetic-algorithms-with-jenes/">here</a>.
    </p>
    
    <p>
      <strong style="font-size: 14px">Recent Searches</strong>
      <ul id="recent-searches" class="ul-list">
        <?php include_once("evolveproxy.php"); ?>
      </ul>
    </p>
  </div>

    <div class="maincontent" id="content">
      
      <div id="enter-word">
	      <h3>Enter a word or phrase:</h3>
	      <form action="" method="GET" onsubmit="evolveWord(); return false;">
	        <input type="text" id="word" name="word" maxlength="128" />
	        <input type="button" onclick="evolveWord()" value="Evolve" />
	        <img id="loading" src="images/loading.gif" style="display:none" />
	      </form>
      </div>
      
      <div style="clear: both"></div>
      
      <div id="result-graph">
        <h3>Generations vs. % Correct</h3>
        <img id="graph" />
      </div>
      
      <div id="results">  
	      <div id="finalword" class="result"></div>
	      <div id="runtime" class="result"></div>
	      <div id="generations" class="result"></div>
	      <div id="playback" class="result" style="display: none">
	       Results loaded. <a href="" onclick="switchText(); return false;">
	                       Click to playback evolution.</a>
	      </div>
      </div>
      
      <div style="clear: both"></div>
      
      <div id="gen-current">
        Current Best Candidate: <div id="gen-list"></div>
      </div>
      
      <div id="gen-progress" style="display: none">
        Progress: <img src="images/whiteprogress.png" style="height: 10px; width: 0px" id="progress-bar" />
      </div>
      
    </div>

<iframe src="" id="ajax" name="ajax" style="display: none"></iframe>

<?php include('footer.php');?>

<script type="text/javascript">
  var target;
  var intermediateArray = new Array();
  var index = 0;
  var timerId = -1;
  var totalGenerations;
  
  $(document).ready( function(){
    var hashVal = window.location.hash.replace("#", "");
    if(hashVal.length){
      setQuery(hashVal);
    }
  });
  
  function setQuery(q){
    $("#word").val(q);
    evolveWord();
  }
  
  function switchText(){
  
    if(timerId == -1){
      timerId = window.setInterval(function(){ switchText(); }, 100);
      $("#gen-progress").show();
    }
  
    if(index >= intermediateArray.length){
      window.clearInterval(timerId);
      index = 0;
      timerId = -1;
      return false;
    }
    
    $("#gen-list").html( intermediateArray[index].html );
    $("#progress-bar").css("width", Math.ceil(((intermediateArray[index].index/totalGenerations) * 300)) + "px" ); 
    
    index += 1;
    return false;
  }
  
  function evolveWord(){
    var targetUrl = "evolveproxy.php";
    target = $("#word").val();
    
    if( target.length > 128 ){
      alert("Sorry! We're imposing a 128 charecter limit.");
      return false;
    }
    
    $("#gen-list").html( "" );
    $("#progress-bar").css("width", "0px" );
    
    $("#playback").hide();
    $("#loading").show();
    window.location.hash = target;
    $("#recent-searches").hide();
    
    $.getJSON(targetUrl, {q: target}, 
              function(data){
                var percentRight = new Array();
                
                $("#loading").hide();
                $("#finalword").html("Final iteration: " + data.finalWord);
                $("#runtime").html("Running Time: " + data.runningTime + "ms");
                $("#generations").html("# of Generations: " + data.numberOfGenerations);
                totalGenerations = data.numberOfGenerations;
                
                if( data.finalWord != target ){
                  alert("Sorry! Your evolution timed out before it completed.");
                }
                
                $("#intermediates").html("");
                var listHTML = new Array();
                $.each(data.intermediates, function(i, n){
                  
                  var numRight = 0;
                  for(var j=0; j < n.length; j++){
                    numRight += ( target[j] === n[j] ) ? 1 : 0;
                  }
                  
                  percentRight.push( {index: i, val: Math.ceil((numRight/target.length) * 100)} );
                  listHTML.push( {index: parseInt(i), html: n} );
                });
                
                function sortList(a, b){
                    if( a.index < b.index )
                      return -1;
                    else if( a.index > b.index )
                      return 1;
                    
                    return 0;
                }
                
                var ix = 0;
                var str = new String( data.finalWord );
                for(var j=0; j < str.length; j++){
                  ix += ( target[j] == str[j] ) ? 1 : 0;
                }
                percentRight.push( {index: data.numberOfGenerations, 
                                      val: Math.ceil( (ix / str.length) * 100)} );
                listHTML.push( {index: parseInt(data.numberOfGenerations), html: data.finalWord} );
                
                percentRight = percentRight.sort( sortList );
                listHTML = listHTML.sort( sortList );
                intermediateArray = listHTML;
                
                var graphString = "";
                for(var i=0; i < percentRight.length; i++){
                  graphString += percentRight[i].val + ( i == percentRight.length-1 ? "" : "," );
                }
                
                $("#graph").attr("src", "http://chart.apis.google.com/chart?chs=300x225&cht=ls&chco=FFFFFF&chd=t:" + graphString + "&chf=bg,s,10181F");
                $("#result-graph").show();
                $("#recent-searches").load( targetUrl, {}, function(){ $("#recent-searches").slideDown("slow"); } );
                $("#playback").show();
              });
    
    return false;
  }
  
</script>
