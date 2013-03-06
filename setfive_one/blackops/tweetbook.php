<?php
  include('../functions.php');
  $TITLE = "Tweet A Book";
  $TITLE .= checkSubPageTitle();
  include_once("../header.php");
?>

<script type="text/javascript" src="jquery.json-1.3.min.js">

</script>

<style>
  .sidebar {
    width: 180px;
  }
</style>

<div><h1>tweet a book</h1></div>
   
  <div class="sidebar">
    
    <h3>About</h3>
    <p>
      Tweet a book runs blocks of text through twitter to "build" some text from various tweets.
      Think of it like writing a note with parts of a magazine.
    </p>

    <h3>Status</h3>
    <p id="status" style="display: none">
      Loading <img src="../images/loading.gif" />
    </p>
  </div>

    <div class="maincontent" id="content">
      
      <div id="enter-word">
        <h3>Enter some text (100 word max):</h3>
        <form action="" method="GET" onsubmit="tweetBook(); return false;">
          <textarea rows="20" cols="50" id="search-text"></textarea> <br />
          <input type="submit" value="Search" />
        </form>
      </div>
</div>

<script type="text/javascript">
  
  var wordArray = new Array();
  
  function tweetBook(){
    wordArray = $("#search-text").val().split(" ");
    var tempArray = new Array();
    var range;
    
    $("#status").show();
    
    for(var i=0; i< wordArray.length; i++){
    
      tempArray.push( wordArray[i] );
      if(i > 0 && i % 10 == 0){
        $.post("tweetbooksearch.php", {words: $.toJSON(tempArray)}, 
             function(data){ 
                handleData(data);
              }, "json");
        tempArray = new Array();
      }
    }
    
    $.post("tweetbooksearch.php", {words: $.toJSON(tempArray)}, 
             function(data){ 
                handleData(data, range);
              }, "json");
    
  }
  
  function handleData(data, range){
    
  }
  
</script>

<?php include('../footer.php');?>