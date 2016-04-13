<?php

class DefaultController extends ApiController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionApiCall()
	{
		// echo $byte;
		// exit;
		// $host = 'http://192.168.0.8/php/shopnext/api/';
		$host = 'http://localhost/shopnext/api/';
		//Please note : use below access_keys
		//		shop owner: 9c905b07c207af726a06fd632dc18df8
		//		site user: dbdde28ff6a2e094c0f4e9cfe465b61f
		/**
		  OAuth2 Access token  api calls start
		 */
		$data = 'client_id=root&client_secret=Admin&type=json&user_id=1';
		$url = $host.'default/getAccessToken';
		
		/**
		  Shop api calls start
		 */
		 // $data = 'access_key=9c905b07c207af726a06fd632dc18df8&name=Phablets&category_id=1&address=Village road,Bhandup(w)&state_id=1&city_id=3&locality_id=1&zip_code=400078&user_id=1&description=&latitude=&longitude=&contact_no=&pic=&type=json';
		// $data = 'access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&shop_id=44&name=D Mart Vashi abc&category_id=1&address=Village road,Bhandup(w)&state_id=1&city_id=3&locality_id=1&zip_code=400078&user_id=1&description=&latitude=&longitude=&contact_no=&pic=&type=json';
		// $url = $host.'shop/list?access_key=9c905b07c207af726a06fd632dc18df8&locality_id=&city_id=&state_id=&type=json';
		// $url = $host.'shop/create';
		// $url = $host.'shop/update';
		// $url = $host.'shop/view?access_key=9c905b07c207af726a06fd632dc18df8&shop_id=34&type=json';
		// $url = $host.'shop/delete?access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&shop_id=44&user_id=1&type=';
		// $url = $host.'shop/category?access_key=9c905b07c207af726a06fd632dc18df8&type=json';
		// $url = $host.'shop/autosuggest?access_key=9c905b07c207af726a06fd632dc18df8&term=D ma&type=json';
		// $url = $host.'shop/statistic?access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&shop_id=34';
		// $url = $host.'shop/createStatistic?access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&shop_id=34&date=2015-02-18&user_id=&latitude=&longitude=&location=&other_details=';

		/**
		  Shop api calls end
		 */
		
		/**
		  Review api calls start
		 */
		// $data = 'access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&shop_id=34&comment=Good product to buy&review_type=P&user_id=4&type=json';
		$url = $host.'review/list?access_key=9c905b07c207af726a06fd632dc18df8&shop_id=34&product_id=&deal_id=&type=json';
		// $url = $host.'review/create';
		/**
		  Review api calls end
		 */
		 
		/**
		  Deal api calls start
		 */
		// $data = 'access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&shop_id=5&title=15% off on no minimum purchases&desc=On occasion of Diwali flat 20% off on all products&code=&start_date=03/14/2015&end_date=03/20/2015&amount=20&deal_type=P&type=json';
		// $data = 'access_key=9c905b07c207af726a06fd632dc18df8&deal_id=110&shop_id=5&title=25% off on no minimum purchases&desc=On occasion of Diwali flat 20% off on all products&code=&start_date=02/10/2015&end_date=02/15/2015&amount=20&deal_type=P&type=json';
		// $url = $host.'deal/list?access_key=9c905b07c207af726a06fd632dc18df8&type=xml';
		// $url = $host.'deal/create';
		// $url = $host.'deal/view?access_key=9c905b07c207af726a06fd632dc18df8&deal_id=107&type=json';
		// $url = $host.'deal/update';
		// $url = $host.'deal/delete?access_key=60870ec6c92662ba415f50122c9f9789&deal_id=110&type=';
		/**
		  Deal api calls end
		 */
		 
		/**
		/**
		  Product api calls start
		 */
		// $data = 'access_key=9c905b07c207af726a06fd632dc18df8&name=Create api product by garima&shop_id=6&product_category_id=1&price=123&description=test desc&pic='.$byte.'&type=json';
		// $data = 'access_key=9c905b07c207af726a06fd632dc18df8&product_id=21&name=Update product by garima&shop_id=6&product_category_id=1&price=199&description=update test desc&pic='.$byte.'&type=json';
		// $url = $host.'product/list?access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&shop_id=5&type=json';
		// $url = $host.'product/create';
		// $url = $host.'product/view?access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&product_id=6&type=json';
		// $url = $host.'product/update';
		// $url = $host.'product/delete?access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&product_id=19&type=xml';
		// $url = $host.'product/category?access_key=9c905b07c207af726a06fd632dc18df8&type=json';
		/**
		  Product api calls end
		 */
		 
		/**
		  User api calls start
		 */
		// $data = 'access_key=9c905b07c207af726a06fd632dc18df8&name=Amit N&email=amit.nalawade@sts.in&password=12345678&type=json';
		// $data = 'access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&user_id=51&name=Amit Na&email=amit.nalawade@sts.in&gender=M&dob=12-04-1990&contact_no=9898989898&profile_pic='.$byte.'&address=Test api address&locality_id=1&city_id=1049&state_id=16&country_id=1&send_newsletter=N&type=json';
		// $data = 'access_key=60870ec6c92662ba415f50122c9f9789&username=amit.nalawade@sts.in&old_password=12345678&new_password=87654321&confirm_password=87654321';
		//$data = 'access_key=9c905b07c207af726a06fd632dc18df8&username=amit.nalawade@sts.in&password=Admin@123&register_type=R';
		// $url  = $host.'user/register';
		// $url  = $host.'user/view?access_key=9c905b07c207af726a06fd632dc18df8&user_id=51&type=json';
		// $url  = $host.'user/update';
		$url  = $host.'user/forgotPassword?access_key=9c905b07c207af726a06fd632dc18df8&email=amit.nalawade@sts.in';
		// $url  = $host.'user/changePassword';
		//$url  = $host.'user/login';
		/**
		  User api calls end
		 */
		 
		 /**
		  Blog Comment api calls start
		 */
		// $data = 'access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&blog_id=21&user_id=4&comment=Test api comment reply&parent_id=103&type=json';
		// $url = $host.'blogComment/create';
		// $url = $host.'blogComment/list?access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&blog_id=21&type=json'; 
		// $url = $host.'blogComment/delete?access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&blog_comment_id=24&type=';
		/**
		  Blog Comment api calls end
		 */
		
		/**
		 Locality api call start 
		*/
		 // $url = $host.'locality/list?access_key=9c905b07c207af726a06fd632dc18df8&city_id=1049&type=json';
		/** 
		Locality api calls end
		*/
			
		/**
		 City api call start 
		*/
		 // $url = $host.'city/list?access_key=9c905b07c207af726a06fd632dc18df8&state_id=2&type=json';
		/** 
		City api calls end
		*/
		
		/**
		 State api call start 
		*/
		 // $url = $host.'state/list?access_key=9c905b07c207af726a06fd632dc18df8&type=json';
		/** 
		State api calls end
		*/
		
		/**
		Rating api calls start
		*/
		// $data = 'access_key=dbdde28ff6a2e094c0f4e9cfe465b61f&shop_id=5&user_id=1&rating=3&type=json';
		// $url = $host.'rating/create';
		// $url = $host.'rating/view?access_key=9c905b07c207af726a06fd632dc18df8&shop_id=5&user_id=2&type=json';
		/**
		Rating api calls end
		*/
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		echo $output;
	}
	
	public function actionGenerateApiKeys()
	{
		$controllers = Metadata::app()->getControllers('api');
		$to_remove = array('Default');
		$controllers = array_diff($controllers, $to_remove);
		$controller_actions = array();
		$api_keys_array = array();
		foreach($controllers as $controller) {
			$controller_actions [$controller] = Metadata::app()->getActionswithfunction($controller,'api');
		}
		$shared_key = '@BCD';
		foreach($controller_actions as $controller=>$actions){
			foreach($actions as $action)
			{
				$api_keys_array[$controller][$action]= sha1(lcfirst($controller).lcfirst($action).$shared_key);
			}			
		}
		print"<pre>";
		print_r($api_keys_array);
	}
	
	/**
	 * Authorize user to get request token.
	 */
	public function actionGetAccessToken()
	{
		$provider = 'Shopnext';
		$resp_code = $this->validateRequest();
		
		if($resp_code == '200')
		{
			$client_id = $_REQUEST['client_id'];
			$client_secret = $_REQUEST['client_secret'];
			
			$request_config = array('Shopnext'=>array('client_id'=>$client_id,'client_secret'=>$client_secret,'enabled'=>true));
			Yii::import('ext.oauth2.OAuth2');
	 
			$config = require Yii::getPathOfAlias('ext.oauth2') . '/config.php';
	 
			if ( ! isset($config[$provider]))
			{
				throw new CHttpException('invalid request.');
			}
	 
			try
			{
				$oauth2 = OAuth2::create($provider, $request_config[$provider]);
				
				if(!empty($_REQUEST['user_id']))
				{
					$oauth2->user_id = $_REQUEST['user_id'];// $_REQUEST['user_id'];
				}
				else
				{
					$oauth2->user_id = '';
				}
				
				$token = '';
			}
			catch (OAuth2_Exception $e)
			{
				throw new CHttpException($e->getMessage());
			}
			
			if(!empty($_REQUEST['user_id']))
			{
				$resp_code = $this->authorize($_REQUEST['user_id']);
		 
				if ($resp_code==200)
				{
					$code = strtolower(md5(rand() . rand() . rand() . uniqid()));
				
					try
					{
						$token = $oauth2->getToken($code);
			 
						if ( ! $token)
						{
							throw new CHttpException('500', $provider . ' - get token error.');
						}
					}
					catch (OAuth2_Exception $e)
					{
						throw new CHttpException($e->getMessage());
					}
				}
				else
				{
					$resp = array('code'=>$resp_code);
				}
			}
			else
			{
				$code = strtolower(md5(rand() . rand() . rand() . uniqid()));
				
				try
				{
					$token = $oauth2->getToken($code);
		 
					if ( ! $token)
					{
						throw new CHttpException('500', $provider . ' - get token error.');
					}
				}
				catch (OAuth2_Exception $e)
				{
					throw new CHttpException($e->getMessage());
				}
			}
			
			$resp = array('code'=>$resp_code,'data'=>array('token'=>$token));
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
	
	public function authorize($user_id)
	{
		$user_data = User::model()->findByPk($user_id);
		
		if(empty($user_data))
		{
			$code= 404;
		}
		else
		{
			$code= 200;
		}
		
		return $code;
	}
	
	public function actionAccessToken()
	{
		$client_id = $_REQUEST['client_id'];
		$client_secret = $_REQUEST['client_secret'];
		if(!empty($_REQUEST['user_id']))
		{
			$user_id = $_REQUEST['user_id'];
		}
		else
		{
			$user_id = '';
		}
		
		$token = $this->generateToken($client_id,$client_secret,$user_id);
		$token_data = AccessToken::model()->find(array('condition'=>'access_token="'.$token.'"'));

		if(empty($token_data))
		{
			$model = new AccessToken;
			$model->client_id = $client_id;
			$model->client_secret = $client_secret;
			$model->user_id = $user_id;
			$model->access_token = $token;
			$model->added_on = time();
			$model->updated_on = time(); 
			$model->save();
		}
		
		echo $token;
	}
	
	
}