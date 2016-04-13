<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	
	/**""""
"	" "*" T)hi)s )is) the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>10));
		// if(!empty($_REQUEST) && !empty($_REQUEST))
		// {
		// 	// if(isset($_REQUEST['shop_id']) && !empty($_REQUEST['shop_id']) && isset($_REQUEST['search_input']) && !empty($_REQUEST['search_input']))
		// 	// {
		// 	// 	$this->redirect(Yii::app()->getBaseUrl().'/shop/detail/'.$_REQUEST['search_input']);
		// 	// }			
		// 	$post = $_REQUEST;
		// 	$shops = $this->getNearestShops($post);			
		// 	$dealdataProvider = '';			
		// 	$shopdataProvider = new CActiveDataProvider('Shop',array('data'=>$shops));			

		// }else{
			
			$geodata = ApplicationSessions::run()->read('user_search');

			if(empty($geodata['latitude']) || empty($geodata['longitude']))
			{
				$geodata = $this->getUserGeolocation();
			}
				
			$deals   = $this->getDeals($geodata);
			$dealdataProvider =  new CActiveDataProvider('Deal',array('data'=>$deals));
			$shops = '';
			$post = '';
			$shopdataProvider='';
		// }	
		
		$this->render('index',array(
								'shops'=>$shopdataProvider,
								'criteria'=>$post,
								'deals'=>$dealdataProvider,'limit'=>'10','offset'=>'0',
								'banners'=>$banners,					
								));
	}

	/*
		Rohan
		list shop 
	*/
	// public function actionListShop()
	// {
		// $post = array();

		// $banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>10));
		// if(!empty($_POST))
		// {
			// $post = $_POST;
		// }
		
		// $shops = $this->getNearestShops($post);			
		// $dealdataProvider = '';			
		// $shopdataProvider = new CActiveDataProvider('Shop',array('data'=>$shops));			

		// $this->render('listShop',array(
								// 'shops'=>$shopdataProvider,
								// 'criteria'=>$post,
								// 'banners'=>$banners,
								// 'limit'=>'10',
								// 'offset'=>'0',					
								// ));
	// }
	
	
	/*Amit
	*@ListShop
	*@note : /site/search.php view loaded to load data from solr 
	*/
	public function actionListShop()
	{
		$latitude  = "";
		$longitude = "";
		$current_location = "";
		$user_location_det  = ApplicationSessions::run()->read('user_location_det');
		$user_search  = ApplicationSessions::run()->read('user_search');
		if(!isset($user_location_det) || empty($user_location_det))	{
			$user_location_det = $this->getUserGeolocation();		
		}

		if(!empty($user_search['term']))
		{
			$current_location = $user_search['term'];
		}
		elseif($user_location_det['city']!='' && $user_location_det['state']!='') {
			$current_location = $user_location_det['city'].", ".$user_location_det['state'];
		}

		if(!empty($user_search['latitude']) && !empty($user_search['longitude']))
		{
			$latitude = $user_search['latitude'];
			$longitude = $user_search['longitude'];
		}
		elseif($user_location_det['latitude']!='' && $user_location_det['longitude']!='') {
			$latitude = $user_location_det['latitude'];
			$longitude = $user_location_det['longitude'];
		}
		/******  Above code is to get latitude and  longitude of user current location or searched location ******/
		
		if(isset($_GET['q']) && $_GET['q'] != ''){
			$search = str_replace(' ', '-', $_GET['q']); // Replaces all spaces with hyphens.
			$search =  preg_replace('/[^A-Za-z0-9\-]/', '', $search); // // Removes special chars.
			$search = str_replace('-', ' ', $search); // Replaces all hyphens with spaces.
			$search_input = trim($search);
			$q = trim($search);
		}else{
			$search_input = '';
			$q = '*:*'; 
		}
		 
		 
		 
		$solr_query = $q;
		$fields = array();
		
		
		$additionalParameters = array('fq' => '{!bbox pt='.$latitude.','.$longitude.' sfield=location d=50}');
		$result= Yii::app()->shopSolr->get($solr_query,0,10,$additionalParameters);
		
		if(!empty($result->response->docs))
		{
			$shops = $result->response->docs;
		}else{
			$shops = array();
		}	
		
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>10));
		
		$this->render('search',array(
								'shops'=>$shops,
								'banners'=>$banners,								
								'search_input'=>$search_input,
								'location'=>$current_location,
								'latitude' => $latitude,
								'longitude' => $longitude,	
								));
	}
	
	/* 
		This is the action to handle ajax request for deals pagination.
	*/
	public function actionAjaxDeals()
	{
		$this->layout = false;	
		if(isset($_POST) && !empty($_POST))		
		{
			$offset = $_POST['pagination'];
			
			$geodata  = ApplicationSessions::run()->read('user_location_det');		
			if(!isset($geodata) && empty($geodata))
			{		
				$geodata = $this->getUserGeolocation();	
			}
			$deals   = $this->getDeals($geodata,$offset);
			if(!empty($deals))
			{
				$dataProvider =  new CActiveDataProvider('Deal',array('data'=>$deals));
				$this->render('page',array(
									'deals'=>$dataProvider,'limit'=>'10','offset'=>$offset
									));	
			}
		}
		echo "";	
	}
	public function actionAjaxShops()
	{
		$this->layout = false;
		if(isset($_POST) && !empty($_POST))		
		{
			$offset   = $_POST['pagination'];				
			$criteria = $_POST['criteria'];			
			$shops = $this->getNearestShops($criteria,$offset);
			if(!empty($shops))
			{			
				$shopdataProvider = new CActiveDataProvider('Shop',array('data'=>$shops));			
				$this->render('page',array('shops'=>$shopdataProvider));
			}			
		}
		echo "";		
	}
	

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = false;
		$model=new SiteUserLogin;

		
		if(isset($_POST['SiteUserLogin']))
		{
			$model->attributes=$_POST['SiteUserLogin'];
			
			if($model->validate() && $model->login()) 
			{
				
				print json_encode(array('success'=>true));
				
			}else{
				
				print json_encode(array('success'=>false,'errorCode'=>$model->errorCode));
				
			}	
				
		}
		
	}
	
	public function actionRegister()
	{
		$this->layout = false;		
        $model = new User;
		$model->scenario = 'register';
		if(isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			$model->username   = $model->email;
			$model->active_status   = 'H'; 
			$model->added_on   = time();
			$model->updated_on = time();
			$password = $model->password;
			
			if(isset($_POST['User']['send_newsletter'])){
				$model->send_newsletter = 'Y';
			}else{
				$model->send_newsletter = 'N';
			}				
			
			$model->scenario = 'register';
          
			if($model->validate())
			{
				if(!empty($password))
				{
					$model->password   = md5($model->password);
					$model->repeat_password   = md5($model->repeat_password);
				}					
				if($model->save(false))
				{
				   
                   $name = $model->name;
				   $email = $model->email;
				   $code = Yii::app()->getSecurityManager()->generateRandomString(30);
			       $user_activation_model=new UserActivation;
				   $user_activation_model->user_id=$model->user_id;
				   $user_activation_model->code= $code;
				   $expiry_date = date('Y-m-d',strtotime('+1 week')); // get next week date
				   $user_activation_model->expiry_on=strtotime($expiry_date.' 23:59:59');
				   $user_activation_model->added_on = time();
				   $user_activation_model->updated_on = time();
				   if($user_activation_model->save()){
					   
					   $subject = 'Welcome to Shopnext !!!';
						$body = "Dear <b>".$name."</b>,
								<br/>
								Greetings !!!
								<br/><br/>
								<b>Congratulations - Your registration at Shopnext is successful. </b><br/><br/>
								Please activate your account by clicking on below link</ br></ br>
								</ br></ br>
								<a href='".Yii::app()->params['SERVER']."site/activation?code=".$code."'> Activation Link </a>
								</ br></ br>
								You may now start exploring shops and latest deals & offers at your nearby localities. <br/><br/>
								Do not forget to add your reviews,comments and ratings for the shops you get to experience with.
								<br/><br/>
								Registration action performed at <b>".date('h:i A')." on ".date('l, jS F Y.')."</b>
								<br/><br/><br/>				
								<b>-Shopnext Team.</b>
								<br/><br/>
								";
						$this->sendMail($subject,$body,$email,$name);
					   
					   Yii::app()->user->setFlash('msg', "Please check email to activate your account!");
					   print json_encode(array('success'=>true));	
				   }
					
				}
			}else {
				$errors = $model->getErrors();
				print json_encode(array('success'=>false,'error'=>$errors));
			}			
		}

	}
	
	public function actionActivation($code)
	{   
		$current_time = time();
		$user_id = ApplicationSessions::run()->read('user_id');

		$user_activation_model=UserActivation::model()->find('code=:code and expiry_on>:current_time and status = 1', array(':code'=>$code,':current_time'=>$current_time));
		if($user_activation_model===null){
			Yii::app()->user->setFlash('msg', "This link has been expired!");
		}else{
			$user_activation_model->code = NULL;
			$user_activation_model->status = 0;
			$user_activation_model->update(); // update activation status to database
			
			User::model()->updateByPk($user_activation_model->user_id,array('active_status'=>'S')); // Update user active status
			
			Yii::app()->user->setFlash('msg', "Your account has been activated!");
		}

		if(!empty($user_id))
		{
			$this->redirect(Yii::app()->createUrl('site/index'));
		}
		else
		{
			$this->render('activation');
		}
		
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionGoogleLogin()
    {
		$client = Yii::app()->google->init();
		$redirect_uri = Yii::app()->createAbsoluteUrl('site/googlelogin');
		$client->setRedirectUri($redirect_uri);
					
		if (isset($_GET['code'])) {
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}

		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  $client->setAccessToken($_SESSION['access_token']);
		} else {
		  $authUrl = $client->createAuthUrl();
		}
		
		if ($client->getAccessToken() && isset($_GET['url'])) {
		  $_SESSION['access_token'] = $client->getAccessToken();
		}
		
		if (isset($authUrl)) {
		  header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));  

		} else 
		{
			$oauth2 = new Google_Service_Oauth2($client);
			$user_profile = $oauth2->userinfo->get();
			$name 	= $user_profile['name'];
			$email 	= $user_profile['email'];
			$google_id = $user_profile['id'];
			$gender    = $user_profile['gender'];
			$img_url   = $user_profile['picture'];
			$refresh_token = $client->getRefreshToken();
			$access_token = $client->getAccessToken();
			$sessionToken = json_decode($access_token);
			$token = $sessionToken->access_token;
			$unixtimestamp = time();
			$social_media = array(
								'socialmedia_id'=>$google_id,
								'email_id'=>$email,
								'type'=>'Google',
								'token'=>$token,
								'added_on'=>$unixtimestamp,
								'updated_on'=>$unixtimestamp,
							);
			$model = User::model()->find('username=:email', array(':email'=>$email));
			if($model===null)
			{
				$model = new User;
				$model->name = $name;
				$model->email = $email;
				$model->username = $email;
				$model->profile_pic = $img_url;
				$model->gender = ($gender == 'male') ? 'M' : 'F';
				$model->status = 1;
				$model->register_type = 'Google';
				$model->added_on = time();
				$model->updated_on = time();
				$model->scenario = 'social_media_register';

				if($model->save())
				{
					$social_media['user_id'] = $model->user_id;
					if($this->actionAddSocialMediaDetails($social_media))
					{						
						$this->actionSendWelcomeMail(array('email'=>$model->username,'name'=>$model->name));
					}
				}else{
					print_r ($model->getErrors());
				} 
					
		    }else
			{				
				$data = UserSocialmedia::model()->findByAttributes(array('user_id'=>$model->user_id,'type'=>'Google'));
				
				if(empty($data))
				{
					$social_media['user_id'] = $model->user_id;
					$this->actionAddSocialMediaDetails($social_media);
				}else{
					$data->token = $token;
					$data->refresh_token = $refresh_token;
					$data->updated_on = time();
					$data->save();
				}				
				if($img_url!='' && $model->profile_pic=='')
				{
					$model->profile_pic = $img_url;
					$model->save();
				}	
			}
			$profile_pic = Yii::app()->baseUrl."/themes/classic/img/default.png";
			if($model->profile_pic)
			{				
				if(filter_var($model->profile_pic, FILTER_VALIDATE_URL))
				{
					$profile_pic = $model->profile_pic;					
				}else
				{
					if(file_exists(Yii::app()->baseUrl.'/upload/profile_pic/'.$model->profile_pic))
					{
						$profile_pic = Yii::app()->baseUrl.'/upload/profile_pic/'.$model->profile_pic;
					}					
				}
			}
			ApplicationSessions::run()->write('user_id', $model->user_id);
			ApplicationSessions::run()->write('user_email', $model->email);
			ApplicationSessions::run()->write('username', $model->username);
			ApplicationSessions::run()->write('fullname', $model->name);
			ApplicationSessions::run()->write('profile_pic', $profile_pic);
			
			echo "<script>
						window.close();
				window.opener.location.reload();
					</script>";
			
		}
    }
	
	public function  actionFblogin()
    {            
        if(!empty($_POST)) 
		{
            $token = $_POST['access_token'];  // to store access_token in database
			$name = $_POST['name'];
			$email = $_POST['user_email'];
			$fb_id = $_POST['user_id'];
			$gender = $_POST['gender'];
			$img_url = $_POST['profile_image'];
			$unixtimestamp = time();
			$social_media = array(
									'socialmedia_id'=>$fb_id,
									'email_id'=>$email,
									'type'=>'Facebook',
									'token'=>$token,
									'added_on'=>$unixtimestamp,
									'updated_on'=>$unixtimestamp,
								);
            $model = User::model()->find('username=:email', array(':email'=>$email));
            
			if($model===null)
			{
				$model=new User;
				$model->name = $name;
				$model->email = $email;
				$model->username = $email;
				$model->profile_pic = $img_url;
				$model->gender = ($gender == 'male') ? 'M' : 'F';
				$model->status = 1;
				$model->register_type = 'Facebook';
				$model->added_on = time();
				$model->updated_on = time();
				$model->scenario = 'social_media_register';
				
				if($model->save())
				{	
					$social_media['user_id'] = $model->user_id;
					if($this->actionAddSocialMediaDetails($social_media))
					{						
						$this->actionSendWelcomeMail(array('email'=>$model->username,'name'=>$model->name));
					}					
					
				}else{
					print_r ($model->getErrors());
				}
                        
			}else
			{				
				$data = UserSocialmedia::model()->findByAttributes(array('user_id'=>$model->user_id,'type'=>'Facebook'));
				
				if(empty($data))
				{
					$social_media['user_id'] = $model->user_id;
					$this->actionAddSocialMediaDetails($social_media);
				}else{
					$data->token = $token;
					$data->updated_on = time();
					$data->save();
				}
				if($img_url!='' && $model->profile_pic=='')
				{
					$model->profile_pic = $img_url;
					$model->save();
				}	
		    } 
			$profile_pic = Yii::app()->baseUrl."/themes/classic/img/default.png";
			if($model->profile_pic)
			{				
				if(filter_var($model->profile_pic, FILTER_VALIDATE_URL))
				{
					$profile_pic = $model->profile_pic;					
				}else
				{
					if(file_exists(Yii::app()->baseUrl.'/upload/profile_pic/'.$model->profile_pic))
					{
						$profile_pic = Yii::app()->baseUrl.'/upload/profile_pic/'.$model->profile_pic;
					}					
				}
			}
			ApplicationSessions::run()->write('user_id', $model->user_id);
			ApplicationSessions::run()->write('user_email', $model->email);
			ApplicationSessions::run()->write('username', $model->username);
			ApplicationSessions::run()->write('fullname', $model->name);
			ApplicationSessions::run()->write('profile_pic', $profile_pic); 				
			print "200"; 
			
		} else {
                $loginUrl = $helper->getLoginUrl(array('scope' => 'email,read_stream,user_friends'));
                echo("<script> top.location.href='".$loginUrl."'</script>");
		}
	}
	
	public function actionAddSocialMediaDetails($details)
	{
		$social_media=new UserSocialmedia;
		$social_media->attributes = $details;
		return $social_media->save();
	}
	
	public function actionTwitterLogin()
	{
		print "Ooops...!!!!";
	}
	
	public function actionSendWelcomeMail($userdata)
	{
		$email_id = $userdata['email'];
		$name     = $userdata['name'];
		
		if( empty($name) || $name=='') {
			$name = $email_id;
		}	
		if(isset($userdata['password']) && !empty($userdata['password'])) 
		{
			$password = $userdata['password'];
			$userdata = "<hr><br/><br/>
						<u>Your account details are : </u> </ br><br/>
						<b>Username : </b> <i>".$email_id."</i> <br/><br/>
						<b>Password : </b> <i>".$password."</i> <br/><br/>
						<hr>";
		}else
		{		
			$userdata = "";
		}
		$subject = 'Welcome to Shopnext !!!';
		$body = "Dear <b>".$name."</b>,
				<br/>
				Greetings !!!
				<br/><br/>
				<b>Congratulations - Your registration at Shopnext is successful. </b><br/><br/>
				".$userdata."</ br></ br>
				You may now start exploring shops and latest deals & offers at your nearby localities. <br/><br/>
				Do not forget to add your reviews,comments and ratings for the shops you get to experience with.
				<br/><br/>
				Registration action performed at <b>".date('h:i A')." on ".date('l, jS F Y.')."</b>
				<br/><br/><br/>				
				<b>-Shopnext Team.</b>
				<br/><br/>
				";
		$this->sendMail($subject,$body,$email_id,$name);
	}
	
	public function actionForgotPassword()
	{
		$return = false;
		$username = $_POST['txt_mail'];	
		
		if(!empty($username))
		{
			$umodel = User::model()->find(array('condition'=>'username="'.$username.'" and status="1"','limit'=>1));
			if(empty($umodel))
			{
				$msg      = "Invalid username !";
				$umodel = User::model()->find(array('condition'=>'username="'.$username.'"','limit'=>1));
				
				if(!empty($umodel))
				{
					
					$msg      = "Could not request forgot password. This user account is deleted.  !";
				}
				else if($umodel->active_status=="S"){
					$msg      = "Could not request forgot password. This user account is deactivated.  !";
				}
			}else if($umodel->active_status=="H" )
			{
				$msg      = "Could not request forgot password. This user account is deactivated.  !";
			}
			else if($umodel->status=="0")
			{
				$msg      = "Could not request forgot password. This user account is deleted.  !";
			}
			else
			{
				$random_pass = $this->generateRandomString('10');
				$encrypted   = md5($random_pass);			
				$updated_on  = time();				
				$update 	 = $umodel->updateByPk($umodel->user_id,array('password'=>$encrypted,'updated_on'=>$updated_on));
				if($update)
				{
					if($umodel->name!='')
					{
						$name = $umodel->name;
					}else{
						$name = $umodel->email;			
					}
					$subject = 'Forgot password request successful - Shopnext !!!';
					$body = "Dear <b>".$name."</b>,
							<br/>Greetings !!!<br/>						
							<b>This is with respect to your forgot password request.</b><br/><br/><hr>
							<u>Your password is being regenerated as :</u><br/>
							<b>Username : ".$umodel->username."</b><br/>
							<b>password : ".$random_pass."</b><br/>
							You are requested to <b>change your password</b> immediately when you login.
							<br/><hr>
							Action requested at <b>".date('h:i A')." on ".date('l, jS F Y.')."</b>
							<br/>
							Kindly take a note of it.
							<br/>
							<b>-Shopnext Team.</b>
							<br/><br/>
							";
					$this->sendMail($subject,$body,$umodel->email,$name);
					$return = '200';
					$msg = "Your password has been reset successfully and sent to your email id!";
					
				}else{
					$return = '400';
					$msg = $update;
				}			
			}
		}else{
			$msg = "Username is required!";
			$return = "false";
		}	
	 echo json_encode(array('result'=>$return,'msg'=>$msg));
		
	}

	
	/* 
		Common function for like/unlike functionality
	*/
	public function actionLikeStatus()
	{	
		$return  = 'false';
		if(isset($_POST) && Controller::loggedInStatus()){
			$type = $_POST['type'];
			$model_type = ucfirst($type);
			$id = $_POST[$type.'_id'];
			$fetch_id = $type.'_id';
			$user_id = $_POST['user_id'];
			$like_detail = Likes::model()->find(array('condition'=>'user_id='.$user_id.' and '.$fetch_id.' ='.$id));
			if(!empty($like_detail))
			{
				if($like_detail->status=="1")
				{
					if(Likes::model()->updateByPk($like_detail->like_id,array('status'=>"0")))
					{
						$count = Likes::model()->count(array('condition'=>'"'.$fetch_id.'"="'.$id.'" and status="1"'));
						
						$model_type::model()->updateByPk($id,array('total_likes'=>$count));
						$return  = '200::U';
					}
				}else{
					if(Likes::model()->updateByPk($like_detail->like_id,array('status'=>"1")))
					{
						$count = Likes::model()->count(array('condition'=>'"'.$fetch_id.'"="'.$id.'" and status="1"'));
						$model_type::model()->updateByPk($id,array('total_likes'=>$count));
						$return  = '200::L';
					}
				}
			}else{
				$model = new Likes;
				$model->$fetch_id = $id;
				$model->user_id = $user_id;
				$model->added_on=time();
				$model->updated_on = time();
				if($model->save())
				{
					$count = Likes::model()->count(array('condition'=>'"'.$fetch_id.'"="'.$id.'" and status="1"'));
					
					$model_type::model()->updateByPk($id,array('total_likes'=>$count));
					$return  = '200::L';
				}
			}
		}else{
			$return  = 'false';
		}	
		
		echo $return;
	}
	
	public function actionAutocompleteShop()
	{
		if(!empty($_GET['term']))
			$term = $_GET['term'];
		else
			$term = null;
		
		Controller::autocompleteShopJson($term);	
	}	
	
	public function actionLogLocation()
	{
		$status = '0';$msg = 'An error occurred !!';
		if(isset($_POST) && !empty($_POST))
		{
			$address = addslashes($_POST['address']);
			$latitude = addslashes($_POST['lat']);
			$longitude = addslashes($_POST['lng']);
			
			$criteria = new CDbCriteria();
			$search = str_replace(' ','%',$address);
			$criteria->select = " SQRT(
								POW(69.1 * (latitude - $latitude), 2) +
								POW(69.1 * ($longitude - longitude) * COS(latitude / 57.3), 2)) AS distance";
			$criteria->condition = "status=1  AND active_status='S' and geo_location like '%$search%'";			
			$criteria->having = "distance < 1 ";
			$LocationSearch = LocationSearch::model()->findAll($criteria);
			$city = City::model()->findAll(array("condition"=>"city like '%".$search."%' "));
			$locality = Locality::model()->findAll(array("condition"=>"locality like '%".$search."%'"));
			
			if(empty($LocationSearch) && empty($city) && empty($locality))
			{			
				$model = new LocationSearch;
				$model->geo_location = $address;
				$model->latitude = $latitude;
				$model->longitude = $longitude;	
				$model->added_by   = (isset($this->user_id)) ? $this->user_id : '';
				$model->added_on   = time();
				if($model->save())
				{
					$status = '1';
					$msg = 'Logged successfully';
				}
			}else{
				$status = '1';
				$msg = 'Exists already';
			}
			$user_location_det  = ApplicationSessions::run()->read('user_location_det');
			if(!isset($user_location_det) || empty($user_location_det))
			{		
				$user_location_det  = isset(Yii::app()->request->cookies['geolocation']) ? Yii::app()->request->cookies['geolocation']->value : '';
				$user_location_det  = get_object_vars(json_decode($user_location_det));
			}
			if(isset($user_location_det) && !empty($user_location_det))
			{
				$user_location_det = array_merge($user_location_det,array('latitude'=>$latitude,''=>$longitude));
				$cookiename = new CHttpCookie('geolocation', json_encode($user_location_det));
				$cookiename->expire = time()+60*60*24*180; 
				Yii::app()->request->cookies["'".$cookiename."'"] = $cookiename;
				ApplicationSessions::run()->write('user_location_det', $user_location_det);	
			}			
		}		
		
		print json_encode(array('status'=>$status,"success"=>$msg));
	}
	/*Garima
	*@actionLocationAutosuggest
	*@return : location list based on search term & criteria (and user's current location if available)
	*/
	public function actionLocationAutosuggest()
	{
		$request_data = array_map(function($obj){ return trim(addslashes($obj)); },$_GET);
		$term = $request_data['term'];
		$term = preg_replace('/[^A-Za-z0-9\-]/', '', $term);
		$lat  = $request_data['lat'];
		$lng  = $request_data['lng'];
		$term = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $term);
		$term = str_replace("  "," ",$term);
		//if lat-lng details empty -> try to get the details from getUserGeolocation
		if($lat=='' || $lng=='')
		{
			$geodata = $this->getUserGeolocation();		
			$lat  = $geodata['latitude'];
			$lng  = $geodata['longitude'];
		}
		
		if($lat!='' && $lng!='')
		{
			$distance = 50;
			$location_search = Yii::app()->db->createCommand()
				->select("id,geo_location as name,latitude,longitude,'location_search' as entity_type,SQRT(
									POW(69.1 * (latitude - $lat), 2) +
									POW(69.1 * ($lng - longitude) * COS(latitude / 57.3), 2)) AS distance")
				->from('location_search ')
				->where('status="1" and active_status="S" and geo_location like "%'.$term.'%" ')
				->having('distance < '.$distance)
				->order('distance,name')
				->getText();
				
			$locality = Yii::app()->db->createCommand()
				->select("l.locality_id as id,concat(l.locality,', ',c.city,', ',s.state) as name,l.latitude,l.longitude,'locality' as entity_type,SQRT(
									POW(69.1 * (l.latitude - $lat), 2) +
									POW(69.1 * ($lng - l.longitude) * COS(l.latitude / 57.3), 2)) AS distance")
				->from('locality l')
				->where('l.status="1" and l.active_status="S" and (l.locality like "%'.$term.'%" or concat(l.locality," ",c.city," ",s.state) like "%'.$term.'%" OR concat(l.locality," ",c.city) like "%'.$term.'%" OR concat(l.locality," ",s.state) like "%'.$term.'%") ')
				->join('city c','l.city_id=c.city_id')
				->join('state s','s.state_id=c.state_id')
				->having('distance < '.$distance)
				->order('distance,name')
				->getText();

			$city = Yii::app()->db->createCommand()
				->select("c.city_id as id,concat(c.city,', ',s.state) as name,c.latitude,c.longitude,'city' as entity_type,SQRT(
									POW(69.1 * (c.latitude - $lat), 2) +
									POW(69.1 * ($lng - c.longitude) * COS(c.latitude / 57.3), 2)) AS distance")
				->from('city c')
				->where('c.status="1" and c.active_status="S" and (c.city like "%'.$term.'%" OR s.state like "%'.$term.'%" OR concat(c.city," ",s.state) like "%'.$term.'%")')
				->join('state s','s.state_id=c.state_id')
				->having('distance < '.$distance)
				->order('distance,name')
				->union($location_search)
				->union($locality)
				->order('name')
				->limit('10')
				->queryAll();
		}
		
		if(!isset($city) || empty($city))
		{		
			$location_search = Yii::app()->db->createCommand()
				->select("id,geo_location as name,latitude,longitude,id as entity_type")
				->from('location_search ')
				->where('status="1" and active_status="S" and geo_location like "%'.$term.'%" ')
				->order('name')
				->getText();
				
			$locality = Yii::app()->db->createCommand()
				->select("l.locality_id as id,concat(l.locality,', ',c.city,', ',s.state) as name,l.latitude,l.longitude,'locality' as entity_type")
				->from('locality l')
				->where('l.status="1" and l.active_status="S" and (l.locality like "%'.$term.'%" or concat(l.locality," ",c.city," ",s.state) like "%'.$term.'%" OR concat(l.locality," ",c.city) like "%'.$term.'%" OR concat(l.locality," ",s.state) like "%'.$term.'%") ')
				->join('city c','l.city_id=c.city_id')
				->join('state s','s.state_id=c.state_id')
				->order('name')
				->getText();

			$city = Yii::app()->db->createCommand()
				->select("c.city_id as id,concat(c.city,', ',s.state) as name,c.latitude,c.longitude,'city' as entity_type")
				->from('city c')
				->where('c.status="1" and c.active_status="S" and (c.city like "%'.$term.'%" OR s.state like "%'.$term.'%" OR concat(c.city," ",s.state) like "%'.$term.'%")')
				->join('state s','s.state_id=c.state_id')
				->union($location_search)
				->union($locality)
				->order('name')
				->limit('10')
				->queryAll();
		}
		
		$html = '';
		foreach($city as $row)
		{
			$label = str_ireplace($term , "<b>".substr($row['name'], stripos($row['name'],$term), strlen($term))."</b>", $row['name']);
			$html .= '<div class="search_item" data-lat="'.$row['latitude'].'" data-lng="'.$row['longitude'].'" data-entity-type="'.$row['entity_type'].'" data-entity-id="'.$row['id'].'">'.$label.'</div>';
		}
		$html .= '<span class="detect_current">Detect current location</span>';
		echo $html;			
	}
	
	/*Amit
	*@actionSolrLocationAutosuggest
	*@return : location list based on search term & criteria (and user's current location if available)
	*/
	public function actionSolrLocationAutosuggest()
	{
		$term = trim($_GET['term']);
		$lat  = trim($_GET['lat']);
		$lng  = trim($_GET['lng']);
		//$term = 'tan';
		$solr_query = 'name:'.$term;
		$fields = array('name','latitude','longitude','type');
		
		// $solr_library = new solrLibrary;
		// $core = 'location';
		// $filter_query = '{!geofilt pt='.$lat.','.$lng.' sfield=location d=50}';
		// $response = $solr_library->getResult($core,$solr_query,$filter_query,$fields);
		
		$additionalParameters = array(/*'fq' => '{!bbox pt='.$lat.','.$lng.' sfield=location d=50}',*/
									  'fl'=>implode(",",$fields),
                                    );
		$result= Yii::app()->locationSolr->get($solr_query,0,10,$additionalParameters);
		
		$html = '';
		foreach($result->response->docs as $location)
		{
			$label = str_ireplace($term , "<b>".substr($location->name, stripos($location->name,$term), strlen($term))."</b>", $location->name);
			$html .= '<div class="search_item" data-lat="'.$location->latitude.'" data-lng="'.$location->longitude.'" data-entity-type="'.$location->type.'">'.$label.'</div>';
		}
		$html .= '<span class="detect_current">Detect current location</span>';
		echo $html;
	}
	/*Garima
	*@actionShopAutosuggest
	*@return : shops list based on search term & criteria (and user's current location if available)
	*/
	public function actionShopAutosuggest()
	{
		$request_data = array_map(function($obj){ return trim(addslashes($obj)); },$_GET);
		$term = $request_data['term'];
		$lat  = $request_data['lat'];
		$lng  = $request_data['lng'];
		$location    = $request_data['location'];
		$entity_id   = $request_data['entity_id'];
		$entity_type = $request_data['entity_type'];
		$term = preg_replace('/[^A-Za-z0-9\-]/', '', $term);
		$location = preg_replace('/[^A-Za-z0-9\-]/', '', $location);
		//if lat-lng details empty -> try to get the details from getUserGeolocation
		if($lat=='' || $lng=='')
		{
			$geodata = $this->getUserGeolocation();		
			$lat  = $geodata['latitude'];
			$lng  = $geodata['longitude'];
		}
		//create common search criteria
		$criteria = new CDbCriteria();
		$criteria->alias ='s';
		$criteria->join = "LEFT OUTER JOIN category c ON s.category_id = c.category_id";
		$criteria->join .= " LEFT OUTER JOIN locality l on l.locality_id = s.locality_id";
		$criteria->join .= " LEFT OUTER JOIN city ct on ct.city_id = s.city_id";
		$criteria->join .= " LEFT OUTER JOIN state st on st.state_id = s.state_id";
		$criteria->condition = "s.status=1 and s.active_status='S' and (s.name like '%".$term."%' OR c.category like '%".$term."%' )";
		$criteria->limit = "10";		
		if($location!='')
		{
			$criteria->condition .= " OR s.address like '%".$location."%' 
									  OR l.locality like '%".$location."%'
									  OR ct.city like '%".$location."%'
									  OR st.state like '%".$location."%'";
		}
		if($entity_type=='city' && $entity_id!='')
		{
			$criteria->condition .= " OR s.city_id='$entity_id'";
		}
		if($entity_type=='locality' && $entity_id!='')
		{
			$criteria->condition .= "OR s.locality_id='$entity_id'";
		}		
		//if lat-lng details are available->formulate the query accordingly
		if($lat!='' && $lng!='')
		{	
			$distance = 10;		
			$criteria1 = clone $criteria;//clone the common criteria n formulate for lat-lng and distance
			$criteria1->select = "s.shop_id,s.name,SQRT(
									POW(69.1 * (s.latitude - $lat), 2) +
									POW(69.1 * ($lng - s.longitude) * COS(s.latitude / 57.3), 2)) AS distance";
			$criteria1->order  = "distance, name ";	
			$criteria1->having = "distance < $distance ";
			$model = Shop::model()->findAll($criteria1);
			if(empty($model))
			{
				$criteria2 = clone $criteria;
				$criteria2->having = "distance < ".$distance+10;//if not found within 10 distance -> increase 10+10				
				$model = Shop::model()->findAll($criteria2);
			}
			if(empty($model))
			{	
				$criteria3 = clone $criteria;	
				$criteria3->having = "distance < ".$distance+20;//if not found within 10+10 distance -> increase 10+20		
				$model = Shop::model()->findAll($criteria3);
			}
			if(empty($model))
			{
				$criteria4 = clone $criteria;
				$criteria4->having = "distance < ".$distance+100;//if not found within 10+20 distance -> increase 10+100		
				$model = Shop::model()->findAll($criteria4);
			}
		}
		// if not found as per lat-lng and distance criteria OR
		// if  lat-lng is not available  -> query it regardless of geometric location.		
		if(!isset($model) || empty($model))
		{
			$criteria->select = "s.shop_id,s.name";			
			$criteria->order = "name";				
			$model = Shop::model()->findAll($criteria);
		}
		$html = '';
		foreach($model as $row)
		{
			$label = str_ireplace($term , "<b>".substr($row['name'], stripos($row['name'],$term), strlen($term))."</b>", $row['name']);
			$html .= '<div class="search_item" data-id="'.$row['shop_id'].'">'.$label.'</div>';
		}
		echo $html;		
	}
	
	/*Amit
	*@actionSolrShopAutosuggest
	*@return : return result from solr
	*/
	public function actionSolrShopAutosuggest()
	{
		 $term = trim($_GET['term']);
		//$term = 'tan';
		$solr_query = 'name:'.$term;
		$fields = array('id','name');
		
		// $solr_library = new solrLibrary;
		// $core = 'shop';
		// $filter_query = '';
		// $response = $solr_library->getResult($core,$solr_query,$filter_query,$fields);
		// $shops = $response;
		$additionalParameters = array(	'fl'=>implode(",",$fields),
										'group'=>'true',
										'group.field'=>'name',
										'group.main'=>'true'
									);
		$result= Yii::app()->shopSolr->get($solr_query,0,10,$additionalParameters);
	
		$html = '';
		if(!empty($result->response->docs)){
			foreach($result->response->docs as $shop){
			
				$label = str_ireplace($term , "<b>".substr($shop->name, stripos($shop->name,$term), strlen($term))."</b>", $shop->name);
				$html .= '<div class="search_item" data-id="'.$shop->id.'">'.$label.'</div>';
				
			}
		}
		
		echo $html;	
	}

	
	/* 
		*Neha
		*sending mail for frontend.
	*/
	public function actionSendMail()
	{
		$return = false;
		$from_email = $_POST['from_email'];
		$to_email = $_POST['to_email'];
		if(!empty($_POST['from_name'])){
			$from_name = $_POST['from_name'];
		}else{
			$from_name = '';
		}
		$subject = $_POST['subject'];
		$body = $_POST['body'];
		if(!empty($to_email) && !empty($body) && !empty($from_email))
		{
			$model = new Email;
			if($from_email=='')
			{
				$model->from_email = ApplicationSessions::run()->read('user_email');
			}else{
				$model->from_email = $from_email;
			}
			$model->to_email = $to_email;
			
			$model->to_id = '';
			if(isset($from_email_name))
			{
				$model->from_id = ApplicationSessions::run()->read('user_id');
			}else{
				$model->from_id='';
			}
			$model->added_on = time();
			$model->updated_on = time();
			$site_url 	= Yii::app()->params['SITE_URL'];
			$subject 	= $subject;
			$body 		= $body;
			$to_email 	= $model->to_email;
			if($from_email!='')
			{
				$from_email = $model->from_email;
			}else{
				$from_email = ApplicationSessions::run()->read('user_email');
			}
			if($this->sendMail($subject,$body,$to_email,$to_name=null,$from_email,$from_name))
			{
				if($model->save())
				{
					$return = '200';
				}
			}else{
				$return = '400';
			}
		}else{
			$return = "false";
		}
		echo $return;
	}
	
	/* 
		*Amit
		*Function to check email doesn't exists in register .
	*/
	public function actionEmailCheck()
	{
		$this->layout = false;
		if(isset($_POST)){
			$user_count = User::model()->count(array('condition'=>'status = "1" and email="'.$_POST['email'].'"'));
			if($user_count)
			{
				echo 'false';
			}else{
				echo 'true';
			}
			
		}	
	}
	
	public function actionSubscribeNewsletter()
    {
		if(isset($_POST))
		{
			$email_id = $_POST['email_id'];
			$subscribe_count = Newsletter::model()->count(array('condition'=>'status = "1" and email_id="'.$_POST['email_id'].'"'));
			if($subscribe_count)
			{
				$return = '256';
			}
			else
			{
				$model = new Newsletter;
				$model->email_id = $email_id;
				$model->added_on = time();
				$model->updated_on = time();
				$email_id = $model->email_id;
				
				if($model->validate())
				{
					if($model->save())
					{
						$return = '200';
					}
					else
					{
						$return = '400';
					}
				}
			}		
		}
		echo $return;
	}
	
	public function actionSearch()
	{
		$latitude  = "";
		$longitude = "";
		$current_location = "";
		$user_location_det  = ApplicationSessions::run()->read('user_location_det');
		$user_search  = ApplicationSessions::run()->read('user_search');
		if(!isset($user_location_det) || empty($user_location_det))	{
			$user_location_det = $this->getUserGeolocation();		
		}

		if(!empty($user_search['term']))
		{
			$current_location = $user_search['term'];
		}
		elseif($user_location_det['city']!='' && $user_location_det['state']!='') {
			$current_location = $user_location_det['city'].", ".$user_location_det['state'];
		}

		if(!empty($user_search['latitude']) && !empty($user_search['longitude']))
		{
			$latitude = $user_search['latitude'];
			$longitude = $user_search['longitude'];
		}
		elseif($user_location_det['latitude']!='' && $user_location_det['longitude']!='') {
			$latitude = $user_location_det['latitude'];
			$longitude = $user_location_det['longitude'];
		}
		/******  Above code is to get latitude and  longitude of user current location or searched location ******/
		
		if(isset($_GET['q']) && $_GET['q'] != ''){
			$search = str_replace(' ', '-', $_GET['q']); // Replaces all spaces with hyphens.
			$search =  preg_replace('/[^A-Za-z0-9\-]/', '', $search); // // Removes special chars.
			$search = str_replace('-', ' ', $search); // Replaces all hyphens with spaces.
			$search_input = trim($search);
			$q = trim($search);
		}else{
			$search_input = '';
			$q = '*:*'; 
		}
		 
		 
		$solr_query = $q;
		$fields = array();
		
		
		$additionalParameters = array('fq' => '{!bbox pt='.$latitude.','.$longitude.' sfield=location d=50}');
		$result= Yii::app()->shopSolr->get($solr_query,0,10,$additionalParameters);
		
		if(!empty($result->response->docs))
		{
			$shops = $result->response->docs;
		}else{
			$shops = array();
		}	
		
		$banners = Banner::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'banner_id DESC','limit'=>10));
		
		$this->render('search',array(
								'shops'=>$shops,
								'banners'=>$banners,								
								'search_input'=>$search_input,
								'location'=>$current_location,
								'latitude' => $latitude,
								'longitude' => $longitude,	
								));
	}
	
	public function actionAjaxSolrShop()
	{
		$this->layout = false;
		if(isset($_POST)){
			if(isset($_POST['q']) && $_POST['q'] != ''){
				$q = trim($_POST['q']);
			}else{
				$q = '*:*'; 
			}	
			 
			$solr_query = $q;
			$latitude = $_POST['latitude']; 
			$longitude = $_POST['longitude'];
			$start = $_POST['start'];
			
			
			$additionalParameters = array('fq' => '{!bbox pt='.$latitude.','.$longitude.' sfield=location d=50}');
			$result= Yii::app()->shopSolr->get($solr_query,$start,10,$additionalParameters);
			
			$this->render('_search',array(
									'shops'=>$result->response->docs								
									));
		}			
	}
	
	public function actionIndexAllLocation()
	{
		// ini_set('memory_limit', '-1');
		// $location_search = Yii::app()->db->createCommand()
				// ->select('id,geo_location as name,latitude,longitude,status,active_status, id as type')
				// ->from('location_search ')
				// ->order('name')
				// ->getText();
				
		// $locality = Yii::app()->db->createCommand()
			// ->select("l.locality_id as id,concat(l.locality,', ',c.city,', ',s.state) as name,l.latitude,l.longitude,l.status,l.active_status,'locality' as type")
			// ->from('locality l')
			// ->where('l.status="1" and l.active_status="S"')
			// ->join('city c','l.city_id=c.city_id')
			// ->join('state s','s.state_id=c.state_id')
			// ->order('name')
			// ->getText();

		// $locations = Yii::app()->db->createCommand()
			// ->select("c.city_id as id,concat(c.city,', ',s.state) as name,c.latitude,c.longitude,c.status,c.active_status,'city' as type")
			// ->from('city c')
			// ->join('state s','s.state_id=c.state_id')
			// ->union($location_search)
			// ->union($locality)
			// ->order('name')
			// ->queryAll();
			
			// $location_data = array();
		// foreach($locations as $key => $location){
			
			// $location_data[$key] = $location;
			// if(!empty($location['latitude']) && !empty($location['longitude']))
			// {
				// $cordinates = $location['latitude'].','.$location['longitude'];
			// }else{
				// $cordinates = '0.00,0.00';
			// }	
			
			// $location_data[$key]['location']=$cordinates;
	
			// if(is_numeric($location['type'])){
				// $type = 'search';
			// }else{
				// $type =$location['type'];
			// }		
			// $location_data[$key]['type']=$type;
			
			// $location_data[$key]['id'] = $type.'_'.$location['id'];
		// }		
		
		// Yii::app()->locationSolr->updateMany($location_data);
		
		$location_data = Controller::getLocalionSearchIndexData(1);
		Yii::app()->locationSolr->updateOne($location_data);
		
		print_r($location_data);
	}

	/*
		function to write search term session
	*/
	public function actionWriteSearchSession()
	{
		$arr = array('term'=>$_GET['term'],'latitude'=>$_GET['lat'],'longitude'=>$_GET['lng']);
		ApplicationSessions::run()->write('user_search',$arr);
		return true;
	}
}
	