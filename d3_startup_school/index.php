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

    <div class="container padded-bottom">
        <div class="row">
            <div class="span12 centered">
                <h2>Welcoming the Startup Institute: Boston class of Spring '13</h2>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <div class="alert alert-info centered">
                    We're excited to meet everyone but in the meantime we thought it would be fun to put together a visualization of your class. 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <div class="stats-line">
                    <em class="highlight">Eight</em> members of the class have a first name that starts with "J" and <em class="highlight">two</em> have a first name that starts with "Z". 
                    <em class="highlight">Two</em> last names that start with "Z" and <em class="highlight">one</em> with "Q". On resumes, @gmail.com appeared <em class="highlight">thirty six</em> times, by far the most popular domain.
                    <em class="highlight">Thirteen</em> of you are in the dev track, <em class="highlight">seventeen</em> in marketing, <em class="highlight">ten</em> in product, and <em class="highlight">thirteen</em> in sales.                         
                </div>
            </div>
        </div>
    </div>
    
    <div class="container padded-bottom">
        <div class="row">
            <div class="span12">
                <div class="page-header">
                    <h3>Visualizing the class</h3>                   
                </div>  
                <p>Each 3x3 grid below represents a member of the class. The top two rows represent the student's first and last names.  
                In the <strong>first row</strong>, the left column represents the first letter of the first name, 
                the middle column represents the length of the first name, and the right column is a HSL mapping for a <a href="http://www.php.net/manual/en/function.metaphone.php" target="_blank">metaphone</a> of the first name.
                The <strong>second row</strong> is identical to the first except mapping the last name.
                In the <strong>last row</strong>, the first column represents the track the student is in
                and then the middle left and middle right squares are a HSL representation of the <a href="http://www.php.net/manual/en/function.metaphone.php" target="_blank">metaphone</a> 
                of the student's email user name and domain name (john.doe, gmail.com).
                                
                <div class="pull-left">
                    <ul class="listless inline">
                        <li><strong>Filter: </strong></li>
                        <li><a href="#" class="sales filter-on" data-provide="filter" data-val="sales">Sales</a></li>
                        <li><a href="#" class="prod filter-on" data-provide="filter" data-val="prod">Product</a></li>
                        <li><a href="#" class="marketing filter-on" data-provide="filter" data-val="mrkt">Marketing</a></li>
                        <li><a href="#" class="dev filter-on" data-provide="filter" data-val="dev">Development</a></li>
                    </ul>                                
                    <ul class="listless inline">
                        <li><strong>Sort By:</strong></li>
                        <li><a class="selected" href="#" data-provide="sort" data-sort="fname_text">First Name</a></li>
                        <li><a href="#" data-provide="sort" data-sort="lname_text">Last Name</a></li>
                        <li><a href="#" data-provide="sort" data-sort="track">Track</a></li>
                    </ul>
                </div>
                
                <div class="pull-right">
                	<table class="table table-bordered table-striped table-condensed table-key">
                		<tbody>
                			<tr><td>A</td><td>M</td><td>Z</td></tr>
                			<tr>
                				<td colspan="3">
                					<div class="letter-gradient">&nbsp;</div>
                				</td>
                			</tr>
                		</tbody>
                	</table>
                </div>
                
            </div>
        </div>
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
        
        getData("fname");

        $("[data-provide='sort']").click(function(){
            $("[data-provide='sort']").removeClass("selected");
            $(this).addClass("selected");
                        
            getData( $(this).data("sort") );
            return false;
        });

        $("[data-provide='filter']").click(function(){
			if( $(this).hasClass("filter-on") ){
				$(this).removeClass("filter-on");
			}else{
				$(this).addClass("filter-on");
			}

			getData( $("[data-provide='sort']").filter(".selected").data("sort") );
        	return false;
        });
        
    });

    function getData(sortBy){

    	$("#svg *").remove();
    	
        svg = d3.select("#svg").append("svg")
		.attr("class", "chart")
		.attr("width", 930)
		.attr("height", 900)
	  .append("g");   

        var filters = [ ];
        $("[data-provide='filter']").filter(".filter-on").each(function(){
			filters.push( $(this).data("val") );
        });
    	
        $.getJSON("data.php", {sortBy: sortBy, filters: filters}, function(data){

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

            
            svg.selectAll('g').data(data).exit().remove();
            
            rowSvg = svg.selectAll('g').data(data).enter().append('g').attr("class", "svg-row");

            
            rowSvg.each(function(el, userIndex){
                
				var rowInnerSVG = d3.select(this)
								    .selectAll("g")
								      .data(el)
								      .enter()
								    .append("g");
				
				rowInnerSVG.each(function(ex, step){
					
					var rects = d3.select(this)
								    .selectAll("rect")
								    .data(ex);

					function setSquareCords( square ){
						
					    square.attr('x', function(val, index){
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
				                 return jQuery.Color( {hue: obj.h, saturation: obj.s, lightness: obj.l, alpha: 1} ).toHslaString();
				              })					    
					}

					rects.transition().duration(750).call(setSquareCords);
				    rects.enter().append("rect").call(setSquareCords);				    
				    rects.exit().remove();
		            
				});
								      
            });
			
        });

    }
    
    </script>
    
   </body>
</html>