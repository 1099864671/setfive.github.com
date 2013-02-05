<?php
$link = mysql_connect('internal-db.s45960.gridserver.com', 'db45960', 'Free12!@');
if (!$link) {
   die('Could not connect: ' . mysql_error());
}
$db =mysql_select_db('db45960_twitter', $link)
        or die('Cannot open Database');


/**color scheme
* 0 -2% 0
* 2 -5% 1
*3 - 10 % 2
*10-20% 3
*20+ 4
*/

function assignColor($percent){
  $percent=$percent*100;
  if($percent<=2)
    return 0;
  if($percent<=5)
    return 1;
  if($percent<=10)
    return 2;
  if($percent<=20)
    return 3;
 return 4;

}
//lightest to darkest 29 total
//$redColors=array('c','b','a','Z','Y','X','W','V','U','T','S','R','Q','P','O','N','M','L','K','J','I','H','G','F','E','D','C','B','A');
//Break down for more distinction
$redColors=array('c','W','R','N','A');
//lightest to darkest 29 total
//$blueColors=array('g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9');

//More distinction smaller amount
$blueColors=array('g','m','s','y','9');

$sql="SELECT sum(`count`) as redcount FROM red";
$summation=mysql_fetch_assoc(mysql_query($sql));
$totalCount=$summation['redcount'];
$repub=$summation['redcount'];
$sql="SELECT sum(`count`) as bluecount FROM blue";
$summation=mysql_fetch_assoc(mysql_query($sql));
$demo=$summation['bluecount'];
$totalCount+=$summation['bluecount'];
$sql="SELECT red.state,red.score as redscore, blue.score as bluescore FROM red,blue WHERE red.state=blue.state";
$result=mysql_query($sql);
//Color and statelist are going to be responsible for the query string
$stateList='';
$colorList='';
while($state=mysql_fetch_assoc($result)){
  //Only add to the list if they aren't equal scores
  if($state['redscore']!=$state['bluescore'])
    $stateList.=$state['state'];
  else
    continue;
  if($state['redscore']>$state['bluescore']){
    $totalScore=$state['redscore']+$state['bluescore'];
    $percentage=($state['redscore']-$state['bluescore'])/$totalScore;
    $colorList.=$redColors[assignColor($percentage)];

  }

  else{
    $totalScore=$state['redscore']+$state['bluescore'];
    $percentage=($state['bluescore']-$state['redscore'])/$totalScore;
    $colorList.=$blueColors[assignColor($percentage)];

  }
//   //echo $state['state'].'|red:'.$state['redscore'].'|blue:'.$state['bluescore'].'|percent:'.$percentage.'<br />';

}

$imageURL="http://chart.apis.google.com/chart?chd=s:$colorList&chco=ffffff,FF0000,FF3333,FF4040,FFC1C1,BCD2EE,5993E5,1464F4,0147FA&chf=bg,s,eaf7fe&chtm=usa&chld=$stateList&chs=440x220&cht=t";

$image="<img src='".$imageURL."' alt='map should be here google blocked us.'  />";
mysql_close($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

  <title>Setfive Consulting  - Election Day On Twitter</title>
 <link rel="stylesheet" href="style.css" type="text/css" media="screen" charset="utf-8" />
 </head>
<body>
<a href="http://www.setfive.com"><img src="http://setfive.com/assets/images/logo.png"></a><br />
<h3>Election Day - Who is winning which states?  What does Twitter have to say?</h3>
<div id="updating"></div>
<div id="maincontent">
<p>At about midnight today, we decided to see what states are voting what according to monitoring twitter.  We've written a few algorithms that parse many tweets for certain content,looking to estimate who twitter users support in the election.  As the relative volume of tweets supporting a candidate grows, the state in question gets darker red or darker blue.  So far we have recorded <span class='count'><?=$totalCount?></span> inputs from twitter. Results will update automatically every 10 seconds.</p><p>Currently we have <span class='count'><?=$repub?></span> tweets for the republicans and <span class="count"><?=$demo?></span> for the democrats.</p>
<div align="center">
<?=$image;?>
</div>
</div>
<p>Since we did write this in about 2 hours, we can't promise that it is accurate, but thought it'd be interesting.  Leave us comments over on our <a href="http://shout.setfive.com">blog post.</a></p>
<br />
<div align="center"><p>&copy;2008 Setfive Consulting, LLC</p></div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-3761258-6");
pageTracker._trackPageview();
</script>
<script type="text/javascript">
function updatecontents()
{

	document.getElementById("updating").innerHTML='<p>Updating map....</p>';
	loadXMLDoc();	
	refreshstart();

}
function refreshstart(){
	setTimeout("updatecontents()", 10000);
}
var req;
function loadXMLDoc() {
        req = false;
    // branch for native XMLHttpRequest object
    if(window.XMLHttpRequest) {
        try {
                        req = new XMLHttpRequest();
        } catch(e) {
                        req = false;
        }
    // branch for IE/Windows ActiveX version
    } else if(window.ActiveXObject) {
        try {
                req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
                try {
                        req = new ActiveXObject("Microsoft.XMLHTTP");
                } catch(e) {
                        req = false;
                }
                }
    }
        if(req) {
                req.onreadystatechange = processReqChange;
                req.open("GET", "getUpdate.php", true);
                req.send("");
        }
}

function processReqChange() {
    // only if req shows "loaded"
    if (req.readyState == 4) {
        // only if "OK"
        if (req.status == 200) {
            var select = document.getElementById("maincontent");
	    makeFile(req.responseText);
        } else {
            alert("There was a problem retrieving the data:\n" +req.statusText);
        }
    }
}	

function makeFile(obj)
{
 
document.getElementById("maincontent").innerHTML=obj;
document.getElementById("updating").innerHTML='<p>Updated.</p>';
}
refreshstart();
</script>
</body>
</html>