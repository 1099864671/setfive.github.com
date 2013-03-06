<?php
  include_once("functions.php");
  $TITLE = "Symfony Consulting and Development";
  include_once("header.php");
?>
  
  <div><h1>No "bs." Just results.</h1></div>
  

<div class="maincontent">
  <div class="box1">
    <h3>Web Application Development</h3>
    The desktop is dead. We specialize in developing both enterprise and consumer facing web apps. <a href="services/webdev">Learn More</a>
  </div>
  <div class="box2">
    <h3>Rapid Prototyping</h3>
    The hardest part of building a great product is often the first iteration.
    Our RAD teams will work with you to work from paper mockups to a viable prototype. <a href="services/rapid-prototyping">Learn More</a>
  </div>
  <div class="box3">
    <h3>Symfony Framework (PHP)</h3>
    We do Symfony projects. We build 'em, customize 'em, and optimize 'em. <a href="services/symfony-framework">Learn More</a>
  </div>
  <div class="box4 bottom_box">
    <h3>Mashups</h3>
    Not to sound clich&#233;, but nothing says "web 2.0" like a mashup. Google, Twitter, Foursquare - we rock them all. <a href="services/mashups">Learn More</a>
  </div>
  <div class="box5 bottom_box">
    <h3>Performance Consulting</h3>
    Slow website giving you the blues? We'll analyze your architecture and identify key trouble areas for your site's performance. <a href="services/performance-consulting">Learn More</a>
  </div>
  <div class="box6 bottom_box">
    <h3>Salesforce Integrations</h3>
    Having an awesome CRM is great - except when it doesn't interface with your other systems. That's where we come in. <a href="services/salesforce-integrations">Learn More</a>
  </div>

  
</div>
 <div class="sidebar_home">
    <h3>Latest from the blog</h3>
    <?php
    // get the latest post from the blog
    $rss = @ simplexml_load_file('http://shout.setfive.com/feed/') or false;

    ?>

    <?php if($rss): $post=$rss->channel->item[0]; ?>
	    <p><strong><a href="<? echo $post->link;?>" class="title_link"><?php echo $post->title ?></strong></a></p>
	    <p><?php echo $post->description ?>&nbsp;&nbsp;<a href="<? echo $post->link;?>">Read More</a></p>

            <?php $post=$rss->channel->item[1];?>
	    <p><strong><a href="<? echo $post->link;?>" class="title_link"><?php echo $post->title ?></a></strong></p>
	    <p><?php echo $post->description ?>&nbsp;&nbsp;<a href="<? echo $post->link;?>">Read More</a></p>


    <?php endif; ?>
  </div>
<?php include('footer.php');?>