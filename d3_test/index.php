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
	var cubeSize = 40 * 3;
    
    $(document).ready(function(){
        
        svg = d3.select("#svg").append("svg")
        		.attr("class", "chart")
        		.attr("width", 930)
        		.attr("height", 600)
        	  .append("g");   

        
        $.getJSON("http://symf.setfive.com/d3_test/data.php", function(data){

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
						               return (cubeSize * userIndex) + (index * cellBuffer);
						            })
					               .attr('y', function(val, index){
						               return (step * cellBuffer);
					               })
					               .attr('width', cellSize)
					               .attr('height', cellSize)
					               .attr('fill', function(percent, index){
						               var color = ( step == 0 ) ? "#E01B6A" : "#1F61CC";
						               
						               return jqBaseColor.transition( color, percent ).toHexString(); 
						            })
					               .attr("data", function(e){ return e;});
				});
								      
            });

        	/*
		    innerSvg.each(function(el, i) {
				var colors = d3.select(this)
				              .selectAll('rect')
				               .data(el)
				             .enter().append('rect')
				               .attr('x', function(hex, index){ return (cellSize * index) + (cellSize * 1.03);})
				               .attr('y', (cellSize * i) + (cellSize * 1.03))
				               .attr('width', cellSize)
				               .attr('height',cellSize)
				               .attr('fill', function(hex, index){ return "#eee"; });

			});
			*/
			
        });
        
        var data = [
           ["#EEE", "#E01B6A", "#6A95E6"],
           ["#E01B6A", "#15FF00", "#DE9228"],
           ["#7A8C8B", "#00C8FF", "#FFEA00"]
        ];

    	
        
    });
    </script>
    
   </body>
</html>