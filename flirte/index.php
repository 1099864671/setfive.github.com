<?php require_once 'lob.php'; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Happy Valentines Day!</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet/less" type="text/css" href="styles.less" />
    <link href="jqueryui/jquery-ui.min.css" rel="stylesheet">
    <link href="jqueryui/jquery-ui.theme.min.css" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>            
    
    <script src="jqueryui/jquery-ui.min.js"></script>
    <script src="underscore-min.js"></script>    
    <script src="backbone-min.js"></script>        
    
    <script src="less.min.js"></script>
    
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,300|Raleway:400,700' rel='stylesheet' type='text/css'>
  </head>
  <body>
    
    <div role="navigation" class="navbar navbar-default navbar-fixed-top hidden">
      <div class="container">
        <div class="navbar-header">
          <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="#" class="navbar-brand">Name Here</a>
        </div>
        <div class="navbar-collapse collapse navbar-right">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>            
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>        
    
    <div class="video-background">
    
      <div class="video-container">
        <canvas id="video-background"></canvas>
      </div>
      
      <div class="container hero-container">
        
        <div class="row">
          <div class="col-md-12">
            <div class="text-center banner">
              <h1>Send a Valentines Day postcard<br>to your favorite people!</h1>
              <p>(No seriously, we're sending physical pieces of mail.) 
            </div>
          </div>
        </div>
        
        <div class="row hidden">
          <div class="col-md-6 col-md-offset-3">
            <img src="ryangosling.jpg" class="hero-image" />      
          </div>
        </div>
        
      </div>
      
    </div>
    
    <div class="container copy-container">
      <div class="row">  
        <div class="col-md-6">
          
          <h3>Why?</h3>
                             
          <p>Why not? We wanted to experiment with some new technology and take the 
            <a href="https://lob.com/" target="_blank">lob.com</a> API for a spin. 
            Mostly, we just want to have some fun sending memes to people via snail mail.</p>
            
          <p>We'll let you know once we figure out how to 
             <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">Rick Roll</a> someone...</p>          
           
          <h3>How?</h3>
          <ol class="how-list">
            <li>Select an image from our gallery.</li>
            <li>Add a caption to make the masterpiece your own.</li>
            <li>Fill out FROM and TO address details.</li>
            <li>Push the red button to send!</li>
          </ol>
          
          <h3>Lets do it!</h3>
                    
        </div>
        <div class="col-md-6">
          <div class="text-center">
            <img src="goslingtext.jpg" class="copy-preview" />
          </div>
        </div>      
      </div>
    </div>
    
    <div class="container template-container">
      <div class="row">
        <div class="col-md-3 sidebar">
          <h4>Select a Template</h4>
                    
          <table class="table">
            <tr>
              <td><a href="#"><img class="thumbnail-select" src="gosling_small.jpg" /></a></td>
              <td><a href="#"><img class="thumbnail-select" src="americanpsycho_small.jpg" /></a></td>              
            </tr>
          </table>
          
          <h4>Add Some Text</h4>
          
          <form data-provide="text-form">
            <div class="form-group">
              <textarea class="form-control" class="postcard-text" name="added-text"></textarea>
            </div>                             
            <div class="form-group">
              <button type="submit" class="btn btn-default">Update</button>
            </div>
            
            <p>All set? You just need to fill out the address details down below.</p>
          </form>          
          
        </div>
        <div class="col-md-9">
          <div class="template-image-container">
            <div class="template-bg">
              <div class="overlay-text">
                Excuse me, I need to return some video tapes.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="container address-container">
    
    	<form method="POST" action="sendCard.php" data-provide="address-form" class="form-horizontal">
    	
	    	<div class="row">
	    		<div class="col-md-6">
	    			<h4>Your Address</h4>    			
	    				<?php echo Lob::getAddressForm("from"); ?>    			    
	    		</div>
	    	</div>
    	
	    	<div class="row their-address">
	    		<div class="col-md-6">
	    			<h4>Their Address</h4>
	    				<?php echo Lob::getAddressForm("to"); ?>  			
	    		</div>
	    	</div> 

	    	<div class="row their-address">
	    		<div class="col-md-6">
	    			<h4>Your Email Address</h4>
	    			<div class="form-group">
	    				<div class="col-sm-10 col-sm-offset-2">
	    					<input type="email" name="email" id="email" class="form-control">
	    					<span class="help-block">We'll send you a confirmation email once your postcard is processed.</span>
    					</div>
	    			</div>
	    			
	    			<div class="alert alert-warning text-center hidden" data-provide="email-error">
	    				Sorry! That isn't a valid email address.
	    			</div>
	    			
	    		</div>
    		</div>
    	  	
    	  	<input type="hidden" name="added-text" />
    	  	<input type="hidden" name="selected-template" />
    	  	
    	</form>
    </div>
        
    <div class="container copy-container">
      <div class="row">  
        <div class="col-md-6 col-md-offset-3 text-center">
        	<h3>Ready to rock?</h3>
        	<a data-provide="send" href="#" class="btn btn-lg btn-default big-btn">Fire away!</a>
        </div>
      </div>
    </div>
        
	<div class="footer-container">
		<div class="container">
			<div class="row">
	    		<div class="col-md-12 text-center">
	    			Made with love in Cambridge, MA by <a href="http://www.setfive.com">Setfive</a>
	    		</div>
	    	</div>
		</div>        
	</div>	           
    
    <script>
      $(document).ready(function(){        
        $( ".overlay-text" ).draggable({ containment: "parent" });
        
        var canvas = document.getElementById("video-background");
        var ctx = canvas.getContext("2d");;
        
        $(".hero-container").css("height", window.innerHeight);
        
        ctx.canvas.width  = window.innerWidth;
        ctx.canvas.height = window.innerHeight;
        
        var cw = window.innerWidth;;
        var ch = window.innerHeight;
        
        var v = document.getElementById('tswiftVideo');
        
        v.addEventListener('play', function(){
          draw(this, ctx, cw, ch);
        },false);
        
        function draw(v, c, w, h) {
        
            if(v.paused || v.ended) {
              return false;
            }
            
            var videoHeight = v.videoHeight;
            var videoWidth = v.videoWidth;
            
            var targetWidth = 0;
            var targetHeight = 0;
            
            if( w > h ){
              targetHeight = h;
              targetWidth = (h * v.videoWidth) / v.videoHeight;
            }else{
              targetWidth = w;
              targetHeight = (w * v.videoHeight) / v.videoWidth;
            }
                        
            // console.log( targetWidth + " : " + targetHeight );
                        
            ctx.drawImage(v, 0, 0, targetWidth, targetHeight);
            // setTimeout(draw, 20, v, c, targetWidth, targetHeight);
        }

        $("[data-provide='address-form']").submit(function(){
            return false;
        });
        
        $("[data-provide='send']").click(function(){
            var email = $("#email").val();
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;            

            $("[data-provide='email-error']").addClass("hidden");
            
            if(!re.test(email)){
                $("[data-provide='email-error']").removeClass("hidden");
                return false;
            }

            $("[data-provide='address-form'] [name='added-text']").val( $("[data-provide='text-form'] [name='added-text']").val() );
            
            $("[data-provide='address-form']").off("submit");            
            $("[data-provide='address-form']").submit();
            
            return false;
        });
               
      });
    </script>   
  
  <div class="hidden">  
    <video id="tswiftVideo" autoplay loop>
        <source src="https://s3.amazonaws.com/setfive-public/10things.webm" type="video/webm">
    </video>
  </div>
  
  </body>
</html>
