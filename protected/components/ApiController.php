<?php
/**
 * ApiController is the customized base controller class for api module.
 * All api module controller classes for this application should extend from this base class.
 */
class ApiController extends CController
{
	/**
	 * API set up variables.
	 */
	
	public $status_code = array(
								'SUCCESS'=>'200',
								'ALREADY_VALIDATED'=>'208',
								'ALREADY_EXIST'=>'302',
								'NOT_MODIFIED'=>'304',
								'BAD_REQUEST'=>'400',
								'UNAUTHORIZED'=>'401',
								'PAYMENT_REQUIRED'=>'402',
								'FORBIDDEN'=>'403',
								'NOT_FOUND'=>'404',
								'METHOD_NOT_ALLOWED'=>'405',
								'REQUEST_TIMEOUT'=>'408',
								'UPDATE_REQUIRED'=>'426',
								'INTERNAL_SERVER_ERROR'=>'500',
								'SERVICE_UNAVAILABLE'=>'503',
								'RECORD_DELETED'=>'301'
								);
	public $method = array(
							0=>'list',
							1=>'create',
							2=>'view',
							3=>'update',
							4=>'delete',
							5=>'forgotPassword',
							6=>'login',
							7=>'changePassword',
							8=>'category',
							9=>'autosuggest',
							10=>'statistic',
							11=>'createStatistic',
							12=>'getAccessToken',
							13=>'register',
							);
							
	public $common_method = array(
							0=>'list',
							1=>'view',
							2=>'forgotPassword',
							3=>'login',
							5=>'autosuggest',
							6=>'category',
							7=>'register'
							);
	public $shop_owner_method = array(
							'shop'=>array('create','update','delete','statistic'),
							'deal'=>array('create','update','delete'),
							'product'=>array('create','update','delete'),
							);						
	public $restricted_method = array(
							0=>'create',
							1=>'update',
							2=>'delete',
							3=>'changePassword',
							4=>'statistic',
							5=>'createStatistic',
							);
							
	public $api_key = array('M'=>'@BCD');
	
	public $client_id = 'root';
	
	public $client_secret = 'Admin';
	
	public $device,$type,$access_key;
	
	public function init() 
	{        
		Yii::app()->errorHandler->errorAction='api/shop/error';
		parent::init();
		
		if(!empty($_REQUEST['device']))
		{
			$this->device = $_REQUEST['device'];
		}
		else
		{
			$this->device = "M";
		}
		
		if(!empty($_REQUEST['type']))
		{
			$this->type = $_REQUEST['type'];
		}	
		
		if(!empty($_REQUEST['access_key']))
		{
			$this->access_key = $_REQUEST['access_key'];
		}
	}
	
	/*
	 * function to generate key to check request key
	 */
	public function generateKey($api_key,$method)
	{
		if(in_array($method,$this->common_method))
		{
			$count = AccessToken::model()->count(array('condition'=>'access_token="'.$api_key.'"'));
		}
		else if(in_array($method,$this->restricted_method))
		{
			$count = AccessToken::model()->count(array('condition'=>'access_token="'.$api_key.'" and user_id!=""'));
		}
		else
		{
			$count = 0;
		}
		
		return $count;
	}
	
	/*
	 * function to validate request for parameters.
	 */
	public function validateRequest()
	{
		$api_key = $this->access_key;
		$method = $this->action->Id;
		$controller = Yii::app()->controller->id;		
		
		if($method!='getAccessToken')
		{
			$encrypted_key = $this->generateKey($api_key,$method);
		}
		else
		{
			$encrypted_key = '';
		}
		$owner_verify = $this->ownerValidate();
		$resp_code = $this->status_code['SUCCESS'];
        
		if(!in_array($method,$this->method))
		{
			$resp_code = $this->status_code['METHOD_NOT_ALLOWED'];
		}
		else if($encrypted_key==0 && $method!='getAccessToken')
		{
			$resp_code = $this->status_code['UNAUTHORIZED'];
		}
		else if(!$owner_verify)
		{
			$resp_code = $this->status_code['FORBIDDEN'];
		}		
		else
		{
			switch($controller.'-'.$method)
			{
				case 'shop-view':
									if(empty($_REQUEST['shop_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
									}
									break;
				case 'shop-create':
									if(empty($_REQUEST['name']) or empty($_REQUEST['user_id']) or empty($_REQUEST['category_id']) or empty($_REQUEST['description']) or empty($_REQUEST['address']) or empty($_REQUEST['city_id']) or empty($_REQUEST['state_id']) or empty($_REQUEST['locality_id']) or empty($_REQUEST['zip_code']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
									}									
									break;
				case 'shop-update':
									if(empty($_REQUEST['shop_id']) or empty($_REQUEST['name']) or empty($_REQUEST['user_id']) or empty($_REQUEST['description']) or empty($_REQUEST['category_id']) or empty($_REQUEST['address']) or empty($_REQUEST['city_id']) or empty($_REQUEST['state_id']) or empty($_REQUEST['locality_id']) or empty($_REQUEST['zip_code']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
									}									
									break;
				case 'shop-delete':
									if(empty($_REQUEST['shop_id']) or empty($_REQUEST['user_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
									}									
									break;
				case 'shop-autosuggest':
									if(empty($_REQUEST['term']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];										
									}	
									break;
				case 'product-view':
									if(empty($_REQUEST['product_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
									}
									break;
				case 'product-create':
									if(empty($_REQUEST['name']) or empty($_REQUEST['product_category_id']) or empty($_REQUEST['shop_id']) or empty($_REQUEST['price']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
									}
									break;
				case 'product-update':
									if(empty($_REQUEST['product_id']) or empty($_REQUEST['name']) or empty($_REQUEST['product_category_id']) or empty($_REQUEST['shop_id']) or empty($_REQUEST['price']))
									{	
										$resp_code = $this->status_code['BAD_REQUEST'];
									}
									break;
				case 'product-delete':
									if(empty($_REQUEST['product_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
									}
									break;
				case 'deal-view':
								if(empty($_REQUEST['deal_id']))
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								break;					
				case 'deal-create':
								if(empty($_REQUEST['shop_id']) || empty($_REQUEST['title']) || empty($_REQUEST['desc']) || empty($_REQUEST['start_date']) || empty($_REQUEST['end_date']) || empty($_REQUEST['amount']) || empty($_REQUEST['type']) || empty($_REQUEST['is_hot_deal']))
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								break;
				case 'deal-update':
								if(empty($_REQUEST['deal_id']) || empty($_REQUEST['shop_id']) || empty($_REQUEST['title']) || empty($_REQUEST['desc']) || empty($_REQUEST['start_date']) || empty($_REQUEST['end_date']) || empty($_REQUEST['amount']) || empty($_REQUEST['type']))
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								break;
				case 'deal-delete':
								if(empty($_REQUEST['deal_id']))
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								break;
				case 'user-view':
								if(empty($_REQUEST['user_id']))
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								break;
				case 'user-register':
								if(empty($_REQUEST['name']) || empty($_REQUEST['email']) || empty($_REQUEST['password']))
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								break;
				case 'user-update':
								if(empty($_REQUEST['user_id']) || empty($_REQUEST['name']) || empty($_REQUEST['email']) || empty($_REQUEST['gender']) || empty($_REQUEST['dob']) || empty($_REQUEST['contact_no']) || empty($_REQUEST['address']) || empty($_REQUEST['locality_id']) || empty($_REQUEST['city_id']) || empty($_REQUEST['state_id']) || empty($_REQUEST['country_id']))
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								break;
				case 'user-login':
								if(empty($_REQUEST['username']) || empty($_REQUEST['register_type']))
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								elseif($_REQUEST['register_type'] != 'S' && $_REQUEST['register_type'] != 'R')
								{
									$resp_code = $this->status_code['BAD_REQUEST'];
								}
								break;
				case 'user-forgotPassword':
											if(empty($_REQUEST['email']))
											{
												$resp_code = $this->status_code['BAD_REQUEST'];
											}
											break;
				case 'user-changePassword':
											if(empty($_REQUEST['username']) || empty($_REQUEST['old_password']) || empty($_REQUEST['new_password']) || empty($_REQUEST['confirm_password']))
											{
												$resp_code = $this->status_code['BAD_REQUEST'];
											}
											break;
				case 'blogComment-create':
										if(empty($_REQUEST['blog_id']) || empty($_REQUEST['user_id']) || empty($_REQUEST['comment']))
										{
											$resp_code = $this->status_code['BAD_REQUEST'];
										}
										break;
				case 'blogComment-delete':
										if(empty($_REQUEST['blog_comment_id']))
										{
											$resp_code = $this->status_code['BAD_REQUEST'];
										}
										break;
				case 'review-create':
									if(empty($_REQUEST['shop_id']) && empty($_REQUEST['product_id']) && empty($_REQUEST['deal_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
										
									}elseif(empty($_REQUEST['user_id']) || empty($_REQUEST['comment']) || empty($_REQUEST['review_type']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
										
									}	
									break;
				case 'review-list':
									if(empty($_REQUEST['shop_id']) && empty($_REQUEST['product_id']) && empty($_REQUEST['deal_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
										
									}	
									break;
				case 'review-delete':
									if(empty($_REQUEST['review_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
										
									}	
									break;
				case 'rating-create':
									if(empty($_REQUEST['shop_id']) || empty($_REQUEST['user_id']) || empty($_REQUEST['rating']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
										
									}	
									break;
				case 'rating-view':
									if(empty($_REQUEST['shop_id'])|| empty($_REQUEST['user_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];
										
									}	
									break;
				case 'shop-statistic':
									if(empty($_REQUEST['shop_id']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];										
									}	
									break;
				case 'shop-createStatistic':
									if(empty($_REQUEST['shop_id']) || empty($_REQUEST['date']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];										
									}	
									break;
				case 'default-getAccessToken':
									if(empty($_REQUEST['client_id']) || empty($_REQUEST['client_secret']))
									{
										$resp_code = $this->status_code['BAD_REQUEST'];										
									}	
									break;
				default :
							$resp_code = $this->status_code['SUCCESS'];
							break;
			}
			
		}
		
		return $resp_code;
	}
	
	 /**Amit
	* @generateRandomString : To generate random string
	* @PARAMS : $length 
	* @RETURN : $string
	*/
	function generateRandomString($length = 10) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	/*
	Garima
	* @sendMail : common send mail functionality to be used throughout the project
	* @PARAM : subject,body,to_email,to_name,from_email,from_name,reply_to,attachment
	* @RETURN : boolean ( true or false ) after successful mail send
	*/
	public function sendMail($subject,$body,$to_email,$to_name,$from_email= null,$attachment = null)
	{
		$content_type="text/html";
		$charset="UTF-8";
		$to_bcc = '';
		$session_var = ApplicationSessions::run()->read('user_email');
		if($from_email=='')
		{
			$from_email = (!empty($session_var))?$session_var:'rohan.kadam@sts.in';
		}
		// }else{
			// $from_email = ApplicationSessions::run()->read('user_email');
		// }
		
		
		//$from_email = 'admin@timespro.com';
		$from_name = 'Shopnext';
	  	$body = nl2br($body);
	    $headers = 	"From:$from_email" . "\r\n" .
	        		"Reply-To: $from_email" . "\r\n" .
	        		'X-Mailer: PHP/' . phpversion()."\r\n";
		$headers  .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		try
		{
			//mail($to_email, $subject, $body, $headers); 
	    	Yii::import('application.extensions.phpmailer.JPhpMailer');
	    	$mail = new JPhpMailer;
			$mail->IsSMTP();
			$mail->Mailer = "smtp";
			$mail->SMTPSecure = "ssl"; 
			$mail->Host='smtp.gmail.com';   
			$mail->Port='465';   
			$mail->SMTPAuth = true;
			$mail->Username = "rohan.kadam@sts.in";
			$mail->Password = "Donald@123";
			$mail->SetFrom($from_email,$from_name);
			if($to_bcc)
				$mail->AddBCC($to_bcc);
			if($attachment)
			{
				if(is_array($attachment))
				{
					foreach ($attachment as $key => $value) 
					{
						$mail->AddAttachment($value);
					}	
				}
				else
					$mail->AddAttachment($attachment);
			}
			$mail->Subject =$subject;
			$mail->MsgHTML($body);
			$mail->AddAddress($to_email,$to_name);
			$mail->Send();
			return true;
	  	}
		catch (phpmailerException $e)
		{
			//echo $e->errorMessage(); //Pretty error messages from PHPMailer
			return false;
		}
		catch (Exception $e) 
		{
			//echo $e->getMessage(); //Boring error messages from anything else!
			return false;
		}
	}
	
	public function ownerValidate()
	{
		$api_key = $this->access_key;
		$method = $this->action->Id;
		$controller = Yii::app()->controller->id;
		$return = true;
		if(array_key_exists($controller,$this->shop_owner_method) && in_array($method,$this->shop_owner_method[$controller]))
		{
			$user_data = AccessToken::model()->findByAttributes(array('access_token'=>$api_key,'status'=>1));

			if(!empty($user_data->user))
			{
				$user_type = $user_data->user->type;			
				if($user_type=='O')
				{
					if($controller=='shop' && isset($_REQUEST['shop_id']) && !empty($_REQUEST['shop_id']))
					{					
						$shop_owner = Shop::model()->findByPk($_REQUEST['shop_id']);
						if($shop_owner->user_id != $user_data->user_id)
						{
							$return = false;
						}					
					}else if($controller=='deal' && isset($_REQUEST['deal_id']) && !empty($_REQUEST['deal_id']))
					{
						$deal_data = Deal::model()->findByPk($_REQUEST['deal_id']);
						if($deal_data->shop->user_id != $user_data->user_id)
						{
							$return = false;
						}	
					}else if($controller=='product' && isset($_REQUEST['product_id']) && !empty($_REQUEST['product_id']))
					{
						$product_data = Product::model()->findByPk($_REQUEST['product_id']);
						if($product_data->shop->user_id != $user_data->user_id)
						{
							$return = false;
						}	
					}
				}
			}	
			else{
				$return = false;
			}
		}
		return $return;
	}
	/*
	 * function to move image on server
	 */
	public function uploadPic($id='',$pic='',$path,$old_pic='')
	{
		$ext = 'jpg';
		// $byte_code = base64_decode(trim('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'));
		// echo "pre<br/><br/>";
		// echo $pic."<br/><br/>";
		
		$byte_code = base64_decode(str_replace(" ","+",$pic));
		// echo "atf".$byte_code;
		// exit;
		$base_path = Yii::app()->basePath;
		
		if(!is_dir($base_path."/../upload/".$path))
		{
			mkdir($base_path."/../upload/".$path,0777,true);
		}
	
		if(substr($byte_code, 0, 4) == "\x89PNG") 
		{ 
			$ext = 'png';
		}
		else if(substr($byte_code, 0, 2) == "\xFF\xD8")
		{
			$ext = 'jpg';
		}
		else if (substr($byte_code, 0, 4) == "GIF8")
		{
			$ext = 'gif';
		}
		else
		{
			$ext = Yii::app()->params['NOT_FOUND'];
		}
		
		if($ext!='404')
		{
			if(!empty($old_pic))
			{
				@unlink($base_path."/../upload/".$path."/".$old_pic);
			}
			
			file_put_contents($base_path."/../upload/".$path."/".$id.".".$ext,$byte_code );
			$resp = $id.".".$ext;
		}
		else
		{
			$resp = '';
		}
		
		return $resp;
	}
	
	/*
	 * 	function to generate xml from array
	 */
	public function arrayToXml($data, $rootNodeName = 'data', $xml=null)
	{
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
		ini_set ('zend.ze1_compatibility_mode', 0);
		}
	 
		if ($xml == null)
		{
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}
		
		foreach($data as $key => $value)
		{
			if (is_numeric($key))
			{
				$key = "item_". (string) $key;
			}
			 
			$key = preg_replace('/[^a-z]/i', '', $key);
			 
			if (is_array($value))
			{
				$node = $xml->addChild($key);
				$this->arrayToXml($value, $rootNodeName, $node);
			}
			else
			{
				$value = htmlentities($value);
				$xml->addChild($key,$value);
			}
		}
		
		return $xml->asXML();
	}
		
	/*
	 * function to give response in xml or json
	 */
	public function apiResponse($resp_arr,$resp_type='')
	{
		if($resp_type=='xml')
		{	
			header('Content-Type: application/xml');
			$resp = $this->arrayToXml($resp_arr,'response');
		}
		else
		{
			header('Content-Type: application/json');
			
			if(!empty($_REQUEST['callback']))
			{
				$resp = $_REQUEST['callback']."(".json_encode(array('response'=>$resp_arr)).")";
			}
			else
			{
				$resp = json_encode(array('response'=>$resp_arr));
			}
		}
		
		echo $resp;
	}
	
	/*
	 * function to write api log in database
	 */
	public function writeLog($resp_code)
	{
		$arr = $this->status_code;
		$action = $this->action->Id;
		$controller = Yii::app()->controller->id;
		
		$model = new Log;
		$model->method_name = $action;
		$model->section = $controller;
		$model->response_code = $resp_code;
		$model->description = str_replace("_"," ",array_search($resp_code,$arr));
		$model->added_on = time();
		$model->updated_on = time();
		$model->save();
	}
	
	/**Amit
	* @function to return user image url. 
	* @PARAMS : $profile_pic 
	* @RETURN : url.
	*/
	public function getApiUserImage($profile_pic) 
	{
		$img = '';
          
		if($this->validate_url($profile_pic)){
			$img = $profile_pic;
		}else{
			$img = Yii::app()->params['SERVER'].'upload/user/'.$profile_pic;
		}
		
		return $img;	
	}
	
	/**Amit
	* @function to check user exist. 
	* @PARAMS : $user_id 
	* @RETURN : boolean.
	*/
	public function isUserExist($user_id) 
	{
		$user_count = User::model()->count(array('condition'=>'user_id="'.$user_id.'" and status=1 and active_status="S"'));
		
		if($user_count)
		{
			return true;
		}else{
			return false;
		}	
	}
	
	/**Amit
	* @function to check shop exist. 
	* @PARAMS : $shop_id 
	* @RETURN : boolean.
	*/
	public function isShopExist($shop_id) 
	{
		$shop_count = Shop::model()->count(array('condition'=>'shop_id="'.$shop_id.'" and status=1 and active_status="S"'));
		
		if($shop_count)
		{
			return true;
		}else{
			return false;
		}	
	}
	
	/**Rohan
	* @function to generate access token. 
	* @PARAMS : $client_id , $client_secrete , $user_id [ optional ] 
	* @RETURN : $token string.
	*/
	protected function generateToken($client_id,$client_secret,$user_id='') 
	{
		if(empty($user_id))
		{
			$token = md5($client_id.$client_secret);
		}
		else
		{
			$token = md5($client_id.$client_secret.$user_id);
		}
		
		return $token;	
	}
	
	public static function dateConvert($date)
	{
		$date = date("d-M-Y h:i:s a",$date);
		return $date;
	}
	
	public static function dateFromTimestamp($date,$format = 'd-m-Y')
	{
		$date = date($format,$date);
		return $date;
	}
	
	public static function timeFromTimestamp($date,$format = 'h:i:s a')
	{
		$date = date($format,$date);
		return $date;
	}	
	
	public static function datetimeFromTimestamp($date,$format = 'd-m-Y h:i:s a')
	{
		$date = date($format,$date);
		return $date;
	}	
		/* 	
			*Generic function to convert date to ('d-m-y') format from timestamp
		*/
	public static function dobConvert($date)
	{
		$date = date("d-m-Y",$date);
		return $date;
	}
	
	/*
	* @Garima 
	* @validate_url : to validate urls
	* @PARAM : Boolean values true -> valid, false -> invalid
	*/
	public function validate_url($url) 
	{
		if(!filter_var($url, FILTER_VALIDATE_URL))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}