<?php
  include('functions.php');
  $TITLE = "VPS Solutions";
  $TITLE.=checkSubPageTitle();
  include_once("header.php");
?>
  <div><h1><a href="/vps" class="title_header_link">vps hosting</a></h1></div> 


    <div class="maincontent" id="content">
<p>Please click below to sign up for VPS hosting with Setfive Consulting, LLC.  The cost is $50/month and includes one SSL certificate.  Once the first payment is recieved your server will be setup within two business days.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="5118079">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

    </div>

<?php include('footer.php');?>
