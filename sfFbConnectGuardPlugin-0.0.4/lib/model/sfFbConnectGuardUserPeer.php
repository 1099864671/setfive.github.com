<?php

class sfFbConnectGuardUserPeer extends BasesfFbConnectGuardUserPeer
{
  /**
   * Try and retrieve a sfGuardUser by their Facebook UID
   *
   * @param unknown_type $fbId
   */
  public static function getSfGuardUserByFbId( $fbId ){
    $c = new Criteria();
    $c->add( self::FB_UID, $fbId );
    $c->addJoin( self::USER_ID, sfGuardUserPeer::ID );
    return sfGuardUserPeer::doSelectOne( $c );
  }
  
  public static function createFbUser( $fbId ){
    $user = new sfGuardUser();
    $user->setUsername( $fbId );
    $user->save();
    return $user->getId();
  }
  
  public static function linkSfFbUser( $fbId, $userId ){
    $fb = new sfFbConnectGuardUser();
    $fb->setUserId( $userId );
    $fb->setFbUid( $fbId );
    $fb->save();
  }
  
  public static function isUserFbConnected( $userId ){
    $c = new Criteria();
    $c->add( self::USER_ID, $userId );
    return self::doCount( $c ) == 1 ? true : false;
  }
  
}
