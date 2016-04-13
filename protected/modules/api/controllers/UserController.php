<?php

class UserController extends ApiController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array();
	}
	/* 
		view user profile
	*/
	public function actionView()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$user_id = $_REQUEST['user_id'];
			$user = User::model()->find(array('condition'=>'active_status="S" and status="1" and user_id ='.$user_id)); 
			if(!empty($user))
			{
				$data				 =	$user->attributes;
				$data['locality']	 =	$user->locality->locality; 
				$data['city']		 = 	$user->city->city; 
				$data['state'] 		 = 	$user->state->state; 
				$data['country']     =  $user->country->country;
				if(!empty($user->profile_pic)){
					$data['image'] = $this->getApiUserImage($user->profile_pic);
				}else{
					$data['image'] = Yii::app()->params['SERVER'].'upload/user/default.png';
				}
				$data['added_on']	 =  $this->dateConvert($user->added_on);
				$data['updated_on']  =  $this->dateConvert($user->updated_on);
				$data['dob']		 =  $this->dobConvert($user->dob);
				
				$resp = array('code'=>$resp_code,'data'=>$data);
			}else{
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
		}else {
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type );
		$this->writeLog($resp_code);
	}
	/* 
		*Update user profile.
	*/
	public function actionUpdate()
	{
		$resp_code = $this->validateRequest();
		if($resp_code == '200')
		{
			$model = User::model()->find(array('condition'=>'active_status="S" and status=1 and user_id ='.$_REQUEST['user_id'])); 
			if(!empty($model))
			{
				$model->name			=	$_REQUEST['name'];
				$model->email			=	$_REQUEST['email'];
				$model->gender  		= 	$_REQUEST['gender'];
				$model->dob 			= 	strtotime($_REQUEST['dob']);
				$model->contact_no  	= 	$_REQUEST['contact_no'];
				$model->address 		= 	$_REQUEST['address'];
				$model->locality_id 	=	$_REQUEST['locality_id'];
				$model->city_id			=	$_REQUEST['city_id'];
				$model->state_id		=	$_REQUEST['state_id'];
				$model->country_id  	=	$_REQUEST['country_id'];
				
				//Optional Fields
				$model->updated_on=time();
				if($model->validate())
				{
					if($model->save())
					{
						//saved successfully
						if(!empty($_REQUEST['profile_pic']))
						{
							$path = 'user';
							$old_pic = '';
							 if(!empty($model->profile_pic))
							 {
								 $old_pic = $model->profile_pic;
							 }
							$profile_pic = $this->uploadPic($model->user_id,$_REQUEST['profile_pic'],$path,$old_pic);
						}
						 if(!empty($profile_pic))
						 {
							$model->profile_pic = $profile_pic;
							$model->save();
						 }
						$resp= array('code'=>$resp_code);
					}else {
						///If saved fails
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code);
					}
				}else {
					//// If validations fails
					$resp_code = $this->status_code['BAD_REQUEST'];
					$resp = array('code'=>$resp_code);
				}
			}else {
					/// If shop_id doesn't exist
					$resp_code = $this->status_code['NOT_FOUND'];
					$resp = array('code'=>$resp_code);
				}
		}else {
			$resp = array('code'=>$resp_code);
		}
	 $this->apiResponse($resp,$this->type);
	 $this->writeLog($resp_code);
	}
	/* 
		user Registration.
	*/
	public function actionRegister()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$user_count = User::model()->count(array('condition'=>'email="'.$_REQUEST['email'].'" and status = "1"'));
			if($user_count==0)
			{
				$model = new User;
				$model->name = $_REQUEST['name'];
				$model->email = $_REQUEST['email'];
				$model->username = $_REQUEST['email'];
				$password = $_REQUEST['password'];
				$model->password = md5($password);
				$model->added_on = time();
				$model->updated_on = time();
				if($model->type=='O') ///O =>shop owner
				{
					$model->active_status='H';
				}
				if($model->validate())
				{
					if($model->save())
					{
						/// saved successfully.
						if($model->name!='')
						{		
							$name = $model->name;
						}else{
							$name = $model->email;			
						}
						$subject = 'Welcome to Shopnext !!!';
						$body = "Dear <b>".$name."</b>,
								</br>
								Greetings !!!
								</br></br>
								<b>Congratulations - Your registration at Shopnext is successful. </b></br></br>
								<b> Hi,Your login credentials for shopnext as follow:- </b></br><br>
								<b>Username : ".$model->email."</b></br>
								<b>password : ".$password."</b></br><br>
								You may now start exploring shops and latest deals & offers at your nearby localities. </br></br>
								Do not forget to add your reviews,comments and ratings for the shops you get to experience with.
								</br></br></br></br>
								Registration action performed at <b>".date('h:i A')." on ".date('D, jS F Y.')."</b>
								</br></br></br>				
								<b>-Shopnext Team.</b>
								</br></br>
								";
						$to_email = $model->email;	
						$this->sendMail($subject,$body,$to_email,$name);
						$resp = array('code'=>$resp_code);
					}
					else
					{
						//if save fails.
						$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
						$resp = array('code'=>$resp_code);
					}
				}
				else
				{
					//if validate fails.
					$resp_code = $this->status_code['BAD_REQUEST'];
					$resp = array('code'=>$resp_code);
				}
			}
			else
			{
				// If user already exists. 
				$resp_code = $this->status_code['UPDATE_REQUIRED'];
				$resp = array('code'=>$resp_code);
			}
		}
		else
		{
			$resp = array('code'=>$resp_code);
		}
		
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	/* 
		Forgot password
	*/
	public function actionForgotPassword()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$model = User::model()->find(array('condition'=>'active_status="S" and status=1 and email ="'.$_REQUEST['email'].'"','order'=>'user_id desc','limit'=>1));
			$new_password = $this->generateRandomString();
			$encoded_password = md5($new_password);
			$updated_on = time();
					
			if(!empty($model))
			{
				if($model->updateByPk($model->user_id,array('password'=>$encoded_password,'updated_on'=>$updated_on)))
				{
					if($model->name!='')
					{
						$name = $model->name;
					}else{
						$name = $model->email;			
					}
					$subject = 'Forgot password request successful - Shopnext !!!';
					$body = "Dear <b>".$name."</b>,
						</br>
						Greetings !!!
						</br></br>						
						<b>This is with respect to your forgot password request.</b></br></br>
						Your password is being regenerated as :</br><br>
						<b>Username : ".$model->email."</b></br>
						<b>password : ".$new_password."</b></br><br>
						You are requested to <b>change your password</b> immediately when you login.
						</br></br></br></br>
						Action requested at <b>".date('h:i A')." on ".date('D, jS F Y.')."</b>
						</br></br></br>		
						Kindly take a note of it.
						<b>-Shopnext Team.</b>
						</br></br>
						";
					$to_email = $model->email;
					$send_mail = $this->sendMail($subject,$body,$to_email,$name);
					$resp = array('code'=>$resp_code);
				}else{
					//if save fails.
					$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
					$resp = array('code'=>$resp_code);
				}
			}else{
				/// If email_id doesn't exist
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	/* 
		*Change Password
	*/
	public function actionChangePassword()
	{
		$resp_code = $this->validateRequest();
		if($resp_code=='200')
		{
			$username = $_REQUEST['username'];
			$old_pass = $_REQUEST['old_password'];
			$new_pass = $_REQUEST['new_password'];
			$encoded_new_pass = md5($_REQUEST['new_password']);
			$confirm_pass = $_REQUEST['confirm_password'];
			$user = User::model()->find(array('condition'=>'username="'.$username.'" and password="'.md5($old_pass).'" and status = 1 and active_status = "S"'));
			$updated_on = time();
			if(!empty($user))
			{
				if($old_pass != $new_pass)
				{
					if($new_pass == $confirm_pass)
					{
						if($user->updateByPk($user->user_id,array('password'=>$encoded_new_pass,'updated_on'=>$updated_on)))
						{
							$subject = 'Password changed successfully - Shopnext !!!';
							$body = "Dear <b>".$user->name."</b>,
									</br>
									Greetings !!!
									</br></br>
									This is to inform that your password has been successfully changed.</br></br>
									Action performed at <b>".date('h:i A')." on ".date('D, jS F Y.')."</b>
									</br></br></br>
									Kindly take a note of it.
									</br></br>
									<b>-Shopnext Team.</b>
									</br></br>
									";
							$this->sendMail($subject,$body,$user->email,$user->name);	
							$resp= array('code'=>$resp_code);
						}else{
							/* if save fails. */
							$resp_code = $this->status_code['INTERNAL_SERVER_ERROR'];
							$resp = array('code'=>$resp_code);
						}
					}else{
						/* if new password and confirm password are not same. */
						$resp_code = $this->status_code['BAD_REQUEST'];
						$data = array('message'=>'Confirm password is not correct!!');
						$resp = array('code'=>$resp_code,'data'=>$data);
					}
				}else{
					/* if old password and new password are same. */
					$resp_code = $this->status_code['BAD_REQUEST'];
					$data = array('message'=>'Old password and new password cannot be same!!');
					$resp = array('code'=>$resp_code,'data'=>$data);
				}
			
			}else{
				/* If username and password doesn't exist. */
				$resp_code = $this->status_code['NOT_FOUND'];
				$resp = array('code'=>$resp_code);
			}
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);
	}
	
	/**
	 * Function for login.
	 */
	
	public function actionLogin()
	{
		$resp_code = $this->validateRequest();
		
		if($resp_code=='200')
		{
			$username = $_REQUEST['username'];
			$register_type = $_REQUEST['register_type'];
			
			if($register_type == 'S'){
				$user = User::model()->find(array('condition'=>'username="'.$username.'" and status=1 and active_status="S"'));
				
				if(!empty($user))
				{
					$profile_pic = $this->getApiUserImage($user->profile_pic);
					$data['user_id'] = $user->user_id;
					$data['name'] = $user->name;
					$data['email'] = $user->email;
					$data['profile_pic'] = $profile_pic;
					$data['type'] = $user->type;
					
					$host = Yii::app()->params['SERVER'].'api/';
					// Api call to get access token for user
					$post_fields = 'client_id=root&client_secret=Admin&type=json&user_id='.$user->user_id;
					$url = $host.'default/getAccessToken';
					
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
					$json = curl_exec($ch);
					
					$token_data = json_decode($json,true);
					$data['access_token']  = $token_data['response']['data']['token']['access_token'];
					
					$resp = array('code'=>$resp_code,'data'=>$data);
				}else{
					// If user doesn't exist
					$resp_code = $this->status_code['FORBIDDEN'];
					$resp = array('code'=>$resp_code);
				}
				
			}elseif(!empty($_REQUEST['password'])){ 
				$user = User::model()->find(array('condition'=>'username="'.$username.'" and password="'.md5($_REQUEST['password']).'" and status=1 and active_status="S"'));
				
				if(!empty($user))
				{
					$profile_pic = $this->getApiUserImage($user->profile_pic);
					$data['user_id'] = $user->user_id;
					$data['name'] = $user->name;
					$data['email'] = $user->email;
					$data['profile_pic'] = $profile_pic;
					$data['type'] = $user->type;
			
					$host = Yii::app()->params['SERVER'].'api/';
					// Api call to get access token for user
					$post_fields = 'client_id=root&client_secret=Admin&type=json&user_id='.$user->user_id;
					$url = $host.'default/getAccessToken';
					
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
					$json = curl_exec($ch);
					
					$token_data = json_decode($json,true);
					$data['access_token']  = $token_data['response']['data']['token']['access_token'];
					
					$resp = array('code'=>$resp_code,'data'=>$data);
				}else{
					// If user doesn't exist
					$resp_code = $this->status_code['FORBIDDEN'];
					$resp = array('code'=>$resp_code);
				}
			
			}else{
				// If password field is not sent
				$resp_code = $this->status_code['BAD_REQUEST'];
				$resp = array('code'=>$resp_code);
			}

			
		}else{
			$resp = array('code'=>$resp_code);
		}
		$this->apiResponse($resp,$this->type);
		$this->writeLog($resp_code);	
	}
	
}