<?php 

error_reporting(0);

if( !array_key_exists("vote_key", $_COOKIE) ){    
    setcookie("vote_key", uniqid());
}

$key = $_COOKIE["vote_key"];
$votes = json_decode( file_get_contents( "votes.json" ), true );

$canVote = true;
if( array_key_exists($key, $votes) ){
    $canVote = false;
}

$dates = ["10/28", "11/4"];
$voteTotals = array_fill_keys($dates, 0);

foreach( $votes as $user => $chosenDate ){
    $voteTotals[ $chosenDate ] += 1;
}

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $canVote ) {
    
    if( in_array($_REQUEST["date"], $dates) ){
        $votes[ $key ] = $_REQUEST["date"];
    }       
    
    file_put_contents("votes.json", json_encode($votes));
    header("Location: http://symf.setfive.com/tufts_tech/");
    exit(0);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="keywords" content="symfony, Boston, consulting, php, javascript, Drupal" />
    <meta name="description" content="">
    <link rel="shortcut icon" href="/images/avicon.ico">

    <title>Setfive Consulting | Tufts Tech Voting</title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700,900' rel='stylesheet' type='text/css'>

    <link href="http://www.setfive.com/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://www.setfive.com/css/styles.css" />
    <link rel="stylesheet" href="styles.css" />

    <script src="http://www.setfive.com/js/jquery-1.11.1.min.js"></script>
    <script src="http://www.setfive.com/js/less.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="tufts-tech">
    <div role="navigation" class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand"></a>
            </div>
            <div class="collapse navbar-collapse">
                <div class="logo">
                    <a href="/"><img src="http://www.setfive.com/images/logo_noconsulting.png"></a>
                </div>
                <ul class="nav navbar-nav">

                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
    
    <div class="halfsize-slide-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="slide-body-container text-center">
                            <h2>Tufts in tech voting</h2>
                            <p>Like stir fry night, but better</p>
                        </div>
                    </div>
                </div>
            </div>                
    </div>    
    
    <div class="casestudy-body-container">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="text-center">
                            <h1>Please vote for a day:</h1>
                        </div>
                    </div>
                    
                    <div class="row">
                        <?php if( $canVote ): ?>
                            <ul class="vote-block list-inline text-center">
                                <li><a data-provide="pick-date" href="#10/28">10/28 (Tuesday)</a></li>
                                <li>&bull;</li>
                                <li><a data-provide="pick-date" href="#11/4">11/4 (Tuesday)</a></li>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-success text-center">
                                Thanks for your vote!
                            </div>
                        <?php endif; ?>                        
                    </div>                   
                     
                    <div class="row votes">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $voteTotals as $date => $total ): ?>
                                    <tr>
                                        <td><?php echo $date; ?></td>
                                        <td><?php echo $total; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                     
                </div>
            </div>
        </div>
    </div>
    
    <form method="POST" action="http://symf.setfive.com/tufts_tech/">
        <input type="hidden" name="key" value="<?php echo $key?>" />
        <input type="hidden" name="date" value="" />
    </form>
    
    <script>
      $(document).ready(function(){
          $("[data-provide='pick-date']").click(function(){
              $("[name='date']").val( $(this).attr("href").replace("#", "") );
              $("form").submit();
              return false;
          });
      });
    </script>
    
</body>

</html>