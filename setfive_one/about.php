<?php
  include_once("functions.php");
  $TITLE = "About";
  include_once("header.php");
?>
  <div><h1><a href="/about" class="title_header_link" onClick="return loadPage('our-story');">about</a></h1></div> 
  <div class="sidebar">

    <p class="header"><strong><a href="/about/our-story" class="title_link_sub" onClick="return loadPage('our-story');">Our Story</a></strong></p>
    <p class="no-padding">
        Our story isn't totally written yet so bear with us. <a href="about/story" onClick="return loadPage('our-story');">Read More</a>
    </p>

    <p class="header"><strong><a href="/about/technology" class="title_link_sub" onClick="return loadPage('technology');">Technology</a></strong></p>
    <p class="no-padding">
        We use the good stuff. <a href="about/technology" onClick="return loadPage('technology');">Read More</a>
    </p>
      

      
    <p class="header"><strong><a href="/about/core-team" class="title_link_sub" onClick="return loadPage('core-team');">Core Team</a></strong></p>
    <p class="no-padding">
        If anyone is "corporate" it's these guys. <a href="about/core" onClick="return loadPage('core-team');">Read More</a>
    </p>
      
  </div>

    <div class="maincontent" id="content">
        <?php
            switch($_GET['page'])
            {
              case "technology":
                include_once('technology.html');
                break;
              case "our-story":
                include_once('our-story.html');
                break;
              default:
                include_once("our-story.html");
                break;
            }
            
        ?>
    </div>

<?php include('footer.php');?>