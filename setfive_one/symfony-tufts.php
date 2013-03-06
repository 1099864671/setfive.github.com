<?php

  include_once("functions.php");

function getRateWidget($widgetName){
  $ret="<input type='radio' value='Never Used/Not Comfortable' name='%name%'> Never Used/Not Comfortable ";
  $ret.="<input type='radio' value='Some Experience' name='%name%'> Some Experience ";
  $ret.="<input type='radio' value='Very Comfortable' name='%name%'> Very Comfortable";
  $ret.="<input type='radio' value='Expert' name='%name%'> Expert ";

  
  return str_replace("%name%",$widgetName,$ret);
}
  ini_set("magic_quotes_gpc", "0");  

  $TITLE = "Symfony Training - Tufts University Fall 2009";
  $TITLE.=checkSubPageTitle();
  include_once("header.php");
  $success=false;
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
      $message='';
      foreach($_POST as $key => $value)
        $message.=$key.":".$value."\n";
      
      $success=mail('contact@setfive.com', 'Tufts University Symfony Questions', stripslashes($message) ,'From: Tufts Symfony<training@setfive.com>');
      if(!$success)
	      $msg="<p><strong>It seems something is wrong on our server, manually email us, and we'll try to fix it!</strong></p>";
 }
?>
<style type="text/css">
.survey-questions li{
  padding-top:20px;
}
</style>
  <div><h1><a href="/clients" class="title_header_link" onClick="return loadPage('clients_overview');">Tufts University Symfony Experience Review</a></h1></div> 
  <div class="sidebar">


  </div>

    <div id="content">
<?php
if(!$success)
{
?>
  <p>Please complete each of the questions below.  The questions are meant to provide us with an overview of your experience with the Symfony Framework in advance of our tailored training program.</p><br />
  <form action="/symfony-tufts" method="POST" onSubmit="return checkFields();">
  Name: <input type="text" name="name">
  <ol type="1" class='survey-questions'>
  <li>Are you familiar with the syntax of YAML and the various Symfony configuration files?<br /><?php echo getRateWidget('q1');?></li>
  <li>Have you used Propel Routing? <select name="q2"><option value=""></option><option value="Yes">Yes</option><option value="No">No</option></li>
  <li>What is the difference between a partial and a component in Symfony? Why should you use one over the other?<br ><textarea rows=5 cols=60 name="q3"></textarea></li>
  <li>Are you comfortable using components and partials to support AJAX functionality?<br /><?php echo getRateWidget('q4');?></li>
  <li>Do you know what a Symfony Filter is?  If so how have you used it?<br /><textarea rows=5 cols=60 name="q5"></textarea></li>
  <li>Have you customized the generator files for the automatically generated user backend?  If so, how?<br /><textarea rows=5 cols=60 name="q6"></textarea></li>
  <li>Have you ever worked with Doctrine? If so, how?<br /><textarea rows=5 cols=60 name="q7"></textarea></li>
  <li>Have you used Propel behaviors? If so, how?<br /><textarea rows=5 cols=60 name="q8"></textarea></li>
  <li>Have you used the form framework Symfony provides? If so, how? <br /><textarea rows=5 cols=60 name="q9"></textarea></li>
  <li>What versions of Symfony have you used?   What size applications did you develop with each version you have used?<br /><textarea rows=5 cols=60 name="q10"></textarea></li>
  <li>What primary plugins do you use with Symfony? <br /><textarea rows=5 cols=60 name="q11"></textarea></li>
  <li>Have you accessed the sfUser, sfResponse, and sfRequest object in a view? If so, how did you access them?<br /><textarea rows=5 cols=60 name="q12"></textarea></li>
  <li>Have you done any performance tuning on Symfony?  If so, what have you done, and why?<br /><textarea rows=5 cols=60 name="q13"></textarea></li>
  <li>What Javascript library are you the most comfortable with? <input type="text" name="q14"><br />How comfortable are you with it?<?php echo getRateWidget('q14a');?></li>
  <li>Do you have a specific part of Symfony you would like to gain a better understanding of?<br /><textarea rows=5 cols=60 name="q15"></textarea></li>
  <li>What would you like to get out of this training program?<br /><textarea rows=5 cols=60 name="q16"></textarea></li>
  </ol><br /><br />
<input type="submit" value="Submit Questions"  />
</form>
<script type="text/javascript">
function checkFields(){
var error=false;
$(":input").each(function(){
  if(!$(this).val())
    error=true;
});
if(error)
  alert('Please answer all questions.');
return !error;

}

</script>
<?php
}
else
  echo "<p>Thank you for answering the experience review questions.<br /><strong><em>-Setfive</em></strong></p>";
?>
    </div>

<?php include('footer.php');?>
