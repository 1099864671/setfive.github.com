<?php 
if( strlen($_REQUEST["name"]) == 0 ){
    die("Nope");
}
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
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <title>Setfive > Apply</title>
    
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
                <h2>Welcome to Setfive's Checkpoint Charlie</h2>
            </div>
        </div>
        <div class="row">
            <div class="span8">
                <div class="padded-bottom">
                    <h3>What you'll need to do</h3>
                    <ul class="big-bullets">
                        <li>Setup a LAMP enviroment with PHP 5.3+</li>
                        <li>Create the test MySQL database</li>
                        <li>Download the PHP unit tests</li>
                        <li>Fill in the missing functions to make all the tests PASS</li>
                        <li>Email us a ZIP/TAR of the working PHP files</li>
                    </ul>
                </div>
                
                <div class="padded-bottom">
                    <div class="big-title">1. Setup LAMP</div>
                    <p>
                        The easiest way to get LAMP running is to use Amazon AWS's EC2 feature to provision a virtual server. 
                        Alternatively, you can setup a virtual machine on your own machine to run the LAMP enviroment.
                    </p>
                    You'll need the following packages:
                    <ul>
                        <li>PHP 5.3+</li>
                        <li>PDO for PHP - <a href="http://php.net/manual/en/book.pdo.php" target="_blank">http://php.net/manual/en/book.pdo.php</a></li>
                        <li>PHPUnit - <a href="http://www.phpunit.de/manual/3.6/en/installation.html" target="_blank">http://www.phpunit.de/manual/3.6/en/installation.html</a></li>
                        <li>MySQL 5+</li>                        
                    </ul>
                    
                    <p>If you want to use Amazon AWS, check out the <a href="http://apply.setfive.com/awssetup.php" target="_blank">quickstart guide</a>.</p>
                </div>
                
                <div class="padded-bottom">
                    <div class="big-title">2. Create the test MySQL database</div>
                    <p>Once you have your enviroment up, you'll need to create the test MySQL database with the following schema:</p>
                    <script src="https://gist.github.com/adatta02/5272778.js"></script>                    
                </div>
                
                <div class="padded-bottom">
                    <div class="big-title">3. Download the PHP unit tests</div>
                    <p>Once you have everything setup, download the tests by clicking below!</p>
                    <div class="centered">
                        <p><a href="downloadPayload.php?name=<?php echo $_REQUEST["name"]; ?>" class="btn btn-large btn-primary">Give me the files!</a></p>
                    </div>
                    <div class="centered">
                        <p><strong>FYI, we're recording when you download the files so don't click it willy nilly.</strong></p>
                    </div>
                </div>
                
                <div class="padded-bottom">
                    <div class="big-title">4. Fill in the missing functions to make all the tests PASS</div>
                    <p>This is the meat and potatoes. What you'll need to do is fill in the empty methods in the PHP files to make all the tests pass.
                      To run the tests, run the following:</p>
                    <script src="https://gist.github.com/adatta02/5272932.js"></script>
                    
                    <p>You'll need to complete the files in the following order:</p>
                    <ol class="big-bullets">
                        <li>BasicFunctions.php</li>
                        <li>DB.class.php</li>
                        <li>User.class.php</li>
                    </ol>
                    
                    <strong>Useful architecture notes:</strong>
                    <ul>
                        <li>The tests run in order, so you'll want to work from the top of the files down.</li>
                        <li>The User class depends on the DB class so you'll have to get the DB class working first.</li>
                        <li>If you run into a blocking problem, just shoot us an email.</li>
                        <li>Don't spend more than 1-2 hours on this</li>
                    </ul>
                    
                </div>
                
                <div class="padded-bottom">
                    <div class="big-title">Email us a ZIP/TAR of the working PHP files</div>
                    <p>Once everything is working, email an archive of the files to <a href="mailto:hiring@setfive.com">hiring@setfive.com</a></p>
                </div>
                
                <div class="padded-bottom centered">
                    <h3>Feel free to email us with questions or bugs!</h3>
                    <h3>Good luck!</h3>
                </div>
                
            </div>
            <div class="span4">
                <img src="Berlin_Checkpoint_Charlie_089.jpg" style="width: 300px;" />
            </div>
        </div>
    </div>
    
    </body>
</html>