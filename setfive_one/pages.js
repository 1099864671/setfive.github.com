function loadPage(page)
{
  switch(page)
  {
    case "clients_overview":
      doLoad('Clients','clients');
      break;
    case "mfa":
      doLoad('Clients','mfa');
      break;
    case "lfb":
      doLoad('Clients','lfb');
      break;
    case "tufts":
      doLoad('Clients','tufts');
      break;
    case "bt":
     doLoad('Clients','bt');
     break;
    case "our-story":
      doLoad('About','our-story');
      break;
    case "technology":
      doLoad('About','technology');
      break;
    case "core-team":
      doLoad('About','core-team');
      break;
    case "web-development":
      doLoad('Services','web-development');
      break;
    case "rapid-prototyping":
      doLoad('Services','rapid-prototyping');
      break;
    case "symfony-framework":
      doLoad('Services','symfony-framework');
      break;
    case "mashups":
      doLoad('Services','mashups');
      break;
    case "performance-consulting":
      doLoad('Services','performance-consulting');
      break;
    case "salesforce-integrations":
      doLoad('Services','salesforce-integrations');
      break;
    case "services_overview":
      doLoad('Services','services');
  }
  return false;

}

function doLoad(section,page)
{
    $("#content").hide();
    $("#content").html("&nbsp;.");
    $("#content").fadeIn('slow').load("/"+page+".html");
    window.location.hash=page;
    // Couple special cases for the url
    if(page=='lfb')
    {
      document.title="{5} Setfive Consulting - "+section+" > The LFB";
      return;
    }
    if(page=='mfa')
    {
      document.title="{5} Setfive Consulting - "+section+" > MFA";
      return;
    }
    if(page=='bt')
    {
      document.title="{5} Setfive Consulting - "+section+" > BT";
      return;
    }
    document.title="{5} Setfive Consulting - "+section+" > "+titleString(page);
}
function titleString(s){
  s=new String(s);
  return s.substring(0,1).toUpperCase()+s.substring(1).replace("-"," ");
}

$(document).ready(function(){
  if(window.location.hash)
    loadPage(new String (window.location.hash).slice(1));
});