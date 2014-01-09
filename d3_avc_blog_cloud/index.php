  <?php 
  	$arr = array();
  	
  	$byYear = array();
  	$tableData = array();
  	
  	$data = explode("\n", file_get_contents("avc_by_year.csv"));  	  	
  	unset( $data[0] );
  	  	
  	foreach( $data as $ln ){
  		$parts = explode(",", $ln );
  		$obj = array("word" => $parts[1], "year" => $parts[0], "count" => intval($parts[2]), "usedLastYear" => $parts[3]);
  		
  		if( !strlen($obj["year"]) ){
  			continue;
  		}
  		
  		if( !array_key_exists($obj["year"], $byYear) ){
  			$byYear[ $obj["year"] ] = array();
  		}
  		
  		$byYear[ $obj["year"] ][] = $obj;
  	}

  	$years = array_keys( $byYear );
  	foreach( $data as $ln ){
  		
  		$parts = explode(",", $ln );
  		$obj = array("word" => $parts[1], "year" => $parts[0], "count" => intval($parts[2]), "usedLastYear" => $parts[3]);
  		
  		if( !strlen($obj["year"]) ){
  			continue;
  		}  		
  		
  		if( !array_key_exists($obj["word"], $tableData) ){
  			$tableData[ $obj["word"] ] = array_fill_keys( $years, array("cnt" => null, "usedLastYear" => null) );
  		}
  		  		
  		$tableData[ $obj["word"] ][ $obj["year"] ] = array("cnt" => $obj["count"], "usedLastYear" => $obj["usedLastYear"]);
  		  		  	
  	}  	
  	
  	$tableDataWords = array_keys( $tableData );
  	sort( $tableDataWords );  	
  	  	
  ?>
  
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
    <script src="d3.layout.cloud.js"></script>
    
    <title>AVC Blog Wordcloud</title>
    
  </head>
  
  <body>

    <a target="_blank" href="https://github.com/adatta02/avc_blog_scraper"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png" alt="Fork me on GitHub"></a>
  
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
  	<div class="row-fluid">
	  	<div class="span12">    
  			<div class="page-header"><h2>The <a target="_blank" href="http://www.avc.com">AVC.com</a> Word Cloud</h2></div>  			
  		</div>
  	</div>
  	
	<div class="row-fluid">
	  	<div class="span9">  
  			<ul class="faq-list">
  				<li>
  					<strong>What is it?</strong> 
  					The word clouds below are the top 100 most used words per year 
  					from <a href="https://twitter.com/fredwilson" target="_blank">Fred Wilson's</a> 
  					blog at <a href="http://avc.com/" target="_blank">avc.com.</a>
  				</li>
  				<li>
  					<strong>Why did you make it?</strong>
  					I wanted to build something "smallish" using Scala, had some time over the holidays, and this seemed like a reasonable project.
  				</li>
  				<li>
  					<strong>Cool, as a PHP/JS guy how did you like Scala?</strong>
  					I actually liked programming with it a lot. I'll flush out some real thoughts in a blog post soon.  					
  				</li>
  				<li>
  					<strong>Are the HTML files you used available for download?</strong>
  					Sure thing! They're available <a href="http://symf.setfive.com/d3_avc_blog_cloud/avc_downloaded_urls.tar.gz" target="_blank">avc_downloaded_urls.tar.gz</a>
  				</li>
  				<li>
  					<strong>Is the Scala code available?</strong>
  					Sure it's on <a href="https://github.com/adatta02/avc_blog_scraper" target="_blank">GitHub</a>
  				</li>
  			</ul>    	
  		</div>
	</div>  		
  </div>    
  
  <div class="word-container">
  	  <div class="centered">
	  	  <ul class="listless inline">
	  	  	<li>Toggle Year: </li>
	  	  	<?php 
	  	  	$years = array_keys($byYear); sort( $years );
	  	  	for($i = 0; $i < count($years); $i++): 
	  	  	?>
	  	  		<li><a href="#" data-provide="toggle-year"><?php echo $years[$i] ?></a></li>
	  	  		<?php if( $i < count($years) - 1 ): ?>
	  	  			<li>&bull;</li>
	  	  		<?php endif;?>
	  	  	<?php endfor; ?>
	  	  </ul>
	  	  <p class="first-year-copy">Blue words didn't appear in the top 100 list for the previous year.</p> 
	  </div>
      <div id="wordDataContainer"></div>
  </div>
  
<?php $fn = function() use ($years){ ?>
  			<tr class="header-row">
  				<th>Word</th>
  				<?php foreach( $years as $yr ): ?>
  					<th class="year"><?php echo $yr; ?></th>
  				<?php endforeach; ?>
  			</tr>
<?php } ?>
  
  <div class="container">
  	<div class="row-fluid">
	  	<div class="span12">  		
			  <div class="table-word-container">
			  	<p class="first-year-copy">Cells with a blue background didn't appear in the top 100 list for the previous year.</p> 
			  	<table class="table table-bordered table-striped table-condensed">
			  		<?php $fn(); ?>
			  		<tbody>
			  			<?php $index = 0; foreach( $tableDataWords as $word ): ?>
			  			<tr>
			  				<th><?php echo $word; ?></th>
			  				<?php 
			  					$years = $tableData[$word];
			  					ksort( $years );
			  					foreach( $years as $y => $obj ):			  					
			  				?>
			  				<td class="center <?php echo $obj["usedLastYear"] === "0" ? "new-word" : ""?>">
			  					<?php echo $obj["cnt"] === null ? "" : $obj["cnt"]; ?>
			  				</td>
			  				<?php endforeach; ?>
			  			</tr>
			  			<?php if( $index > 0 && $index % 25 == 0 ){ $fn(); } ?>
			  			<?php $index += 1; endforeach; ?>
			  		</tbody>
			  	</table>
			  </div>
		  </div>
		</div>
	</div>
  </div>
  
  <script>

  	var wordCloudData = <?php echo json_encode( $byYear ); ?>;
  	
	$(document).ready(function(){
				
		var fill = d3.scale.category20();
		var w = $("#wordDataContainer").width() * .90, h = 800;

		var svg = d3.select("#wordDataContainer").append("svg").attr("width", w).attr("height", h);				
		var target = svg.append("g").attr("transform", "translate(" + w / 2 + ", 400)");	
				
		window.layout = d3.layout.cloud().size([w, h])
			            .padding(5)
			            .rotate(function() { return ~~(Math.random() * 2) * 90; })
			            .font("Impact")
			            .fontSize(function(d) { return d.size; })
			            .on("end", draw)	            
			            .stop();

		$("[data-provide='toggle-year']").click(function(){
			var key = $(this).text();
			var fontSize = d3.scale["log"]()
							 .range([8, 100])			
							 .domain([ _.min(wordCloudData[ key ], 
										function(e){return e.count}).count, _.max(wordCloudData[ key ], 
											function(e){return e.count}).count] );
						
			var words = wordCloudData[ key ].map(function(d) {
	            return {text: d.word, usedLastYear: d.usedLastYear, size: fontSize(d.count)};
	        });			

			window.layout.stop().words(words).start();

			$("[data-provide='toggle-year']").removeClass("selected");
			$(this).addClass("selected");
			
			return false;
		});		

		$("[data-provide='toggle-year']:last").click();
			
	  	function draw(words) {
		    			  
		  var text = target.selectAll("text").data(words);
      	  
		  text.enter().append("text")
            .style("font-size", function(d) { return d.size + "px"; })
        	.style("font-family", "Impact")
        	.style("fill", function(d, i) { return d.usedLastYear == "1" ? "#CCD2DE" : "#2F4E91"; })
        	.attr("text-anchor", "middle")
        	.attr("transform", function(d) {return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";})
        	.text(function(d) { return d.text; });	        	

		  text.transition()
			  .duration(1000)
			  .attr("transform", function(d) {return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";})
			  .style("font-size", function(d) { return d.size + "px"; })
			  .style("fill", function(d, i) { return d.usedLastYear == "1" ? "#CCD2DE" : "#2F4E91"; });
        	
		  text.exit().remove();
		  
	  	}	  
			
	});
  </script>
  
  </body>
  
</html>