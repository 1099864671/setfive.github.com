<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="main.css" rel="stylesheet">
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script src="jquery-1.8.3.min.js"></script>
    <script src="d3.v3.min.js"></script>
    <script src="underscore-min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="jquery.color-2.1.1.min.js"></script>
    
  </head>

  <body>

    <div class="header">
        <div class="pull-left">
            <a href="http://www.setfive.com" target="_blank"><img src="logo_website_no_consulting.png" /></a>
        </div>
        <div class="pull-right">
            <div class="header-menubar">
                <a href="mailto:contact@setfive.com">contact@setfive.com</a>
                |
                <a href="http://www.twitter.com/setfive.com">@setfive</a>
                |
                <a href="tel:6178630440">617.863.0440</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="span12">
                <div id="svg"></div>
            </div>
        </div>
    </div>
    
    <script>

    var svg;
    var innerSvg;
    var jqBaseColor = jQuery.Color( "#eee" );
    
    var cellSize = 30;
	var cellBuffer = 32;
	var cubeSize = (cellBuffer * 1.15) * 3;
    var cubesPerRow = 8;
    
    $(document).ready(function(){
        
        svg = d3.select("#svg").append("svg")
        		.attr("class", "chart")
        		.attr("width", 930)
        		.attr("height", 900)
        	  .append("g");   

        
        $.getJSON("data.php", function(data){

            /*
                [
                     [
                          {"h":0,"s":0.44311377245509,"l":0.38235294117647},
                          {"h":170.74390243902,"s":0.65560165975104,"l":0.65271966527197}
                     ],
                     [
                          {"h":168.68674698795,"s":0.94610778443114,"l":0.83529411764706},
                          {"h":170.74390243902,"s":0.67219917012448,"l":0.61924686192469}
                     ],
                     [{"h":0,"s":0.9940119760479,"l":0.84117647058824},{"h":0,"s":0.31535269709544,"l":0.30125523012552}]
                     
                ]
            
            */
                     
        	/*
        	 [
         	 	[ userIndex = 0
          	 		[0.47,0.45,0], step = 0
          	 		[0.61,0.64,0.41] step = 1
          	 		[index=0, index=1, index=2]
          	 	],
          	 	[ userIndex = 1
           	 		[0.48,0.48,0],
           	 		[0.57,0.67,0.39]
           	 	],
           	 	[ userIndex = 2
            	 	[0.72,0.71,1],
            	 	[0.58,0.67,0.21]
            	]
             ]
        	*/
            rowSvg = svg.selectAll('g')
		    		      .data(data)
		   			   .enter().append('g');

            rowSvg.each(function(el, userIndex){
                
				var rowInnerSVG = d3.select(this)
								    .selectAll("g")
								    .data(el)
								    .enter()
								    .append("g");
				
				rowInnerSVG.each(function(ex, step){
					
					var rects = d3.select(this)
								    .selectAll("rect")
								    .data(ex)
								  .enter().append("rect")
					               .attr('x', function(val, index){
						               var rowMultiplier = userIndex % cubesPerRow;             
						               return (cubeSize * rowMultiplier) + (index * cellBuffer);
						            })
					               .attr('y', function(val, index){
					                   var rowOffset = userIndex >= cubesPerRow ? Math.floor(userIndex / cubesPerRow) * cubeSize : 0;						               
						               return rowOffset + (step * cellBuffer);
					               })
					               .attr('width', cellSize)
					               .attr('height', cellSize)
					               .attr('fill', function(obj, index){
						               return jQuery.Color( {hue: obj.h, saturation: obj.s, lightness: obj.l, alpha: 1} ).toRgbaString();
						            })
					               .attr("data", function(e){ return e;});
				});
								      
            });
			
        });        

    	
        
    });
    </script>
    
   </body>
</html>