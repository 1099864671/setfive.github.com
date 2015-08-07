<?php
if($_SERVER['REQUEST_METHOD']=='POST'
   && isset($_POST['query']) 
   && strlen($_POST['query'])
   && isset($_POST['doctrineVersion'])
  )
{

  $input = trim($_POST['query']);
    
  if($_POST['doctrineVersion']==2)
  {
    preg_match('/.* \[(.*)\] \[\]/',$input,$matches);
    $query = preg_replace('/ (\[.*\] \[\])$/','',$input);
    $parameters = explode(',',$matches[1]);
    $finalQuery = '';
    foreach(explode('?',$query) as $i => $queryPart)
    {
      if(isset($parameters[$i]))
      {
	$parameter =  trim($parameters[$i]);
	if(strpos($parameter,'"')===0) // String representation? 
	{
	  $parameter = substr($parameter,1,-1);
	  $parameter = '"'.mysql_escape_string($parameter).'"';
	}
      }
      else
	$parameter = null;

      $finalQuery.=$queryPart.$parameter.' ';
    }
  
  }
  else
  {
  
    preg_match('/.* - \((.*)\)$/',$input,$matches);

    $query = preg_replace('/ (- \(.*\)$)/','',$input);

    $parameters = explode(',',$matches[1]);
    $finalQuery = '';
    foreach(explode('?',$query) as $i => $queryPart)
    {
      if(isset($parameters[$i]))
      {
	$parameter =  trim($parameters[$i]);
	if(!is_numeric($parameter) && $parameter!=='NULL') // String representation? 
	{
	  $parameter = '"'.mysql_escape_string($parameter).'"';
	}
      }
      else
	$parameter = null;

	
      $finalQuery.=$queryPart.$parameter.' ';
    }
  
  }
  
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700,900' rel='stylesheet' type='text/css'>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <style>
      
      html, body {
       width: 100%;  
       height: 100%;
       background-color: #000;
      }
      
   
      .logo {
        float: left;
        width: 300px;
      }
      
      .content{
	background-color: #FFF;
	color: #000;
	padding: 20px;
      }
      
      label.radio-inline{
	font-weight: bold;
      }
      
      hr.space{
	visibility: hidden;
      }

    </style>    
  </head>
  <body>
    
    <div class="header-container">
        <div class="logo">          
          <a href="http://www.setfive.com" target="_blank"><img src="logo_noconsulting.png" /></a>
        </div>      
    
        
        <div class="clearfix"></div>                     
    </div>
    
   
    
    <div class="container">
      <div class="col-md-6 col-md-offset-3 content">
	  <h3 class="text-center">Doctrine Query Converter</h3>
	  
	  <p>When using Doctrine 1.X and 2.X with symfony1.X and Symfony2.X we found ourselves often not being able to 
	      use the profiler to get runnable queries.  Often this was due to background processing in the application, for example via Gearman.  
	      To convert queries we wrote up this quick script.  Just copy and paste the log line from the starting SQL to the end
	      (including brackets and parameters), select your Doctrine version and click submit.  We'll output the runnable version of the 
	      query with the parameters plugged in. No queries are saved to our server.</p>
	      
	  <?php if(isset($finalQuery)):?>
	    <div class="alert alert-success">
	      <strong>Runnable Query: </strong>
	      <?php echo $finalQuery;?>
	    </div>
	  <?php endif;?> 
	  <form method="POST">
	    <div class="form-group">
	      <label>Log line of Query (From start of SQL to end of log line):</label>
	      <textarea name="query" class="form-control" required="required"></textarea>
	    </div>
	    
	    <div class="radio">
	      <label class="radio-inline">
		<input type="radio" name="doctrineVersion" value="1" required="required" > Doctrine 1.X
	      </label>
	      <label class="radio-inline">
		<input type="radio" name="doctrineVersion" value="2" required="required" checked=checked> Doctrine 2.X
	      </label>
	    </div>
	    <hr class="space" />
	    <div class="text-center">
	      <input type="submit" class="btn btn-primary text-center" value="View Runnable Query" />
	    </div>
	  </form>
	     
	  
      </div>
       
    </div>
                

  </body>
 </html>