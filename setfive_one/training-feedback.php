<?php
  include_once("functions.php");
  $TITLE = "Training Feedback";
  include_once("header.php");
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    $hasContent=false;
    $message='';
    foreach($_POST as $key=>$val)
      if(trim($val))
        $message.=$key.': '.$val."\n";
    if($message)
      mail('contact@setfive.com','Training feedback',$message,'From: Feedback<feedback@setfive.com>');
  }
?>
<div><h1>Training Feedback</h1></div> 


  <div id="content">
  <?php if($_SERVER['REQUEST_METHOD']=='POST'):
          echo '<p>Thank you for your feedback!  We will be in contact with you if necessary.  <br /><em>-Setfive</em>';
        else:?>
    <p>Please provide any feedback on our Symfony Training Seminar.  It's quick and painless!  This feedback is invaluable to us and helps us better our services.  All feedback is anonymous, unless you enter your email and name.  All fields are optional</p>
    <form action="" method="POST">
      <p>What was the most beneficial part of the seminar?<br />
      <textarea name="benefit" style='width:500px;'></textarea></p>
      <p>What was the least helpful part of the seminiar?<br />
      <textarea name="least-helpful" style='width:500px;'></textarea></p>
      <p>What do you wish we spent more time on or talked more about?<br />
      <textarea name="more-time" style='width:500px;'></textarea></p>
      <p>Additional Comments?<br />
      <textarea name="additional-comments" style='width:500px;'></textarea></p>
      <p>Name (optional): <input type="text" name="name"></p>
      <p>Email(optional): <input type="text" name="email"></p>
      <p><input type="submit" value="Submit feedback" /></p>
    </form>
    <?php endif;?>
  </div>

<?php include('footer.php');?>