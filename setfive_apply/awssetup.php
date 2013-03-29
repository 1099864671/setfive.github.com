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
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <title>Setfive > AWS Quickstart</title>
    
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
            <div class="span12">
                <h1>Amazon AWS Quickstart Guide</h1>
            </div>            
        </div>
        <div class="row">
            <div class="span8">
                <div class="alert alert-info">If you want to use Amazon AWS's EC service to run your LAMP enviroment these steps will walk you through setting everything up.</div>
                
                <div class="padded-bottom">
                    <h3>1. Sign up for Amazon AWS</h3>
                    <p>Head to <a href="http://aws.amazon.com/" target="_blank">http://aws.amazon.com/</a> and sign up for AWS.
                        Don't worry, you won't get charged unless you go over the "free" tier, and you wont.
                    </p>
                </div>
                
                <div class="padded-bottom">
                    <h3>2. Launch an instance</h3>
                    <p>While logged in to the AWS console, 
                       click <a href="https://console.aws.amazon.com/ec2/home?region=us-east-1#launchAmi=ami-3bec7952" target="_blank">https://console.aws.amazon.com/ec2/home?region=us-east-1#launchAmi=ami-3bec7952</a> 
                       to launch a new AWS instance. Follow the prompts, add a SSH key, and then finally hit "Launch" to create the instance.
                </div>
                
                <div class="padded-bottom">
                    <h3>2. Setup the instance</h3>
                    <p>Once the instance moves into the "running" state, click on the instance to bring up its details. 
                    In the details, use the "Public DNS" hostname and then ssh into the machine. Use the user "ubuntu" along with the SSH key that you created in the previous step:</p>
                    <script src="https://gist.github.com/adatta02/5273124.js"></script> 
                    <p>Once you're connected, run the following to install the required packages:</p>
                    <script src="https://gist.github.com/adatta02/9c87a6413d043b46b691.js"></script>
                </div>
                
                <div class="padded-bottom">
                    <h3>3. Pass the tests</h3>
                    <p>Thats about it, at this point you have a fully functioning virtual server to run the tests on.</p> 
                </div>
                
                <div class="padded-bottom">
                    <h3>3. Terminate your instance</h3>
                    <div class="alert alert-danger">
                        <h3>When you're done, TERMINATE your instance otherwise you'll get billed after the free tier expires</h3>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    </body>
</html>