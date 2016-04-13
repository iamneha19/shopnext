<?php

require_once( 'autoload.php' );

class YiiGoogle extends CApplicationComponent{
    
    
    public function init()
    {
        parent::init();
        $client_id = Yii::app()->params['googleClientId'];
        $client_secret = Yii::app()->params['googleClientSecret'];
        $redirect_uri = Yii::app()->createAbsoluteUrl('site/googlelogin');
        
        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->addScope(array("https://www.googleapis.com/auth/plus.login",
                                "https://www.googleapis.com/auth/plus.me",
                                "https://www.googleapis.com/auth/userinfo.email",
                                "https://www.googleapis.com/auth/userinfo.profile")
                        );
         
        
        return $client;
    }
}

