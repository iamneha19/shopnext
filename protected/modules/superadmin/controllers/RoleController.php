<?php

class RoleController extends Controller
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
		$model=new Role;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Role']))
		{
			$model->attributes = array_map('trim',$_POST['Role']);
			$model->added_on   = time();
			$model->updated_on = time();
			if($model->save())
				$this->redirect(array('view','id'=>$model->role_id));
		}

		$this->render('create',array(
			'model'=>$model,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Role']))
		{
			$model->attributes = array_map('trim',$_POST['Role']);
			$model->updated_on = time();
			if($model->save()){
				// Admin::model()->updateAll(array('active_status'=>$model->active_status),'role_id="'.$id.'"');
				$this->redirect(array('view','id'=>$model->role_id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
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
			Controller::updateDeletedStatus('Role',$id);
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
		$model=new Role('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Role']))
			$model->attributes=$_GET['Role'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/**
	 * Assign module access permissions to admin users.
	 * Use role_id instead of permission_id.
	 */
	public function actionAssignPermission($id)
	{
		$model = new Permission;
		$data  = $model->find(array('condition'=>'role_id='.$id));
		
		if(!empty($data))
		{
			$model = $model->find(array('condition'=>'role_id='.$id));
		}
		
		
		
		$controllervalue = Metadata::app()->getControllers('superadmin');
		$block_controller = Yii::app()->params['BLOCK_CONTROLLER'];
		$block_action = Yii::app()->params['BLOCK_ACTION'];
		
		
		if(!empty($controllervalue))
		{
			foreach($controllervalue as $controller)
			{
				if(!in_array($controller,$block_controller))
				{
					$user_actions = Metadata::app()->getActionswithfunction($controller,'superadmin'); 
				
					foreach($user_actions as $key=>$action)
					{
						if(!in_array($action,$block_action))
						{
							$controller_action[$controller][$key] = $action;
						}
					}
				}				
			}
		}
		
		if(isset($_POST['Permission']))
		{
			$permission = '';
			
			if(!empty($controllervalue))
			{
				foreach($controllervalue as $controller)
				{
					if(!in_array($controller,$block_controller))
					{
						if(!empty($_POST['Permission'][$controller]))
						{
							if(empty($permission))
							{
								$permission .= implode(",",$_POST['Permission'][$controller]);
							}
							else 
							{
								$permission .= ",".implode(",",$_POST['Permission'][$controller]);
							}							
						}
					}
				}
			}
			
			$model->role_id = $_POST['Permission']['role_id'];
			
			if(!empty($_POST['Permission']['all_permission']))
			{
				$model->all_permission = implode(",",$_POST['Permission']['all_permission']);
			}
			else 
			{
				$model->all_permission = '';	
			}
			
			$model->permission_name = $permission;
			
			if(empty($data))
			{
				$model->added_on = time();
			}
			
			$model->updated_on = time();
			$model->save();
		}
		
		$data = $model->find(array('condition'=>'role_id='.$id));
		
		if(!empty($data))
		{
			$model = $model->find(array('condition'=>'role_id='.$id));
			$all_permission = explode(",",$data->all_permission);
			$action_permission = explode(",",$data->permission_name);
		}
		else 
		{
			$all_permission = array();
			$action_permission = array();
		}
		
		$this->render('permission',array(
			'model'=>$model,
			'role_id'=>$id,
			'controller_action'=>$controller_action,
			'all_permission'=>$all_permission,
			'action_permission'=>$action_permission,
		));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Role the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Role::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested role record has been deleted !!!');			
		}	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Role $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='role-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
