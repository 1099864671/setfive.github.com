<?php 
$data = json_decode( file_get_contents("capital-appreciation-bonds.json"), true );
$uniqueArrays = array_fill_keys( array("County", "District"), array() ); 
foreach( $data as $d ){
    foreach( array_keys($uniqueArrays) as $key ){
        $uniqueArrays[$key][] = $d[$key];
    }
}

foreach($uniqueArrays as $key => $vals){
    $uniqueArrays[$key] = array_unique($uniqueArrays[$key]);
    sort($uniqueArrays[$key]);
}
?>

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
    <script src="d3.v2.min.js"></script>
    <script src="underscore-min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
  </head>

  <body>

    <div class="header">
        <div class="pull-left">
            <a href="http://www.setfive.com" target="_blank"><img src="http://www.setfive.com/logo.png" /></a>
        </div>
        <div class="pull-right">
            <a href="mailto:contact@setfive.com">contact@setfive.com</a>
            |
            <a href="http://www.twitter.com/setfive.com">@setfive</a>
            |
            <a href="tel:6178630440">617.863.0440</a>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="container">
        
        <div class="row">
            <div class="span12">
            <h3>About</h3>
            <p>The data visualized here is from the <a href="http://spreadsheets.latimes.com/capital-appreciation-bonds/" target="_blank">
                LA Times "Capital appreciation bonds"</a> feature. They summarized it as:</p>
            <blockquote>Hun­dreds of Cali­for­nia school and com­munity
                col­lege dis­tricts have fin­anced con­struc­tion
                pro­jects with cap­it­al ap­pre­ci­ation bonds that push
                re­pay­ment far in­to the fu­ture and ul­ti­mately cost
                many times what the dis­trict bor­rowed. Gov­ern­ment
                fin­ance ex­perts con­sider bonds im­prudent if the
                total cost is more than four times the money bor­rowed
                or the ma­tur­ity peri­od is great­er than 25 years.</blockquote>
          </div>
        </div>
    
        <div class="row filters">
            <form id="filterForm">
            <div class="span12">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>County:</th>
                            <td>
                                <select name="County" multiple="multiple">
                                    <?php foreach($uniqueArrays["County"] as $v): ?>
                                        <option value="<?= $v ?>" selected><?= $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <th>District:</th>
                            <td>
                                <select name="District" multiple="multiple">
                                    <?php foreach($uniqueArrays["District"] as $v): ?>
                                        <option value="<?= $v ?>" selected><?= $v ?></option>
                                    <?php endforeach; ?>
                                </select>                            
                            </td>
                        </tr>
                        <tr>
                            <th>Aggregate By:</th>
                            <td>
                                <select name="aggregate">
                                    <option value="">None</option>
                                    <option value="County">County</option>
                                    <option value="District">District</option>
                                </select>
                            </td>
                            <td colspan="2" class="right">
                                <input type="submit" value="Filter" class="btn" />
                            </td>                        
                        </tr>
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>

    <div id="graphTarget" class="container">
        
    </div>

    <div class="container">
        <div class="row info-table">
            <div class="span12">
                <div id="infoTable"></div>
            </div>
        </div>    
    </div>
    
    <script id="dataTableTemplate" type="text/template">
<table class="table table-striped table-bordered">
<tbody>
<% _.each(data, function(el){ %>
<tr>
    <td><%= el["District"] %></td>
    <td><%= el["County"] %></td>
    <td><%= parseInt(el["Total debt service"]).toLocaleString() %></td>
    <td><%= parseFloat(el["Debt ratio"]).toFixed(2) %></td>
    <td><%= parseInt(el["Maturity length"]) %></td>
    <td><%= parseFloat(el["Debt ratio"]) > 4 ? "<div class='warning-red'></div>" : "" %></td>
</tr>
<% }); %>
</tbody>
<thead>
<tr>
    <th>District</th>
    <th>County</th>
    <th>Total debt service</th>
    <th>Debt ratio</th>
    <th>Maturity length (yrs.)</th>
    <th>Ratio warning</th>
</tr>
</thead>
</table>
    </script>
    
    <script>

    var d3Config = { };
    var jsonData = <?php echo file_get_contents("capital-appreciation-bonds.json"); ?>;
    var template;

    var chartHeight = 600;
    var chartWidth = 930;
    var padding = 30;
    
    var chartHeightInner = (chartHeight - (padding*2));
    var chartWidthInner = (chartWidth - (padding*2));
    
    function drawCircles(){        
        
    	var counties = $("#filterForm [name='County']").val();
    	var districts = $("#filterForm [name='District']").val();
    	    	
        var myData = _.filter(jsonData, function(el){
            return _.indexOf(counties, el["County"]) > -1 && _.indexOf(districts, el["District"]); 	        	
        });                
        
        var groupBy = $("#filterForm [name='aggregate']").val();
        if( groupBy.length ){
        	
        	var groupedData = _.groupBy(myData, function(el){
        	    return el[ groupBy ];
        	});
        	
        	myData = [];
        	_.each(groupedData, function(list, index){
        		        		
        		var total = {"District": "", "County": "", "Sale year": 0, "Total debt service": 0, "Debt ratio": 0, "Maturity length": 0};
        		var aggregateKeys = ["Total debt service", "Debt ratio", "Maturity length", "Sale year"];
        		
        		total[groupBy] = index;
        		
        		_.each(list, function(el){        			
        			_.each(aggregateKeys, function(key){
        				total[key] += isNaN(el[key]) ? 0 : parseFloat(el[key]);	
        			});        			
        		});
        		
        		_.each(aggregateKeys, function(key){
        			total[key] = total[key] / list.length;
        			total[key] = total[key] + "";
        		});
        		
        		myData.push( total );
        	});
        
        }
                        
        setGraphAxis(myData);
        
        var chart = d3.select("g");        
        var circles = chart.selectAll(".circle").data(myData);

        function setCircle(items){
            items.attr("class", function(d){
                return parseFloat(d["Debt ratio"]) < 4 ? "circle" : "circle red";
            })
            .attr("cx", function(d){ 
                return d3Config.x( parseInt(d["Sale year"]) + parseInt(d["Maturity length"]) - 2012 );
            })
            .attr("cy", function(d) {                 
                return d["Debt ratio"].length ? d3Config.y( parseFloat(d["Debt ratio"]) ) : 0; 
            })
            .attr("r", function(d){
                return d["Total debt service"].length ? d3Config.radius( parseInt(d["Total debt service"]) ) : 1;            	
            })
            .attr("title", function(d){
                return d["District"] + " / " + d["Debt ratio"] + " / " + d["Total debt service"];
            });
        }

        circles.transition().duration(750).call(setCircle);
        circles.enter().append("circle").call(setCircle);                
        circles.exit().remove();

        $("#infoTable").html( template({data: myData}) );
    }
    
    function setGraphAxis( data ){

    	var chart = d3.select("g");

        var getYearsLeft = function(v){
            return (parseInt(v["Sale year"]) + parseInt(v["Maturity length"])) - 2012;
        };
        
        var getDebtRatio = function(v){
            return parseFloat(v["Debt ratio"]);
        };
        
        d3Config.x = d3.scale.linear()
                  .domain([d3.min(data, getYearsLeft),  d3.max(data, getYearsLeft)])
                  .nice()
                  .range([padding + "px", chartWidthInner + "px"]);             
        
        d3Config.y = d3.scale.linear()
                  .domain([ d3.max(data, getDebtRatio), 0 ])
                  .nice()
                  .range([padding + "px", chartHeightInner + "px"]);
        
        d3Config.radius = d3.scale.linear()
                  .domain([ d3.max(data, function(v){ return parseInt(v["Total debt service"]) }), 0 ])
                  .nice()
                  .range(["5px", "30px"]);
        
        chart.selectAll(".y-ticks")
            .data(d3Config.y.ticks(10))
            .enter().append("line")
            .attr("x1", padding)
            .attr("x2", chartWidthInner)
            .attr("y1", d3Config.y)
            .attr("y2", d3Config.y)
            .attr("class", "y-ticks")
            .style("stroke", "#ccc");
        
        chart.selectAll(".y-rule")
            .data(d3Config.y.ticks(10).splice(1))
            .enter().append("text")
            .attr("x", padding)
            .attr("y", d3Config.y)
            .attr("dx", -15)
            .attr("text-anchor", "middle")
            .attr("class", "y-rule")
            .text(function(d){return d + "%"});
        
        chart.selectAll(".y-label")
            .data([0])
            .enter().append("text")
            .attr("x", chartWidthInner / 2)
            .attr("y", chartHeight)
            .attr("dy", -15)
            .text("Years to maturity");
        
        chart.selectAll(".x-ticks")
            .data(d3Config.x.ticks(10))
            .enter().append("line")
            .attr("x1", d3Config.x)
            .attr("x2", d3Config.x)
            .attr("y1", padding)
            .attr("y2", chartHeightInner)
            .attr("class", "x-ticks")
            .style("stroke", "#ccc");
        
        chart.selectAll(".x-rule")
             .data(d3Config.x.ticks(10))
             .enter().append("text")
             .attr("x", d3Config.x)
             .attr("y", chartHeightInner)
             .attr("dy", 10)
             .attr("text-anchor", "middle")
             .attr("class", "x-rule")
             .text(String);
        
        chart.selectAll(".x-label")
            .data([0])
            .enter().append("text")
            .attr("x", padding)
            .attr("y", chartHeightInner / 2)
            .attr("dy", -20)
            .attr("transform", "rotate(270, " + padding + ", " + (chartHeightInner / 2) + ")")
            .text("Debt %");
    }
    
    jQuery(document).ready(function(){    	

        template = _.template( $("#dataTableTemplate").html() );        
        jsonData = _.shuffle(jsonData);
        
        var chart = d3.select("#graphTarget").append("svg")
                      .attr("class", "chart")
                      .attr("width", chartWidth)
                      .attr("height", chartHeight)
                      .append("g");   

        setGraphAxis(jsonData);
        drawCircles();
        
        $("#filterForm").submit(function(){
        	drawCircles();
        	return false;
        });
        
    });
    
    </script>

  </body>
</html>
