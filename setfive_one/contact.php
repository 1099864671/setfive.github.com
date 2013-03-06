<?php

  include_once("functions.php");

  ini_set("magic_quotes_gpc", "0");  

  $TITLE = "Contact";
  $TITLE.=checkSubPageTitle();
  include_once("header.php");
  $success=false;
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    if($_POST['subject']!=''&&$_POST['body']!=''&&$_POST['email']!='')
    {
      $success=mail('contact@setfive.com', stripslashes($_POST['subject']), stripslashes($_POST['body']) ,'From: '.$_POST['email']);
      if(!$success)
	      $msg="<p><strong>It seems something is wrong on our server, manually email us, and we'll try to fix it!</strong></p>";
    }
    else
    {

        $msg="<p><strong>You need to make sure you've filled out all the fields for the form to submit!</strong></p>";
    }

 }
?>
  <div><h1><a href="/clients" class="title_header_link" onClick="return loadPage('clients_overview');">contact</a></h1></div> 
  <div class="sidebar">


  </div>

    <div class="maincontent" id="content">
<?php
if(!$success)
{
?>
  <p>
		 Contact forms are kind of a drag to fill out, so we're not making you use one! 
  </p>
  <p>
	   Just email us at <a href="mailto:contact@setfive.com">contact@setfive.com</a> or give us a call at +1 (617) 863-0440 and tell us about 
your 
project. <br/>
		 We'll get back to you within a business day.
	</p>
	
	<p>
		We're in Boston, so we'd be happy to meet anywhere in New England as well as New York City.
	</p>
	
  <p>For those who have fun with forms:</p>
  <?php if(isset($msg)) echo $msg;?>
  <form name="contact" action="/contact" method="POST" >
  <table><tr><td>Email:</td><td><input type="text" name="email" value="<?php echo $_POST['email'];?>" /></td></tr>
  <tr><td>Subject:</td><td><input type="text" name="subject" value="<?php echo $_POST['subject'];?>" /></td></tr>
  <tr><td>Question <br />or<br />Comment:</td><td><textarea name="body" cols=60 rows=5><?php echo $_POST['body'];?></textarea></td></tr></table>
  <input type="submit" name="submit" value="Send" />
  </form>
  
  <script type='text/javascript'>(function(){document.write("<img src='http://bostonbuilt.org/icon.php?u=" + window.location.host + "' />");})();</script>
  
    <?php
}
else
  echo "<p>Thank you for contacting us.  We will respond to you within a business day regarding your inquiry.<br /><strong><em>-Setfive</em></strong></p>";
?>
    </div>

<?php include('footer.php');?>
