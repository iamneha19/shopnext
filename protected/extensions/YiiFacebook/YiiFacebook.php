<?php

require_once( 'autoload.php' );

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;

class YiiFacebook extends CApplicationComponent{
    
    
    public function init()
    {
//        session_start();
        $appId = Yii::app()->params['fbAppId'];
        $appSecret = Yii::app()->params['fbAppSecret'];
        // init app with app id (APPID) and secret (SECRET)
        FacebookSession::setDefaultApplication($appId,$appSecret);
        parent::init(); 
    }
    
    public function getRedirectLoginHelper($redirect_uri){
        // login helper with redirect_uri
        $helper = new FacebookRedirectLoginHelper($redirect_uri);
        return $helper;
    }
    
    public function getSession($helper){
    
        try {
            $session = $helper->getSessionFromRedirect();
            return $session;
        } catch( FacebookRequestException $ex ) {
          // When Facebook returns an error
            echo "Facebook Exception occured, code: " . $ex->getCode();
            echo " with message: " . $ex->getMessage();
        } catch( Exception $ex ) {
          // When validation fails or other local issues
            echo 'Exception Error: ' . $ex->getMessage();
        }
    }
    
    public function getUser($session){
    
        // graph api request for user data
          $request = new FacebookRequest( $session, 'GET', '/me' );
          $response = $request->execute();
        // get response
          $graphObject = $response->getGraphObject();
          
          return $graphObject;
    }
    
    public function getUserImg($session){
    
        // graph api request for user data
          $request = new FacebookRequest( $session, 
                                          'GET', 
                                          '/me/picture',
                                          array (
                                                    'redirect' => false,
                                                    'height' => '200',          
                                                    'type' => 'normal',
                                                    'width' => '200',
                                                ) 
                                        );
          $response = $request->execute();
        // get response
          $graphObject = $response->getGraphObject();
          
          return $graphObject;
    }
    
    public function getFriends($session){
        // graph api request for user data
          $request = new FacebookRequest( $session, 
                                          'GET', 
                                          '/me/friends'
                                        );
          $response = $request->execute();
        // get response
          $graphObject = $response->getGraphObject();
          
          return $graphObject;
    }
    
    public function getLongLivedAccessToken($session){
        $longLivedSession = $session->getLongLivedSession();
        return $longLivedSession->getToken();
    }
    
    public function postFeed($session,$message){
        try {
            $response = (new FacebookRequest(
              $session, 'POST', '/me/feed', array(
                'link' => 'www.sts.in',
                'message' => $message
              )
            ))->execute()->getGraphObject();

            echo "Posted with id: " . $response->getProperty('id');

          } catch(FacebookRequestException $e) {

            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();

          } 
    }
    
    public function postImage($session){
        $img_path = Yii::app()->basePath . '/../upload/restaurant/6320_Tulips.jpg';
        try {

            // Upload to a user's profile. The photo will be in the
            // first album in the profile. You can also upload to
            // a specific album by using /ALBUM_ID as the path  
            
            
            if (!function_exists('curl_file_create')) {
                function curl_file_create($filename, $mimetype = '', $postname = '') {
                    return "@$filename;filename="
                        . ($postname ?: basename($filename))
                        . ($mimetype ? ";type=$mimetype" : '');
                }
            }

            $response = (new FacebookRequest(
              $session, 'POST', '/me/photos', array(
                'source' => curl_file_create($img_path, 'image/jpeg'),
                'message' => 'New image posted'
              )
            ))->execute()->getGraphObject();

            // If you're not using PHP 5.5 or later, change the file reference to:
            // 'source' => '@/path/to/file.name'

            echo "Posted with id: " . $response->getProperty('id');

          } catch(FacebookRequestException $e) {

            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();

          }
    }
    
    
}

