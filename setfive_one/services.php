<?php
  include('functions.php');
  $TITLE = "Services";
  $TITLE.=checkSubPageTitle();
  include_once("header.php");
?>
  <div><h1><a href="/services" class="title_header_link" onClick="return loadPage('services_overview');">services</a></h1></div> 
  <div class="sidebar">

    <p><strong><a href="services/web-development" class="title_link_sub"  onClick="return loadPage('web-development');">Web Application Development</a></strong></p>
    <p>
        The desktop is dead. We specialize in developing both enterprise and consumer facing web apps... <a href="services/webdev"   onClick="return loadPage('web-development');">Read More</a>
    </p>
      
    <p><strong><a href="services/rapid-prototyping" class="title_link_sub" onClick="return loadPage('rapid-prototyping');">Rapid Prototyping</a></strong></p>
    <p>
        The hardest part of building a great product is often the first iteration... <a href="services/rapid-prototyping" onClick="return loadPage('rapid-prototyping');">Read More</a>
    </p>
      
    <p><strong><a href="services/symfony-framework" class="title_link_sub" onClick="return loadPage('symfony-framework');">Symfony (PHP)</a></strong></p>
    <p>
        We do Symfony projects. We build 'em, customize 'em, and optimize 'em... <a href="services/symfony-framework" onClick="return loadPage('symfony-framework');">Read More</a>
    </p>
    
    <p><strong><a href="services/mashups" class="title_link_sub" onClick="return loadPage('mashups');">Mashups</a></strong></p>
    <p>
        Not to sound clich&#233;, but nothing says "web 2.0" like a mashup. Google, Twitter, Yahoo - we rock them all... <a href="services/mashups" onClick="return loadPage('mashups');">Read More</a>
    </p>
    
    <p><strong><a href="services/performance-consulting" class="title_link_sub" onClick="return loadPage('performance-consulting');">Performance Consulting</a></strong></p>
    <p>
        Slow website giving you the blues? We'll analyze your architecture and identify key trouble areas for your site's performance... <a href="services/performance-consulting" onClick="return loadPage('performance-consulting');">Read More</a>
    </p>

    <p><strong><a href="services/salesforce-integrations" class="title_link_sub" onClick="return loadPage('salesforce-integrations');">Salesforce Integrations</a></strong></p>
    <p>
        Having an awesome CRM is great - except when it doesn't interface with your other systems. That's where we come in... <a href="services/salesforce-integrations" onClick="return loadPage('salesforce-integrations');">Read More</a>
    </p>
    
  </div>

    <div class="maincontent" id="content">
<?php
switch($_GET['page'])
{
  case "web-development":
    include_once('web-development.html');
    break;
  case "rapid-prototyping":
    include_once('rapid-prototyping.html');
    break;
  case "symfony-framework":
    include_once('symfony-framework.html');
    break;
  case "mashups":
    include_once('mashups.html');
    break;
  case "performance-consulting":
    include_once('performance-consulting.html');
    break;
  case "salesforce-integrations":
    include_once('salesforce-integrations.html');
    break;
  default:
    include_once("services.html");
    break;
}
?>
    </div>

<?php include('footer.php');?>
