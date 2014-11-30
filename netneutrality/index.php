<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Net Neutrality: Back to the future</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-metro.min.css" rel="stylesheet">
    
    <link href="loadingbar.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    
    <?php if( 0 ): ?>   
      <link rel="stylesheet/less" type="text/css" href="styles.less" />
    <?php endif; ?>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="less.min.js"></script>
    <script src="jquery.ba-throttle-debounce.min.js "></script>      
      
  </head>
  <body class="net-neutrality-body">
    
    <div id='loadingbar'><dt/><dd/></div>
    
    <div class="jumbotron slide-container vertical-center">
      <div class="container">
         <div class="col-md-12 text-center">
           <h1>Net Neutrality: Back to the future</h1>
           <p>Explore the bills, court cases, and disagreements that led up to today.</p>
           <p>You can navigate with &#8593; and &#8595;</p>
         </div>    
      </div>
      
      <div class="slide-indicator-container">
        <a href="#up" data-provide="indicator-navigate" class="down-btn btn btn-xs btn-default"><span class="glyphicon glyphicon-chevron-up"></span></a>
        
        <?php if(0): ?>
          <ol class="carousel-indicators">
            <li data-provide="slide-navigate" data-index="0" class="active"></li>
            <?php foreach( range(1, 54) as $i ): ?>
              <li data-provide="slide-navigate" data-index="<?php echo $i; ?>"></li>
            <?php endforeach; ?>          
          </ol>
        <?php endif; ?>
        
        <a href="#down" data-provide="indicator-navigate" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-chevron-down"></span></a>
      </div>
      
    </div>    
    
    <?php include_once("content.html") ?>                  

  <script>
    
    var slideIndex = 0;
        
    function navigateToSlideIndex(){
      var slideHeight = parseInt($(".slide-container:first").css("height").replace("px", ""));
      var newHeight = (slideIndex * slideHeight);          
      
      $("html, body").animate({ scrollTop: newHeight }, function(){
        resetIndicator();
      });      
      
      syncIndicator();
    }
    
    function resetIndicator(){
      $(".carousel-indicators li").removeClass("active");
      $(".carousel-indicators [data-index='" + slideIndex + "']").addClass("active");      
    }
    
    function syncIndicator(){
      var percentDone = Math.ceil( (slideIndex / MAX_SLIDES) * 100 );
      $("#loadingbar").css( {width: percentDone + "%"} );    
    }
    
    function navigate(dir){
        if( dir == "up" && slideIndex > 0 ){
          slideIndex -= 1;
        }                
         
        if( dir == "down" && slideIndex < (MAX_SLIDES - 1) ){
          slideIndex += 1;
        }        
        
        navigateToSlideIndex();    
    }
    
    $(document).ready(function(){            
      
      $(window).scrollTop(0);      
      
      $("[data-provide='indicator-navigate']").click(function(){
        var dir = $(this).attr("href").replace("#", "");
        navigate(dir);
        return false;      
      });      
      
      $("body").keydown(function(e){                              
        if( e.keyCode == 38 ){
          navigate("up");
        }                
         
        if( e.keyCode == 40 ){
          navigate("down");
        }        
      });      
            
      $(window).scroll(function(){                   
          var slideHeight = parseInt($(".slide-container:first").css("height").replace("px", ""));          
          var scrollIndex = Math.round( $(window).scrollTop() / slideHeight );          
          
          if( scrollIndex != slideIndex ){
            slideIndex = scrollIndex;
            syncIndicator();
          }                   
      });            
      
      /*      
      $("[data-provide='slide-navigate']").click(function(){        
        slideIndex = parseInt($(this).data("index"));
        navigateToSlideIndex();                
        return false;
      });
      */
                  
    });
  </script>

  </body>
</html>