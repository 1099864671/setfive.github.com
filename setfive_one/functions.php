<?php
function checkSubPageTitle()
{
  $title='';
  if(isset($_GET['page']))
  {
    //Special cases for BT, LFB and MFA, all caps
    if($_GET['page']=='mfa')
      return ' > MFA';
    if($_GET['page']=='lfb')
      return ' > The LFB';
    if($_GET['page']=='BT')
      return ' > BT';

    $title=str_replace('-',' ',$_GET['page']);
    $title=' > '.ucwords($title);

    return $title;

  }
  return '';
}