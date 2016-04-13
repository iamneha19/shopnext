<?php

class BlogCommentController extends Controller
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

		if(isset($_POST['BlogComment']))
		{
			$model->attributes = array_map('trim',$_POST['BlogComment']);
			$model->updated_on = time();
			if($model->save())
				$this->redirect(array('view','id'=>$model->blog_comment_id));
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
			Controller::updateDeletedStatus('BlogComment',$id);
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
		$model=new BlogComment('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['BlogComment'])){
			$model->attributes=$_GET['BlogComment'];	
		}
		
		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/* 
		*Performs activation and deactivation of blogcomments.
	*/
	public function actionSetstatus($active_status,$id)
	{
		$model=$this->loadModel($id);
		$model->active_status=$active_status;
		//$model->updateAll(array('active_status'=>$active_status),'blog_comment_id="'.$id.'"');
		$model->save();
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/* 
		*For activation and deactivation of multiple records.
	*/
	public function actionChangeStatus()
	{		
		$status = false;		
		if(isset($_POST))
		{
			$action = $_POST['status'];
			$id_arr = $_POST['blog_comment_id_arr'];			
			if(!empty($action) && is_array($id_arr) && !empty($id_arr))
			{
				if($action=='D')
				{
					$status = $this->deleteMultiple('BlogComment',$id_arr);		
				}else{
					$status = $this->setActiveStatus('BlogComment',$action,$id_arr,'blog_comment_id');
				}
			}		
		}	
		if(($status==true && $status!='update') || $status='1')
		{
			if($action=='S' )
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
		echo json_encode(array('success'=>$status));		
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BlogComment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BlogComment::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}else if($model->status=='0'){
			throw new CHttpException(404,'The requested blog comment record has been deleted !!!');			
		}		
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BlogComment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='blog-comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionAutocompleteBlog()
	{
		$title = array('id'=>'','label'=>'No records found');		
		
		if(!empty($_GET['term']))
		{
			$model = new Blog;			
			$resp = $model->findAll(array('condition'=>'status="1" and active_status="S" and title like "%'.$_GET['term'].'%"','select'=>'blog_id,title'));			
			
			if(!empty($resp))
			{
				$i = 0;
				$title = array();		
				
			 	foreach($resp as $val)
				{
					$title[$i]['id'] = $val['blog_id'];
					$title[$i]['label'] = $val['title'];
					$i++;
				}
			}
		}
		echo CJSON::encode($title);
	} 
	
	public function actionAutocompleteBlogComment()
	{
		$title = array('id'=>'','label'=>'No records found');		
		
		if(!empty($_GET['term']))
		{
			$model = new BlogComment;			
			$resp = $model->findAll(array('condition'=>'status="1" and comment like "%'.$_GET['term'].'%"','select'=>'blog_comment_id,comment'));			
			
			if(!empty($resp))
			{
				$i = 0;
				$title = array();		
				
			 	foreach($resp as $val)
				{
					$title[$i]['id'] = $val['blog_comment_id'];
					$title[$i]['label'] = $val['comment'];
					$i++;
				}
			}
		}
		echo CJSON::encode($title);
	} 	
}
