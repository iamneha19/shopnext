<?php

class DealCommentController extends Controller
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
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Comment']))
		{
			$model->attributes = array_map('trim',$_POST['Comment']);
			$model->updated_on = time();
			if($model->save())
				$this->redirect(array('view','id'=>$model->comment_id));
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
			Controller::updateDeletedStatus('Comment',$id);
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
		$model=new Comment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Comment']))
			$model->attributes=$_GET['Comment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Comment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Comment::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested deal comment record has been deleted !!!');			
		}	
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Comment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	/* 
		*Performs the activation and deactivation of comments.
	*/
	public function actionSetstatus($id,$active_status)
	{
		$model=$this->loadModel($id);
		 $model->active_status=$active_status;
		 $model->save();
		// $model->updateAll(array('active_status'=>$active_status),'comment_id="'.$id.'"');
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/* 
		*For activation and deactivation of multiple records.
	*/
	public function actionchangeStatus()
	{
		$status = false;		
		if(isset($_POST))
		{
			$action = $_POST['status'];			
			$id_arr = $_POST['comment_id_arr'];
			$deal_id_arr = explode(",",$_POST['deal_ids']); // To update total_comments of deal
			
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{
					$status = $this->deleteMultiple('Comment',$id_arr);	
				}else{
					$status = $this->setActiveStatus('Comment',$action,$id_arr,'comment_id');
				}
			}			
		}
		if(($status==true && $status!='update') || $status=='1')
		{
			if($action=='S')
			{
				$msg = 'Selected comments have been approved successfully !!';
			}
			else if($action=='H')
			{
				$msg = 'Selected comments have been disapproved successfully !!';
			}
			else if($action=='D')
			{
				$msg = 'Selected comments have been deleted successfully !!';
			}
			$this->setFlashMessage($msg);
		}
		
		// code to update total_comments of deal.
		foreach($deal_id_arr as $deal_id){
			$count = Comment::model()->count(array('condition'=>"deal_id=".$deal_id." and active_status='S' and status='1' and parent_id IS NULL"));
			Deal::model()->updateByPk($deal_id,array('total_comments'=>$count));
		}
		
		echo json_encode(array('success'=>$status));	
	}
}
