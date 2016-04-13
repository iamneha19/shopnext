<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return Yii::app()->GetAccessRule->getOwner();
		// return array();
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if(isset(Yii::app()->session['owner_id']))
		{
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
		{		
			$this->layout = "//layouts/b4login";
			$model = new OwnerLogin;			
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			if(isset($_POST['OwnerLogin']))
			{
				$model->attributes = $_POST['OwnerLogin'];
				
				if($model->validate() && $model->login())
					{
						$this->redirect(Yii::app()->getModule('owner')->user->returnUrl);
					}
			}			
			$this->render('login',array('model'=>$model));
		}
	}
        
    public function actionView()
	{
		$id = ApplicationSessions::run()->read('owner_id');
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	/* update profile */
	 public function actionUpdate()
	{
		$id    = ApplicationSessions::run()->read('owner_id');
		$model = $this->loadModel($id);
				
		if(!is_dir("upload/owner/"))
			 	mkdir("upload/owner/" , 0777,true);
		if(isset($_POST['Owner']))
		{
			$modelObject 	   = CUploadedFile::getInstance($model,'profile_pic');
				
			$model->attributes = array_map('trim',$_POST['Owner']);			
			$model->updated_on = time();
			
			if($model->save())
			{
				if(!empty($modelObject))
				{
					$ext = explode(".",$modelObject->name);
					$image_name = $model->owner_id.".".$ext[1];
					$image_path = Yii::app()->basePath . '/../upload/owner/'.$image_name;
					$return     = $modelObject->saveAs($image_path);
				}else {
					$image_name = $_POST['Owner']['profile_pic'];
				}
				
				$model->updateByPk($model->owner_id,array("profile_pic"=>$image_name));
				$cache = new CFileCache();
				$cache->flush();// to delete image cache if prof image is changed - garima
				ApplicationSessions::run()->write('owner_pic',$image_name);
				ApplicationSessions::run()->write('owner_email', $model->email);
				ApplicationSessions::run()->write('owner_fullname', $model->name);
				Yii::app()->user->setFlash('success', "Profile updated Successfully!");			
				$this->redirect(array('view'));
			}else{
				// print_r($model->getErrors());
				// exit;
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
        
        /* Renders change password page */
	public function actionChangePassword()
	{	
		$id = ApplicationSessions::run()->read('owner_id');
		$model = $this->loadModel($id);
		$model->scenario='changepwd_scenario';
        $this->render('changepassword',array('model'=>$model));
	}
        
         /* check the old password field from database through ajax */
	public function actionCheckPassword()
	{
		$pass = md5($_POST['old_pass']);
		$username = $_POST['username'];
		$susername = ApplicationSessions::run()->read('owner_username');
		if($username==$susername)
		{
			$owner_id = ApplicationSessions::run()->read('owner_id');
			$return_arr = Owner::model()->findByPk($owner_id,array("condition"=>"password='".$pass."' "));

			 if(count($return_arr)>0)
			{
				$return=true;
				$code = 200;
			}else{
				$return=false;
				$code = 404;
			}
		} else {
			$return=false;
			$code = 401;
		}
		
		 echo json_encode(array("success"=>$return,"code"=>$code));
	}
		/* Update the password  */
	public function actionUpdatePassword()
	{		
		$return=false;

		$model = new Owner;
		$owner_id = ApplicationSessions::run()->read('owner_id');
		$model = $this->loadModel($owner_id);
		$new_password = md5($_POST['Owner']['confirm_password']);
		if($model->updateByPk($owner_id,array('password'=>$new_password)))
		{
			if($model->name!='')
			{
				$name = $model->name;
			}else{
				$name = $model->email;			
			}
			$subject  = 'Password changed successfully - Shopnext - Owner account';
			$body 	  = 'Dear <b>'.$name.'</b>, <br/><br/> This is to notify you that your password has been changed successfully.<br /><br /> -Regards <br /> Team Shopnext.';
			$to_email = ApplicationSessions::run()->read('owner_email');
			$to_name  = ApplicationSessions::run()->read('owner_fullname');	
			$this->sendMail($subject,$body,$to_email,$to_name);			
			$return=true;
		}
		 echo json_encode(array("success"=>$return,));
	}
        
	/* 
		*Forgot password.
	*/
    public function actionRequestNewPassword()
	{
		$username = $_POST['username'];
		$success  = false;
		$msg      = 'An internal error occurred, please try after some time !';
		if($username!='')
		{
			$owner_detail = Owner::model()->find(array('condition'=>'username="'.$username.'"','order'=>'owner_id desc','limit'=>1));
			
			if(empty($owner_detail))
			{
				$msg      = "Invalid username !";
			}
			else if($owner_detail->active_status=="H" )
			{
				$msg      = "Could not request forgot password. This admin account is deactivated.  !";
			}
			else if($owner_detail->status=="0")
			{
				$msg      = "Could not request forgot password. This admin account is deleted.  !";
			}
			else 
			{
				$new_password = $this->generateRandomString();	
				$encoded_password = md5($new_password);
				$model = new Owner;
				$ret = $model->updateByPk($owner_detail->owner_id,array('password'=>$encoded_password));
				if($ret)
				{
					if($owner_detail->name!='')
					{
						$name = $owner_detail->name;
					}else{
						$name = $owner_detail->email;			
					}
					$subject = 'Forgot password request successful - Shopnext - Owner account';
					$body = 'Dear <b>'.$name.'</b>,
							<br/> This is with reference to your change password request on '.date('D d M, Y  h:i A').'.
							<br /><br />  Username : '.$owner_detail->username.'
							<br/> New password : '.$new_password.'
							<br /><br /> -Regards <br /> Team Shopnext.';
					$to_email = $owner_detail->username;
					$to_name  = $owner_detail->username;	
					$this->sendMail($subject,$body,$to_email,$to_name);
					
					$success  = true;
					$msg      = "Your password has been reset successfully and sent to your email id!";
				}else{
					$msg = $ret;
				}
			}
		}else{
			$msg      = "Username is required !";
		}
		print json_encode(array('success'=>$success,'msg'=>$msg));
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->createUrl('/owner/default/login'));
	}
        
    public function loadModel($id)
	{
		$model=Owner::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}