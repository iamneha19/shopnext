<?php

class OwnerController extends Controller
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
		return Yii::app()->GetAccessRule->getOwner();
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
		$model=new Owner;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$owner_role = CHtml::listData(OwnerRole::model()->findAll(array('condition'=>'status="1" and active_status="S"' ,'order'=>'name')),'owner_role_id','name');
		if(isset($_POST['Owner']))
		{
			$model->attributes = array_map('trim',$_POST['Owner']);
			$model->username   = $model->email;
            $model->created_by = ApplicationSessions::run()->read('owner_id');
			$parent_id = ApplicationSessions::run()->read('parent_id');
			 
			if(!empty($parent_id)){
				$model->parent_id = $parent_id;
			}else{
				$model->parent_id = ApplicationSessions::run()->read('owner_id');
			}	
			
			$password = $_POST['Owner']['password'];
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
						$image_name = $model->owner_id.".".$ext[1];
						$image_path = Yii::app()->basePath . '/../upload/owner/'.$image_name;
						if($modelObject->saveAs($image_path))
						{
							$model->updateByPk($model->owner_id,array("profile_pic"=>$image_name));
						}
					}
					if($model->name!='')
					{
						$name = $model->name;
					}else{
						$name = $model->email;			
					}
					$site_url 	= Yii::app()->params['SITE_URL'];
					$subject 	= 'Login credentials for - Shopnext - Owner account';
					$body 		= 'Hello <b>'.$name.'</b>, <br/> Your login credentials for shopnext as follow :- <br/>Username :- '.$model->email.'<br/>Password :- '.$password.'<br> Please refer given link for login <br/> <a href= '.Yii::app()->params['SERVER'].'owner> Click here for login </a>';
					$to_email 	= $model->email;
					$to_name 	= $model->name;
					$from_email = ApplicationSessions::run()->read('owner_email');

					$this->sendMail($subject,$body,$to_email,$to_name,$from_email);
					
					$this->redirect(array('view','id'=>$model->owner_id));
				}
            }else{
                $model->password ='';
            }	
		}

		$this->render('create',array(
			'model'=>$model,
			'owner_role'=>$owner_role,
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
       $owner_role = CHtml::listData(OwnerRole::model()->findAll(array('condition'=>'status="1" and active_status="S"' ,'order'=>'name')),'owner_role_id','name');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Owner']))
		{
			$model->attributes = array_map('trim',$_POST['Owner']);
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
						$image_name = $model->owner_id.".".$ext[1];
						$image_path = Yii::app()->basePath . '/../upload/owner/'.$image_name;
						if($modelObject->saveAs($image_path))
						{
							$model->updateByPk($model->owner_id,array("profile_pic"=>$image_name));
						}
					}
					$this->redirect(array('view','id'=>$model->owner_id));
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'owner_role'=>$owner_role,
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
			Controller::updateDeletedStatus('Owner',$id);
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
		$dataProvider=new CActiveDataProvider('Owner');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Owner('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Owner']))
			$model->attributes=$_GET['Owner'];

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
			$id_arr = $_POST['owner_id_arr'];
			
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action== 'D')
				{
					$status = $this->deleteMultiple('Owner',$id_arr);	
				}else{
					$status = $this->setActiveStatus('Owner',$action,$id_arr,'owner_id');
				}
			}	
		}
		if(($status==true && $status!='update') || $status=='1')
		{
			if($action=='S')
			{
				$msg = 'Selected owners have been activated successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected owners have been deactivated successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected owners have been deleted successfully !!';
			}
			
			$this->setFlashMessage($msg);	
		}
		echo json_encode(array('success'=>$status));
	}
	
	public function actionDeactivate($id)
	{
		$model = new Owner;		
		if($model->updateAll(array('active_status'=>"H"),'owner_id="'.$id.'"')){
			$this->setFlashMessage('Admin has been deactivated successfully.','pageMessage');
		}
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	public function actionActivate($id)
	{
		$model = new Owner;
		$model = $this->loadModel($id);
		
		if($model->updateAll(array('active_status'=>"S"),'owner_id="'.$id.'"'))
		{
			if($model->name!='')
			{
				$name = $model->name;
			}else{
				$name = $model->email;			
			}
			$site_url 	= Yii::app()->params['SITE_URL'];
			$subject 	= 'Login activated for - Shopnext - Owner account';
			$body 		= 'Hello <b>'.$name.'</b>, <br/> Your account has been approved by owner, You will be now able to login to the system. <br/>Please use below url to login<br/>'.Yii::app()->params['SERVER'].'owner/default/login ';
			$to_email   = $model->email;
			$to_name    = $model->name;
			$from_email = ApplicationSessions::run()->read('owner_email');			
			$this->sendMail($subject,$body,$to_email,$to_name,$from_email);
		}
		$this->setFlashMessage('Owner has been activated successfully.','pageMessage');
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Owner the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Owner::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Owner $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='owner-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
