<?php

class AdminController extends Controller
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
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return Yii::app()->GetAccessRule->get();
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{ 
		$model=new Admin;
        $role = CHtml::listData(Role::model()->findAll(array('condition'=>'active_status="S" and status="1"','order'=>'name' )),'role_id','name');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Admin']))
		{
			$model->attributes = array_map('trim',$_POST['Admin']);
            $model->username   = $model->email;
            $model->created_by = ApplicationSessions::run()->read('admin_id');
			$password = $_POST['Admin']['password'];
			$model->added_on   = time();
			$model->updated_on = time();
			
			$model->scenario='insert';
            if($model->validate()) 
			{
				if(!empty($password))
				{
					$model->password   = md5($model->password);
				}
			
				$modelObject = CUploadedFile::getInstance($model,'profile_pic');
				if($model->save(false)) 
				{
					if(!empty($modelObject))
					{
						$ext = explode(".",$modelObject->name);
						$image_name = $model->admin_id.".".$ext[1];
						$image_path = Yii::app()->basePath . '/../upload/admin/'.$image_name;
						if($modelObject->saveAs($image_path))
						{
							$model->updateByPk($model->admin_id,array("profile_pic"=>$image_name));
						}
					}
					if($model->name!='')
					{
						$name = $model->name;
					}else{
						$name = $model->email;			
					}
					$site_url 	= Yii::app()->params['SITE_URL'];
					$subject 	= 'Login credentials for - Shopnext - Admin account';
					$body 		= 'Hello <b>'.$name.'</b>, <br/> Your login credentials for shopnext as follow :- <br/>Username :- '.$model->email.'<br/>Password :- '.$password.'<br> Please refer given link for login <br/> <a href= '.Yii::app()->params['SERVER'].'superadmin> Click here for login </a>';
					$to_email 	= $model->email;
					$to_name 	= $model->name;
					$from_email = ApplicationSessions::run()->read('admin_email');

					$this->sendMail($subject,$body,$to_email,$to_name,$from_email);
					
					$this->redirect(array('view','id'=>$model->admin_id));
				}
            }else{
                $model->password ='';
            }	
		}

		$this->render('create',array(
			'model'=>$model,
            'role'=>$role,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        $role = CHtml::listData(Role::model()->findAll(array('condition'=>'status="1" and active_status="S"','order'=>'name' )),'role_id','name');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Admin']))
		{
			$model->attributes = array_map('trim',$_POST['Admin']);
            $model->username = $model->email;
			$model->updated_on = time();
			
			if($model->validate()) 
			{	
				$modelObject = CUploadedFile::getInstance($model,'profile_pic');
				if($model->save(false))
				{
					if(!empty($modelObject))
					{
						$ext = explode(".",$modelObject->name);
						$image_name = $model->admin_id.".".$ext[1];
						$image_path = Yii::app()->basePath . '/../upload/admin/'.$image_name;
						if($modelObject->saveAs($image_path))
						{
							$model->updateByPk($model->admin_id,array("profile_pic"=>$image_name));
						}
					}
					$this->redirect(array('view','id'=>$model->admin_id));
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
                        'role'=>$role,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		try
		{
			Controller::updateDeletedStatus('Admin',$id);
		}
		catch(CDbException $e)
		{
			echo "Please try after some time.";
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Admin('search');
		
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Admin']))
			$model->attributes=$_GET['Admin'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionChangeStatus()
	{
		$status = false;
		
		if(isset($_POST))
		{
			$action = $_POST['status'];
			$id_arr = $_POST['admin_id_arr'];
			
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action== 'D')
				{
					$status = $this->deleteMultiple('Admin',$id_arr);	
				}else{
					$status = $this->setActiveStatus('Admin',$action,$id_arr,'admin_id');
				}
			}	
		}
		if(($status==true && $status!='update') || $status=='1')
		{
			if($action=='S')
			{
				$msg = 'Selected users have been activated successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected users have been deactivated successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected users have been deleted successfully !!';
			}
			
			$this->setFlashMessage($msg);	
		}
		echo json_encode(array('success'=>$status));
	}
	
	public function actionDeactivate($id)
	{
		$model = new Admin;		
		if($model->updateAll(array('active_status'=>"H"),'admin_id="'.$id.'"')){
			$this->setFlashMessage('Admin has been deactivated successfully.','pageMessage');
		}
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	public function actionActivate($id)
	{
		$model = new Admin;
		$model = $this->loadModel($id);
		
		if($model->updateAll(array('active_status'=>"S"),'admin_id="'.$id.'"'))
		{
			if($model->name!='')
			{
				$name = $model->name;
			}else{
				$name = $model->email;			
			}
			$site_url 	= Yii::app()->params['SITE_URL'];
			$subject 	= 'Login activated for - Shopnext - Admin account';
			$body 		= 'Hello <b>'.$name.'</b>, <br/> Your account has been approved by admin, You will be now able to login to the system. <br/>Please use below url to login<br/>'.Yii::app()->params['SERVER'].'superadmin/default/login ';
			$to_email   = $model->email;
			$to_name    = $model->name;
			$from_email = ApplicationSessions::run()->read('admin_email');			
			$this->sendMail($subject,$body,$to_email,$to_name,$from_email);
		}
		$this->setFlashMessage('Admin has been activated successfully.','pageMessage');
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Admin the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{		
		$model=Admin::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested admin record has been deleted !!!');			
		}			
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Admin $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='admin-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
