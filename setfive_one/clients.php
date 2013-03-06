<?php
  include_once("functions.php");
  $TITLE = "Clients";
  $TITLE.=checkSubPageTitle();
  include_once("header.php");
?>
  <div><h1><a href="/clients" class="title_header_link" onClick="return loadPage('clients_overview');">clients</a></h1></div> 
  <div class="sidebar">

    <p><strong><a href="/clients/lfb" class="title_link_sub" onClick="return loadPage('mfa');">The MFA</a></strong></p>
    <p>
        The MFA came to us with a unique problem... <a href="/clients/mfa" onClick="return loadPage('mfa');">Read More</a>
    </p>
      
    <p><strong><a href="/clients/tufts" class="title_link_sub" onClick="return loadPage('tufts');">Tufts University</a></strong></p>
    <p>
        Tufts University needed help. Fast... <a href="/clients/tufts" onClick="return loadPage('tufts');">Read More</a>
    </p>
      
    <p><strong><a href="/clients/lfb" class="title_link_sub" onClick="return loadPage('lfb');">The LFB Forex</a></strong></p>
    <p>
        The LFB Forex brought us specs and requirements. Nice... <a href="/clients/lfb" onClick="return loadPage('lfb');">Read More</a>
    </p>
    
    <p><strong><a href="/clients/bt" class="title_link_sub" onClick="return loadPage('bt');">Boston Technologies</a></strong></p>
    <p>
        Boston Technologies (BT) wanted a team they could count on... <a href="/clients/bt" onClick="return loadPage('bt');">Read More</a>
    </p>
    
  </div>

    <div class="maincontent" id="content">
        <?php
          switch($_GET['page'])
          {
            case "mfa":
              include_once("mfa.html");
              break;
            case "lfb":
              include_once("lfb.html");
              break;
            case "tufts":
              include_once("tufts.html");
              break;
            case "bt":
              include_once("bt.html");
              break;
            default:
              include_once("clients.html");
              break;
	 }
        ?>
    </div>

<?php include('footer.php');?>
