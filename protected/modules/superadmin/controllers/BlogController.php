<?php

class BlogController extends Controller
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
		$model=new Blog;

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Blog']))
		{
			$model->attributes = array_map('trim',$_POST['Blog']);
			$model->admin_id   = ApplicationSessions::run()->read('admin_id');
			$model->added_on   = time();
			$model->updated_on = time();
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->blog_id));
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

		if(isset($_POST['Blog']))
		{
			$model->attributes = array_map('trim',$_POST['Blog']);
			$model->updated_on = time();
			if($model->save())
				$this->redirect(array('view','id'=>$model->blog_id));
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
			$ret = Controller::updateDeletedStatus('Blog',$id);
			if($ret)
			{
				$comment_delete = BlogComment::model()->updateAll(array('status'=>'0'),'status="1" and blog_id="'.$id.'"');					
			}
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
		$model=new Blog('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Blog']))
			$model->attributes=$_GET['Blog'];

		$this->render('admin',array(
			'model'=>$model,
		)); 
	}
	
	/* 
		*Performs the activation and deactivation of blogs.
	*/
	public function actionSetstatus($id,$active_status)
	{		
		$model=$this->loadModel($id);
		$model->active_status =$active_status;
		$model->save();
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/* 
		*For activation / deactivation / deletion of multiple records.
	*/
	public function actionchangeStatus()
	{
		$status = false;		
		if(isset($_POST))
		{
			$action = $_POST['status'];
			$id_arr = $_POST['blog_id_arr'];	
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{			
					$status = $this->deleteMultiple('Blog',$id_arr);					
				}else 
				{	
					$status = $this->setActiveStatus('Blog',$action,$id_arr,'blog_id');
				}	
			}	
		}

		if( ($status==true && $status!='update') || $status=='1')
		{
			if($action=='S')
			{
				$msg = 'Selected blogs have been activated successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected blogs have been deactivated successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected blogs have been deleted successfully !!';
			}
		
			$this->setFlashMessage($msg);
		}
		
		echo json_encode(array('success'=>$status));			
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Blog the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Blog::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested blog record has been deleted !!!');			
		}	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Blog $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='blog-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}