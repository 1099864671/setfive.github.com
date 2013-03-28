<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
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
    <title></title>
    
    <style>
    	.month-name {
    		fill: #fff;
    		font-size: 14px;
    		font-weight: bold;
    		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    	}
    </style>
    
  </head>

  <body>

    <a target="_blank" href="https://github.com/Setfive/setfive.github.com/tree/master/d3_calendar"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png" alt="Fork me on GitHub"></a>
  
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
    
    <div class="container padded-bottom">
        <div class="row">
            <div class="span12">
    			<div class="alert alert-info centered follow-us-bottom">
				Dig the visualization? We'd love if you followed us  
				<iframe scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/follow_button.1362636220.html#_=1362664812230&amp;id=twitter-widget-0&amp;lang=en&amp;screen_name=setfive&amp;show_count=false&amp;show_screen_name=true&amp;size=m" class="twitter-follow-button twitter-follow-button" style="width: 109px; height: 20px;" title="Twitter Follow Button" data-twttr-rendered="true"></iframe>		 
				</div>
			</div>
		</div>
	</div>
    
    <script>

    var svg;
    var innerSvg;
        
    $(document).ready(function(){

    	var fills = ["#1c3941", "#153a88", "#1b6cae", "#34b8ab", "#029f38", "#6fb91a",
    	             "#aac51e", "#f1be03", "#e47a0a", "#ce3305", "#5f2813", "#1c3d44"];
		var months = ["January", "February", "March", "April", "May", "June", 
		      		  "July", "August", "September", "October", "November", "December"];
    	var data = [];
    	
    	for(var i = 0; i < 12; i ++){
			data.push( {i: i, fill: fills[i], label: months[i]} );
    	}
    	
        svg = d3.select("#svg").append("svg")
				.attr("class", "chart")
				.attr("width", 930)
				.attr("height", 900)
				.append("g");

        var pie = d3.layout.pie()
        			.value(function(d, i){ 
            			return 20; 
            	    });
        
		var arc = d3.svg.arc()
					.innerRadius(390)
					.outerRadius(440);

		svg.selectAll("path")
		     .data(pie(data))
		     .enter()
		   .append("path")
		     .attr("id", function(d){return "path" + d["data"].i;})
		     .attr("d", arc)
		     .attr("transform", "translate(440, 440)")
		     .attr("stroke", "white")
		     .attr("stroke-width", 4)
		     .attr("fill", function(d){return d["data"].fill});

	     svg.selectAll("textPath")
	     	  .data(pie(data))
	     	  .enter()
	     	.append("text")
	     	  .attr("dy", 30)
	     	  .attr("dx", 90)
	     	.append("textPath")
	     	  .attr("xlink:href", function(d){ return "#path" + d["data"].i;})
	     	  .attr("class", "month-name")
	     	  .text(function(d){ return d["data"].label;});

	     	    
		var innerArcs = [ ];
		var inner = 360;
		var outter = 390;
		
		for(var i = 0; i < 5; i++){
			innerArcs.push( d3.svg.arc().innerRadius(inner).outerRadius(outter) );

			svg.selectAll(".inner-path-" + i)
		     .data(pie(data))
		     .enter()
		   .append("path")
		     .attr("id", function(d){return "inner-path-" + d["data"].i + "-" + i;})
		     .attr("d", innerArcs[i])
		     .attr("transform", "translate(440, 440)")
		     .attr("stroke", "white")
		     .attr("stroke-width", 1)
		     .attr("fill", function(d){return d["data"].fill})
		     .attr("class", "inner-path-" + i);
			
			inner -= 30;
			outter -= 30;
		}

		var text = svg.selectAll(".dates")
					.data([0])
					.enter()
				  .append("text")
				    .attr("dy", 10)
				    .attr("dx", 10)
				  .append("textPath")
				    .attr("class", "month-name")
				    .attr("xlink:href", "#inner-path-0-0")
				    .text("1  2  3  4  5  6  7");
	        
    });

    function getMonthDays(year, month) {
                        
    	var monthStart = new Date(year, month, 1);
    	var monthEnd = new Date(year, month + 1, 1);
    	var res = { starts: monthStart.getDay(), days: [] };

    	while( monthStart.getMonth() != monthEnd.getMonth() ){        	
        	res["days"].push( monthStart.getDate() );
        	monthStart.setDate( monthStart.getDate() + 1 );
    	}
    	
    	return res;
    }
    
    </script>
    
   </body>
</html>